<?php
/*模板设置表单*/
return array(
	//上传图片
	'logo' => array(
        'type' => 'image',
        'name' => 'LOGO',
		'btn'=>'上传logo',
        'value'=>'',//默认没有数据
        'description' => '上传网站logo',//描述
    ),
	//二维码
	'ewm' => array(
        'type' => 'image',
        'name' => 'QQ群二维码',
		'btn'=>'上传群二维码',
        'value'=>'',//默认没有数据
        'description' => '上传QQ群二维码',//描述
    ),
	//上传文件
	'file' => array(
        'type' => 'file',
        'name' => '文件',
		'btn'=>'上传文件',
        'value'=>'',//默认没有数据
        'description' => '上传文件',//描述
    ),
	//广告图片
	'addimg' => array(
        'type' => 'image',
        'name' => '广告图',
		'btn'=>'上传广告图',
        'value'=>'',//默认没有数据
        'description' => '上传广告图，侧边栏广告',//描述
    ),
	//广告连接
	'addurl' => array(
        'type' => 'text',
        'name' => '广告链接',
        'value'=>'',//默认没有数据 
        'description' => '广告链接，侧边栏广告链接',//描述
    ),
	//公告标题
	'adtitle' => array(
        'type' => 'text',
        'name' => '网站公告标题',
        'value'=>'',//默认没有数据 
        'description' => '网站公告标题',//描述
    ),
	//公告内容
	'adinfo' => array(
        'type' => 'multi',
        'name' => '网站公告内容',
        'value'=>'',//默认没有数据 
        'description' => '网站公告内容',//描述
    ),
	//公告连接
	'adurl' => array(
        'type' => 'text',
        'name' => '网站公告连接',
        'value'=>'',//默认没有数据 
        'description' => '网站公告连接',//描述
    ),
	//内容页分享按钮
	'share' => array(
        'type' => 'multi',
        'name' => '分享按钮',
        'value'=>'',//默认没有数据 
        'description' => '内容页分享按钮',//描述
    ),
	//是否开启幻灯片   单选
	'slider' => array(
		'type' => 'radio',
		'name' => '开启幻灯片',
		'values' => array(
			'on' => '开启', //值  =>   名称
			'off' => '不开启'//值  =>   名称
		),
		'value' => 'on',
		'description' => '网站公告内容',//描述
    ),
	//复选框   复选框
	'ceshi' => array(
		'type' => 'chexkbox',
		'name' => '复选框测试',
		'values' => array(
			'1' => '测试1',//值  =>   名称
			'2' => '测试2',//值  =>   名称
			'3' => '测试3',//值  =>   名称
			'4' => '测试4',//值  =>   名称
		),
		'description' => '复选框测试',//描述
    ),
	//下拉框
	'select' => array(
		'type' => 'select',
		'name' => '下拉框',
		'values' => array(
			'1' => '测试1',//值  =>   名称
			'2' => '测试2',//值  =>   名称
			'3' => '测试3',//值  =>   名称
			'4' => '测试4',//值  =>   名称
		),
		'value' => '2',
		'description' => '下拉框',//描述
    ),
	//富文本编辑器
	'about' => array(
		'type' => 'editor',
		'name' => '关于博客', 
		'description' => '富文本编辑器内容',//描述
    ),
);