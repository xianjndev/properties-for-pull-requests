<?php

namespace App\Http\Controllers;

use Homeful\Products\Models\Product;
use Illuminate\Http\Request;
use App\Data\FetchData;

class FetchProductsController extends Controller
{
    public function __invoke(Request $request): FetchData
    {
        $products = Product::withMeta(['phased_out' => false])->get();

        return FetchData::from(compact('products'));
//            ->only(
//                'products.sku',
//                'products.name',
//                'products.brand',
//                'products.category',
//                'products.price',
//                'products.appraised_value',
//                'products.percent_down_payment',
//                'products.down_payment_term',
//                'products.percent_miscellaneous_fees',
//            )
    }
}
