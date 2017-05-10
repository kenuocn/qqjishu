<?php
// +----------------------------------------------------------------------
// | Author: Ansel   <3126620990@qq.com>  绵阳人维网络科技有限公司
// +----------------------------------------------------------------------
namespace Common\Controller;
class Base extends Ansel{

	//初始化
	protected function _initialize() {
		parent::_initialize();
		//获取站点信息
		$config=self::$Cache["Config"];
		if($config['web']==2){
			header("Content-type: text/html; charset=utf-8"); 
			echo $config['webtip'];exit;
		}else{
			$siteurl=(is_ssl() ? 'https://' : 'http://').$_SERVER['HTTP_HOST'].'/';
			//判断是否开启手机版
			if($config['ismobile']==1){
				if(ismobile()){
					C('VIEW_PATH','./Template/mobile/');
					$type='mobile';
					$name=$config['ThemeMobile'];
					$this->theme($config['ThemeMobile']); 	
				}else{
					C('VIEW_PATH','./Template/pc/');
					$type='pc';
					$name=$config['ThemePc'];
					$this->theme($config['ThemePc']); 
				}
			}else{
				C('VIEW_PATH','./Template/pc/');
				$type='pc';
				$name=$config['ThemePc'];
				$this->theme($config['ThemePc']); 
			}
			//获取当前使用的模板配置
			if(S('theme_cfg')==false){
				$info=M('theme_set')->where(array('name'=>$name,'type'=>$type))->getfield('config');
				$info=unserialize($info);
				S('theme_cfg',$info);
				$theme_cfg=$info;
			}else{
				$theme_cfg=S('theme_cfg');	
			}	
			//会员id
			$mid=session('uinfo.uid');	
			if($mid){
				$user=M('member')->where(array('id'=>$mid))->find();
				$this->assign('user',$user);	
			}
			$site_url=$siteurl.'Template/'.$type.'/'.$name."/";	
			$this->assign('ANSEL_URL',$site_url)
				->assign('config',$config)
				->assign('cfg',$theme_cfg);
		}
    }
}