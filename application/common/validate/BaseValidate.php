<?php

namespace app\common\validate;

use think\Db;
use think\Validate;

class BaseValidate extends Validate
{
    /**
     * 获取验证器定义的字段
     *
     * @param  array    $params        参数
     * @return array                   需要验证的参数名
     */
    public function getDateByRule(array $params)
    {
        $currentScene = $this->currentScene; // 当前场景
        $scene        = $this->scene; // 验证器的所有场景

        // 判断场景是否存在，检测参数中间已经做了，这里可以不要
        /* if (!array_key_exists($currentScene, $scene)) {
        throw new Fail(['msg' => '检验场景不存在']);
        } */

        // 获取场景需要检验的字段，过滤掉可能被恶意添加的字段，例如强加上去的用户余额字段 total
        $rules    = $scene[$currentScene];
        $newArray = [];
        foreach ($rules as $value) {
            $newArray[$value] = $params[$value];
        }
        return $newArray;
    }

    // 该id是否存在于数据库
    protected function exist($value, $rule = '', $data = '', $field = '')
    {
        $data = Db::table($rule)->find($value);
        if (!$data) {
            return '该数据不存在';
        }

        return true;
    }

}
