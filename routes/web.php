<?php

use App\Http\Controllers\BusinessController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ContractController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
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

Route::get('/clear-cache', function() {
    $exitCode = Artisan::call('cache:clear');
    Artisan::call('route:cache');
    // return what you want
});

Route::get('/create-symlink', function() {
    $exitCode = Artisan::call('storage:link');
    return $exitCode;
});

Route::middleware(['auth:sanctum'])->group(function () {


    #contratos
    Route::get('/contract/menu',[ContractController::class,'welcome'])->name('contract.welcome');
    Route::get('/contract/{contract}/review',[ContractController::class,'review'])->name('contract.review');
    Route::get('/contract/{contract}/correct',[ContractController::class,'correct'])->name('contract.correct');
    Route::get('/contract/{contract}/finalize',[ContractController::class,'finalize'])->name('contract.finalize');
    Route::patch('/contract/{contract}/review',[ContractController::class,'updateReview'])->name('contract.review.update');
    Route::post('/contract/{contract}/correct',[ContractController::class,'updateCorrect'])->name('contract.correct.update');
    Route::patch('/contract/{contract}/finalize',[ContractController::class,'updateFinalize'])->name('contract.finalize.update');
    Route::patch('/contract/{contract}/finalize-forze',[ContractController::class,'forceUpdateFinalize'])->name('contract.finalize.update.force');
    Route::resource('/contract', ContractController::class)->only(['index','create','store']);

    #business
    Route::get('/businees/{business}/position/{position}/get-employees',[BusinessController::class,'getEmployeeByBusinessAndPosition'])->name('business.position.users');
    Route::resource('business', BusinessController::class);



    #Usuarios
    Route::patch('/user/{user}/restore',[UserController::class,"restore"])->name('user.restore');
    Route::resource('/user', UserController::class)->except(['show']);

});
