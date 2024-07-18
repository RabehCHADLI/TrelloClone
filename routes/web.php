<?php

use App\Http\Controllers\BoardListsController;
use App\Http\Controllers\BoardsController;
use App\Http\Controllers\CardsController;
use App\Http\Controllers\ProfileController;
use App\Models\Boards;
use App\Models\Cards;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
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
    $boards = Boards::all();
    $user = Auth::user();

    if (empty($user->id)) {
        return redirect(route('/register'));
    }
    $userboards = $boards->where('user_id', $user->id);
    return view('dashboard', compact('userboards'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
Route::resource('boards', BoardsController::class)->names([
    'index' => 'boards.index',
    'show' => 'boards.show'
]);
Route::get('/boards/{id}', [BoardsController::class, 'show']);
Route::resource('boardLists', BoardListsController::class)->names([
    'index' => 'boardLists.index',
]);
Route::get('/boards/edit/{id}', [BoardsController::class, 'edit']);
Route::get('/cards/{id}/move', [CardsController::class, 'edit'])->name('card.edit');
Route::get('/cards/moveList/{id}/{cardId}', [CardsController::class, 'edit'])->name('card.list');
Route::post('/cards/move', [CardsController::class, 'show'])->name('card.position');
Route::post('/list/move', [BoardListsController::class, 'show'])->name('list.position');
Route::put('/boards/put/{id}', [BoardsController::class, 'update']);
Route::delete('/boards/delete/{id}', [BoardsController::class, 'destroy']);
Route::delete('/boardLists/delete/{id}', [BoardListsController::class, 'destroy']);
Route::delete('/cards/delete/{id}', [CardsController::class, 'destroy'])->name('cards.destroy');
Route::post('/cards/create', [CardsController::class, 'store'])->name('card.store');


require __DIR__ . '/auth.php';
