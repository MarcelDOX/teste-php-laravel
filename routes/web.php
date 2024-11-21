<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DocumentController;

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

Route::controller(DocumentController::class)->group(function () {
    Route::view('/documents', 'documents.import');
    Route::post('/documents/import', 'import');
    Route::get('/documents/dispatch', 'dispatch');
});
