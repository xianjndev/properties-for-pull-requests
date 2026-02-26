<?php

namespace Homeful\Properties\Data;

use Homeful\Properties\Models\Project;
use Spatie\LaravelData\Data;

class ProjectData extends Data
{
    public function __construct(
        public string $code,
        public string $name,
        public string $location,
        public ?string $type,
        public ?string $licenseNumber,
        public ?string $licenseDate,
        public ?string $company_code,
        public ?float $appraised_lot_value,
        public ?string $address,
        public ?string $total_sold,
        public ?string $project_description,
        public ?string $filing_site,
        public ?string $company_name,
        public ?string $company_tin,
        public ?string $company_address,
        public ?string $pagibig_filing_site,
        public ?string $exec_postion,
        public ?string $exec_signatory,
        public ?string $exec_tin,
        public ?string $board_resolution_date,
    ) {}

    public static function fromModel(Project $project): ProjectData
    {
        return new self(
            code: $project->code,
            name: $project->name,
            location: $project->location,
            type: $project->type->getName(),
            licenseNumber: $project->licenseNumber,
            licenseDate: $project->licenseDate?->format('Y-m-d'),
            company_code: $project->company_code,
            appraised_lot_value: $project->appraised_lot_value,
            address: $project->address ?? '',
            total_sold: $project->total_sold ?? '',
            project_description: $project->project_description ?? '',
            filing_site: $project->filing_site ?? '',
            company_name: $project->company_name,
            company_tin: $project->company_tin,
            company_address: $project->company_address,
            pagibig_filing_site: $project->pagibig_filing_site,
            exec_postion: $project->exec_position,
            exec_signatory: $project->exec_signatory,
            exec_tin: $project->exec_tin,
            board_resolution_date: $project->board_resolution_date?->format('Y-m-d'),
        );
    }
}
