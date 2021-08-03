<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\VerificationCodeRequest;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Overtrue\EasySms\EasySms;
use Overtrue\EasySms\Exceptions\NoGatewayAvailableException;

class VerificationCodesController extends Controller
{
    public function store(VerificationCodeRequest $request, EasySms $easySms)
    {
        $capthchaData = Cache::get($request->captcha_key);
        if (!$capthchaData) {
            return response("图片验证码失效", 422);
        }
        if (!captcha_api_check($request->captcha_code, $capthchaData['captchaKey'], 'flat')) {
            Cache::forget($request->captcha_key);
            return response("验证码错误", 403);
        }

        $phone = $request->phone;

        if (!app()->environment('production')) {
            $code = '6379';
        } else {
            $code = str_pad(random_int(1, 9999), 4, 0, STR_PAD_LEFT);
            try {
                $easySms->send($phone, [
                    'template' => 'SMS_179611210',
                    'data' => [
                        'code' => $code
                    ]
                ]);
            } catch (NoGatewayAvailableException $exception) {
                $message = $exception->getException('aliyun')->getMessage();
                return response(['message' => $message ?? '发送短信异常'], 500);
            }

        }

        $key = 'verificationCodes_'.Str::random(15);
        // 缓存验证码 10分钟过期
        $experiAt = now()->addMinutes(10);
        Cache::put($key, ['phone' => $phone, 'code' => $code], $experiAt);
        // 清楚图片验证码缓存
        Cache::forget($request->captcha_key);
        return response([
            'key' => $key,
            'expired_at' => $experiAt->toDateTimeString()
        ], 201);

    }
}
