<?php
/**
 * Member模型
 * @author      [K?Germany:De] <[972581428@qq.com]>
 * @copyright   Copyright (c) 2016 [环企优站科技]  (https://www.h7uz.com)
 */
namespace app\index\model;
use think\facade\Cache;
use think\Model;
class Member extends Model{

    protected $type = [
        'createtime'  =>  'timestamp'
    ];

    //find
    public function get_find($phone){
        $data = $this->where('phone',$phone)->findOrEmpty();
        if ($data->isEmpty()){
            return [];
        }
        return $data;
    }

    //cache-member
    public function cache_data($uid){
        $data = $this->where('id',$uid)->field('password',true)->findOrEmpty();
        if ($data->isEmpty()) {
            return false;
        }
        return Cache::store('umember')->set('member_'.$uid,$data->toArray());
    }

    //save-data
    public function save_data($data){
        return $this->allowField(true)->save($data);
    }

    //up_data
    public function up_data($data){
        return $this->allowField(true)->update($data);
    }

    //up_pwds
    public function up_pwds($phone,$pwds){
        return $this->where('phone',$phone)->update(['password'=>$pwds]);
    }

    //检查手机是否存在
    public function check_phone($phone){
        return $this->where('phone',$phone)->count();
    }



}