<?php
/**
 * Created by PhpStorm.
 * User: luyan
 * Date: 2019/1/11
 * Time: 10:01
 */

namespace app\index\model;


use think\Model;
use traits\model\SoftDelete;

class Article extends Model
{
    //引用软删除方法集
    use SoftDelete;

    //设置当前表默认日期时间显示格式
    protected $dateFormat = 'Y/m/d';

    //定义表中的删除时间字段,来记录删除时间
    protected $deleteTime = 'delete_time';

    // 开启自动写入时间戳
    protected $autoWriteTimestamp = true;

    protected $createTime = 'create_time';

    protected $updateTime = 'update_time';

    protected $type = [
        'add_time'=>'timestamp',
    ];


}