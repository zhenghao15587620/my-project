<?php
/**
 * Created by PhpStorm.
 * User: luyan
 * Date: 2019/1/10
 * Time: 13:36
 */

namespace app\index\controller;
use app\index\model\Picture as PictureModel;
use think\Request;

class Picture extends Base
{
    /**
     *
     */
    public function pictureList(){
        $this -> view -> assign('title', '图片列表');
        $this -> view -> assign('keywords', '教学系统');
        $this -> view -> assign('desc', '教学系统图片');

        $this->view->count=PictureModel::count();
        $list=PictureModel::all();
//        var_dump($list);
//        exit;
        $this -> view -> assign('list', $list);
        //渲染管理员列表模板
        return $this -> view -> fetch('picture_list');

    }
    //更改上线下线的状态
    public function setStatus(Request $request)
    {
        $user_id = $request -> param('id');
        $result = PictureModel::get($user_id);
        if($result->getData('status') == 1) {
            PictureModel::update(['status'=>0],['id'=>$user_id]);
        } else {
            PictureModel::update(['status'=>1],['id'=>$user_id]);
        }
    }


    //渲染//添加图片界面
    public function PictureAdd()
    {
        $this->view->assign('title','添加图片');
        $this->view->assign('keywords','picture');
        $this->view->assign('desc','开发');

        //将班级表中所有数据赋值给当前模板
        $this->view->assign('gradeList',\app\index\model\Grade::all());

        return $this->view->fetch('picture_add');
    }

    //添加图片
    public function doAdd(Request $request)
    {
        //从提交表单中获取数据
        $data = $request -> param();

        //向表中新增记录
        $file = request()->file('picture');//获取上传图片
        // 移动到框架应用根目录/public/uploads/ 目录下
        if ($file) {
            $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
            if ($info) {
                $img = $info->getSaveName();//获取名称
                $imgpath = DS.'uploads'.DS.$img;
                $path = str_replace(DS,"/",$imgpath);//数据库存储路径
            } else {
                $status = 0;
                $message = '图片上传失败';
            }
        }else{
            $status = 0;
            $message = '图片上传失败';

        }
        $result = PictureModel::create($data);
        if ($result==true){
            $status=1;
            $message='上传成功';
        }
        return ['status' => $status, 'message' => $message];
    }



    //软删除操作
    public function deletePicture(Request $request)
    {
        $picture_id = $request -> param('id');
        PictureModel::update(['is_delete'=>1],['id'=> $picture_id]);
        PictureModel::destroy($picture_id);

    }

    //恢复删除操作
    public function unDelete()
    {
        PictureModel::update(['delete_time'=>NULL],['is_delete'=>1]);
    }
}