<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Resources\Category as CategoryResource;

class CategoriesController extends Controller
{
    public function index()
    {
        return CategoryResource::collection(Category::all());
    }
}
