<?php
namespace app\index\controller;

class Upload extends BaseApi
{
    public function uploadImg()
    {
        // 获取文件对象
        $file = request()->file('file');
        // 验证并上传
        $info = $file->validate(['size' => '5242880', 'ext' => 'jpg,gif,png'])->move('storage');
        // 判断是否成功
        if ($info) {
            $src = '/storage/' . $info->getSaveName();
            return $this->create(200, '上传成功', $src);
        } else {
            return $this->create(400, $file->getError());
        }
    }
    
    // 上传多张图片
    public function uploadImgs(){
        return $this->create(200, '阿卡丽');
    }
}
