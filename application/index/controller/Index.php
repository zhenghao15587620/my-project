<?php
namespace app\index\controller;

use app\index\model\Grade;
use app\index\model\Teacher;

class Index extends Base
{
    public function index()
    {
        $this -> isLogin();  //判断用户是否登录
        $this -> view -> assign('title', '开发');
        return $this -> view -> fetch();  //渲染首页模板
    }



    //教师与班级之间查询:当前模型为Teacher教师
    public function demo1()
    {
        $teacher = Teacher::get(3);
        $data = [
            'id' => $teacher->id,
            'name' => $teacher->name,
            'teacher' => $teacher->grade->name,
        ];
        dump($data);

    }
}
