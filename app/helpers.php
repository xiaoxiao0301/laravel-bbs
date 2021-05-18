<?php

function route_class()
{
    return Str::replace('.', '-', Route::currentRouteName());
}

function category_nav_active($categoryId)
{
    return active_class((if_route('categories.show')) && if_route_param('category', $categoryId));
}

function make_excerpt($body, $length = 200)
{
    $excerpt = trim(preg_replace('/\r\n|\r|\n+/', ' ', strip_tags($body)));
    return Str::limit($excerpt, $length);
}
