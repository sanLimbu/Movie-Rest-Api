<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MovieController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/movies', [MovieController::class, 'index'])->name('api.movies.index');
Route::post('/movies', [MovieController::class, 'store'])->name('api.movies.store');
Route::delete('/movies/{id}', [MovieController::class, 'destroy'])->name('api.movies.destroy');
Route::put('/movies/{movie}', [MovieController::class, 'update'])->name('api.movies.update');



