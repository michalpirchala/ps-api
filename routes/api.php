<?php

use App\Http\Controllers\CodelistController;
use App\Http\Controllers\SalesmanController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('/codelists', [CodelistController::class, 'show']);
Route::controller(SalesmanController::class)->group(function () {
    Route::get('/salesmen', 'index');
    Route::post('/salesmen', 'store');
    Route::get('/salesmen/{id}', 'show');
    Route::put('/salesmen/{id}', 'update');
    Route::delete('/salesmen/{id}', 'destroy');
});
