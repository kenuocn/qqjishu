<?php
// +----------------------------------------------------------------------
// | 插件钩子管理
// +----------------------------------------------------------------------
// | Author: Ansel 3126620990@qq.com  绵阳人维网络科技有限公司
// +---------------------------------------------------------------------- 
namespace Admin\Controller; 
use Common\Controller\AdminBase;
class HooksController extends AdminBase { 
	/**
     * @cc 钩子管理
     */ 
 	public function hooks(){
		$this->assign('data',info_page('hooks','20','id desc')); 
		$this->display();
	} 
	/**
     * @cc 添加钩子
     */ 
 	public function add_hooks(){
		if(IS_POST){
			$Hooks=D('hooks');
			if ($Hooks->ins_up_data(I('post.'),1)){
				S('hooks', null);
				$this->success('添加成功',U('Hooks/hooks'));		
			}else{
				$this->error($Hooks->getError());	
			}
		}else{
			$this->display();	
		}
	} 
	/**
     * @cc 编辑钩子
     */ 
 	public function edit_hooks(){
		if(IS_POST){
			$Hooks=D('hooks');
			if ($Hooks->ins_up_data(I('post.'))){
				S('hooks', null);
				$this->success('保存成功',U('Hooks/hooks'));	
			}else{
				$this->error($Hooks->getError());	
			}
		}else{
			$id=I('get.id')?I('get.id'):$this->error("参数错误");
			$info=M('hooks')->where(array('id'=>$id))->find();
			$this->assign("info",$info);
			$this->display('add_hooks');	
		}
	} 
	/**
     * @cc 删除钩子
     */ 
 	public function del_hooks(){
		if(IS_GET){
			$id = I('get.id')?I('get.id'):$this->error("参数错误");
			if(M('hooks')->where(array('id'=>$id))->delete()){
				$this->success("删除成功");	
			}else{
				$this->error("删除失败");	
			}
		}
	} 
}