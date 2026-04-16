<?php

namespace App\Http\Resources;

use Homeful\Properties\Data\ProjectData;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
{
    protected ProjectData $data;

    public function __construct($resource)
    {
        parent::__construct($resource);

        $this->data = ProjectData::fromModel($resource);
    }

    public function toArray(Request $request): array
    {
        return $this->data->toArray();
    }
}
