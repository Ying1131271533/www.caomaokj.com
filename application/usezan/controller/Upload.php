<?php
namespace app\usezan\controller;

class Upload
{
    public function file()
    {
        $file = request()->file('file');
        // 移动到框架应用根目录/uploads/ 目录下
        $info = $file->validate(['image'=>'filesize:5242880|fileExt:jpg,jpge,png,gif'])->move('storage');
        if ($info) {
            $path = str_replace('\\', '/', '/storage/' . $info->getSaveName());
            return success(['path' => $path]);
        } else {
            // 上传失败获取错误信息
            return fail($file->getError());
        }
    }
}
