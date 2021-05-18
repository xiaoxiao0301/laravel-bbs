<?php

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\TopicsController;
use App\Http\Controllers\UsersController;
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

Route::get('/', [PagesController::class, 'root'])->name('root');

// 用户身份验证相关的路由
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');
// 用户注册相关路由
Route::get('register', [RegisterController::class,'showRegistrationForm'])->name('register');
Route::post('register', [RegisterController::class,'register']);
// 密码重置相关路由
Route::get('password/reset', [ForgotPasswordController::class,'showLinkRequestForm'])->name('password.request');
Route::post('password/email', [ForgotPasswordController::class,'sendResetLinkEmail'])->name('password.email');
Route::get('password/reset/{token}', [ResetPasswordController::class,'showResetForm'])->name('password.reset');
Route::post('password/reset', [ResetPasswordController::class,'reset'])->name('password.update');
// Email 认证相关路由
Route::get('email/verify', [VerificationController::class,'show'])->name('verification.notice');
Route::get('email/verify/{id}', [VerificationController::class,'verify'])->name('verification.verify');
Route::get('email/resend', [VerificationController::class,'resend'])->name('verification.resend');

// 用户资料
Route::resource('users', UsersController::class, ['only' => ['show', 'update', 'edit']]);
// 话题
Route::resource('topics', TopicsController::class, ['only' => ['index', 'create', 'store', 'update', 'edit', 'destroy']]);
// 分类
Route::resource('categories', CategoriesController::class, ['only' => 'show']);

Route::get('topics/{topic}/{slug?}', [TopicsController::class, 'show'])->name('topics.show');
Route::post('upload_image', [TopicsController::class, 'uploadImage'])->name('topics.upload_image');
