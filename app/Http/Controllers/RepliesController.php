<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReplyRequest;
use App\Models\Reply;
use Auth;

class RepliesController extends Controller
{
    public function store(ReplyRequest $request, Reply $reply)
    {
        $reply->content = $request->get('content');
        $reply->user_id = Auth::id();
        $reply->topic_id = $request->topic_id;
        $reply->save();
        return redirect()->to($reply->topic->link())->with('success', '评论创建成功！');
    }


    public function destroy(Reply $reply)
    {
        $this->authorize('destroy', $reply);
        $reply->delete();
        return redirect()->to($reply->topic->link())->with('success', '评论删除成功！');
    }
}
