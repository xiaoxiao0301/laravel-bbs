<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CaptchaRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Mews\Captcha\Captcha;

class CaptchasController extends Controller
{
    public function store(CaptchaRequest $request, Captcha $captcha)
    {
        $captchaKey = 'captcha-' . Str::random(15);
        $phone = $request->phone;
        /**
         * captchaInfo 结构如下
         * [
         *     "sensitive" => false,
         *     "key" => 图片验证码值,
         *     "img" => data:image/png;base64, 直接复制到浏览器地址可以查看
         * ]
        */
        $captchaInfo = $captcha->create('flat', true);
        $expiredAt = now()->addMinutes(5);
        Cache::put($captchaKey, [
            'phone' => $phone,
            'captchaKey' => $captchaInfo['key']
        ], $expiredAt);

        return response([
            'captcha_key' => $captchaKey,
            'expired_at' => $expiredAt->toDateTimeString(),
            'captcha_image_content' => $captchaInfo['img']
        ], 201);

    }
}
