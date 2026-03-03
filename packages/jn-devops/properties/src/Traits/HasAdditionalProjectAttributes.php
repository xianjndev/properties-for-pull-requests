<?php

namespace Homeful\Properties\Traits;

use Homeful\Property\Enums\MarketSegment;
use Homeful\Property\Enums\HousingType;
use Homeful\Properties\Models\Project;
use Illuminate\Support\Carbon;
use ReflectionEnum;

trait HasAdditionalProjectAttributes
{
    const ADDRESS = 'address';
    const TYPE = 'type';
    const HOUSING_TYPE = 'housing_type';
    const LICENSE_NUMBER = 'license_number';
    const LICENSE_DATE = 'license_date';
    const COMPANY_CODE = 'company_code';
    const APPRAISED_LOT_VALUE = 'appraised_lot_value';
    const TOTAL_SOLD = 'total_sold';
    const PROJECT_DESCRIPTION = 'project_description';

    const COMPANY_NAME ='company_name';
    const COMPANY_TIN ='company_tin';
    const COMPANY_ADDRESS = 'company_address';
    const PAGIBIG_FILING_SITE = 'pagibig_filing_site';

    const EXEC_POSITION = 'exec_position';
    const EXEC_SIGNATORY = 'exec_signatory';
    const EXEC_TIN = 'exec_tin';
    const BOARD_RESOLUTION_DATE = 'board_resolution_date';
    const FILING_SITE = 'filing_site';

    public function setAddressAttribute(?string $value): self
    {
        $this->getAttribute('meta')->set(Project::ADDRESS, $value);

        return $this;
    }

    public function getAddressAttribute(): ?string
    {
        return $this->getAttribute('meta')->get(Project::ADDRESS);
    }

    public function setTypeAttribute(MarketSegment|string $value): self
    {
        $reflection = new ReflectionEnum(MarketSegment::class);

        $this->getAttribute('meta')->set(Project::TYPE,  $value instanceof MarketSegment
            ? $value->name
            : $reflection->getCase($value)
        );

        return $this;
    }

    public function getTypeAttribute(): MarketSegment
    {
        $reflection = new ReflectionEnum(MarketSegment::class);
        $name = $this->getAttribute('meta')->get(Project::TYPE);

        return $name ? $reflection->getCase($name)->getValue() : MarketSegment::default();
//        return MarketSegment::{$name}; //PHP 8.3
    }

    public function setHousingTypeAttribute(HousingType|string|null $value): self
    {
        $reflection = new ReflectionEnum(HousingType::class);

        $this->getAttribute('meta')->set(Project::HOUSING_TYPE,  $value instanceof HousingType
            ? $value->name
            : $reflection->getCase($value));

        return $this;
    }

    public function getHousingTypeAttribute(): ?HousingType
    {
        $reflection = new ReflectionEnum(HousingType::class);
        $name = $this->getAttribute('meta')->get(Project::HOUSING_TYPE);

        return $name ? $reflection->getCase($name)->getValue() : HousingType::SINGLE_DETACHED;
//        return MarketSegment::{$name}; //PHP 8.3
    }

    public function setLicenseNumberAttribute(?string $value): self
    {
        $this->getAttribute('meta')->set(Project::LICENSE_NUMBER, $value);

        return $this;
    }

    public function getLicenseNumberAttribute(): ?string
    {
        return $this->getAttribute('meta')->get(Project::LICENSE_NUMBER);
    }




    public function setCompanyCodeAttribute(?string $value): self
    {
        $this->getAttribute('meta')->set(Project::COMPANY_CODE, $value);

        return $this;
    }

    public function getCompanyCodeAttribute(): ?string
    {
        return $this->getAttribute('meta')->get(Project::COMPANY_CODE);
    }

    public function setAppraisedLotValueAttribute(?float $value): self
    {
        $this->getAttribute('meta')->set(Project::APPRAISED_LOT_VALUE, $value);

        return $this;
    }

    public function getAppraisedLotValueAttribute(): ?float
    {
        return $this->getAttribute('meta')->get(Project::APPRAISED_LOT_VALUE);
    }

    public function setTotalSoldAttribute(?int $value): self
    {
        $this->getAttribute('meta')->set(Project::TOTAL_SOLD, $value);

        return $this;
    }

    public function getTotalSoldAttribute(): ?int
    {
        return $this->getAttribute('meta')->get(Project::TOTAL_SOLD);
    }

    public function setProjectDescriptionAttribute(?string $value): self
    {
        $this->getAttribute('meta')->set(Project::PROJECT_DESCRIPTION, $value);

        return $this;
    }

    public function getProjectDescriptionAttribute(): ?string
    {
        return $this->getAttribute('meta')->get(Project::PROJECT_DESCRIPTION);
    }

    public function setCompanyNameAttribute(?string $value): self
    {
        $this->getAttribute('meta')->set(Project::COMPANY_NAME, $value);

        return $this;
    }

    public function getCompanyNameAttribute(): ?string
    {
        return $this->getAttribute('meta')->get(Project::COMPANY_NAME);
    }

    public function setCompanyTINAttribute(?string $value): self
    {
        $this->getAttribute('meta')->set(Project::COMPANY_TIN, $value);

        return $this;
    }

    public function getCompanyTINAttribute(): ?string
    {
        return $this->getAttribute('meta')->get(Project::COMPANY_TIN);
    }

    public function setCompanyAddressAttribute(?string $value): self
    {
        $this->getAttribute('meta')->set(Project::COMPANY_ADDRESS, $value);

        return $this;
    }

    public function getCompanyAddressAttribute(): ?string
    {
        return $this->getAttribute('meta')->get(Project::COMPANY_ADDRESS);
    }
    public function setPagIbigFilingSiteAttribute(?string $value): self
    {
        $this->getAttribute('meta')->set(Project::PAGIBIG_FILING_SITE, $value);

        return $this;
    }

    public function getPagIbigFilingSiteAttribute(): ?string
    {
        return $this->getAttribute('meta')->get(Project::PAGIBIG_FILING_SITE);
    }

    public function setExecPositionAttribute(?string $value): self
    {
        $this->getAttribute('meta')->set(Project::EXEC_POSITION, $value);

        return $this;
    }

    public function getExecPositionAttribute(): ?string
    {
        return $this->getAttribute('meta')->get(Project::EXEC_POSITION);
    }

    public function setExecSignatoryAttribute(?string $value): self
    {
        $this->getAttribute('meta')->set(Project::EXEC_SIGNATORY, $value);

        return $this;
    }

    public function getExecSignatoryAttribute(): ?string
    {
        return $this->getAttribute('meta')->get(Project::EXEC_SIGNATORY);
    }

    public function setExecTINAttribute(?string $value): self
    {
        $this->getAttribute('meta')->set(Project::EXEC_TIN, $value);

        return $this;
    }

    public function getExecTINAttribute(): ?string
    {
        return $this->getAttribute('meta')->get(Project::EXEC_TIN);
    }

    public function setBoardResolutionDateAttribute($value): self
    {
        if ($value instanceof Carbon) {
            $value = serialize($value);
        } elseif (is_string($value)) {
            try {
                $value = serialize(Carbon::parse($value));
            } catch (\Exception $e) {
                $value = null;
            }
        } else {
            $value = null;
        }

        $this->meta[Project::BOARD_RESOLUTION_DATE] = $value;
        return $this;
    }

    public function getBoardResolutionDateAttribute(): ?Carbon
    {
        $date = $this->meta[Project::BOARD_RESOLUTION_DATE] ?? null;

        if ($date instanceof Carbon) {
            return $date;
        }

        if (is_string($date) && str_starts_with($date, 'O:')) {
            try {
                $unserialized = unserialize($date);
                return $unserialized instanceof Carbon ? $unserialized : null;
            } catch (\Exception $e) {
                return null;
            }
        }

        return null;
    }

    public function setLicenseDateAttribute($value): self
    {
        if ($value instanceof Carbon) {
            $value = serialize($value);
        } elseif (is_string($value)) {
            try {
                $value = serialize(Carbon::parse($value));
            } catch (\Exception $e) {
                $value = null;
            }
        } else {
            $value = null;
        }

        $this->meta[Project::LICENSE_DATE] = $value;
        return $this;
    }

    public function getLicenseDateAttribute(): ?Carbon
    {
        $date = $this->meta[Project::LICENSE_DATE] ?? null;

        if ($date instanceof Carbon) {
            return $date;
        }

        if (is_string($date) && str_starts_with($date, 'O:')) {
            try {
                $unserialized = unserialize($date);
                return $unserialized instanceof Carbon ? $unserialized : null;
            } catch (\Exception $e) {
                return null;
            }
        }

        return null;
    }



    public function setFilingSiteAttribute(?string $value): self
    {
        $this->getAttribute('meta')->set(Project::FILING_SITE, $value);

        return $this;
    }
    public function getFilingSiteAttribute(): ?string
    {
        return $this->getAttribute('meta')->get(Project::FILING_SITE);
    }
}
