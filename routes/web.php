<?php

use App\Http\Controllers\CatalogController;
use App\Http\Controllers\PublisherController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\MemberController;

use Illuminate\Support\Facades\Route;

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
    if(Auth::check()){
        return redirect('/home');
    } else {
        return redirect('/login');
    }
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'home']);
Route::get('/test_spatie', [App\Http\Controllers\HomeController::class, 'test_spatie']);

Route::resource('/transactions', App\Http\Controllers\TransactionController::class);
Route::get('/api/transactions', [App\Http\Controllers\TransactionController::class, 'api']);

Route::resource('catalogs', App\Http\Controllers\CatalogController::class);

Route::resource('/authors', App\Http\Controllers\AuthorController::class);
Route::get('/api/authors', [App\Http\Controllers\AuthorController::class, 'api']);

Route::resource('/publishers', App\Http\Controllers\PublisherController::class);
Route::get('/api/publishers', [App\Http\Controllers\PublisherController::class, 'api']);

Route::resource('/members', App\Http\Controllers\MemberController::class);
Route::get('/api/members', [App\Http\Controllers\MemberController::class, 'api']);

Route::resource('/books', App\Http\Controllers\BookController::class);
Route::get('/api/books', [App\Http\Controllers\BookController::class, 'api']);

//get data dropdown
Route::get('selectmbr', [App\Http\Controllers\TransactionController::class , 'selectmbr'])->name('select.mbr');
Route::get('selectbk', [App\Http\Controllers\TransactionController::class, 'selectbk'])->name('select.bk');


// Route::get('test_spatie' , 'AdminController@test_spatie');
Route::middleware('auth')->group(function () {
    Route::resource('/parent', App\Http\Controllers\ParentController::class);
});