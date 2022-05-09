<?php
namespace app\usezan\validate;

use think\Validate;

class Article extends Validate
{
    protected $rule = [
        'catid'       => 'require',
        'title'       => 'require|unique:article|max:80',
        'thumb'       => 'require',
        'keyword'     => 'require|max:10',
        'description' => 'require|max:255',
    ];

    protected $message = [

        // 分类
        'catid.require'       => '分类不能为空',

        // 标题
        'title.require'       => '标题不能为空',
        'title.unique'        => '标题已存在',
        'title.max'           => '标题不能超过80个字符',

        // 封面
        'thumb.require'       => '封面不能为空',

        // 关键词
        'keyword.require'     => '关键词不能为空',
        'keyword.max'         => '关键词不能超过10个字符',

        // 描述
        'description.require' => '描述不能为空',
        'description.max'     => '描述不能超过255个字符',

    ];

    // edit 验证场景定义
    public function sceneEdit($id)
    {
        return $this->only(['caitd', 'title', 'thumb', 'keyword', 'description'])
            ->remove('title', 'unique:article') // 移除
            ->append('title', 'unique:article,' . $id); // 追加
    }
}
