<?php
// +----------------------------------------------------------------------
// | 会员管理
// +----------------------------------------------------------------------
// | Author: Ansel 3126620990@qq.com  绵阳人维网络科技有限公司
// +---------------------------------------------------------------------- 
namespace Admin\Controller; 
use Common\Controller\AdminBase;
class MemberController extends AdminBase { 
	/**
     * @cc 会员列表
     */ 
 	public function index(){
		$order=I('get.order','id desc');
		if($order=='id desc'){
			$this->assign('order','id asc');
		}else{
			$this->assign('order','id desc');	
		}
		$this->assign('data',info_page('member','20',$order)); 
		$this->display();
	} 
	/**
     * @cc 添加会员
     */ 
 	public function add_member(){
		if(IS_POST){
			$Member=D('Member');
			if ($Member->ins_up_data(I('post.'),1)){
				$this->success('添加成功','close');		
			}else{
				$this->error($Member->getError());	
			}
		}else{
			$group_list=M('group')->where(array('status'=>1))->select();
			$this->assign('group_list',$group_list);
			$this->display();	
		}
	} 
	/**
     * @cc 编辑会员
     */ 
 	public function edit_member(){
		if(IS_POST){
			$Member=D('Member');
			if ($Member->ins_up_data(I('post.'))){
				$this->success('保存成功','close');	
			}else{
				$this->error($Member->getError());	
			}
		}else{
			$uid=I('get.uid')?I('get.uid'):session('userinfo.uid');
			$info=D('Member')->where(array('id'=>$uid))->find();
			$this->assign("info",$info);
			$group_list=M('group')->where(array('status'=>1))->select();
			$this->assign('group_list',$group_list);
			$this->display('add_member');	
		}
	} 
	/**
     * @cc 修改会员密码
     */ 
 	public function editpwd(){
		if(IS_POST){
			$uid=I('post.uid')?	I('post.uid'):$this->error("参数错误");
			$pwd=I('post.pwd')?	I('post.pwd'):$this->error("参数错误");
			if(!preg_match('/^[a-z]\w{6,10}$/i',$pwd)){
				$this->error("请输入6-10位字母和数字混合密码");
			}
			$pwd=md5($pwd.C('ANSELKEY'));
			if(M('Member')->where(array('id'=>$uid))->setfield('password',$pwd)){
				$this->success("密码修改成功");	
			}else{
				$this->error("密码修改失败");
			}
		}
	}
	/**
     * @cc 删除会员
     */ 
 	public function del_member(){
		if(IS_GET){
			$uid=I('get.uid')?I('get.uid'):$this->error("参数错误");
			if(M('Member')->where(array('id'=>$uid))->delete()){
				$this->success("删除成功");
			}else{
				$this->error("删除失败");	
			}
		}
	} 
	/**
     * @cc 会员状态
     */ 
 	public function status_member(){
		if(IS_POST){
			$id=I('post.id')?I('post.id'):$this->error("参数错误");
			$type=I('post.type')?I('post.type'):$this->error("参数错误");
			$status=I('post.status');
			if(M('Member')->where(array('id'=>$id))->setfield($type,$status)){
				$this->success('状态更新成功');	
			}else{
				$this->error('状态更新失败');	
			}
		}
	} 
	/**
     * @cc 会员分组
     */ 
 	public function group(){
		$this->assign('data',info_page('group'));
		$this->display();
	} 
	/**
     * @cc 添加会员组
     */ 
 	public function add_group(){
		if(IS_POST){
			$group=D('Group');
			if($group->ins_up_data(I('post.'))){
				$this->success('添加成功','close');	
			}else{
				$this->error($group->getError());	
			}
		}else{
			$this->display();	
		}
	} 
	/**
     * @cc 编辑会员组
     */ 
 	public function edit_group(){
		if(IS_POST){
			$group=D('Group');
			if($group->ins_up_data(I('post.'))){
				$this->success('保存成功','close');	
			}else{
				$this->error($group->getError());	
			}
		}else{
			$id=I('get.gid')?I('get.gid'):$this->error("参数错误");
			$info=M('Group')->where(array('id'=>$id))->find();
			$this->assign("info",$info);
			$this->display('add_group');	
		}
	} 
	/**
     * @cc 删除会员组
     */ 
 	public function del_group(){
		if(IS_GET){
			$gid=I('get.gid')?I('get.gid'):$this->error("参数错误");
			if(M('Group')->where(array('id'=>$gid))->delete()){
				M('member')->where(array('gid'=>$gid))->delete();	
				$this->success("删除成功");
			}else{
				$this->error("删除失败");	
			}
		}
	} 
	/**
     * @cc 会员组状态
     */ 
 	public function status_group(){
		if(IS_POST){
			$id=I('post.id')?I('post.id'):$this->error("参数错误");
			$type=I('post.type')?I('post.type'):$this->error("参数错误");
			$status=I('post.status');
			if(M('group')->where(array('id'=>$id))->setfield($type,$status)){
				$this->success('状态更新成功');	
			}else{
				$this->error('状态更新失败');	
			}
		}
	} 
}