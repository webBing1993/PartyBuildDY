<?php
/**
 * Created by PhpStorm.
 * User: laowang <958364865@qq.com>
 * Date: 2017/9/13
 * Time: 15:30
 */

namespace app\home\controller;

/**
 * Class Introduce
 * @package app\home\controller  党员简介
 */
class Introduce extends Base
{
    /**
     * 简介 首页
     */
    public function index(){
        return $this->fetch();
    }
}