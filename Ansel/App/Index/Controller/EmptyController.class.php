<?php
// +----------------------------------------------------------------------
// | Author: Ansel 3126620990@qq.com  绵阳人维网络科技有限公司
// +---------------------------------------------------------------------- 
namespace Index\Controller;
class EmptyController {

    //插件标识
    public $addonName = NULL;
    //插件路径
    protected $addonPath = NULL;

    public function __construct() {
        $this->addonName = CONTROLLER_NAME;
        $this->addonPath = ANSEL_ADDONS . $this->addonName . '/';
    }

    //魔术方法
    public function __call($method, $args) {
        $isAdmin = I('get.isadmin');
        $class = 'Index';//$isAdmin ? 'Admin' : 'Index';
        if (!require_cache("{$this->addonPath}Controller/{$class}Controller.class.php")) {
            E("插件{$this->addonName}实例化错误！");
        }
        define('ADDON_MODULE_NAME', $class);
        $object = \Think\Think::instance("\\Addon\\".$this->addonName."\\Controller\\{$class}Controller");
        return $object->$method($args);
    }

}
