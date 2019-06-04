<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CollectiveLogic
 *
 * @author Administrator
 */

namespace Service\Common;

class CollectiveLogic {

    /**
     * 公共的导出方法 
     * @param type $fileName
     * @param type $title
     * @param type $result
     */
    public function export($fileName, $title, $result) {
        $fp = fopen($fileName, "a+");
        $data = array();
        foreach ($title as $val) {
            $arr[] = iconv("UTF-8", "GBK", $val);
        }
        fputcsv($fp, $arr);
        foreach ($result as $value) {
            foreach ($value as $k => $v) {
                $data[$k] = $v;
            }
            fputcsv($fp, $data);
        }
        fclose($fp);
    }

    /**
     * @param type $filePsn (文件的位置)
     * @param type $fileFmt (文件的格式)
     * @return type
     */
    function importExcel($filePsn, $fileFmt, $islimit = true) {
        vendor("PHPExcel.PHPExcel");
        Vendor("PHPExcel.Reader.Excel2007");
        if ($fileFmt == "xlsx") {
            $objReader = \PHPExcel_IOFactory::createReader('Excel2007');
        } elseif ($fileFmt == "xls") {
            $objReader = \PHPExcel_IOFactory::createReader('Excel5');
        } elseif ($fileFmt == "csv") {
            $objReader = \PHPExcel_IOFactory::createReader('csv');
        }
        $objPHPExcel = $objReader->load($filePsn, $encode = 'utf-8');
        $sheet = $objPHPExcel->getSheet(0);
        $highestRow = $sheet->getHighestRow(); // 取得总行数
        $highestColumn = $sheet->getHighestColumn(); // 取得总列数
        $data = array();
        if ($highestRow > 101 && $islimit === true) { //限制每次只能导入100条数据
            $highestRow = 101;
        }
        for ($i = 2; $i <= $highestRow; $i++) { //从第几行开始 i=2戴表从第二行开始 
            for ($j = "A"; $j <= $highestColumn; $j++) {
                $data[$i][$j] = $objPHPExcel->getActiveSheet()->getCell($j . $i)->getValue();
            }
        }
        return $data;
    }

    /**
     * 只能导出三种格式的文件（csv , xls ,xlsx）
     * @param type $fileName
     */
    public function checkFile($fileName) {
        $count = substr_count($fileName, ".");
        //不允许出现两个点防止文件名当做了文件后缀
        if ($count > 1) {
            return false;
        }
        $isXlsx = stripos($fileName, ".xlsx");
        $isxls = stripos($fileName, ".xls");
        $isCsv = stripos($fileName, ".csv");
        $fileFmt = false;
        if (!empty($isXlsx)) {
            $fileFmt = "xlsx";
        } elseif (!empty($isxls)) {
            $fileFmt = "xls";
        } elseif (!empty($isCsv)) {
            $fileFmt = "csv";
        }
        return $fileFmt;
    }

    /**
     * 转换excel数据为字符串 目前只能拼接A1列的数据 等到后面有更多的需求这个方法可以做适当的调整数据
     * @param type $data
     * @param type $mark 标示以什么分割
     */
    public function trmatn($data, $mark) {
        if (empty($data) || empty($mark)) {
            return false;
        }
        $keys = array_keys($data);
        $total = max($keys);
        $result = "";
        foreach ($data as $k => $v) {
            if ($k == $total) {
                $result.=$v["A"];
            } else {
                $result.=$v["A"] . ",";
            }
        }
        return $result;
    }

}
