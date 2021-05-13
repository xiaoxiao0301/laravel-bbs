<?php

function route_class()
{
    return Str::replace('.', '-', Route::currentRouteName());
}
