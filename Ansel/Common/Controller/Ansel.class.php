<?php
// +----------------------------------------------------------------------
// | Author: Ansel   <3126620990@qq.com>  绵阳人维网络科技有限公司
// +----------------------------------------------------------------------

namespace Common\Controller;
class Ansel extends \Think\Controller{
	//缓存
    public static $Cache = array();
	//配置
	protected $config;
	//初始化
	protected function _initialize() {
		//$this->inithooks();
		$this->initSite();
		$this->config=self::$Cache['Config'];
    }
	/**
     * 初始化钩子信息
     * @return Arry 配置数组
    
	protected function inithooks() {
		$data = S('hooks');
        if(!$data){
            $hooks = M('hooks')->getField('name,addons');
            foreach ($hooks as $key => $value) {
                if($value){
                    $map['status']  =   1;
                    $names          =   explode(',',$value);
                    $map['name']    =   array('IN',$names);
                    $data = M('Addons')->where($map)->getField('id,name');
                    if($data){
                        $addons = array_intersect($names, $data);
                        Hook::add($key,array_map('get_addon_class',$addons));
                    }
                }
            }
            S('hooks',Hook::get());
        }else{
            Hook::import($data,false);
        } 
    } */
	/**
     * 初始化站点配置信息
     * @return Arry 配置数组
     */
    protected function initSite() {
		if(S('Config')==false){
			$site=M('config')->getField("name,value");
			S("Config",$site);
		}
        $Config = S("Config");
        self::$Cache['Config'] = $Config;
        $this->assign("Config", $Config);
    }
	/**
     * 操作错误跳转的快捷方法
     * @access protected
     * @param string $message 错误信息
     * @param string $jumpUrl 页面跳转地址
     * @param mixed $ajax 是否为Ajax方式 当数字时指定跳转时间
     * @return void
     */
    final public function error($message = '', $jumpUrl = '', $ajax = false) {
		if($this->config['Operationlog']==1){
			D('Admin/Operationlog')->record($message, 0);
		}
        parent::error($message, $jumpUrl, $ajax);
    }

    /**
     * 操作成功跳转的快捷方法
     * @access protected
     * @param string $message 提示信息
     * @param string $jumpUrl 页面跳转地址
     * @param mixed $ajax 是否为Ajax方式 当数字时指定跳转时间
     * @return void
     */
    final public function success($message = '', $jumpUrl = '', $ajax = false) {
		if($this->config['Operationlog']==1){
			D('Admin/Operationlog')->record($message, 1);
		}
        parent::success($message, $jumpUrl, $ajax);
    }
}