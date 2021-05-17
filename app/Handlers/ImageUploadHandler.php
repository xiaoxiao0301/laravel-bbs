<?php


namespace App\Handlers;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

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
     * @return array|boolean
     */
    public function save($file, $folder, $file_prefix)
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

        return [
            'path' => "/$folder_name/$filename"
        ];
    }




}
