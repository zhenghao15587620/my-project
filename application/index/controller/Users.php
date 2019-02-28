<?php
/**
 * Created by PhpStorm.
 * User: luyan
 * Date: 2019/2/12
 * Time: 10:32
 */

namespace app\index\controller;


use Illuminate\Support\Facades\DB;

class Users extends Base
{
    //显示用户列表
    public function index(){
        $users=DB::table('user')->get();

        //在数据表中取某一行得数据first
        $user=DB::table('user')->where('name',"zhenghao")->first();
        echo $user->name;
        //取某一个值
        $phone=DB::table('user')->where("name",'zhenghao')->value('phone');
        echo $phone->phone;
        foreach ($users as $user){
            //访问某一个字段
            echo $user->name;
        }
        return view('user.index',['users'=>$users]);
    }

}