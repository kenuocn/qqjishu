<?php
// +----------------------------------------------------------------------
// | Author: Ansel 3126620990@qq.com  绵阳人维网络科技有限公司
// +---------------------------------------------------------------------- 

namespace Addon\SystemInfo;
use Common\Util\Addon;

/**
 * 系统环境信息插件
 * @author Ansel
 */
class SystemInfoAddon extends Addon{

	public $info = array(
		'name'=>'SystemInfo',
		'title'=>'系统环境信息',
		'description'=>'用于显示一些服务器的信息',
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
	//实现的AdminIndex钩子方法
	public function AdminIndex($param){
		$config = $this->getConfig();
		$this->assign('sys_info',$this->_get_sys_info());
		$this->assign('config', $config);
		if($config['display']){
			$this->display('widget');
		}
	}
	/**
     * @cc 获取服务器信息
     */
	private function _get_sys_info(){
    	$sys_info['os']             = PHP_OS;
		$sys_info['zlib']           = function_exists('gzclose') ? 'YES' : 'NO';//zlib
		$sys_info['safe_mode']      = (boolean) ini_get('safe_mode') ? 'YES' : 'NO';//safe_mode = Off		
		$sys_info['timezone']       = function_exists("date_default_timezone_get") ? date_default_timezone_get() : "no_timezone";
		$sys_info['curl']			= function_exists('curl_init') ? 'YES' : 'NO';	
		$sys_info['web_server']     = $_SERVER['SERVER_SOFTWARE'];
		$sys_info['phpv']           = phpversion();
		$sys_info['ip'] 			= GetHostByName($_SERVER['SERVER_NAME']);
		$sys_info['fileupload']     = @ini_get('file_uploads') ? ini_get('upload_max_filesize') :'unknown';
		$sys_info['max_ex_time'] 	= @ini_get("max_execution_time").'s'; //脚本最大执行时间
		$sys_info['set_time_limit'] = function_exists("set_time_limit") ? true : false;
		$sys_info['domain'] 		= $_SERVER['HTTP_HOST'];
		$sys_info['memory_limit']   = ini_get('memory_limit');	
		
		$sys_info['appname']   	    = C('APPNAME');	
		$sys_info['bulid']   	    = C('BUILD');	
        $sys_info['version']   	    = C('VERSION');
		
		$sys_info['mysql_version']  = mysql_get_client_info();
		if(function_exists("gd_info")){
			$gd = gd_info();
			$sys_info['gdinfo'] 	= $gd['GD Version'];
		}else {
			$sys_info['gdinfo'] 	= "未知";
		}
		return $sys_info;
    }
}