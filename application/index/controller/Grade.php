<?php

namespace app\index\controller;
use app\index\model\Grade as GradeModel;
use app\index\model\Teacher;
use think\Request;

class Grade extends Base
{
    //班级列表
    public function  gradeList()
    {
        //获取所有班级表数据
        $grade = GradeModel::all();

        //获取记录数量
        $count = GradeModel::count();
        //遍历grade表
        foreach ($grade as $value){
            $data = [
                'id' => $value->id,
                'name' => $value->name,
                'length' => $value->length,
                'price' => $value->price,
                'status' => $value->status,
                'create_time' => $value->create_time,
                //用关联方法teacher属性方式访问teacher表中数据
                'teacher' => isset($value->teacher->name)? $value->teacher->name : '<span style="color:red;">未分配</span>',
            ];
            //每次关联查询结果,保存到数组$gradeList中
            $gradeList[] = $data;
        }

        $this -> view -> assign('gradeList', $gradeList);
        $this -> view -> assign('count', $count);

        return $this -> view -> fetch('grade_list');
    }

    //班级状态变更
    public function setStatus(Request $request)
    {
        //获取当前的班级ID
        $grade_id = $request -> param('id');

        //查询
        $result = GradeModel::get($grade_id);

        //启用和禁用处理
        if($result->getData('status') == 1) {
            GradeModel::update(['status'=>0],['id'=>$grade_id]);
        } else {
            GradeModel::update(['status'=>1],['id'=>$grade_id]);
        }
    }

    //渲染班级编辑界面
    public function gradeEdit(Request $request)
    {
        //获取到要编辑的班级ID
        $grade_id = $request -> param('id');

        //根据ID进行查询
        $result = GradeModel::get($grade_id);

        //关联查询,获取与当前班级对应的教师姓名
        $result -> teacher = $result -> teacher->name;

        //给当前页面seo变量赋值
        $this->view->assign('title','编辑班级');
        $this->view->assign('keywords','php.cn');
        $this->view->assign('desc','开发');

        //给当前编辑模板赋值
        $this->view->assign('grade_info',$result);

        //渲染编辑模板
        return $this->view->fetch('grade_edit');
    }

    //班级更新
    public function doEdit(Request $request)
    {
        //从提交表单中排除关联字段teacher字段
        $data = $request -> except('teacher');
//        $data = $request -> param();  如果全部获取,提交会提示缺少字段teacher

        //设置更新条件
        $condition = ['id'=>$data['id']];

        //更新当前记录
        $result = GradeModel::update($data,$condition);

        //设置返回数据
        $status = 0;
        $message = '更新失败,请检查';

        //检测更新结果,将结果返回给grade_edit模板中的ajax提交回调处理
        if (true == $result) {
            $status = 1;
            $message = '恭喜, 更新成功~~';
        }
        return ['status'=>$status, 'message'=>$message];
    }

    //渲染班级添加界面
    public function gradeAdd()
    {
        //给模板赋值seo变量
        $this->view->assign('title','编辑班级');
        $this->view->assign('keywords','php.cn');
        $this->view->assign('desc','开发');

        //渲染添加模板
        return $this->view->fetch('grade_add');
    }

    //添加班级
    public function doAdd(Request $request)
    {
        //从提交表单中获取数据
        $data = $request -> param();

        //更新当前记录
        $result = GradeModel::create($data);

        //设置返回数据的初始值
        $status = 0;
        $message = '添加失败,请检查';

        //检测更新结果,将结果返回给grade_edit模板中的ajax提交回调处理
        if (true == $result) {
            $status = 1;
            $message = '恭喜, 添加成功~~';
        }

        //自动转为json格式返回
        return ['status'=>$status, 'message'=>$message];
    }

    //软删除操作
    public function deleteGrade(Request $request)
    {
        $user_id = $request -> param('id');
        GradeModel::update(['is_delete'=>1],['id'=> $user_id]);
        GradeModel::destroy($user_id);

    }

    //恢复删除操作
    public function unDelete()
    {
        GradeModel::update(['delete_time'=>NULL],['is_delete'=>1]);
    }

}