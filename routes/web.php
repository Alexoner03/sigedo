<?php

use App\Http\Controllers\DocumentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ContractController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
    ]);
});


Route::middleware(['auth:sanctum'])->group(function () {

    #Documentos
    Route::get('/document/reviewobs',[DocumentController::class,'reviewobs'])->name('document.reviewobs');
    Route::get('/document/review',[DocumentController::class,'review'])->name('document.review');
    Route::get('/document/welcome',[DocumentController::class,'welcome'])->name('document.welcome');
    Route::resource('/document', DocumentController::class);

    #Contratos
    Route::resource('/contract', ContractController::class);

    #Usuarios
    Route::resource('/user', UserController::class)->except(['show']);


});
