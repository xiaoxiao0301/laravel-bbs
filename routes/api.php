<?php

use App\Http\Controllers\Api\CaptchasController;
use App\Http\Controllers\Api\CategoriesController;
use App\Http\Controllers\Api\ImagesController;
use App\Http\Controllers\Api\UsersController;
use App\Http\Controllers\Api\VerificationCodesController;
use App\Http\Controllers\Api\AuthorizationsController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// api路由默认携带throttle中间件，限流是1分钟之内可以请求60次， 60:1

// 获取短信验证码
Route::post('verificationCodes', [VerificationCodesController::class, 'store'])
    ->name('api.verificationCodes.store');

// 用户注册
Route::post('users', [UsersController::class, 'store'])
    ->name('api.users.store');

// 图片验证码
Route::post('captchas', [CaptchasController::class, 'store'])
    ->name('api.captchas.store');

// 第三方登录
Route::post('socials/{social_type}/authorizations', [AuthorizationsController::class, 'socialStore'])
    ->name('api.socials.authorizations.store');

// 登录
Route::post('authorizations', [AuthorizationsController::class, 'store'])
    ->name('api.authorizations.store');

// 刷新token
Route::put('authorizations/current', [AuthorizationsController::class, 'update'])
    ->name('api.authorizations.update');
// 删除token
Route::delete('authorizations/current', [AuthorizationsController::class,'destroy'])
    ->name('api.authorizations.destroy');

// 需要传递token才能使用的接口
Route::middleware('auth:api')->group(function () {
    // 当前登录用户信息
    Route::get('user', [UsersController::class,'me'])
        ->name('api.user.show');

    Route::patch('user', [UsersController::class, 'update'])
        ->name('api.user.update');

    // 图片资源
    Route::post('images', [ImagesController::class, 'store'])
        ->name('api.images.store');
});


Route::get('categories', [CategoriesController::class, 'index'])
    ->name('api.categories.index');
