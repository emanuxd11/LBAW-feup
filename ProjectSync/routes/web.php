<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ItemController;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\AdminController;

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

// Home
Route::redirect('/', '/login');

//Admin
Route::get('/adminPage', [AdminController::class, 'showAdminPage'])->name('adminPage');
Route::get('/adminPage/search', [AdminController::class, 'search'])->name('adminPage.search');

// Projects
Route::controller(ProjectController::class)->group(function () {
    Route::get('/projects', 'list')->name('projects');
    Route::get('/projects/{id}', 'show');
});

// API
Route::controller(ProjectController::class)->group(function () {
    Route::put('/api/projects', 'create');
    Route::delete('/api/projects/{project_id}', 'delete');
});

Route::controller(ItemController::class)->group(function () {
    Route::put('/api/projects/{project_id}', 'create');
    Route::post('/api/item/{id}', 'update');
    Route::delete('/api/item/{id}', 'delete');
});

// Authentication
Route::controller(LoginController::class)->group(function () {
    Route::get('/login', 'showLoginForm')->name('login');
    Route::post('/login', 'authenticate');
    Route::get('/logout', 'logout')->name('logout');
});

Route::controller(RegisterController::class)->group(function () {
    Route::get('/register', 'showRegistrationForm')->name('register');
    Route::post('/register', 'register');
});
