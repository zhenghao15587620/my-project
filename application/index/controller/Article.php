<?php
/**
 * Created by PhpStorm.
 * User: luyan
 * Date: 2019/1/11
 * Time: 10:00
 */

namespace app\index\controller;

use app\index\model\Article as ArticleModel;
use think\Db;
use think\Request;

class Article extends Base
{
    //文章的首页
    public function articleList()
    {
        $this->view->assign('title', '文章列表');
        $this->view->assign('keywords', '文章系统');
        $this->view->assign('desc', '文章系统图片');

        $this->view->count = ArticleModel::count();
        $articleList = ArticleModel::paginate(5);
        $this->view->assign('articleList', $articleList);
        //渲染管理员列表模板
        return $this->view->fetch('article_list');

    }

    //文章状态的变更
    public function setStatus(Request $request)
    {
        $article_id = $request->param('id');
        $result = ArticleModel::get($article_id);

        if ($result->getData('status') == 1) {
            ArticleModel::update(['status' => 0], ['id' => $article_id]);
        } else {
            ArticleModel::update(['status' => 1], ['id' => $article_id]);
        }
    }

    //渲染文章添加界面
    public function addArticle()
    {
        $this->view->assign('title', '添加文章');
        $this->view->assign('keywords', 'php.cn');
        $this->view->assign('desc', '开发');

        //将班级表中所有数据赋值给当前模板
        $this->view->assign('list', \app\index\model\Article::all());

        return $this->view->fetch('article_add');
    }

    //执行添加文章的操作
    public function doAdd(Request $request)
    {
        //从提交表单中获取数据
        $data = $request->param();

        //新增学生记录
        $result = ArticleModel::create($data);

        //设置返回数据
        $status = 0;
        $message = '添加失败,请检查';

        //检测更新结果,将结果返回给grade_edit模板中的ajax提交回调处理
        if (true == $result) {
            $status = 1;
            $message = '恭喜, 添加成功~~';
        }
        return ['status' => $status, 'message' => $message];
    }

    //渲染文章编辑界面
    public function articleEdit(Request $request)
    {
        $article_id = $request->param('id');
        $result = ArticleModel::get($article_id);
        $this->view->assign('title', '编辑文章');
        $this->view->assign('keywords', 'php.cn');
        $this->view->assign('desc', '开发');

        $this->view->assign('article', $result);
        return $this->view->fetch('article_edit');
    }

    //执行更新文章操作
    public function doEdit(Request $request)
    {
        //获取前端提交过来的表单数据,此处可以修改班级信息,请不要过滤过grade字段
        $data = $request->param();


        //设置更新条件
        $condition = ['id' => $data['id']];

        //更新当前记录,update必须要有条件,否则不会执行
        $result = ArticleModel::update($data, $condition);

        //设置返回数据给前端ajax处理
        $status = 0;
        $message = '更新失败,请检查';

        //检测更新结果,将结果返回给student_edit模板中的ajax提交回调处理
        if (true == $result) {
            $status = 1;
            $message = '恭喜, 更新成功~~';
        }
        return ['status' => $status, 'message' => $message];
    }


    //软删除操作
    public function deleteArticle(Request $request)
    {
        $article_id = $request->param('id');
        ArticleModel::update(['is_delete' => 1], ['id' => $article_id]);
        ArticleModel::destroy($article_id);

    }

    //恢复删除操作
    public function unDelete()
    {
        ArticleModel::update(['delete_time' => NULL], ['is_delete' => 1]);
    }

    //导出文章得数据
   public function excel(){
       vendor("PHPExcel.PHPExcel");
       $objPHPExcel = new \PHPExcel();

       $PHPSheet = $objPHPExcel->getActiveSheet();

       // $PHPSheet->setTitle("数据导出"); //给当前活动sheet设置名称
       $PHPSheet   ->setCellValue("A1", "ID")
                   ->setCellValue("B1", "文章主题")
                   ->setCellValue("C1", "文章描述")
                   ->setCellValue("D1", "文章状态");

       $PHPSheet->getColumnDimension('C')->setWidth(30);//设置宽度
       $PHPSheet->getColumnDimension('D')->setWidth(15);
       $PHPSheet->getColumnDimension('A')->setWidth(15);
       $PHPSheet->getColumnDimension('B')->setWidth(30);
       $PHPSheet->getStyle('A')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_JUSTIFY);
       $PHPSheet->getStyle('E')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_JUSTIFY);
       // $PHPSheet->getStyle('A')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
       $data = Db::table('article')
           ->select();
       $d = 2;
       foreach ($data as $key => $vo) {
           $PHPSheet->setCellValue("A" . $d, $vo['id'])
               ->setCellValue("B" . $d, $vo['article_name'])
               ->setCellValue("C" . $d, $vo['article_desc'])
               ->setCellValue("D" . $d, $vo['status']);
           $d++;
       }
       $PHPWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, "Excel2007");
       ob_end_clean(); // Added by me
       ob_start(); // Added by me
       header('Content-Disposition: attachment;filename="article.xlsx"');
       header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
       $PHPWriter->save("php://output"); //表示在$path路径下面生成demo.xlsx文件
   }

}