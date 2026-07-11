<?php

use App\Http\Controllers\ApiController;
use App\Http\Controllers\GetNextPropertyDetailController;
use App\Http\Controllers\GetProductByProjectController;
use App\Http\Controllers\GetProductDetailController;
use App\Http\Controllers\GetProjectDetailController;
use App\Http\Controllers\GetPropertyDetailController;
use App\Http\Controllers\GetTechnicalDescriptionController;
use App\Http\Controllers\UpsertTechnicalDescriptionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

    Route::get('technical-descriptions/{property_code}', GetTechnicalDescriptionController::class)
        ->middleware('throttle:60,1')
        ->name('technical-description-details');

    Route::post('technical-descriptions/{property_code}', UpsertTechnicalDescriptionController::class)
        ->middleware('throttle:30,1')
        ->name('technical-description-create');

    Route::match(['put', 'patch'], 'technical-descriptions/{property_code}', UpsertTechnicalDescriptionController::class)
        ->middleware('throttle:30,1')
        ->name('technical-description-update');

    Route::get('products/by-project/{project_code}', GetProductByProjectController::class)
        ->name('product-by-project');
});
