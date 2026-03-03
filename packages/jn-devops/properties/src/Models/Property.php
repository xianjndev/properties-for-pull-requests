<?php

namespace Homeful\Properties\Models;

use Homeful\Properties\Traits\HasAdditionalPropertyAttributes;
use Homeful\Common\Traits\HasPackageFactory as HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;
use Homeful\Products\Models\Product;
use Homeful\Common\Traits\HasMeta;

/**
 * Class Property
 *
 * @property int $id
 * @property string $code
 * @property string $name
 * @property string $type
 * @property string $cluster
 * @property string $phase
 * @property string $block
 * @property string $lot
 * @property string $building
 * @property float $floor_area,
 * @property float $lot_area,
 * @property string $unit_type
 * @property string $unit_type_interior
 * @property string $house_color
 * @property string $roof_style
 * @property bool $end_unit
 * @property bool $veranda
 * @property bool $balcony
 * @property bool $firewall
 * @property bool $eaves
 * @property int $bedrooms
 * @property int $toilets_and_bathrooms
 * @property int $parking_slots
 * @property int $carports
 * @property string $project_code
 * @property string $sku
 * @property Product $product
 * @property Project $project
 * @property string $status
 * @property float $tcp
 * @property string $project_description
 *
 * @method int getKey()
 */
class Property extends Model
{
    use HasAdditionalPropertyAttributes;
    use HasFactory;
    use HasMeta;

    protected $fillable = [
        'code',
        'name',
        'type',
        'cluster',
        'sku',
        'status',
        'tcp',
    ];

    public function getConnectionName()
    {
        $connection = config('properties.models.property.connection');

        return !empty($connection)
            ? $connection
            : parent::getConnectionName();
    }

    public function getTable()
    {
        $table = config('properties.models.property.table');

        return !empty($table)
            ? $table
            : parent::getTable();
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'sku', 'sku', 'product');
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_code', 'code', 'projects');
    }
}
