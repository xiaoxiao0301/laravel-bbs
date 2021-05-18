<?php

namespace App\Http\Controllers;

use App\Handlers\ImageUploadHandler;
use App\Http\Requests\TopicRequest;
use App\Models\Category;
use App\Models\Topic;
use Illuminate\Http\Request;
use Auth;

class TopicsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }

    public function index(Request $request, Topic $topic)
    {
        $topics = $topic->withOrder($request->order)->paginate(20);
        return view('topics.index', compact('topics'));
    }

    public function show(Topic $topic)
    {
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

        return redirect()->route('topics.show', $topic->id)->with('success', '帖子创建成功');
    }

    public function edit(Topic $topic)
    {
        return view('topics.edit', compact('topic'));
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
