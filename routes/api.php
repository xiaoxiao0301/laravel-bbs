<?php

use App\Http\Controllers\Api\CaptchasController;
use App\Http\Controllers\Api\CategoriesController;
use App\Http\Controllers\Api\ImagesController;
use App\Http\Controllers\Api\LinksController;
use App\Http\Controllers\Api\NotificationsController;
use App\Http\Controllers\Api\RepliesController;
use App\Http\Controllers\Api\TopicsController;
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

    // 发布话题
    Route::post('topics', [TopicsController::class, 'store'])
        ->name('api.topics.store');
    Route::patch('topics/{topic}', [TopicsController::class, 'update'])
        ->name('api.topics.update');
    Route::delete('topics/{topic}', [TopicsController::class, 'destroy'])
        ->name('api.topics.destroy');

    // 话题回复
    Route::post('topics/{topic}/replies', [RepliesController::class, 'store'])
        ->name('api.topics.replies.store');
    // 删除回复
    Route::delete('topics/{topic}/replies/{reply}', [RepliesController::class,'destroy'])
        ->name('api.topics.replies.destroy');

    // 通知列表
    Route::get('user/notifications', [NotificationsController::class, 'index'])
        ->name('api.user.notifications.index');
    // 通知统计
    Route::get('user/notifications/stats', [NotificationsController::class, 'stats'])
        ->name('api.user.notifications.stats');
    // 标记消息通知为已读
    Route::patch('user/read/notifications', [NotificationsController::class, 'read'])
        ->name('api.user.notifications.read');
});


Route::get('categories', [CategoriesController::class, 'index'])
    ->name('api.categories.index');

Route::get('topics', [TopicsController::class, 'index'])
    ->name('api.topics.index');

Route::get('topics/{topic}', [TopicsController::class, 'show'])
    ->name('api.topics.show');

Route::get('users/{user}/topics', [TopicsController::class, 'userIndex'])
    ->name('api.users.topics.index');

// 话题回复列表
Route::get('topics/{topic}/replies', [RepliesController::class, 'index'])
    ->name('api.topics.replies.index');
// 某个用户的回复列表
Route::get('users/{user}/replies', [RepliesController::class, 'userIndex'])
    ->name('api.users.replies.index');

Route::get('links', [LinksController::class, 'index'])
    ->name('api.links.index');
