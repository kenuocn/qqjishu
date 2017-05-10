<?php
// +----------------------------------------------------------------------
// | Author: Ansel 3126620990@qq.com  绵阳人维网络科技有限公司
// +---------------------------------------------------------------------- 

namespace Addon\Language;
use Common\Util\Addon;

/**
 * 系统多语言测试插件
 * @author Ansel
 */
class LanguageAddon extends Addon{

	public $info = array(
		'name'=>'Language',
		'title'=>'系统多语言插件',
		'description'=>'用于设置系统多语言插件',
		'status'=>1, 
		'author'=>'Ansel',
		'version'=>'0.1'
	);
 
	public function install(){
		return true;
	}

	public function uninstall(){
		return true;
	}

	//实现的Admintopmenu钩子方法
	public function Admintopmenu($param){
		$this->display('widget');
	}
}