<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\TopicRequest;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Resources\Topic as TopicResource;

class TopicsController extends Controller
{

    public function index(Request $request, Topic $topic)
    {
        // 使用with是预加载，解决N+1问题
        $query = $topic->query()->with('user')->with('category');
        if ($categoryId = $request->category_id) {
            $query->where('category_id', $categoryId);
        }

        switch ($request->order) {
            case 'recent':
                $query->recent();
                break;
            default:
                $query->recentReplied();
                break;
        }
        $topics = $query->paginate(20);
        return TopicResource::collection($topics);
    }

    /**
     * 新增话题
     *
     * @param TopicRequest $request
     * @param Topic $topic
     * @return JsonResponse|object
     */
    public function store(TopicRequest $request, Topic $topic)
    {
        $topic->fill($request->all());
        $topic->user_id = auth('api')->user()->id;
        $topic->save();

        return (new TopicResource($topic))->response()->setStatusCode(201);
    }

    /**
     * 修改话题
     *
     * @param TopicRequest $request
     * @param Topic $topic
     * @return TopicResource
     * @throws AuthorizationException
     */
    public function update(TopicRequest $request, Topic $topic)
    {
        $this->authorize('update', $topic);
        $topic->update($request->all());
        return new TopicResource($topic);
    }

    /**
     * 删除话题
     *
     * @param Topic $topic
     * @return JsonResponse|object
     * @throws AuthorizationException
     */
    public function destroy(Topic $topic)
    {
        $this->authorize('destroy', $topic);
        $topic->delete();
        return response()->json()->setStatusCode(204);
    }

    public function userIndex(User $user, Request $request)
    {
        $topics = $user->topics()->recent()->with('user')->with('category')->paginate(20);

        return TopicResource::collection($topics);
    }

}
