<?php

namespace App\Http\Controllers;

use App\Http\Resources\TechnicalDescriptionResource;
use App\Models\TechnicalDescription;
use App\Support\EncryptedRouteParameter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use InvalidArgumentException;

class GetTechnicalDescriptionController extends Controller
{
    public function __invoke(Request $request, string $property_code): \Illuminate\Http\JsonResponse
    {
        try {
            $property_code = EncryptedRouteParameter::fromConfig()->decrypt($property_code);
        } catch (InvalidArgumentException) {
            abort(404);
        }

        validator(
            ['property_code' => $property_code],
            ['property_code' => ['required', 'string', 'max:255']],
        )->validate();

        $technicalDescription = TechnicalDescription::query()
            ->where('property_code', $property_code)
            ->firstOrFail();

        Gate::authorize('view', $technicalDescription);

        return (new TechnicalDescriptionResource($technicalDescription))->response();
    }
}
