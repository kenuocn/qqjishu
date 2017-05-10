<?php
// +----------------------------------------------------------------------
// | 权限管理
// +----------------------------------------------------------------------
// | Author: Ansel 3126620990@qq.com  绵阳人维网络科技有限公司
// +----------------------------------------------------------------------
namespace Admin\Controller;
use Common\Controller\AdminBase;
class AuthController extends AdminBase {
    /**
     * @cc 权限管理
     */
    public function index(){
        $this->assign('data',info_page('auth_rule','20','id desc'));
        $this->display();
    }
    /**
     * @cc 添加权限
     */
    public function add(){
        if(IS_POST){
            $User=D('AuthRule');
            if ($User->ins_up_data(I('post.'),1)){
                $this->success('添加成功',U('Auth/index'));
            }else{
                $this->error($User->getError());
            }
        }else{
            $this->assign('list',M('auth_rule')->where(array('status'=>1))->select());
            $this->display();
        }
    }
    /**
     * @cc 编辑权限
     */
    public function edit(){
        if(IS_POST){
            $User=D('AuthRule');
            if ($User->ins_up_data(I('post.'))){
                $this->success('保存成功',U('Auth/index'));
            }else{
                $this->error($User->getError());
            }
        }else{
            $id=I('get.id')?I('get.id'):$this->error("参数错误");
            $info=M('AuthRule')->where(array('id'=>$id))->find();
            $this->assign("info",$info);
            $this->display('add');
        }
    }
    /**
     * @cc 删除权限
     */
    public function del(){
        if(IS_GET){
            $id = I('get.id')?I('get.id'):$this->error("参数错误");
            if(M('auth_rule')->where(array('id'=>$id))->delete()){
                $this->success("删除成功");
            }else{
                $this->error("删除失败");
            }
        }
    }
    /**
     * @cc 权限状态
     */
    public function status(){
        if(IS_GET){
            $status=I('get.status');
            $id=I('get.id')?I('get.id'):$this->error('参数错误');
            if(M('auth_rule')->where(array('id'=>$id))->setField('status',$status)){
                $this->success("状态更新成功");
            }else{
                $this->error("状态更新失败");
            }
        }
    }
}