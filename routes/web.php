<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Models\PropDefinition;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\RecordController;
use App\Http\Controllers\PropDefinitionController;
use App\Http\Controllers\StoreFavoriteController;
use App\Http\Controllers\RecordFavoriteController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    if (\Auth::check())
        return redirect("/dashboard");

    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, "index"])
    ->middleware(['auth', 'verified'])->name('dashboard');

Route::group([["middleware" => "auth"]], function () {
    // store へのリクエストをルーティング
    Route::resource("stores", StoreController::class, ["only" => ["store", "show", "edit", "update", "destroy"]]);
    
    // store へのその他の操作をルーティング
    Route::prefix("stores/{id}")->group(function () {
        // store をお気に入りへ追加/削除
        Route::post('favorite', [StoreFavoriteController::class, "store"])->name("stores.favorite");
        Route::delete('unfavorite', [StoreFavoriteController::class, "destroy"])->name("stores.unfavorite");
    });

    // record へのリクエストをルーティング
    Route::resource("records", RecordController::class, ["only" => ["store", "edit", "update", "destroy"]]);

    // record へのその他の操作をルーティング
    Route::prefix("records/{id}")->group(function () {
        // record をお気に入りへ追加/削除
        Route::post('favorite', [RecordFavoriteController::class, "store"])->name("records.favorite");
        Route::delete('unfavorite', [RecordFavoriteController::class, "destroy"])->name("records.unfavorite");
        Route::post('insert', [RecordController::class, "insert"])->name("records.insert");
    });

    // propDefinition へのリクエストをルーティング
    Route::resource("prop_definitions", PropDefinitionController::class, ["only" => ["store", "update", "destroy"]]);
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});



require __DIR__.'/auth.php';
