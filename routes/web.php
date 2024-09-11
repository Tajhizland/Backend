<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function (Request $request) {
   echo "Tajhizland Backend Api";
});
Route::get("option/get/{id}", [\App\Http\Controllers\V1\Admin\ProductController::class, "getOption"]);
Route::get("filter/get/{id}", [\App\Http\Controllers\V1\Admin\ProductController::class, "getFilter"]);
