<?php
/**
 * Created by PhpStorm.
 * User: luyan
 * Date: 2019/1/10
 * Time: 13:37
 */

namespace app\index\model;

use think\Model;
use traits\model\SoftDelete;

class Picture extends Model
{
    //引用软删除方法
    use SoftDelete;
    //设置当前表默认时间日期
    protected $dateFormat='Y/m/d';
    //定义表中的删除的时间字段，来记录删除时间
    protected $deleteTime='delete_time';
    //开启自动时间戳
    protected $autoWriteTimestamp=true;

    protected $createTime='create_time';
    protected $updateTime='update_time';
    protected $type = [
        'add_time'=>'timestamp'
    ];
//    // 定义自动完成的属性
//    protected $insert = ['status' => 1];

    public function getUpAttr($value)
    {
        $up = [
            0=>'下线',
            1=> '上线'
        ];
        return $up[$value];
    }


}