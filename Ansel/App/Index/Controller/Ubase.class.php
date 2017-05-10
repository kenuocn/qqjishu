<?php
// +----------------------------------------------------------------------
// | Author: Ansel 3126620990@qq.com  绵阳人维网络科技有限公司
// +---------------------------------------------------------------------- 
// | 会员中心 继承模块
// +---------------------------------------------------------------------- 
namespace Index\Controller;
use Common\Controller\Base;
class Ubase extends Base {

	protected $uid; //用户id
	protected $theme;//模板目录	
	protected $uinfo; //用户信息
	/**
     * @cc 初始化
     */
	protected function _initialize(){
		parent::_initialize();
		$this->theme='user/';
		//不需要验证登录的地址
		$nologin=array(
			'Index/User/login',
			'Index/User/reg',
			'Index/User/send_email'
		);
		$uid=session('uinfo.uid');
		$this->uid=$uid;
		//不需要判断登录
		if(!in_array(MODULE_NAME."/".CONTROLLER_NAME."/".ACTION_NAME,$nologin)){
			if(empty($this->uid)){
				$this->redirect('/login/');
			}	
			$user=M('Member')->where(array('id'=>$uid))->find();
			if(empty($user) || empty($user['status'])){
				session(null);
				$this->error("账号不存在或已被禁用",U('/login/'));		
			}else{
				$this->uinfo=$user;
				$this->assign('user',$user);	
			}
		}
	}
}