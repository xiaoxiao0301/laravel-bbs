<?php

namespace App\Http\Controllers;

use App\Handlers\ImageUploadHandler;
use App\Http\Requests\TopicRequest;
use App\Models\Category;
use App\Models\Config;
use App\Models\Link;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Http\Request;
use Auth;

class TopicsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }

    public function index(Request $request, Topic $topic, User $user, Link $link)
    {
        $topics = $topic->withOrder($request->order?? '')->paginate(20);
        $activeUsers = $user->getActiveUsers();
        $links = $link->getAllCached();
        return view('topics.index', compact('topics', 'activeUsers', 'links'));
    }

    public function show(Request $request, Topic $topic)
    {
        if (!empty($topic->slug) && $topic->slug != $request->slug) {
            return redirect($topic->link(), 301);
        }
        return view('topics.show', compact('topic'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('topics.create', compact('categories'));
    }

    public function store(TopicRequest $request, Topic $topic)
    {
        $topic->fill($request->all());
        $topic->user_id = Auth::id();
        $topic->save();

        return redirect()->to($topic->link())->with('success', '成功创建话题');
    }

    public function edit(Topic $topic)
    {
        $this->authorize('update', $topic);
        $categories = Category::all();
        return view('topics.edit', compact('topic', 'categories'));
    }


    public function update(TopicRequest $request, Topic $topic)
    {
        $this->authorize('update', $topic);
        $topic->update($request->all());
        return redirect()->to($topic->link())->with('success', '更新成功！');
    }

    public function destroy(Topic $topic)
    {
        $this->authorize('destroy', $topic);
        $topic->delete();

        return redirect()->route('topics.index')->with('success', '成功删除！');
    }

    /**
     * 上传文件
     * @param Request $request
     * @param ImageUploadHandler $imageUploadHandler
     * @return array
     */
    public function uploadImage(Request $request, ImageUploadHandler $imageUploadHandler)
    {
        $data = [
            'success' => false,
            'msg' => '上传失败',
            'file_path' => ''
        ];
        $file = $request->upload_file;
        if ($file) {
            $result = $imageUploadHandler->save($file, 'topics', Auth::id(), 1024);
            if ($result) {
                $data['file_path'] = $result['path'];
                $data['success'] = true;
                $data['msg'] = '上传成功';
            }
        }

        return $data;
    }
}
