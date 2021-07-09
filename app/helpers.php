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

function get_db_config()
{
    if (getenv('IS_IN_HEROKU')) {
        $url = parse_url(getenv("DATABASE_URL"));
        return $dbConfig = [
            'connection' => 'pgsql',
            'host' => $url["host"],
            'database' => substr($url["path"], 1),
            'username' => $url["user"],
            'password' => $url["pass"]
        ];
    } else {
        return $dbConfig = [
            'connection' => env('DB_CONNECTION'),
            'host' => env('DB_HOST'),
            'database' => env('DB_DATABASE', 'forge'),
            'username' => env('DB_USERNAME', 'forge'),
            'password' => env('DB_PASSWORD', '')
        ];
    }
}
