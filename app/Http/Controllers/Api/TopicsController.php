<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\TopicRequest;
use App\Models\Topic;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Resources\Topic as TopicResource;

class TopicsController extends Controller
{
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

    public function update(TopicRequest $request, Topic $topic)
    {
        $this->authorize('update', $topic);
        $topic->update($request->all());
        return new TopicResource($topic);
    }
}
