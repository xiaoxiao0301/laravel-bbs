<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\UserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class UsersController extends Controller
{
    public function store(UserRequest $request)
    {
        $verifyData = Cache::get($request->verification_key);


        if (!$verifyData) {
            return response('验证码已失效', 422);
        }

        if (!hash_equals($verifyData['code'], $request->verification_code)) {
            return response('验证码错误', 401);
        }

        $user = User::create([
            'name' => $request->name,
            'phone' => $verifyData['phone'],
            'password' => $request->password,
        ]);

        Cache::forget($request->verification_key);

        return response('', 201);

    }
}
