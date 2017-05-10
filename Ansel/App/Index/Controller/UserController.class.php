<?php
// +----------------------------------------------------------------------
// | Author: Ansel 3126620990@qq.com  绵阳人维网络科技有限公司
// +---------------------------------------------------------------------- 
// | 会员中心
// +---------------------------------------------------------------------- 
namespace Index\Controller;
class UserController extends Ubase {

	/**
     * @cc 初始化
     */
	protected function _initialize(){
		parent::_initialize();
	}
	
	
	/**
     * @cc 会员中心
     */
    public function account(){
		//查出自己的资料
		$model = M('member');
		$data = $model->where(array('id'=>$this->uid,'status'=>1))->find();
		$this->assign('userinfo',$data);
		$this->display($this->theme.'account');
    }
	/**
     * @cc 会员中心
     */
    public function center(){
		$this->assign('user',info_page('member','20','id desc',array('id'=>array("NEQ",$this->uid)))); 
		//$this->assign('article',info_page('member','20','id desc',array('id'=>array("NEQ",$this->uid)))); 
		$this->display($this->theme.'center');
    }
	/**
     * @cc 获取好友列表
     */
	public function getfriends(){
		$uid=$this->uid;
		$uinfo=M('Member')->where(array('id'=>$uid))->find();
		$user=array( //当前用户信息
		  "username"=>$uinfo['name'],
		  "id"=>$uinfo['id'],
		  "status"=>"online",
		  "sign"=>$uinfo['sign']?$uinfo['sign']:"注册时间:".date("Y-m-d",$uinfo['regtime']),
		  "avatar"=>$uinfo['avatar']?$uinfo['avatar']:"/statics/images/demo.jpg",
		);
		$group=M('Group')->where(array('status'=>1))->select();
		foreach($group as $k=>$v){
			$friend[$k]['groupname']=$v['name'];//分组名称
			$friend[$k]['id']=$v['id'];//分组id
			$glist=M('Member')->where(array('gid'=>$v['id'],'status'=>1,'id'=>array("NEQ",$uid)))->select();
			foreach($glist as $kk=>$vv){
				$friend[$k]['list'][$kk]['username']=$vv['name'];
				$friend[$k]['list'][$kk]['id']=$vv['id'];
				$friend[$k]['list'][$kk]['status']=$vv['online']?"online":"offline";
				$friend[$k]['list'][$kk]['avatar']=$vv['avatar']?$vv['avatar']:"/statics/images/demo.jpg";
				$friend[$k]['list'][$kk]['sign']=$vv['sign']?$vv['sign']:"注册时间:".date("Y-m-d",$uinfo['regtime']);
			}

		}
		//配置数据返回接口
		$data['code']=0;
		$data['msg']='';
		$data['data']['mine']=$user;
		$data['data']['friend']=$friend;
		echo json_encode($data);exit;
	}
	/**
	* 保存用户信息
	*/
	public function saveinfo(){
		$name=I('post.name')?I('post.name'):$this->error("请输入姓名");
		$sign=I('post.sign');
		if(M('Member')->where(array('id'=>$this->uid))->save(array('name'=>$name,'sign'=>$sign))){
			$this->success("保存成功");	
		}else{
			$this->error("保存失败");	
		}
	}
	/**
	* 提交bug或建议
	*/
	public function problem(){
		$data['type']=I('post.type')?I('post.type'):$this->error("请选择类型");
		$data['title']=I('post.title')?I('post.title'):$this->error("请输入标题");
		$data['info']=I('post.info')?I('post.info'):$this->error("请输入内容");	
		$data['mid']=$this->uid;
		$data['status']=0;
		$data['time']=time();
		if(M('problem')->add($data)){
			//发送邮件通知
			switch ($data['type']){
				case 1:
					$type="BUG";
				break;
				case 2:
					$type="建议";	
				break;
			}
			$uinfo=$this->uinfo;
			$str=<<<PHP
			<div style="font-size:20px;color:#f00;width:80%;margin:auto;text-align:center;">{$data['title']}</div>
			<div style="font-size:14px;color:#222;width:80%;margin:auto;margin-top:20px;">{$data['info']}</div>
PHP;
			SendMail("3126620990@qq.com","用户：".$uinfo['username']." 提交了".$type,$str);	
			$this->success("提交成功");	
		}else{
			$this->error("提交失败");	
		}
	}
	/*=============会员积分处理=================*/
	private function score($type){

	}
	
	
	
	/*====================登录 注册 退出======================*/
	
	/**
     * @cc 会员登陆
     */
	public function login(){
		if(IS_POST){
			$username=I('post.username')?I('post.username'):$this->error("请输入账号");
			$password=I('post.password')?I('post.password'):$this->error("请输入密码");
			$password=md5($password.C('ANSELKEY'));
			$field=isemail($username)?'email':'username';
			$user=M('Member')->where(array($field=>$username,"password"=>$password))->find();
			if($user){
				if($user['status']==0){session(null);$this->error("账号已被停用");}
				//登录后缓存用户信息
				session('uinfo',array('uid'=>$user['id'],'uname'=>$user['username'],'sign'=>$user['sign']));
				//登录成功后修改登录信息
				$data['lasttime']=time();
				$data['lastip']=get_client_ip();
				M('member')->where(array('id'=>$user['id']))->save($data);
				$this->success('登录成功');	
			}else{
				$this->error("账号或密码错误");	
			}
		}
	}
	/**
     * @cc 会员注册
     */
    public function reg(){
		if(IS_POST){
			$data['username']= I("post.username")?I("post.username"): $this->error('请输入用户名');
			if($data['username']=='admin'){
				$this->error("用户名包含非法字符");	
			}
			if(M('Member')->where(array('username'=>$data['username']))->find()){
				$this->error("该用户名已被使用");	
			}
			$s_email=session("send_code.email");
			$data['email'] = I("post.email");
			if($data['email']!=$s_email){
				$this->error('邮箱错误');
			}
			$data['code'] = I("post.code");
			$s_code=session("send_code.code");
			if($data['code']!=$s_code){
				$this->error('验证码错误');
			}
			$data['password'] = I("post.password")? md5(I('post.password').C('ANSELKEY')) : $this->error('请输入密码');
			$data['regtime']=time();
			$data['lasttime']=time();
			$data['lastip']=get_client_ip();
			$data['email_test']=1;
			$Member=D('Member');
			$uid=$Member->add($data);
			if ($uid){ 
				session('uinfo',array('uid'=>$uid,'uname'=>$data['username'],'sign'=>"暂无签名"));
				session('send_code',null);
				$this->success("注册成功");		
			}else{
				$this->error("注册失败");	
			}
		}
    }
	/**
     * @cc 发送邮件
     */
	public function send_email(){
		$email=I('post.email')?	I('post.email'):$this->error("请输入邮箱地址");
		if(M('Member')->where(array('email'=>$email))->find()){
			$this->error("邮箱已被注册");	
		}else{
			//发送邮件验证
			$code=genRandomString(6);
			$title="Ansel系统会员中心注册验证";
			$msg="验证码为：".$code;
			SendMail($email,$title,$msg);	
			//缓存验证码以及邮箱
			session('send_code',array('email'=>$email,'code'=>$code));
		}
	}
	/**
     * @cc 退出登陆
     */
    public function logout(){
		session(null);
		$this->redirect('/index/');
    }
	
}