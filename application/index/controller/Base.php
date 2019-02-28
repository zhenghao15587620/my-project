<?php
namespace app\index\controller;

use think\Controller;
use think\Request;
use think\Session;

class Base extends Controller
{
    protected function _initialize()
    {
        parent::_initialize();

        define('USER_ID', Session::has('user_id') ? Session::get('user_id'):null);
    }

    //判断用户是否登陆,放在系统后台入口前面: index/index
    protected function isLogin()
    {
        if (is_null(USER_ID)) {
            $this -> error('用户未登陆,无权访问',url('user/login'));
        }
    }

    //防止用户重复登陆,放在登陆操作前面:user/login
    protected function alreadyLogin(){
        if (USER_ID) {
            $this -> error('您已经登陆,请勿重复登陆',url('index/index'));
        }
    }

    /**

     * excel表格导出

     * @param string $fileName 文件名称

     * @param array $headArr 表头名称

     * @param array $data 要导出的数据

     * @author static7  */

    function excelExport($fileName = '', $headArr = [], $data = []) {

        $fileName .= "_" . date("Y_m_d", Request::instance()->time()) . ".xls";

        $objPHPExcel = new \PHPExcel();

        $objPHPExcel->getProperties();

        $key = ord("A"); // 设置表头

        foreach ($headArr as $v) {

            $colum = chr($key);

            try {
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($colum . '1', $v);
            } catch (\PHPExcel_Exception $e) {
            }

            try {
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($colum . '1', $v);
            } catch (\PHPExcel_Exception $e) {
            }

            $key += 1;

        }

        $column = 2;

        try {
            $objActSheet = $objPHPExcel->getActiveSheet();
        } catch (\PHPExcel_Exception $e) {
        }

        foreach ($data as $key => $rows) { // 行写入

            $span = ord("A");

            foreach ($rows as $keyName => $value) { // 列写入

                $objActSheet->setCellValue(chr($span) . $column, $value);

                $span++;

            }

            $column++;

        }

        $fileName = iconv("utf-8", "gb2312", $fileName); // 重命名表

        try {
            $objPHPExcel->setActiveSheetIndex(0);
        } catch (\PHPExcel_Exception $e) {
        } // 设置活动单指数到第一个表,所以Excel打开这是第一个表

        header('Content-Type: application/vnd.ms-excel');

        header("Content-Disposition: attachment;filename='$fileName'");

        header('Cache-Control: max-age=0');

        try {
            $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        } catch (\PHPExcel_Reader_Exception $e) {
        }

        try {
            $objWriter->save('php://output');
        } catch (\PHPExcel_Writer_Exception $e) {
        } // 文件通过浏览器下载

        exit();

    }

}
