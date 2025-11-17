<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PortfolioController;

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

// Show the welcome page (now wired to PortfolioController@index so it can
// display stored portfolio entries and the add form)
Route::get('/', [PortfolioController::class, 'index']);

// Endpoint to store a simple portfolio entry (saved to storage/app/portfolio.json)
Route::post('/portfolio', [PortfolioController::class, 'store'])->name('portfolio.store');

// Include CV routes
require __DIR__ . '/cv.php';

