<?php
// +----------------------------------------------------------------------
// | Author: Ansel 3126620990@qq.com  绵阳人维网络科技有限公司
// +---------------------------------------------------------------------- 
namespace Common\Util;

abstract class Addon {

    //视图实例对象
    protected $view = NULL;
    //插件名称
    public $addonName = NULL;
    //插件配置文件
    public $configFile = NULL;
	//自定义配置模板 默认 调用系统的 config.html  
	public $custom_config = NULL;
    //插件目录
    public $addonPath = NULL;
    //安装插件 错误信息
    public $error = NULL;

    /**
     * 架构函数 取得模板对象实例
     * @access public
     */
    public function __construct() {
		$this->view=\Think\Think::instance('Think\View');
        //获取插件名称
        $this->addonName = $this->getAddonName();
        //插件目录
		$this->addonPath=ANSEL_ADDONS.$this->addonName.'/';
        //插件配置文件
        if (is_file($this->addonPath . 'config.php')) {
            $this->configFile = $this->addonPath . 'config.php';
        }
        //插件初始化
        if (method_exists($this, '_initialize')){
            $this->_initialize();
		}
    }

    /**
     * 获取插件名称
     * @return type
     */
    final public function getAddonName() {
        $class = end(explode('\\', get_class($this)));
        return substr($class, 0, strrpos($class, 'Addon'));
    }
	/**
     * 获取插件的配置数组
     */
    final public function getConfig($name = NULL){
        static $_config = array();
        if(empty($name)){
            $name = $this->getAddonName();
        }
        if(isset($_config[$name])){
            return $_config[$name];
        }
        $config =   array();
        $where['name']=$name;
        $where['status']=1;
        $config=M('Addons')->where($where)->getField('config');
		if($config){
			$config=unserialize($config);
		}else{
            $fileConfig = include $this->configFile;
            foreach ($fileConfig as $key => $value) {
                $config[$key] = $value['value'];
            }
        }
        $_config[$name]=$config;
        return $config;
    }
	
    /**
     * 模板主题设置
     * @access protected
     * @param string $theme 模版主题
     * @return Action
     */
    final protected function theme($theme){
        $this->view->theme($theme);
        return $this;
    }

    //显示方法
    final protected function display($template=''){
        if($template == ''){
            $template = CONTROLLER_NAME;
		}
        echo ($this->fetch($template));
    }

    /**
     * 模板变量赋值
     * @access protected
     * @param mixed $name 要显示的模板变量
     * @param mixed $value 变量的值
     * @return Action
     */
    final protected function assign($name,$value='') {
        $this->view->assign($name,$value);
        return $this;
    }


    //用于显示模板的方法
    final protected function fetch($templateFile = CONTROLLER_NAME){
        if(!is_file($templateFile)){
            $templateFile = $this->addonPath.$templateFile.C('TMPL_TEMPLATE_SUFFIX');
            if(!is_file($templateFile)){
                throw new \Exception("模板不存在:$templateFile");
            }
        }
        return $this->view->fetch($templateFile);
    }
	/**
     * 获取错误信息，安装卸载插件
     * @return string
     */
    public function getError() {
        return $this->error;
    }
	
    //必须实现安装
    abstract public function install();

    //必须卸载插件方法
    abstract public function uninstall();

    

}
