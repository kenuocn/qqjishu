<?php
// +----------------------------------------------------------------------
// | Author: Ansel 3126620990@qq.com  绵阳人维网络科技有限公司
// +----------------------------------------------------------------------
return array(
	// +---------------------------------数据库配置开始-------------------------------------+ //
    /* 数据库设置 */
    'DB_TYPE' => 'mysql', // 数据库类型
    'DB_HOST' => '127.0.0.1', // 服务器地址
    'DB_NAME' => 'ansel', // 数据库名
    'DB_USER' => 'root', // 用户名
    'DB_PWD' => 'root', // 密码
    'DB_PORT' => '3306', // 端口
    'DB_PREFIX' => 'an_', // 数据库表前缀
    'DB_DEBUG' => false,

    /* 站点安全设置 */
    "AUTHCODE" => 'FwFEjzaVNNl5wjZYnA', //密钥

    /* Cookie设置 */
    "COOKIE_PREFIX" => 'glC_', //Cookie前缀

    /* 数据缓存设置 */
    'DATA_CACHE_PREFIX' => 'euA_', // 缓存前缀

	// +---------------------------------数据库配置结束-------------------------------------+ //
	
	 /* 默认设定 */
    'DEFAULT_M_LAYER' => 'Model', // 默认的模型层名称
    'DEFAULT_C_LAYER' => 'Controller', // 默认的控制器层名称
    'DEFAULT_V_LAYER' => 'View', // 默认的视图层名称
    'DEFAULT_LANG' => 'zh-cn', // 默认语言
    'DEFAULT_THEME' => '', // 默认模板主题名称
	'MODULE_DENY_LIST' => array('Common', 'Runtime'), // 设置禁止访问的模块列表
	'MODULE_ALLOW_LIST'=>array('Admin','Index','Worker'),//可访问模块
    'DEFAULT_MODULE' => 'Index', // 默认模块
    'DEFAULT_CONTROLLER' => 'Index', // 默认控制器名称
    'DEFAULT_ACTION' => 'index', // 默认操作名称
    'DEFAULT_CHARSET' => 'utf-8', // 默认输出编码
    'DEFAULT_TIMEZONE' => 'PRC', // 默认时区
    'DEFAULT_AJAX_RETURN' => 'JSON', // 默认AJAX 数据返回格式,可选JSON XML ...
    'DEFAULT_JSONP_HANDLER' => 'jsonpReturn', // 默认JSONP格式返回的处理方法
	'DATA_CACHE_KEY'=>'think',	
	
    /* 定义 I 函数过滤方法*/
    'DEFAULT_FILTER' => 'trim,htmlspecialchars,stripslashes',
  
    /*自定义标签*/
    'TAGLIB_BUILD_IN' => 'cx,Common\TagLib\Ansel', // 内置标签库名称(标签使用不必指定标签库名称),以逗号分隔 注意解 析顺序
	
	/*系统变量名称设置*/
	'VAR_MODULE'            =>  'm',     // 默认模块获取变量 
    'VAR_CONTROLLER'        =>  'c',    // 默认控制器获取变量
    'VAR_ACTION'            =>  'a',    // 默认操作获取变量
    'VAR_AJAX_SUBMIT'       =>  'ajax',  // 默认的AJAX提交变量
    'VAR_JSONP_HANDLER'     =>  'callback',
    'VAR_PATHINFO'          =>  's',    // 兼容模式PATHINFO获取变量例如 ?s=/module/action/id/1 后面的参数取决于URL_PATHINFO_DEPR
    'VAR_TEMPLATE'          =>  't',    // 默认模板切换变量
    'VAR_ADDON'             =>  'addon',    // 默认的插件控制器命名空间变量 3.2.2新增
	
	//注册新 命名空间
    'AUTOLOAD_NAMESPACE' => array(
        'Common' => COMMON_PATH,
        'Libs' => PROJECT_PATH . 'Libs', 
		'Addon' => ANSEL_ADDONS,
    ),
	
	//自定义函数库
	'LOAD_EXT_FILE'=>'common',
	
    //常量配置
    'TMPL_PARSE_STRING'=>array(
        '$public' => __ROOT__. '/statics',
    ),
	
    'TMPL_ACTION_ERROR' => PROJECT_PATH . 'Tpl/error.html', // 默认错误跳转对应的模板文件
    'TMPL_ACTION_SUCCESS' => PROJECT_PATH . 'Tpl/success.html', // 默认成功跳转对应的模板文件
    'TMPL_EXCEPTION_FILE' => PROJECT_PATH . 'Tpl/exception.html', // 异常页面的模板文件
	
    //扩展配置
    'LOAD_EXT_CONFIG'=>'addition,upload,version',
	
	//加密钥匙 
	'ANSELKEY'=>'FC0BAC628E649CBA8C1833433F10D93E9EE1C8D4',
    
	//缓存设置
    'DATA_CACHE_TIME'=>  0,      // 数据缓存有效期 0表示永久缓存
	'DATA_CACHE_KEY'=>'FC0BAC628E649CBA8C1833433F10D93E9EE1C8D4',
	
	'SHOW_PAGE_TRACE' => true, //显示页面Trace信息
	
	//系统模块名称设置
	'Admin_Name'=>'系统后台',

	
	//超级管理员用户id
	'ADMIN_UID'=>1,
	//超级管理员用户组
	'ADMIN_GID'=>1,
	

);
