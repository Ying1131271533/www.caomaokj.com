<?php
/**
 * Notice控制器
 * @author      [K?Germany:De] <[972581428@qq.com]>
 * @copyright   Copyright (c) 2016 [环企优站科技]  (https://www.h7uz.com)
 */
namespace app\usezan\controller;
use app\usezan\model\Member;
use app\usezan\model\Notice;
class Notice extends Base{
    protected $notice,$member;
    public function _uzauto() {
        $this->notice = new Notice();
        $this->member = new Member();
    }
    //发布公告
    public function index(){
        if (request()->isPost()){
            $data = input('post.title','');
            if ($data){
                $notice_data = [];
                $cursor = Member::where('status', 1)->field('id')->cursor();
                foreach ($cursor as $key=>$vo){
                    $notice_data[$key] = [
                        'uid' => $vo->id,
                        'title' => $data,
                        'createtime' => time(),
                        'type' => 2
                    ];
                }
                if ($notice_data){
                 $this->notice->save_all($notice_data);
                 unset($notice_data,$cursor);
                 $this->success('~~发布通知成功~~');
                }
            } else {
                $this->error('~~请输入发布通知内容~~');
            }
        }
        return $this->fetch();
    }












}
