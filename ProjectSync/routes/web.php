<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
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

// Home
Route::get('/', [HomeController::class, 'index'])->name('home');

// FAQ
Route::view('/faq', 'pages.faq')->name('faq');

// About Us
Route::view('/aboutUs', 'pages.aboutUs')->name('aboutUs');

// Admin
Route::get('/adminPage', [AdminController::class, 'showAdminPage'])->name('adminPage');
Route::get('/adminPage/search', [AdminController::class, 'search'])->name('adminPage.search');
Route::post('/block_user', [AdminController::class, 'blockUser'])->name('adminPage.block');

// Projects
Route::controller(ProjectController::class)->group(function () {
    Route::get('/projects', 'list')->name('projects');
    Route::get('/projects/{id}', 'show');
    Route::put('/api/projects', 'create')->name('create_project');
    Route::delete('/api/projects/{project_id}', 'delete');
});

// Task
Route::controller(TaskController::class)->group(function () {
    Route::put('/api/projects/{project_id}', 'create')->name('create_task');
    Route::post('/api/task/{id}', 'update')->name('edit_task');
    Route::delete('/api/task/{id}', 'delete')->name('delete_task');
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

// Profile Page
Route::get('/profile/{username}', [ProfileController::class, 'showProfilePage'])->name('profilePage');
Route::get('/profile/{username}/edit', [ProfileController::class, 'editProfile'])->name('editProfile');
Route::match(['post', 'put'],'/profile/{username}/update', [ProfileController::class, 'updateProfile'])->name('updateProfile');
