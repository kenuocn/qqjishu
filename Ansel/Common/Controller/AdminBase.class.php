<?php
// +----------------------------------------------------------------------
// | Author: Ansel   <3126620990@qq.com>  绵阳人维网络科技有限公司
// +----------------------------------------------------------------------
// | 需要登录和权限判断的模块都继承
// +----------------------------------------------------------------------
namespace Common\Controller;
class AdminBase extends Ansel{
	protected $auth = true;
	protected $uid = null;
	//初始化
	protected function _initialize() {
		parent::_initialize();
		if(I('get.menuid')){
			cookie("menuid", I('get.menuid') , array("prefix" =>MODULE_NAME."/".CONTROLLER_NAME."/".ACTION_NAME."_"));
		}
		$userid=session('userinfo.uid');
		if(empty($userid)){
			$this->redirect('Login/login');
		}else if(getGroups()==false){//如果用户组被禁用
			session(null); 
			$this->redirect('login/login');
		}else if(isuser()==false){//账号被禁用
			session(null);
			$this->redirect('login/login');		
		}else if(session('userinfo.session_id') != getsid() || !getsid()){//单点登录
			session(null); 
			$this->redirect('login/login');		
		}

		//先判断用户有没有模块访问权限
		if(!in_array(MODULE_NAME."/".CONTROLLER_NAME."/".ACTION_NAME, C('PUBLIC_AUTH'))){
			//权限验证
			if(!authCheck(MODULE_NAME."/".CONTROLLER_NAME."/".ACTION_NAME,$userid)){
				$this->error('无权限操作!');
			}		
		}

		$this->auth=authGroup($userid);
		$this->uid=$userid; 
    }
}