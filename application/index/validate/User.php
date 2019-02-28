<?php
namespace app\index\validate;
use think\Validate;

class User extends Validate
{
  protected $rule = [
    'name' => 'require'
  ];

  protected $msg = [
    'name' => ['require' => '必须填呀~~']
  ];
}
