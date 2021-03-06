@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="col-md-10 offset-md-1">
            <div class="card ">
                <div class="card-body">
                    <h2 class="">
                        <i class="far fa-edit"></i>
                        新建话题
                    </h2>
                    <hr>
                    <form action="{{ route('topics.store') }}" method="POST" accept-charset="UTF-8">
                        @csrf
                        @include('shared._errors')
                        <div class="form-group">
                            <input class="form-control" type="text" name="title" value="{{ old('title') }}" placeholder="请填写标题" required />
                        </div>
                        <div class="form-group">
                            <select name="category_id" id="category_id" class="form-control" required>
                                <option value="" hidden disabled></option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <textarea name="body" class="form-control" id="editor" rows="6" placeholder="请填入至少三个字符的内容。" required>{{ old('body') }}</textarea>
                        </div>

                        <div class="well well-sm">
                            <button type="submit" class="btn btn-primary"><i class="far fa-save mr-2" aria-hidden="true"></i> 保存</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@section('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/simditor.css') }}">
@stop
@section('scripts')
    <script type="text/javascript" src="{{ asset('js/module.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/hotkeys.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/uploader.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/simditor.js') }}"></script>
    <script>
        $(document).ready(function() {
            var editor = new Simditor({
                textarea: $('#editor'),
                upload: {
                    url: '{{ route('topics.upload_image') }}',
                    params: {
                        _token: '{{ csrf_token() }}'
                    },
                    fileKey: 'upload_file',
                    connectionCount: 3,
                    leaveConfirm: '文件上传中，关闭此页面将取消上传。'
                },
                pasteImage: true, // 是否支持图片黏贴上传
            });
        });
    </script>
@stop
@endsection
