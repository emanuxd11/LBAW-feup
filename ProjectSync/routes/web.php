<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ForumController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PostCommentController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\TaskCommentsController;
use App\Http\Controllers\EmailController;

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

// Contacts
Route::view('/contacts', 'pages.contacts')->name('contacts');


// Admin
Route::get('/adminPage', [AdminController::class, 'showAdminPage'])->name('adminPage');
Route::get('/adminPage/search', [AdminController::class, 'search'])->name('adminPage.search');
Route::get('/create_user_form', [AdminController::class, 'showCreateUserForm'])->name('adminPage.create_user_form');
Route::post('/block_user', [AdminController::class, 'blockUser'])->name('adminPage.block');
Route::put('/create_user', [AdminController::class, 'createUser'])->name('adminPage.create_user');
Route::delete('/delete_user', [AdminController::class, 'deleteUser'])->name('adminPage.delete');
Route::get('/userHandleLogic', [AdminController::class, 'showUserLogicPage'])->name('adminPage.userLogic');
Route::post('/assignNewCoordinator', [AdminController::class, 'adminAssignNewCoordinator'])->name('admin.assign.new.coordinator');

// Projects
Route::controller(ProjectController::class)->group(function () {
    Route::get('/projects', 'list')->name('projects');
    Route::get('/projects/{id}', 'show')->name('project.show');
    Route::post('/projects/{project_id}/update', 'update')->name('project.update');
    Route::put('/api/projects', 'create')->name('create_project');
    Route::post('/api/projects/{project_id}', 'archive')->name('archive');
    Route::post('/api/projects/{project_id}/assignNewCoordinator', 'assignNewCoordinator')->name('assign.new.coordinator');
    Route::delete('/projects/{project_id}/remove_member/{user_id}', 'remove_member')->name('remove_member');
    Route::delete('/projects/{project_id}/member_leave/{user_id}', 'member_leave')->name('member_leave');
    Route::get('/projects/accept/invitation/redirect/{project_id}/{user_id}/{token}', 'acceptInvitationRedirect')->name('accept.project.invitation.redirect');
    Route::post('/projects/{project_id}/accept_invitation/{user_id}/{token}', 'acceptInvitation')->name('accept.project.invitation');
    Route::delete('/projects/revoke/invitations/{project_id}/{user_id}', 'revokeInvitations')->name('project.revoke.invitations');
    // the ajax part
    Route::get('/projects/{id}/search_user', 'search_user')->name('project_search_user');
    Route::post('/projects/{id}/invite_user', 'inviteUserToProject')->name('project_invite_user');
    Route::get('/projects/{id}/search_task', 'search_task')->name('project_search_task');
    Route::post('/projects/{project_id}/favorite', 'favorite')->name('project.favorite');
    Route::post('/projects/{id}/email_invite_user', 'sendProjectInvitation')->name('project.invite.user');
});

// Forum
Route::controller(ForumController::class)->group(function () {
    Route::get('/projects/{id}/forum', 'show')->name('forum.show');
    Route::get('/projects/{id}/forum/search', 'search')->name('forum.search');
});

// Post
Route::controller(PostController::class)->group(function () {
    Route::get('/projects/{project_id}/forum/post/{id}', 'show')->name('post.show');
    Route::put('/createPost', 'create')->name('post.create');
    Route::post('/post/{id}', 'update')->name('post.update');
    Route::post('/post/{id}/upvote', 'upvote')->name('post.upvote');
    Route::delete('/post/{id}', 'delete')->name('post.delete');
});

// PostComment
Route::controller(PostCommentController::class)->group(function () {
    Route::put('/createPostComment', 'create')->name('postComment.create');
    Route::post('/postComment/{id}', 'update')->name('postComment.update');
    Route::delete('/postComment/{id}', 'delete')->name('postComment.delete');
});

// Task
Route::controller(TaskController::class)->group(function () {
    Route::put('/api/projects/{project_id}', 'create')->name('create_task');
    Route::post('/api/task/{id}', 'update')->name('edit_task');
    Route::delete('/api/task/{id}', 'delete')->name('delete_task');
    Route::get('/api/projects/{project_id}/task/{id}', 'show')->name('show_task');
    Route::delete('/api/task/{id}/remove-user', 'removeUserFromTask')->name('task.remove.user');
});

// TaskComments
Route::controller(TaskCommentsController::class)->group(function () {
    Route::put('/api/task/{id}/createComment', 'create')->name('taskComment.create');
    Route::delete('/api/task/{id}/deleteComment', 'delete')->name('taskComment.delete');
    Route::post('/api/task/{id}/updateComment', 'update')->name('taskComment.update');
});

// Authentication
Route::controller(LoginController::class)->group(function () {
    Route::get('/login', 'showLoginForm')->name('login');
    Route::post('/login', 'authenticate');
    Route::get('/logout', 'logout')->name('logout');
});

// Register
Route::controller(RegisterController::class)->group(function () {
    Route::get('/register', 'showRegistrationForm')->name('register');
    Route::post('/register', 'register');
});

// Profile Page
Route::get('/profile/{username}', [ProfileController::class, 'showProfilePage'])->name('profilePage');
Route::get('/profile/{username}/edit', [ProfileController::class, 'editProfile'])->name('editProfile');
Route::match(['post', 'put'],'/profile/{username}/update', [ProfileController::class, 'updateProfile'])->name('updateProfile');
Route::delete('/profile/{username}/delete', [ProfileController::class, 'deleteAccount'])->name('delete.account');

// Emails
Route::controller(EmailController::class)->group(function() {
    Route::post('password/email', 'sendResetLink')->name('password.email');
});

// Password Reset
Route::controller(ResetPasswordController::class)->group(function() {
    Route::get('password/reset', 'showLinkRequestForm')->name('password.request');
    Route::get('password/reset/{token}', 'showResetForm')->name('password.reset');
    Route::post('password/reset', 'resetPassword')->name('password.update');
});

Route::controller(NotificationController::class)->group(function() {
    Route::get('{username}/notifications', 'show')->name('notification.show');
    Route::post('{username}/notifications/check', 'check')->name('notification.check');
    Route::post('{username}/notifications/checkAll', 'checkAll')->name('notification.checkAll');
});
