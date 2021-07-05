<?php

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use Dcat\Admin\Admin;

Admin::routes();

Route::group([
    'prefix'     => config('admin.route.prefix'),
    'namespace'  => config('admin.route.namespace'),
    'middleware' => config('admin.route.middleware'),
], function (Router $router) {

    $router->get('/', 'HomeController@index');

    // 用户
    $router->resource('users', 'UserController');
    // 链接
    $router->resource('links', 'LinkController');
    // 分类
    $router->resource('categories', 'CategoryController');
    // 话题
    $router->resource('topics', 'TopicController');
    // 回复
    $router->resource('replies', 'ReplyController');
    // 站点配置
    $router->resource('config', 'ConfigController');

});
