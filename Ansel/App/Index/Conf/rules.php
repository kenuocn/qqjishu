<?php
return array(
	//路由规则
	'URL_ROUTE_RULES'       =>  array(        
		'index/:p\d'=>'Index/index',// 首页
		'lists/:catid\d'=>'Index/lists',// 分类 
		'show/:id\d'=>'Index/show',//文章内容页
		'tag/:tag'=>'Index/tag',//标签
		'like'=>'Index/like',//点赞
		'search'=>'Index/search',// 搜索 
		'user'=>'User/user',  //用户中心
		'center'=>'User/center',//用户中心
		'saveinfo'=>'User/saveinfo',// 保存用户信息 
		'problem'=>'User/problem',// 提交bug或建议 
		'login'=>'User/login',// 登录页面 
		'send_email'=>'User/send_email',// 发送邮件验证码 
		'email_test/:uid'=>'User/email_test',// 获取好友列表 
		'reg'=>'User/reg', //会员注册页面
		'logout'=>'User/logout',// 退出登录 
		'getfriends'=>'User/getfriends',// 获取好友列表 
	),
);