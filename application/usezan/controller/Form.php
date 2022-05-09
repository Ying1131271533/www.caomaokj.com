<?php
/**
 * 表单管理
 * @author      [我就叫小柯] <[972581428@qq.com]>
 * @copyright   Copyright (c) 2017 [环企优站科技]  (https://www.h7uz.com)
 */
namespace app\usezan\controller;

use app\usezan\model\ActivityComment;
use PHPExcel;
use PHPExcel_IOFactory;

class Form extends Base
{
    protected $activity_comment;
    public function _uzauto()
    {
        $this->activity_comment = new ActivityComment();
    }

    //活动报名
    public function activity()
    {
        $keys = input('get.');
        $map  = [];
        if (!empty($keys['keyword'])) {
            $map['name'] = ['like', '%' . $keys['keyword'] . '%'];
        }
        if (isset($keys['status']) && $keys['status'] >= 0) {
            $map['status'] = $keys['status'];
        } else {
            $keys['status'] = -1;
        }
        $list = $this->activity_comment->get_paginate($map);
        $this->assign("list", $list);
        $this->assign('keys', $keys);
        return $this->fetch();
    }

    //导出excel
    public function excel()
    {
        if (request()->isAjax()) {
            return ['excid' => input("post.data", 0), 'status' => 1];
        }
        //文件名称
        $xlsName = 'Excel' . date('Y-m-d');
        //数据字段
        //数据字段
        $xlsCell = [
            0 => [0 => 'id', 1 => 'ID'],
            1 => [0 => 'title', 1 => '活动标题'],
            2 => [0 => 'name', 1 => '姓名'],
            3 => [0 => 'phone', 1 => '手机'],
            4 => [0 => 'createtime', 1 => '报名时间'],
        ];
        $field = 'id,aid,name,phone,createtime';
        $excid = input('get.excid');
        $keys  = input('param.keys');
        if (empty($excid)) {
            if ($keys) {
                $xlsData = $this->activity_comment->get_select([['name', 'like', '%' . $keys . '%']], $field);
            } else {
                $xlsData = $this->activity_comment->get_select([], $field);
            }
        } else {
            $xlsData = $this->activity_comment->get_select([['id', 'in', $excid]], $field);
        }
        if (!$xlsData) {
            $this->error("没有数据,无法导出操作");
        }

        $this->exportExcel($xlsName, $xlsCell, $xlsData);
    }

    //导出方法
    protected function exportExcel($expTitle, $expCellName, $expTableData)
    {
        $fileName    = iconv('utf-8', 'gb2312', $expTitle); //文件名称
        $cellNum     = count($expCellName);
        $dataNum     = count($expTableData);
        $objPHPExcel = new PHPExcel();
        $cellName    = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG', 'AH', 'AI', 'AJ', 'AK', 'AL', 'AM', 'AN', 'AO', 'AP', 'AQ', 'AR', 'AS', 'AT', 'AU', 'AV', 'AW', 'AX', 'AY', 'AZ');
        $objPHPExcel->getActiveSheet(0)->mergeCells('A1:' . $cellName[$cellNum - 1] . '1'); //合并单元格
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', $expTitle . ' Time:' . date('Y-m-d H:i:s'));
        for ($i = 0; $i < $cellNum; $i++) {
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($cellName[$i] . '2', $expCellName[$i][1]);
        }
        for ($i = 0; $i < $dataNum; $i++) {
            for ($j = 0; $j < $cellNum; $j++) {
                $objPHPExcel->getActiveSheet(0)->setCellValueExplicit($cellName[$j] . ($i + 3), $expTableData[$i][$expCellName[$j][0]], \PHPExcel_Cell_DataType::TYPE_STRING);
            }
        }
        //ob_end_clean();
        header('pragma:public');
        header('Content-type:application/vnd.ms-excel;charset=utf-8;name="' . $fileName . '.xlsx"');
        header("Content-Disposition:attachment;filename=$fileName.xlsx");
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        ob_clean();
        $objWriter->save('php://output');
    }

    //批量状态
    public function statusallok()
    {
        $status = input("post.ids/a");
        if (!$status) {
            $this->error("缺少必要参数");
        }

        //更新状态
        $data = [];
        foreach ($status as $key => $v) {
            $data[$key] = [
                'id'     => $v,
                'status' => 1,
            ];
        }
        $this->activity_comment->save_all($data);
        $this->success("更新成功");
    }

}
