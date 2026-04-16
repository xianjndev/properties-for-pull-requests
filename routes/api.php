<?php

use App\Http\Controllers\{ApiController, GetNextPropertyDetailController, GetProductByProjectController, GetProductDetailController, GetProjectDetailController, GetPropertyDetailController};
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/login', [ApiController::class, 'login'])->middleware('throttle:5,1');

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('fetch-products', \App\Http\Controllers\FetchProductsController::class)->name('fetch-products');
    Route::get('fetch-projects', \App\Http\Controllers\FetchProjectsController::class)->name('fetch-projects');

    Route::get('property-details/{property_code}', GetPropertyDetailController::class)
        ->name('property-details');

    Route::get('product-details/{product_code}', GetProductDetailController::class)
        ->name('product-details');

    Route::get('next-property-details/{product_code}', GetNextPropertyDetailController::class)
        ->name('next-property-details');

    Route::get('projects/{project_code}', GetProjectDetailController::class)
        ->name('project-details');

    Route::get('products/by-project/{project_code}', GetProductByProjectController::class)
        ->name('product-by-project');
});
