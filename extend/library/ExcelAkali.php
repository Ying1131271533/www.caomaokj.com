<?php
namespace library;

use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use think\exception\ValidateException;

class ExcelAkali
{


    // 导出
    public static function export($header = [], $type = true, $data = [], $fileName = "Akali", $width = [])
    {
        // 实例化类
        $preadsheet = new Spreadsheet();
        // 创建sheet
        $sheet = $preadsheet->getActiveSheet();
        // 循环设置表头数据
        foreach ($header as $k => $v) {
            $sheet->setCellValue($k, $v);
        }
        // 生成数据
        $sheet->fromArray($data, null, "A2");

        // 样式设置
        // 默认宽度
        $sheet->getDefaultColumnDimension()->setWidth(12);
        // 默认高度
        $sheet->getDefaultRowDimension()->setRowHeight(16);
        
        // 自定义宽度
        if ($width) {
            foreach($width as $key => $value){
                $sheet->getColumnDimension($value['alphabet'])->setWidth($value['width']);
            }
        }

        // 设置下载与后缀
        if ($type) {
            header("Content-Type:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
            $type = "Xlsx";
            $suffix = "xlsx";
        } else {
            header("Content-Type:application/vnd.ms-excel");
            $type = "Xls";
            $suffix = "xls";
        }
        ob_end_clean();//清楚缓存区
        // 激活浏览器窗口
        header("Content-Disposition:attachment;filename=$fileName.$suffix");
        //缓存控制
        header("Cache-Control:max-age=0");
        // 调用方法执行下载
        $writer = IOFactory::createWriter($preadsheet, $type);
        // 数据流
        $writer->save("php://output");
    }
}
