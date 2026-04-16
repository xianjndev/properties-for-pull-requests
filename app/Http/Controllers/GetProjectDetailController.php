<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProjectResource;
use Homeful\Properties\Models\Project;
use Illuminate\Http\Request;

class GetProjectDetailController extends Controller
{
    public function __invoke(Request $request, string $project_code): \Illuminate\Http\JsonResponse
    {
        $project = Project::where('code', $project_code)->firstOrFail();

        return (new ProjectResource($project))->response();
    }
}
