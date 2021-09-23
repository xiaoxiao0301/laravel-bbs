<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ReplyRequest;
use App\Models\Reply;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Resources\Reply as ReplyResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class RepliesController extends Controller
{
    /**
     * 发布话题回复
     *
     * @param ReplyRequest $request
     * @param Topic $topic
     * @param Reply $reply
     * @return JsonResponse|object
     */
    public function store(ReplyRequest $request, Topic $topic, Reply $reply)
    {
        $reply->content = $request->get('content');
        $reply->topic_id = $topic->id;
        $reply->user_id = auth('api')->user()->id;
        $reply->save();

        return (new ReplyResource($reply))->response()->setStatusCode(201);
    }

    /**
     * 删除话题的回复
     *
     * @param Topic $topic
     * @param Reply $reply
     * @return JsonResponse|object
     * @throws AuthorizationException
     */
    public function destroy(Topic $topic, Reply $reply)
    {
        /**
         * 1. 回复的作者
         * 2. 话题的作者
         * 3. 管理员
         */
        if ($reply->topic_id != $topic->id) {
            return response()->json()->setStatusCode(400);
        }

        $this->authorize('destroy', $reply);
        $reply->delete();

        return response()->json()->setStatusCode(204);
    }

    /**
     * 话题回复列表
     *
     * @param Topic $topic
     * @return AnonymousResourceCollection
     */
    public function index(Topic $topic)
    {
        $replies = $topic->replies()->with('user')->paginate(20);
        return ReplyResource::collection($replies);
    }


    /**
     * 某个用户的回复列表
     *
     * @param User $user
     * @return AnonymousResourceCollection
     */
    public function userIndex(User $user)
    {
        $replies = $user->replies()->with('user')->with('topic')->paginate(20);
        return ReplyResource::collection($replies);
    }
}
