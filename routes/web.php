<?php

use App\Http\Controllers\DocumentController;
use App\Http\Controllers\UserController;
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
    Route::get('/document/welcome',[DocumentController::class,'welcome'])->name('document.welcome');
    Route::resource('/document', DocumentController::class);

    #Usuarios
    Route::resource('/user', UserController::class)->except(['show']);
});
