<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\FavoriteController;

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
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


//buku
Route::get('/buku', [BukuController::class, 'index'])->name('buku.index');
Route::post('/buku', [BukuController::class, 'store'])->name('buku.store');
Route::get('/buku/search', [BukuController::class, 'search'])->name('buku.search');
Route::get('/detail-buku/{id}',[BukuController::class, 'galbuku'])->name('buku.detailbuku');
Route::get('/listbuku', [BukuController::class, 'listbuku'])->name('buku.listbuku');

// detail buku
Route::get('/detail-buku/{title}', [BukuController::class, 'galBuku'])->name('galeri.buku');
// rate buku
Route::post('/buku/rate/{id}', [BukuController::class, 'rate'])->name('buku.rate');
// favorites
Route::post('/buku/favorites/{id}', [BukuController::class, 'addToFavorites'])->name('buku.favorite');
Route::get('/buku/myfavorite', [FavoriteController::class, 'index'])->name('favorites.index');
// populer
Route::get('/buku/populer', [BukuController::class, 'populer'])->name('buku.populer');
// review
Route::get('/buku/{id}', [BukuController::class, 'show'])->name('buku.show');
Route::post('/buku/review/{id}', [BukuController::class, 'addReview'])->name('buku.review');

Route::middleware('auth')->group(function () {

    Route::middleware('admin')->group(function () {
        Route::get('/buku/create', [BukuController::class, 'create'])->name('buku.create');
        Route::get('/buku/edit/{id}', [BukuController::class, 'edit'])->name('buku.edit');
        Route::post('/buku/update/{id}', [BukuController::class, 'update'])->name('buku.update');
        Route::post('/buku/{id}', [BukuController::class, 'destroy'])->name('buku.destroy');
        Route::get('/gallery/delete/{id}', [BukuController::class, 'destroyGallery'])->name('galeri.destroy');
    });
    
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
