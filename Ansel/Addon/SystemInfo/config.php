<?php
/*插件设置*/
return array(
	'title1' => array(//配置在表单中的键名 ,这个会是  name 值 
        'type' => 'text',
        'name' => '板块1',
        'value'=>'系统信息',//默认没有数据 
        'description' => '系统信息',//描述
    ),
	'title2' => array(//配置在表单中的键名 ,这个会是  name 值 
        'type' => 'text',
        'name' => '板块2',
        'value'=>'版权信息',//默认没有数据 
        'description' => '版权信息',//描述
    ),
	'display' => array(
		'type' => 'radio',
		'name' => '是否显示',
		'values' => array(
			'1'=>'显示',
			'0'=>'不显示'
		),
		'value'=>'1',
		'description' => '是否显示',//描述
    ),
);
