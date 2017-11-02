<?php
/**
 * Created by PhpStorm.
 * User: laowang <958364865@qq.com>
 * Date: 2017/9/13
 * Time: 9:46
 */

namespace app\home\controller;
use app\home\model\ConstitutionCopy;
use app\home\model\WechatUser;
use think\Controller;
use app\home\model\Constitution as ConstitutionModel;
/**
 * Class Party
 * @package app\home\controller  抄党章 
 */
class Party extends Base
{
    /**
     * 主页目录列表
     */
    public function index(){
        $userId = session('userId');
        //主页信息
        $list = ConstitutionModel::all();
        $this->assign('list',$list);

        //总字数
        $sum = 0;
        foreach ($list as $value) {
            $sum += $value['len'];
        }
        //当前字数
        $all = ConstitutionCopy::where('create_user',$userId)->select();
        $copy = array();
        $number = 0;
        foreach ($all as $value) {
            $number += $value['length'];
        }
        $copy['percent'] = round(($number/$sum)*100);   //四舍五入百分比
        //抄写次数
        $times = WechatUser::where('userid',$userId)->find();
        $copy['times'] = $times['times'];
        $this->assign('copy',$copy);

        //当前抄写章数
        $personal = ConstitutionCopy::where('create_user',$userId)->order('chapter desc')->find();
        if(empty($personal)) {
            $personal['chapter'] = 1;
            $personal['restart'] = 0;
        }else{
            //是否显示重置按钮
            if($personal['chapter'] == 12 && $personal['status'] == 1){
                $personal['restart'] = 1;   //是
            }else{
                $personal['restart'] = 0;   //否
            }

            //status为1时自动跳转到下一篇
            if($personal['status'] == 1) {
                $personal['chapter'] = $personal['chapter'] + 1;
            };
        }
        $this->assign('personal',$personal);

        return $this->fetch();
    }
    /**
     * 抄写页面
     */
    public function copy(){
//        $userId = session('userId');
        $userId = "060941edf92a51b75c6dfbeee67a8d35";
        $id = input('id');
        if(IS_POST){
            //传递数组
            $data = array(
                'chapter' => $id,
                'length' => input('length'),
                'status' => input('status'),
                'create_user' => $userId,
            );
            $map = array(
                'chapter' => $id,
                'create_user' => $userId,
            );

            $info = ConstitutionCopy::where($map)->find();  //查看是否存在记录，存在则更新，不存在新增
            if($info) {
                $mes = ConstitutionCopy::where($map)->update($data);
                if($mes){
//                    $userId = session('userId');
                    $userId = "060941edf92a51b75c6dfbeee67a8d35";
                    //总字数
                    $list = ConstitutionModel::all();
                    $sum = 0;
                    foreach ($list as $value) {
                        $sum += $value['len'];
                    }
                    //当前字数
                    $all = ConstitutionCopy::where('create_user',$userId)->select();
                    $number = 0;
                    foreach ($all as $value) {
                        $number += $value['length'];
                    }
                    $score = round(($number/$sum)*100);   //四舍五入百分比
                    $times = WechatUser::where('userid',$userId)->find();   //抄写次数
                    //总分，不足百分之一记1分。
                    if($score < 1) {
                        $mark = 1 + 100*$times['times'];
                    }else {
                        $mark = $score + 100*$times['times'];
                    }
                    $code['trad_score'] = $mark;
                    WechatUser::where('userid',$userId)->update($code);

                    return $this->success("更新成功");
                }else{
                    return $this->error("更新失败");
                }
            }else {
                $mes = ConstitutionCopy::create($data);
                if($mes) {
//                    $userId = session('userId');
                    $userId = "060941edf92a51b75c6dfbeee67a8d35";
                    //总字数
                    $list = ConstitutionModel::all();
                    $sum = 0;
                    foreach ($list as $value) {
                        $sum += $value['len'];
                    }
                    //当前字数
                    $all = ConstitutionCopy::where('create_user',$userId)->select();
                    $number = 0;
                    foreach ($all as $value) {
                        $number += $value['length'];
                    }
                    $score = round(($number/$sum)*100);   //四舍五入百分比
                    $times = WechatUser::where('userid',$userId)->find();   //抄写次数
                    //总分，不足百分之一记1分。
                    if($score < 1) {
                        $mark = 1 + 100*$times['times'];
                    }else {
                        $mark = $score + 100*$times['times'];
                    }
                    $code['trad_score'] = $mark;
                    WechatUser::where('userid',$userId)->update($code);

                    return $this->success("新增成功");
                }else{
                    return $this->error("新增失败");
                }
            }
        }else{
            //党章内容
            $msg = ConstitutionModel::get($id);
            switch ($msg['id']){
                case 1:
                    $msg['title'] = "第一章 总 纲";
                    break;
                case 2:
                    $msg['title'] = "第二章 党 员";
                    break;
                case 3:
                    $msg['title'] = "第三章 党的组织制度";
                    break;
                case 4:
                    $msg['title'] = "第四章 党的中央组织";
                    break;
                case 5:
                    $msg['title'] = "第五章 党的地方组织";
                    break;
                case 6:
                    $msg['title'] = "第六章 党的基层组织";
                    break;
                case 7:
                    $msg['title'] = "第七章 党的干部";
                    break;
                case 8:
                    $msg['title'] = "第八章 党的纪律";
                    break;
                case 9:
                    $msg['title'] = "第九章 党的纪律检查机关";
                    break;
                case 10:
                    $msg['title'] = "第十章 党 组";
                    break;
                case 11:
                    $msg['title'] = "第十一章 党和共产主义青年团的关系";
                    break;
                case 12:
                    $msg['title'] = "第十二章 党徽党旗";
                    break;
            }
            $this->assign('msg',$msg);

            //文章必须按照顺序来抄写。
            $con = array(
                'chapter' => $id,
                'create_user' => $userId,
            );
            $info = ConstitutionCopy::where($con)->find();
            /* status : 0 可提交，1已完成 ，2未到顺序
               length : 完成/未完成返回文章长度，未开始则为空 */
            $return = array();
            if($id == 1) {
                if($info && $info['status'] == 1) {
                    $return['status'] = 1;    //初始文章存在记录，显示已完成
                    $return['length'] = $info['length'];
                }else{
                    $return['status'] = 0;    //不存在记录或未完成，可提交
                    ($info['length']) ? $return['length'] = $info['length'] : $return['length'] = "";
                }
            }else{
                if($info) {
                    if($info['status'] == 1) {
                        $return['status'] = 1;    //存在且完成
                    }else{
                        $return['status'] = 0;    //未完成
                    }
                    $return['length'] = $info['length'];
                }else{
                    $return['status'] = 2;     //不存在按顺序
                    $return['length'] = "";

                    //上一篇完成，开启下篇可写
                    $con2 = array(
                        'chapter' => $id-1,
                        'create_user' => $userId,
                    );
                    $info2 = ConstitutionCopy::where($con2)->find();
                    if($info2['status'] == 1){
                        $return['status'] = 0;
                        $return['length'] = "";
                    }else{
                        $return['status'] = 2;
                        $return['length'] = "";
                    }

                }
            }
            $this->assign('return',json_encode($return));
            $this->assign('visit',$userId);
            return $this->fetch();
        }
    }
    /**
     * 排行榜
     */
    public function rank(){
        $this->anonymous();
        $wechatModel = new WechatUser();
        $userId = session('userId');
        $personal = $wechatModel->where('userid',$userId)->find();    //获取个人信息
        //传统模式
        $map['trad_score'] = array('neq',0);
        $trad = $wechatModel->where($map)->order('trad_score desc')->limit(50)->select();
        foreach ($trad as $key => $value) {
            if($value['userid'] == $userId) {
                $personal['trad_rank'] = $key+1;     //该用户传统排名
            }
        }
        if(isset($personal['trad_rank'])) {
            $personal['trad_rank'] = "第".$personal['trad_rank']."名";
        }else {
            $personal['trad_rank'] = "无";
        }
        $this->assign('per',$personal);
        $this->assign('trad',$trad);

        return $this->fetch();
    }

    /**
     * 重置文章
     */

    public function restart(){
        $userId = session('userId');
        $all = ConstitutionCopy::where('create_user',$userId)->delete();
        if($all) {
            //wechatuser抄写次数加1
            $map['times'] = array('exp','`times`+1');
            $first = WechatUser::where('userid',$userId)->update($map);
            if($first) {
                //重置刷新排行榜分数
                $time = WechatUser::where('userid',$userId)->find();
                $info['trad_score'] = 100*$time['times'];
                WechatUser::where('userid',$userId)->update($info);
                return $this->success("重置成功");
            }
        }else{
            return $this->error("重置失败");
        }
    }
}