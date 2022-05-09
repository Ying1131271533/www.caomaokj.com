<?php

/**

 * Attachment(附件管理)

 * @author      [我就叫小柯] <[972581428@qq.com]>

 * @copyright   Copyright (c) 2017 [环企优站科技]  (https://www.h7uz.com)

 * @version     Usezan管理系统 v1.0 @TP 5.1

 */

namespace app\usezan\controller;

use think\Env;

use think\Image;

class Attachment extends Base {



	//文件上传加载页

	public function index (){

		$upload = input("get.");

		$isthumb = explode(",", $upload['isthumb']);

		//处理上传类型

		$type = array();

		foreach ($isthumb as $key => $v) {

			$type[] = '"'.$v.'"';

		}

		$upload['type'] = implode(",", $type);

		//检查文件类型

		$typeon = $this->checkType($isthumb);

		if ($typeon) {

			$this->error("文件类型不符,请在系统设置配置");

		}

		$this->assign("upload",$upload);

        return $this->fetch();

	}



    //自定义上传

    public function up_style(){

        $upload = input("get.");

        $isthumb = explode(",", $upload['isthumb']);

        //处理上传类型

        $type = array();

        foreach ($isthumb as $key => $v) {

            $type[] = '"'.$v.'"';

        }

        $upload['type'] = implode(",", $type);

        //检查文件类型

        $typeon = $this->checkType($isthumb);

        if ($typeon) {

            $this->error("文件类型不符,请在系统设置配置");

        }

        $this->assign("upload",$upload);

        return $this->fetch();

    }



    /**

     * 文件上传封装

     * $data['usezan_auth'] 0:本地

     * @author 小柯 <972581428@qq.com>

     */

	public function usezan_upload () {

		$data = input('get.'); //获取类型

		if (empty($data)) $this->error("缺少必要参数╮(╯_╰)╭");

        $file = request()->file('file');

		if (!empty($file)) {

			switch ($data['usezan_auth']) {

				case 0:

                    $file_ext = ['ext' => config('system_cn.attach_type')]; //上传后缀

                    $file_size = ['size'=>config('system_cn.attach_maxsize')]; //文件大小

                    $info = $file->validate($file_ext,$file_size)->move(config("app.static_url.rooturl"));

                    if(!$info) {

                    //上传错误提示错误信息

                        echo $file->getError();

                    }else{

                    // 上传成功 获取上传文件信息

                        $ext = $info->getExtension();

                        $thumb = config("app.static_url.surl").$info->getSaveName();

                    }

					break;

			}



			//开启图片水印[云存储不支持]

			if (config('system_cn.watermark_enable') && !empty($thumb) && !$data['usezan_auth']) {

                $usezan_images = ".".$thumb; //添加水印的图片

                $image = Image::open($usezan_images);

                $width = $image->width();

                $height = $image->height();

                //满足图片定义宽*高

                $image_satisfy = explode("*",config('watemard_satisfy'));

                if ($width > $image_satisfy[0] && $height > $image_satisfy[1]) {

                    //先是图片水印 没有就文字水印

                    if (!config('system_cn.watermark_img')) {

                        $water_txt = config('system_cn.watemard_text'); //水印文字

                        $water_text_face = Env::get('vendor_path').'topthink/think-captcha/assets/zhttfs/'.config('watemard_text_face');//文字水印路径

                        $water_text_size =  config('system_cn.watemard_text_size'); //字体大小

                        $water_text_color = config('system_cn.watemard_text_color'); //字体颜色

                        $image->text($water_txt, $water_text_face, $water_text_size, $water_text_color)->save($usezan_images);

                    } else {

                        //水印图片位置

                        switch (config('system_cn.watermark_pos')) {

                            case 1:

                                $water_pos =  Image::WATER_NORTHWEST; //左上角

                                break;

                            case 2:

                                $water_pos =  Image::WATER_NORTH;//上居中

                                break;

                            case 3:

                                $water_pos =  Image::WATER_NORTHEAST;//右上角

                                break;

                            case 4:

                                $water_pos =  Image::WATER_WEST;//左居中

                                break;

                            case 5:

                                $water_pos =  Image::WATER_CENTER;//居中

                                break;

                            case 6:

                                $water_pos =  Image::WATER_EAST;//右居中

                                break;

                            case 7:

                                $water_pos =  Image::WATER_SOUTHWEST;//左下角

                                break;

                            case 8:

                                $water_pos =  Image::WATER_SOUTH;//下居中

                                break;

                            case 9:

                                $water_pos =  Image::WATER_SOUTHEAST;//右下角

                                break;

                        }

                        $water_logo = '.'.config('system_cn.watermark_img'); //水印图片路径

                        $water_pct  = config('system_cn.watermark_pct'); //透明度

                        $image->water($water_logo, $water_pos, $water_pct)->save($usezan_images);

                    }

                }



            }

            $data_img = [

                'thumb'     => str_replace("\\","/", $thumb),

                'imgs'      => $data['imgs'],

                'inputname' => $data['inputname'],

                'ext'       => $ext

            ];

			return json($data_img);

		}

	}



	//检查文件类型

	protected function checkType($type) {

		//判断类型

		$configtype =  explode(',',config("system_cn.attach_type"));

		$datatype = array_diff($type,$configtype);

		if(empty($datatype)) {

			$config_type = 0;

		} else {

			$config_type = 1;

		}

		return $config_type;

	}













}