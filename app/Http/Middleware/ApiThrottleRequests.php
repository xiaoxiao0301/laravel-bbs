<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\Request;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Symfony\Component\HttpFoundation\Response;

class ApiThrottleRequests extends ThrottleRequests
{
    // api路由默认携带throttle中间件，限流是1分钟之内可以请求60次， 60:1
    /*
     * 限流原理
        获取唯一请求来源，进行唯一标识(key)
        获取该请求请求次数 (hits)
        判断是否超过最大限制
        若达到上限，进入5。未达到，则进入6
        丢出访问次数限制异常，结束请求。
        首先判断hits 是否达到限制，若未达到，进入7。若达到，进入8。
        hits 进行计数 + 1，更新到缓存中。 若是第一次，则需要 hits = 1（次数）, 并添加访问标识 key
         （1分钟）到缓存中，以标记请求周期。
        请求次数已达到上限（hits >= 60），此时需要判断是否在周期范围内(1分钟)，若在周期内，进入9；不在周期内，进入10.
        此时请求处在 “1分钟内请求次数达到60次”，即达到限制，返回 false 。
        此时请求处在 “不在1分钟内请求次数达到60次”，即不在周期内，需要重新计算周期。
     */
    // 达到限流上限返回的是一个429的html页面，api请求需要替换成json响应

    /**
     * @param Request $request
     * @param Closure $next
     * @param int $maxAttempts
     * @param int $decayMinutes
     * @param string $prefix
     * @return \Illuminate\Http\Exceptions\HttpResponseException|\Illuminate\Http\Exceptions\ThrottleRequestsException|\Symfony\Component\HttpFoundation\Response
     */
    public function handle($request, Closure $next, $maxAttempts = 60, $decayMinutes = 1, $prefix = '')
    {

        $key = $this->resolveRequestSignature($request);

        $maxAttempts = $this->resolveMaxAttempts($request, $maxAttempts);

        if ($this->limiter->tooManyAttempts($key, $maxAttempts)) {
            //throw $this->buildException($key, $maxAttempts);
            // 原来的是抛出异常,修改成直接返回
            return $this->buildException($request, $key, $maxAttempts);
        }
        //去掉 `* 60` 限制秒级,加上去限制分钟
        //$this->limiter->hit($key, $decayMinutes);
        $this->limiter->hit($key, $decayMinutes*60);

        $response = $next($request);

        return $this->addHeaders(
            $response, $maxAttempts,
            $this->calculateRemainingAttempts($key, $maxAttempts)
        );
    }

    /**
     * Create a 'too many attempts' exception.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $key
     * @param  int  $maxAttempts
     * @param  callable|null  $responseCallback
     */
    protected function buildException($request, $key, $maxAttempts, $responseCallback = null)
    {
        $retryAfter = $this->limiter->availableIn($key);

        //要返回的数据
        $message = json_encode([
            'code' => 429,
//            'data' => null,
            'message' => '您的请求太频繁，已被限制请求',
//            'retryAfter' => $retryAfter,
        ], 320);

        $response = new Response($message, 429);

        $retryAfter = $this->getTimeUntilNextRetry($key);

        $headers = $this->getHeaders(
            $maxAttempts,
            $this->calculateRemainingAttempts($key, $maxAttempts, $retryAfter),
            $retryAfter
        );

        return is_callable($responseCallback)
            ? new HttpResponseException($responseCallback($request, $headers))
            : $this->addHeaders(
                $response, $maxAttempts,
                $this->calculateRemainingAttempts($key, $maxAttempts, $retryAfter),
                $retryAfter
            );
    }


    /**
     * @param \Symfony\Component\HttpFoundation\Response $response
     * @param int $maxAttempts
     * @param int $remainingAttempts
     * @param null $retryAfter
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function addHeaders(\Symfony\Component\HttpFoundation\Response $response, $maxAttempts, $remainingAttempts, $retryAfter = null)
    {
        // 添加 `response` 头 为 `json`
        $response->headers->add(
            ['Content-Type' => 'application/json;charset=utf-8']
        );
        return parent::addHeaders($response, $maxAttempts, $remainingAttempts, $retryAfter);
    }
}
