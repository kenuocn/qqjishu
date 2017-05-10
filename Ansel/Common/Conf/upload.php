<?php
return array(
	"rootPath"=>"/d/",
	
/***百度编辑器***/
	/* 上传图片配置项 */
	"ueditor"=>array(
		"imageActionName"=>"image", /* 执行上传图片的action名称 */
		"imageFieldName"=> "upimage", /* 提交的图片表单名称 */
		"imageMaxSize"=> 2048000, /* 上传大小限制，单位B */
		"imageAllowFiles"=>array(".png", ".jpg", ".jpeg", ".gif", ".bmp"), /* 上传图片格式显示 */ 
		"imageCompressEnable"=> true, /* 是否压缩图片,默认是true */
		"imageCompressBorder"=> 1600, /* 图片压缩最长边限制 */
		"imageInsertAlign"=> "none", /* 插入的图片浮动方式 */
		"imageUrlPrefix"=> "", /* 图片访问路径前缀 */
		"imageSavePath"=>"image",
		
		/* 涂鸦图片上传配置项 */ 
		"scrawlActionName"=> "scrawl", /* 执行上传涂鸦的action名称 */
		"scrawlFieldName"=> "upscrawl", /* 提交的图片表单名称 */
		"scrawlMaxSize"=> 2048000, /* 上传大小限制，单位B */
		"scrawlUrlPrefix"=> "", /* 图片访问路径前缀 */
		"scrawlInsertAlign"=> "none",
		"scrawlSavePath"=>"scrawl", 
	
		/* 截图工具上传 */
		"snapscreenActionName"=> "image", /* 执行上传截图的action名称 */
		"snapscreenUrlPrefix"=> "", /* 图片访问路径前缀 */
		"snapscreenInsertAlign"=> "none", /* 插入的图片浮动方式 */
		"imageSavePath"=>"image",
	
		/* 抓取远程图片配置 */
		"catcherLocalDomain"=>array("127.0.0.1", "localhost", "img.baidu.com"),
		"catcherActionName"=> "catch", /* 执行抓取远程图片的action名称 */
		"catcherFieldName"=> "upcatch", /* 提交的图片列表表单名称 */
		"catcherUrlPrefix"=> "", /* 图片访问路径前缀 */
		"catcherMaxSize"=> 2048000, /* 上传大小限制，单位B */
		"catchSavePath"=>"image",
		"catcherAllowFiles"=>array(".png", ".jpg", ".jpeg", ".gif", ".bmp"), /* 抓取图片格式显示 */
	
		/* 上传视频配置 */
		"videoActionName"=> "video", /* 执行上传视频的action名称 */
		"videoFieldName"=> "upvideo", /* 提交的视频表单名称 */
		"videoUrlPrefix"=> "", /* 视频访问路径前缀 */
		"videoMaxSize"=> 102400000, /* 上传大小限制，单位B，默认100MB */
		"videoSavePath"=>"video",
		"videoAllowFiles"=>array(
			".flv", ".swf", ".mkv", ".avi", ".rm", ".rmvb", ".mpeg", ".mpg",
			".ogg", ".ogv", ".mov", ".wmv", ".mp4", ".webm", ".mp3", ".wav", ".mid"), /* 上传视频格式显示 */ 
	
		/* 上传文件配置 */
		"fileActionName"=> "file", /* controller里,执行上传视频的action名称 */
		"fileFieldName"=> "upfile", /* 提交的文件表单名称 */
		"fileUrlPrefix"=> "", /* 文件访问路径前缀 */
		"fileMaxSize"=> 20480000, /* 上传大小限制，单位B，默认20MB */
		"fileSavePath"=>"file",
		"fileAllowFiles"=>array( 
			".png", ".jpg", ".jpeg", ".gif", ".bmp",
			".flv", ".swf", ".mkv", ".avi", ".rm", ".rmvb", ".mpeg", ".mpg",
			".ogg", ".ogv", ".mov", ".wmv", ".mp4", ".webm", ".mp3", ".wav", ".mid",
			".rar", ".zip", ".tar", ".gz", ".7z", ".bz2", ".cab", ".iso",
			".doc", ".docx", ".xls", ".xlsx", ".ppt", ".pptx", ".pdf", ".txt", ".md", ".xml"
		), /* 上传文件格式显示 */
	
		/* 列出指定目录下的图片 */
		"imageManagerActionName"=> "listimage", /* 执行图片管理的action名称 */
		"imageManagerListPath"=> "image", /* 指定要列出图片的目录 */
		"imageManagerListSize"=> 20, /* 每次列出文件数量 */
		"imageManagerUrlPrefix"=> "", /* 图片访问路径前缀 */
		"imageManagerInsertAlign"=> "none", /* 插入的图片浮动方式 */
		"imageManagerAllowFiles"=>array(".png", ".jpg", ".jpeg", ".gif", ".bmp"), /* 列出的文件类型 */
	
		/* 列出指定目录下的文件 */
		"fileManagerActionName"=> "listfile", /* 执行文件管理的action名称 */
		"fileManagerUrlPrefix"=> "", /* 文件访问路径前缀 */
		"fileManagerListSize"=> 20, /* 每次列出文件数量 */
		"fileManagerAllowFiles"=>array(
			".png", ".jpg", ".jpeg", ".gif", ".bmp",
			".flv", ".swf", ".mkv", ".avi", ".rm", ".rmvb", ".mpeg", ".mpg",
			".ogg", ".ogv", ".mov", ".wmv", ".mp4", ".webm", ".mp3", ".wav", ".mid",
			".rar", ".zip", ".tar", ".gz", ".7z", ".bz2", ".cab", ".iso",
			".doc", ".docx", ".xls", ".xlsx", ".ppt", ".pptx", ".pdf", ".txt", ".md", ".xml"
		)/* 列出的文件类型 */
	)
);