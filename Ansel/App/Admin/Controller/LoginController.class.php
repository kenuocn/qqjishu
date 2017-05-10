<?php
// +----------------------------------------------------------------------
// | 系统登录
// +----------------------------------------------------------------------
// | Author: Ansel   <3126620990@qq.com>  绵阳人维网络科技有限公司
// +----------------------------------------------------------------------

namespace Admin\Controller;
use Common\Controller\Ansel;
class LoginController extends Ansel {
	
	/**
     * @cc 验证登录
     */ 
    public function login(){
		if(IS_POST){
			$username=I('post.username')?I('post.username'):$this->error("请输入用户名或者登陆邮箱");
			$password=I('post.password')?I('post.password'):$this->error("请输入密码");
			$password=md5($password.C('ANSELKEY'));
			$field=isemail($username)?'email':'username';
			$model=M('user');
			$user=$model->where(array($field=>$username,'password'=>$password))->find();
			if($user){
				if($user['status']==0){session(null);$this->error("账号已被停用");}
				//登录成功后修改登录信息
				$data['last_time']=time();
				$data['last_ip']=get_client_ip();
				$data['online']=1;
				$data['session_id']=session_id();
				$model->where(array('id'=>$user['id']))->save($data);
				//登录后缓存用户信息
				$session['uid']=$user['id'];
				$session['username']=$user['username'];
				$session['name']=$user['name'];
				$session['session_id']=session_id();
				session('userinfo',$session);
				if($this->config['loginglog']==1){
					$this->record($username,'',1);
				}
				$this->success('登录成功');	
			}else{
				if($this->config['loginglog']==1){
					$this->record($username,'',0);
				}
				$this->error("账号或密码错误");	
			}
		}else{
			if(session('userinfo.uid')){
				$this->redirect('Index/index');
			}
			$this->display();
		}
    }
	/**
     * @cc 记录登陆日志
     * @param type $identifier 登陆方式，uid,username
     * @param type $password 密码
     * @param type $status
     */
    private function record($identifier, $password, $status = 0) {
        //登录日志
        D('Admin/Loginlog')->addLoginLogs(array(
            "username" => $identifier,
            "status" => $status,
            "password" => $status ? '密码保密' : $password,
            "info" => is_int($identifier) ? '用户ID登录' : '用户名登录',
        ));
    }
	/**
     * @cc 退出登录
     */
	public function logout(){
		$data['online']=0;
		M('user')->where(array('id'=>session('userinfo.uid')))->save($data);
		session(null);
		$this->redirect('Login/login');
	}
}