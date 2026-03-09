<?php

namespace Homeful\Properties\Models;

use Carbon\Carbon;
use Homeful\Properties\Traits\HasAdditionalProjectAttributes;
use Homeful\Common\Traits\HasPackageFactory as HasFactory;
use Homeful\Property\Enums\{HousingType, MarketSegment};
use Illuminate\Database\Eloquent\Model;
use Homeful\Common\Traits\HasMeta;

/**
 * Class Project
 *
 * @property int $id
 * @property string $code
 * @property string $name
 * @property string $location
 * @property string $address
 * @property MarketSegment $type
 * @property HousingType $housingType
 * @property string $licenseNumber
 * @property Carbon $licenseDate
 * @property string $company_code
 * @property float $appraised_lot_value
 * @property int $total_sold
 * @property string $company_name
 * @property string $company_tin
 * @property string $company_address
 * @property string $pagibig_filing_site
 * @property string $exec_position
 * @property string $exec_signatory
 * @property string $exec_tin
 * @property Carbon $board_resolution_date
 *
 * @method int getKey()
 */
class Project extends Model
{
    use HasAdditionalProjectAttributes;
    use HasFactory;
    use HasMeta;

    protected $fillable = [
        'code',
        'name',
        'location',
        'address',
        'company_code',
        'appraised_lot_value',
        'total_sold',
        'project_description',
        'company_name',
        'company_tin',
        'company_address',
        'pagibig_filing_site',
        'exec_position',
        'exec_signatory',
        'exec_tin',
        'board_resolution_date'
    ];

    public function getConnectionName()
    {
        $connection = config('properties.models.project.connection');

        return !empty($connection)
            ? $connection
            : parent::getConnectionName();
    }

    public function getTable()
    {
        $table = config('properties.models.project.table');

        return !empty($table)
            ? $table
            : parent::getTable();
    }
}
