<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/13 0013
 * Time: 上午 11:12
 */

namespace app\home\controller;

/**
 * Class Video
 * @package app\home\controller  视频控制器
 */
class Video extends Base
{
    /**
     * 主页
     */
    public function index(){
        return $this->fetch();
    }
}