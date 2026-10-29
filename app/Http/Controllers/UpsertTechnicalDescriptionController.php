<?php

namespace App\Http\Controllers;

use App\Http\Resources\TechnicalDescriptionResource;
use App\Models\TechnicalDescription;
use App\Models\TechnicalDescriptionSyncLog;
use App\Models\TechnicalDescriptionSyncRun;
use App\Support\EncryptedRouteParameter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use InvalidArgumentException;

class UpsertTechnicalDescriptionController extends Controller
{
    public function __invoke(Request $request, string $property_code): \Illuminate\Http\JsonResponse
    {
        $syncRequestId = $request->header('X-Sync-Request-ID');
        $failurePropertyCode = 'Unresolved property';
        $failureAction = 'sync_failed';
        $technicalDescription = null;
        $syncRun = filled($syncRequestId)
            ? TechnicalDescriptionSyncRun::query()->where('sync_request_id', $syncRequestId)->first()
            : null;

        if (filled($syncRequestId) && $syncRun === null) {
            Log::warning('Technical description sync callback rejected an unknown request ID.', [
                'sync_request_id' => $syncRequestId,
                'user_id' => $request->user()?->getKey(),
            ]);

            abort(404, 'The technical description sync request was not found.');
        }

        if ($syncRun !== null && ! in_array($syncRun->status, ['queued', 'running'], true)) {
            Log::warning('Technical description sync callback rejected an inactive run.', [
                'sync_request_id' => $syncRequestId,
                'sync_run_id' => $syncRun->getKey(),
                'status' => $syncRun->status,
            ]);

            abort(409, 'The technical description sync request is no longer active.');
        }

        try {
            try {
                $property_code = EncryptedRouteParameter::fromConfig()->decrypt($property_code);
                $failurePropertyCode = $property_code;
            } catch (InvalidArgumentException) {
                Log::warning('Technical description sync callback had an invalid encrypted property code.', [
                    'sync_request_id' => $syncRequestId,
                    'user_id' => $request->user()?->getKey(),
                ]);

                abort(404);
            }

            Log::info('Technical description sync callback received.', [
                'sync_request_id' => $syncRequestId,
                'property_code' => $property_code,
                'user_id' => $request->user()?->getKey(),
                'received_fields' => array_keys($request->all()),
            ]);

            validator(
                ['property_code' => $property_code],
                ['property_code' => ['required', 'string', 'max:255', Rule::exists('properties', 'code')]],
            )->validate();

            $technicalDescription = TechnicalDescription::query()
                ->where('property_code', $property_code)
                ->first();
            $failureAction = $technicalDescription ? 'update_failed' : 'create_failed';

            Gate::authorize($technicalDescription ? 'update' : 'create', $technicalDescription ?? TechnicalDescription::class);

            $hasUsableBearings = false;

            // if ($request->has('bearings')) {
            //     $rawBearings = $request->input('bearings');

            //     // M-Files represents an empty bearing value as null, an empty string, or "-".
            //     // The Properties field is boolean, so these placeholders are treated as false.
            //     if ($rawBearings === null || (is_string($rawBearings) && in_array(trim($rawBearings), ['', '-'], true))) {
            //         $request->merge(['bearings' => false]);
            //     } else {
            //         // Normalize values such as "TRUE" and "FALSE", which Laravel's boolean
            //         // validation rule does not accept in every string representation.
            //         $normalizedBearings = filter_var(
            //             $rawBearings,
            //             FILTER_VALIDATE_BOOLEAN,
            //             FILTER_NULL_ON_FAILURE,
            //         );

            //         // Leave unrecognized values unchanged so validation rejects bad source data.
            //         if ($normalizedBearings !== null) {
            //             $request->merge(['bearings' => $normalizedBearings]);
            //             $hasUsableBearings = true;
            //         }
            //     }
            // }

            // If no technical description exists for the property code, require all fields so a new record can be created.
            $requiredRule = $technicalDescription ? 'sometimes' : 'required';

            $rules = [
                'company_name' => [$requiredRule, 'string', 'max:255'],
                'registry_of_deeds' => [$requiredRule, 'string', 'max:255'],
                'tct' => [$requiredRule, 'string', 'max:255'],
                'vsr' => [$requiredRule, 'string', 'max:255'],
                'old_technical_desc' => ['nullable', 'string', 'max:10000'],
                'survey_plan_no' => [$requiredRule, 'string', 'max:255'],
                'block_no' => [$requiredRule, 'string', 'max:255'],
                'lot_no' => [$requiredRule, 'string', 'max:255'],
                'portion_of_lot' => [$requiredRule, 'string', 'max:5000'],
                'lrc_record_no' => [$requiredRule, 'string', 'max:255'],
                'land_owner_claimant' => [$requiredRule, 'string', 'max:255'],
                'location' => [$requiredRule, 'string', 'max:5000'],
                'area' => [$requiredRule, 'string', 'max:255'],
                'description_of_corners' => [$requiredRule, 'string', 'max:5000'],
                'bearings' => [$requiredRule, 'boolean'],
                'original_date_of_survey' => [$requiredRule, 'string', 'max:255'],
                'date_of_survey' => [$requiredRule, 'string', 'max:255'],
                'date_approved' => [$requiredRule, 'string', 'max:255'],
                'geodetic_engineer' => [$requiredRule, 'string', 'max:255'],
            ];

            $technicalDescriptionFields = array_diff(array_keys($rules), ['bearings']);

            $hasUsableMfilesData = $hasUsableBearings
                || collect($request->only($technicalDescriptionFields))
                    ->contains(fn (mixed $value): bool => ! ($value === null
                        || (is_string($value) && in_array(trim($value), ['', '-'], true))));

            if (! $hasUsableMfilesData) {
                Log::warning('Technical description sync rejected an all-placeholder M-Files payload.', [
                    'sync_request_id' => $syncRequestId,
                    'property_code' => $property_code,
                ]);

                abort(422, 'M-Files returned no usable technical description data.');
            }

            $validated = $request->validate($rules);

            [$technicalDescription, $wasRecentlyCreated, $changes] = DB::transaction(function () use ($request, $property_code, $technicalDescription, $validated, $syncRun): array {
                $wasRecentlyCreated = $technicalDescription === null;
                $technicalDescription ??= new TechnicalDescription(['property_code' => $property_code]);
                $technicalDescription->fill($validated);

                $dirty = $technicalDescription->getDirty();
                $changes = [];

                foreach ($dirty as $field => $newValue) {
                    if ($field === 'property_code') {
                        continue;
                    }

                    $changes[$field] = [
                        'old' => $wasRecentlyCreated ? null : $technicalDescription->getOriginal($field),
                        'new' => $newValue,
                    ];
                }

                $technicalDescription->save();

                $action = $wasRecentlyCreated ? 'created' : ($changes === [] ? 'unchanged' : 'updated');
                $attributes = [
                    'technical_description_id' => $technicalDescription->getKey(),
                    'action' => $action,
                    'status' => $action,
                    'changes' => $changes,
                    'error_message' => null,
                    'user_id' => $syncRun?->user_id ?? $request->user()?->getKey(),
                ];

                if ($syncRun !== null) {
                    TechnicalDescriptionSyncLog::updateOrCreate([
                        'sync_run_id' => $syncRun->getKey(),
                        'property_code' => $property_code,
                    ], $attributes);
                }

                return [$technicalDescription, $wasRecentlyCreated, $changes];
            });

            $syncRun?->refreshCounts();

            Log::info('Technical description sync callback processed.', [
                'sync_request_id' => $syncRequestId,
                'property_code' => $property_code,
                'action' => $wasRecentlyCreated ? 'created' : ($changes === [] ? 'unchanged' : 'updated'),
                'changed_field_count' => count($changes),
                'changed_fields' => array_keys($changes),
                'technical_description_id' => $technicalDescription->getKey(),
            ]);

            return response()->json([
                'message' => $wasRecentlyCreated
                    ? 'Technical description created successfully.'
                    : ($changes === [] ? 'Technical description is unchanged.' : 'Technical description updated successfully.'),
                'status' => $wasRecentlyCreated ? 'created' : ($changes === [] ? 'unchanged' : 'updated'),
                'data' => (new TechnicalDescriptionResource($technicalDescription))->resolve($request),
            ], $wasRecentlyCreated ? 201 : 200);
        } catch (\Throwable $exception) {
            $failureDetails = [
                'message' => $exception->getMessage(),
                'exception' => $exception::class,
            ];

            if (method_exists($exception, 'errors')) {
                $failureDetails['validation_errors'] = $exception->errors();
            }

            Log::error('Technical description sync callback failed.', [
                'sync_request_id' => $syncRequestId,
                'property_code' => $failurePropertyCode,
                'action' => $failureAction,
                'user_id' => $request->user()?->getKey(),
                'failure' => $failureDetails,
            ]);

            try {
                if ($syncRun !== null) {
                    TechnicalDescriptionSyncLog::recordFailure(
                        propertyCode: $failurePropertyCode,
                        action: $failureAction,
                        details: $failureDetails,
                        userId: $syncRun->user_id ?? $request->user()?->getKey(),
                        technicalDescriptionId: $technicalDescription?->getKey(),
                        syncRunId: $syncRun->getKey(),
                    );
                    $syncRun->refreshCounts();
                }
            } catch (\Throwable $loggingException) {
                Log::error('Unable to persist technical description sync failure log.', [
                    'sync_request_id' => $syncRequestId,
                    'property_code' => $failurePropertyCode,
                    'original_exception' => $exception->getMessage(),
                    'logging_exception' => $loggingException->getMessage(),
                ]);
            }

            throw $exception;
        }
    }
}
