<?php
namespace app\common\model;

use think\facade\Cache;
use think\Model;

class Member extends Model
{
    protected $type = [
        'createtime' => 'timestamp',
    ];

    // 密码加密
    public function setPasswordAttr($value)
    {
        return sysmd5($value);
    }

    // 头像
    public function setAvatarAttr($value)
    {
        return '\static\icon\avatar-caomao.png';
    }

    // 用户昵称
    // public function setNicknameAttr($value)
    // {
    //     return 'cmkj_' . user_number();
    // }

    public function comments()
    {
        return $this->hasMany('ArticleComment');
    }

    public function activityComments()
    {
        return $this->hasMany('ActivityComment');
    }

    public function collegeComments()
    {
        return $this->hasMany('CollegeComment');
    }

    public function collects()
    {
        return $this->belongsToMany('Article', 'ArticleCollect');
    }

    public function likes()
    {
        return $this->belongsToMany('Article', 'ArticleLike');
    }

    public function activityCollects()
    {
        return $this->belongsToMany('Activity', 'ActivityCollect');
    }

    public function activityLikes()
    {
        return $this->belongsToMany('Activity', 'ActivityLike');
    }

    public function activityjoins()
    {
        return $this->belongsToMany('Activity', 'ActivityJoin');
    }

    public function collegejoins()
    {
        return $this->belongsToMany('College', 'CollegeJoin');
    }

    public function collegeCollects()
    {
        return $this->belongsToMany('College', 'CollegeCollect');
    }

    //find
    public function getFind($phone)
    {
        $data = $this->where('phone', $phone)->findOrEmpty();
        if ($data->isEmpty()) {
            return [];
        }
        return $data;
    }

    //cache-member
    public function cacheData($uid)
    {
        $data = $this->where('id', $uid)->field('password', true)->findOrEmpty();
        if ($data->isEmpty()) {
            return false;
        }
        return Cache::store('umember')->set('member_' . $uid, $data->toArray());
    }

    //save-data
    public function saveData($data)
    {
        return $this->allowField(true)->save($data);
    }

    //up_data
    public function upData($data)
    {
        return $this->allowField(true)->update($data);
    }

    //up_pwds
    public function upPwds($phone, $pwds)
    {
        return $this->where('phone', $phone)->update(['password' => $pwds]);
    }

    //检查手机是否存在
    public function checkPhone($phone)
    {
        return $this->where('phone', $phone)->count();
    }

    // 获取用户列表
    public static function getMemberDate($where = [], int $limit = 30, string $order = 'id'){
        $list = self::where($where)->order($order, 'desc')->paginate($limit);
        return $list;
    }

}
