<?php
return array(
	
    'URL_REQUEST_URI' => 'REQUEST_URI', // 获取当前页面地址的系统变量 默认为REQUEST_URI
    'URL_HTML_SUFFIX' => 'html', // URL伪静态后缀设置
    'URL_DENY_SUFFIX' => 'ico|png|gif|jpg', // URL禁止访问的后缀设置
    'URL_PARAMS_BIND' => true, // URL变量绑定到Action方法参数
    'URL_PARAMS_BIND_TYPE' => 0, // URL变量绑定的类型 0 按变量名绑定 1 按变量顺序绑定
    'URL_PARAMS_FILTER' => false, // URL变量绑定过滤
    'URL_PARAMS_FILTER_TYPE' => '', // URL变量绑定过滤方法 如果为空 调用DEFAULT_FILTER
    'URL_404_REDIRECT' => '', // 404 跳转页面 部署模式有效
	'TMPL_DETECT_THEME' => true,
	'VIEW_PATH'       =>'./Template/', // 改变某个模块的模板文件目录
	'DEFAULT_THEME'    =>'default', // 默认模板名称
	
	//扩展
	'LOAD_EXT_CONFIG'=>'url,rules',
	
	'SESSION_PREFIX' => 'Index',
);