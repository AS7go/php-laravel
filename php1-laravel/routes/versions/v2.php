<?php

use Illuminate\Support\Facades\Route;

Route::resource('products', \App\Http\Controllers\Api\V2\ProductsController::class); //v2
