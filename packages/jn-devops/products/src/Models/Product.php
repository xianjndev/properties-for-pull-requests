<?php

namespace Homeful\Products\Models;

use App\Models\UpdateLog;
use Homeful\Common\Traits\HasPackageFactory as HasFactory;
use Spatie\SchemalessAttributes\SchemalessAttributes;
use Homeful\Products\Traits\HasAdditionalAttributes;
use Homeful\Common\Interfaces\ProductInterface;
use Homeful\Common\Interfaces\PropertyInterface;
use Illuminate\Database\Eloquent\Model;
use Homeful\Common\Casts\PriceCast;
use Homeful\Common\Traits\HasMeta;
use Whitecube\Price\Price;
use Brick\Money\Money;

/**
 * Class Product
 *
 * @property int $id
 * @property string $sku
 * @property string $name
 * @property string $brand
 * @property string $category
 * @property string $description
 * @property Price $price
 * @property string $market_segment
 * @property string $location
 * @property string $destinations
 * @property string $directions
 * @property string $amenities
 * @property string $facade_url
 * @property string $project_location
 * @property string $project_code
 * @property string $property_name
 * @property string $phase
 * @property string $block
 * @property string $lot
 * @property float|null $lot_area
 * @property float|null $floor_area
 * @property string $project_address
 * @property string $property_type
 * @property string house_type
 * @property string $unit_type
 * @property SchemalessAttributes $meta
 * @property Price $appraised_value
 * @property float $percent_down_payment
 * @property int $down_payment_term
 * @property float $percent_miscellaneous_fees
 * @property string $status_code
 * @property string $key_location
 * @property string $digital_assets
 * @property float $percent_gross_monthly_income
 * @property int $max_age
 * @property float $balance_payment_interest_rate
 * @property float $mortgage_redemption_insurance_fee
 * @property float $income_requirement_multiplier
 * @property int $maximum_paying_age
 * @property int balance_payment_term
 * @property float processing_fee
 * @property bool $phased_out
 *
 * @method int getKey()
 */
class Product extends Model implements ProductInterface
{
    use HasAdditionalAttributes;
    use HasFactory;
    use HasMeta;

    protected $fillable = [
        'sku',
        'name',
        'brand',
        'category',
        'description',
        'price',
        'digital_assets',
        'phased_out',
    ];

    protected $casts = [
        'price' => PriceCast::class,
    ];

    public function getConnectionName()
    {
        $connection = config('products.models.product.connection');

        return !empty($connection)
            ? $connection
            : parent::getConnectionName();
    }

    public function getTable()
    {
        $table = config('products.models.product.table');

        return !empty($table)
            ? $table
            : parent::getTable();
    }

    public function getSKU(): string
    {
        return $this->sku;
    }

    public function getProcessingFee(): Price
    {
        return new Price(Money::of(10000, 'PHP'));
    }

    public function getTotalContractPrice(): Price
    {
        return $this->price;
    }

    public function getAppraisedValue(): Price
    {
        return $this->appraised_value;
    }

    public function getPercentDownPayment(): float
    {
        return $this->percent_down_payment;
    }

    public function getDownPaymentTerm(): int
    {
        return $this->down_payment_term;
    }

    public function getPercentMiscellaneousFees(): float
    {
        return $this->percent_miscellaneous_fees;
    }

    public function updateLogs(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(UpdateLog::class, 'loggable');
    }
}
