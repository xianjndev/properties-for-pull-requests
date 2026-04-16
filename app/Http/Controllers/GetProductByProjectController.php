<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use Homeful\Products\Models\Product;
use Illuminate\Http\Request;

class GetProductByProjectController extends Controller
{
    public function __invoke(Request $request, string $project_code): \Illuminate\Http\JsonResponse
    {
        $product = Product::where('meta->project_code', 'like', $project_code)
            ->where('meta->phased_out', false)
            ->firstOrFail();

        return (new ProductResource($product))->response();
    }
}
