<?php
/**
 * Created by PhpStorm.
 * User: luyan
 * Date: 2019/1/15
 * Time: 9:30
 */

namespace app\index\controller;


class Test extends Common
{
    public function index(){
        return $this->view->fetch();
    }
    //导出数据表
    public function excel() {



        $name='文章';


        $header=['产品名称','描述','编号'];

        $id=input('id/a');
        // dump($id);die;
        $a=0;
        for($a=0;$a<count($id);$a++){

            $data[$a]=db('article')->where(array('id'=>$id[$a]))->find();
            for($b=0;$b<count($data);$b++){
                $newdata[$b]['article_name']=$data[$a]['article_name'];
                $newdata[$b]['article_desc']=$data[$a]['article_desc'];
                $newdata[$b]['id']=$data[$a]['id'];
            }
        }
        // dump($data);
        // dump($newdata);
        // dump($newdata[0]['stock']);
        // die;

        excelExport($name,$header,$newdata);



    }


}