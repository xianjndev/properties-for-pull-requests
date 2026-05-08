<?php

namespace App\Http\Controllers;

use App\Http\Resources\TechnicalDescriptionResource;
use App\Models\TechnicalDescription;
use Illuminate\Http\Request;

class GetTechnicalDescriptionController extends Controller
{
    public function __invoke(Request $request, string $project_code): \Illuminate\Http\JsonResponse
    {
        $technicalDescription = TechnicalDescription::query()
            ->where('project_code', $project_code)
            ->firstOrFail();

        return (new TechnicalDescriptionResource($technicalDescription))->response();
    }
}
