<?php

namespace App\Http\Controllers;

use App\Http\Resources\TechnicalDescriptionResource;
use App\Models\TechnicalDescription;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UpsertTechnicalDescriptionController extends Controller
{
    public function __invoke(Request $request, string $property_code): \Illuminate\Http\JsonResponse
    {
        $technicalDescription = TechnicalDescription::query()
            ->where('property_code', $property_code)
            ->first();

        // If no technical description exists for the property code, require all fields so a new record can be created.
        $requiredRule = $technicalDescription ? 'sometimes' : 'required';

        $validated = $request->validate([
            'company_name' => [$requiredRule, 'string', 'max:255'],
            'registry_of_deeds' => [$requiredRule, 'string', 'max:255'],
            'tct' => [$requiredRule, 'string', 'max:255'],
            'vsr' => [$requiredRule, 'string', 'max:255'],
            'old_technical_desc' => ['nullable', 'string'],
            'survey_plan_no' => [$requiredRule, 'string', 'max:255'],
            'block_no' => [$requiredRule, 'string', 'max:255'],
            'lot_no' => [$requiredRule, 'string', 'max:255'],
            'portion_of_lot' => [$requiredRule, 'string'],
            'lrc_record_no' => [$requiredRule, 'string', 'max:255'],
            'land_owner_claimant' => [$requiredRule, 'string', 'max:255'],
            'location' => [$requiredRule, 'string'],
            'area' => [$requiredRule, 'string', 'max:255'],
            'description_of_corners' => [$requiredRule, 'string'],
            'bearings' => [$requiredRule, 'boolean'],
            'original_date_of_survey' => [$requiredRule, 'string', 'max:255'],
            'date_of_survey' => [$requiredRule, 'string', 'max:255'],
            'date_approved' => [$requiredRule, 'string', 'max:255'],
            'geodetic_engineer' => [$requiredRule, 'string', 'max:255'],
        ]);

        validator(
            ['property_code' => $property_code],
            ['property_code' => ['required', 'string', 'max:255', Rule::exists('properties', 'code')]],
        )->validate();

        $technicalDescription = TechnicalDescription::updateOrCreate(
            ['property_code' => $property_code],
            $validated,
        );

        $wasRecentlyCreated = $technicalDescription->wasRecentlyCreated;

        return response()->json([
            'message' => $wasRecentlyCreated
                ? 'Technical description created successfully.'
                : 'Technical description updated successfully.',
            'data' => (new TechnicalDescriptionResource($technicalDescription))->resolve($request),
        ], $wasRecentlyCreated ? 201 : 200);
    }
}
