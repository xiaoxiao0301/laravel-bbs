<?php

namespace App\Http\Controllers\Api;

use App\Handlers\ImageUploadHandler;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ImageRequest;
use App\Models\Image;
use App\Http\Resources\Image as ImageResource;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ImagesController extends Controller
{
    public function store(ImageRequest $request, ImageUploadHandler $imageUploadHandler)
    {
        $user = auth('api')->user();
        $size = $request->type == 'avatar' ? 362 : 1024;
        $result = $imageUploadHandler->save($request->image, Str::plural($request->type), $user->id, $size);
        $image = new Image();
        $image->path = $result['path'];
        $image->type = $request->type;
        $image->user_id = $user->id;
        $image->save();
        return (new ImageResource($image))->response()->setStatusCode(201);
    }
}
