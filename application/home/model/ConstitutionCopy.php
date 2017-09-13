<?php
/**
 * Created by PhpStorm.
 * User: Lxx<779219930@qq.com>
 * Date: 2016/8/16
 * Time: 10:10
 */

namespace app\home\model;
use think\Model;

class ConstitutionCopy extends Model {
    protected $insert=[
        'create_time' => NOW_TIME,
    ];
}