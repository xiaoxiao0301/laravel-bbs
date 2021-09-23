<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\UserRequest;
use App\Models\Image;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\Http\Resources\User as UserResource;

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

        return (new UserResource($user))->additional([
            'meta' => [
                'access_token' => auth('api')->fromUser($user),
                'token_type' => 'Bearer',
                'expires_in' => auth('api')->factory()->getTTL() * 60
            ]
        ])->response()->setStatusCode(201);

    }

    public function me()
    {
        $user = auth('api')->user();
        return new UserResource($user);
    }


    public function update(UserRequest $request)
    {
        $user = auth('api')->user();
        $attributes = $request->only(['name', 'email', 'introduction', 'registration_id']);
        if ($request->avatar_image_id) {
            $image = Image::find($request->avatar_image_id);
            $attributes['avatar'] = $image->path;
        }
        $user->update($attributes);

        return new UserResource($user);
    }

    public function activedIndex(User $user)
    {
        return UserResource::collection($user->getActiveUsers());
    }

}
