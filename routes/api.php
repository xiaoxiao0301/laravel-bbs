<?php

use App\Http\Controllers\Api\CaptchasController;
use App\Http\Controllers\Api\UsersController;
use App\Http\Controllers\Api\VerificationCodesController;
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


Route::get('test', function () {
    echo "123";
});

// 获取短信验证码
Route::post('verificationCodes', [VerificationCodesController::class, 'store'])->name('api.verificationCodes.store');

// 用户注册
Route::post('users', [UsersController::class, 'store'])->name('api.users.store');

// 图片验证码
Route::post('captchas', [CaptchasController::class, 'store'])->name('api.captchas.store');







