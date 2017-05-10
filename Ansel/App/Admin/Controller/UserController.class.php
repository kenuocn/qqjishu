<?php
// +----------------------------------------------------------------------
// | 用户管理
// +----------------------------------------------------------------------
// | Author: Ansel 3126620990@qq.com  绵阳人维网络科技有限公司
// +---------------------------------------------------------------------- 
namespace Admin\Controller; 
use Common\Controller\AdminBase;
class UserController extends AdminBase { 
	/**
     * @cc 用户列表
     */ 
 	public function index(){
		$group_id=I('gid');
		if($group_id){
			$list=M()->table(C('DB_PREFIX').'auth_group_access a,'.C('DB_PREFIX').'auth_group b,'.C('DB_PREFIX').'user c')->where('a.group_id='.$group_id.' and c.id=a.uid and b.id='.$group_id.'')->field("b.title,c.*")->select();
			$nowpage = max(I('get.'.C('VAR_PAGE'), 0, 'intval'), 1);
			$data=info_page($list,$num=10,'','',$nowpage);
		}else{
			$data=info_page('user','20','id asc');
		}
		$this->assign('auth',$this->auth);
		$this->assign('data',$data);
		$this->display();	
	} 
	/**
     * @cc 添加用户
     */ 
 	public function add(){
		if(IS_POST){
			$User=D('User');
			if ($User->ins_up_data(I('post.'),1)){
				$this->success('添加成功',U('User/index'));		
			}else{
				$this->error($User->getError());	
			}
		}else{
			$group_list=M('auth_group')->where(array('status'=>1))->select();
			$this->assign('group_list',$group_list);
			$this->display();	
		}
	} 
	/**
     * @cc 编辑用户
     */ 
 	public function edit(){
		if(IS_POST){
			$User=D('User');
			if ($User->ins_up_data(I('post.'))){
				$this->success('保存成功',U('User/index'));	
			}else{
				$this->error($User->getError());	
			}
		}else{
			$uid=I('get.uid')?I('get.uid'):session('userinfo.uid');
			if($this->auth || $uid==getuid()){
				$info=M('User')->where(array('id'=>$uid))->find();
				$info['group_id']=getGroups($info['id']);
				$this->assign("info",$info);
				$group_list=M('auth_group')->where(array('status'=>1))->select();
				$this->assign('group_list',$group_list);
				$this->assign('auth',$this->auth);
				$this->display('add');	
			}else{
				$this->error("无操作权限");	
			}
		}
	}
	/**
     * @cc 修改用户密码
     */
	public function editpwd(){
		if(IS_POST){
			$uid=I('post.uid')?	I('post.uid'):$this->error("参数错误");
			if($this->auth || $uid==session('userinfo.uid')){
				$pwd=I('post.pwd')?	I('post.pwd'):$this->error("参数错误");
				if(!preg_match('/^[a-z]\w{6,10}$/i',$pwd)){
					$this->error("请输入6-10位字母和数字混合密码");
				}
				$pwd=md5($pwd.C('ANSELKEY'));
				if(M('user')->where(array('id'=>$uid))->setfield('password',$pwd)){
					$this->success("密码修改成功");	
				}else{
					$this->error("密码修改失败");
				}
			}else{
				$this->error("无权限操作");	
			}
		}	
	}
	/**
     * @cc 删除用户
     */ 
 	public function del(){
		if(IS_GET){
			$uid=I('get.uid')?I('get.uid'):$this->error("参数错误");
			if($this->auth || $uid==getuid()){
				if($uid==getuid()){
					$this->error("不能删除自己");	
				}
				if(in_array($uid, C('ADMINUID'))){
					$this->error("当前用户不能删除");
				}
				if(M('User')->where(array('id'=>$uid))->delete()){
					M('auth_group_access')->where(array('uid'=>$uid))->delete();	
					$this->success("删除成功");
				}else{
					$this->error("删除失败");	
				}
			}else{
				$this->error("无操作权限");	
			}
		}
	} 
	/**
     * @cc 用户状态
     */ 
 	public function status(){
		if(IS_GET){
			$uid=I('get.uid')?I('get.uid'):$this->error("参数错误");
			if($this->auth || $uid==getuid()){
				if($uid==getuid()){
					$this->error("不能设置自己的状态");	
				}
				if(in_array($uid, C('ADMINUID'))){
					$this->error("当前用户不可操作");
				}
				$status=I('get.status');
				if(M('User')->where(array('id'=>$uid))->setField('status',$status)){
					$this->success("更新成功");	
				}else{
					$this->error("更新失败");	
				}
			}else{
				$this->error("无操作权限");	
			}
		}
	}  
}