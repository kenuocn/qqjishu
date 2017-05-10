<?php
// +----------------------------------------------------------------------
// | 角色管理
// +----------------------------------------------------------------------
// | Author: Ansel 3126620990@qq.com  绵阳人维网络科技有限公司
// +---------------------------------------------------------------------- 
namespace Admin\Controller; 
use Common\Controller\AdminBase;
class GroupController extends AdminBase { 
	/**
     * @cc 用户角色
     */ 
 	public function index(){
		$this->assign('data',info_page('auth_group'));
		$this->display();
	} 
	/**
     * @cc 添加角色
     */ 
 	public function add(){
		if(IS_POST){
			$AuthGroup=D('AuthGroup');
			if($AuthGroup->ins_up_data(I('post.'))){
				$this->success('添加成功',U('Group/index'));	
			}else{
				$this->error($AuthGroup->getError());	
			}
		}else{
			$this->display();	
		}
	} 
	/**
     * @cc 编辑角色
     */ 
 	public function edit(){
		if(IS_POST){
			$AuthGroup=D('AuthGroup');
			if($AuthGroup->ins_up_data(I('post.'))){
				$this->success('保存成功',U('Group/index'));	
			}else{
				$this->error($AuthGroup->getError());	
			}
		}else{
			$id=I('get.gid')?I('get.gid'):$this->error("参数错误");
			$info=D('AuthGroup')->where(array('id'=>$id))->find();
			$this->assign("info",$info);
			$this->display('add');	
		}
	} 
	/**
     * @cc 删除角色
     */ 
 	public function del(){
		if(IS_GET){
			$gid=I('get.gid')?I('get.gid'):$this->error("参数错误");
			M('auth_group')->where(array('id'=>$gid))->delete();
			$uids=M('auth_group_access')->where(array('group_id'=>$gid))->find();
			foreach($uids as $v){
				M('user')->where(array('id'=>$v['uid']))->delete();	
			}
			M('auth_group_access')->where(array('group_id'=>$gid))->delete();
			$this->success('删除成功');
		}
	} 
	/**
     * @cc 角色状态
     */ 
 	public function status(){
		if(IS_POST){
			$id=I('post.id')?I('post.id'):$this->error("参数错误");
			$type=I('post.type')?I('post.type'):$this->error("参数错误");
			$status=I('post.status');
			if(M('Auth_group')->where(array('id'=>$id))->setfield($type,$status)){
				$this->success('状态更新成功');	
			}else{
				$this->error('状态更新失败');	
			}
		}
	}
	/**
     * @cc 权限设置
     */ 
 	public function auth(){
		if(IS_POST){
			$gid=I('post.gid')?I('post.gid'):$this->error("参数错误");
            $ruleid=I('post.ruleid');
            $menuid=I('post.menuid');
            $urles=implode(',',$ruleid);
			if(M('auth_group')->where(array('id'=>$gid))->setfield(array('rules'=>$urles,'menu_rules'=>$menuid))){
				$this->success("设置成功");
			}else{
				$this->error("设置失败");	
			}
		}else{
			$gid=I('get.gid')?I('get.gid'):$this->error("参数错误");
			//查询所有权限
			$auth=M('auth_rule')->where(array('status'=>1))->select();
			//查出菜单
            $menu=M('menu')->where(array('type'=>1))->field('id,pid,name')->select();
            foreach($menu as $k=>$v){
                $menu[$k]['checked']=D('AuthGroup')->isrules($gid,$v['id'],1);
            }
            //查出权限表
            $auth=M('auth_rule')->where(array('status'=>1))->select();
            foreach($auth as $k=>$v){
                $auth[$k]['checked']=D('AuthGroup')->isrules($gid,$v['id']);
            }
            $this->assign('json', json_encode($menu));
            $this->assign('auth', $auth);
            $this->assign('gid',$gid);
            $this->display();
		}
	}
}