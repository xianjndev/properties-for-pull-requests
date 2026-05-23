<?php

use Illuminate\Support\Facades\Route;

//Route::get('/', function () {
//    return view('welcome');
//});


Route::get('reserve',App\Livewire\ReserveProperty::class, )->name('reserve');

Route::get('update_property_status/{property_code}/{status}/{transaction_id}',\App\Http\Controllers\UpdatePropertyStatusController::class )
    ->name('update_property_status');

//Route::get('fetch-properties', \App\Http\Controllers\FetchPropertiesController::class)->name('fetch-properties');
// Route::get('fetch-products', \App\Http\Controllers\FetchProductsController::class)->name('fetch-products');
// Route::get('fetch-projects', \App\Http\Controllers\FetchProjectsController::class)->name('fetch-projects');
