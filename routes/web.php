<?php

use App\Http\Controllers\BusinessController;
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


    #contratos
    Route::get('/contract/menu',[ContractController::class,'welcome'])->name('contract.welcome');
    Route::resource('/contract', ContractController::class);

    #business
    Route::get('/businees/{business}/position/{position}/get-employees',[BusinessController::class,'getEmployeeByBusinessAndPosition'])->name('business.position.users');


    #Documentos
    Route::get('/document/{user}/client',[DocumentController::class,'getDocumentsByClient'])->name('document.client');
    Route::get('/document/{document}/show-observations',[DocumentController::class,'showWithObservations'])->name('document.observations');
    Route::get('/document/{document}/contract',[DocumentController::class,'finalizeContract'])->name('document.contract');
    Route::post('/document/{document}/finalize',[DocumentController::class,'updateFromCreatorFinalize'])->name('document.contract.finalize');
    Route::post('/document/{document}/update-from-reviewer',[DocumentController::class,'updateFromReviewer'])->name('document.update.reviewer');
    Route::post('/document/{document}/update-from-creator',[DocumentController::class,'updateFromCreator'])->name('document.update.creator');
    Route::resource('/document', DocumentController::class);

    #Contratos

    #Usuarios
    Route::resource('/user', UserController::class)->except(['show']);

});
