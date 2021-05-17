<?php


namespace App\Handlers;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

/**
 * 文件上传类
 * Class ImageUploadHandler
 * @package App\Handlers
 */
class ImageUploadHandler
{

    /**
     * 只允许以下后缀名的图片上传
     * @var string[]
     */
    protected $allowed_ext = ['png', 'jpg', 'gif', 'jpeg'];

    /**
     * @param UploadedFile $file
     * @param $folder
     * @param $file_prefix
     * @param bool $max_width
     * @return array|boolean
     */
    public function save($file, $folder, $file_prefix, $max_width = false)
    {
        // avatar目录存放头像 topic存放话题等，便于查找
        $folder_name = "upload/images/$folder/" . date("Ym/d", time());
        $upload_path = public_path() . '/' . $folder_name;
        // 获取文件的后缀名，因图片从剪贴板里黏贴时后缀名为空，所以此处确保后缀一直存在
        $extension = strtolower($file->getClientOriginalExtension()) ?: 'png';
        // 组装文件名
        $filename = $file_prefix. '_' . time(). '_' . Str::random(10) . '.' . $extension;

        // 上传的不是图片将终止操作
        if ($file->isDir() || ! in_array($extension, $this->allowed_ext)) {
            return false;
        }
        // 移动图片到目标路径
        $file->move($upload_path, $filename);

        // 根据图片宽度裁剪图片
        if ($max_width && $extension != 'gif') {
            $this->reduceSize($upload_path. '/' . $filename, $max_width);
        }

        return [
            'path' => "/$folder_name/$filename"
        ];
    }

    /**
     * 裁剪图片
     * @param $file_path
     * @param $max_width
     */
    public function reduceSize($file_path, $max_width)
    {
        $image = Image::make($file_path);
        // 进行大小调整的操作
        $image->resize($max_width, null, function ($constraint) {
            // 设定宽度是 $max_width，高度等比例双方缩放
            $constraint->aspectRatio();
            // 防止裁图时图片尺寸变大
            $constraint->upsize();
        });
        // 对图片修改后进行保存
        $image->save();
    }


}
