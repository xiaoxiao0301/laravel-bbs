<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    public function show(Category $category, Request $request, Topic $topic, User $user)
    {
        // 读取分类ID 关联的话题，并按每 20 条分页
        $topics = $topic->withOrder($request->order ?? '')->where('category_id', $category->id)->paginate(20);
        $activeUsers = $user->getActiveUsers();
        return view('topics.index', compact('topics','category', 'activeUsers'));
    }
}
