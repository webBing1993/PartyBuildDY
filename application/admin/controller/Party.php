<?php
/**
 * Created by PhpStorm.
 * User: laowang <958364865@qq.com>
 * Date: 2017/9/13
 * Time: 9:35
 */

namespace app\admin\controller;
use app\admin\model\Constitution as ConstitutionModel;
/**
 * Class Party
 * @package app\admin\controller  党章管理 
 */
class Party extends Admin
{
    /**
     * 列表页
     */
    public function index(){
        $map['status'] = 0;
        $list = $this->lists("Constitution",$map,'id');
        foreach ($list as $value) {
            $value['content'] = mb_substr(strip_tags($value['content']),0,50);
        }
        $this->assign('list',$list);

        return $this->fetch();
    }
    /**
     * 添加党章
     */
    public function add(){
        if(IS_POST){
            $data = input('post.');
            if(empty($data['title'])){
                return $this->error("请输入党章标题");
            }elseif (empty($data['content'])){
                return $this->error("请输入党章内容");
            }else{
                $id = ConstitutionModel::create($data);
                if($id){
                    return $this->success("新增成功",Url('Constitution/index'));
                }else{
                    return $this->error("新增失败");
                }
            }
        }else{

            $this->assign('msg','');
            return $this->fetch('edit');
        }
    }
    /**
     * 编辑党章
     */
    public function edit(){
        if(IS_POST){
            $data = input('post.');
            if(empty($data['title'])){
                return $this->error("请输入党章标题");
            }elseif (empty($data['content'])){
                return $this->error("请输入党章内容");
            }else{
                $info = ConstitutionModel::update($data);
                if($info){
                    return $this->success("修改成功",Url('Constitution/index'));
                }else{
                    return $this->error("修改失败");
                }
            }
        }else{
            $id = input('id');
            $msg = ConstitutionModel::get($id);
            $this->assign('msg',$msg);
            return $this->fetch();
        }
    }
}