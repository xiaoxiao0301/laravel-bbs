<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Resources\Notification as NotificationResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class NotificationsController extends Controller
{
    /**
     * 消息通知列表
     *
     * @return AnonymousResourceCollection
     */
    public function index()
    {
        $notifications = auth('api')->user()->notifications()->paginate(20);
        return NotificationResource::collection($notifications);
    }

    /**
     * 未读通知个数
     *
     * @return JsonResponse
     */
    public function stats()
    {
        $count = auth('api')->user()->notification_count;
        return response()->json(['unread_count' => $count]);
    }

    public function read()
    {
        auth('api')->user()->markAsRead();
        return response()->json()->setStatusCode(204);
    }
}
