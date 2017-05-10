<?php
// +----------------------------------------------------------------------
// | Author: Ansel 3126620990@qq.com  绵阳人维网络科技有限公司
// +---------------------------------------------------------------------- 
// | 功能性
// +---------------------------------------------------------------------- 


/**
 * 处理插件钩子
 * @param string $hook   钩子名称
 * @param mixed $params 传入参数
 * @return void
 */
function hook($hook,$params=array()){
    \Think\Hook::listen($hook,$params);
}
/**
 * 获取插件类的类名
 * @param strng $name 插件名
 */
function get_addon_class($name){ 
    $class = "Addon\\{$name}\\{$name}Addon";
    return $class;
}
/**
* 对查询结果集进行排序
* @access public
* @param array $list 查询结果
* @param string $field 排序的字段名
* @param array $sortby 排序类型
* asc正向排序 desc逆向排序 nat自然排序
* @return array
*/
function list_sort_by($list,$field, $sortby='asc') {
   if(is_array($list)){
       $refer = $resultSet = array();
       foreach ($list as $i => $data)
           $refer[$i] = &$data[$field];
       switch ($sortby) {
           case 'asc': // 正向排序
                asort($refer);
                break;
           case 'desc':// 逆向排序
                arsort($refer);
                break;
           case 'nat': // 自然排序
                natcasesort($refer);
                break;
       }
       foreach ( $refer as $key=> $val)
           $resultSet[] = &$list[$key];
       return $resultSet;
   }
   return false;
}

/*
* 过滤词
*/
function filter($str){
	$allergicWord = C('FILTER');  
	for ($i=0;$i<count($allergicWord);$i++){  
		$content = substr_count($str, $allergicWord[$i]);  
		if($content>0){  
			$info = $content;  
			break;  
		 }  
	}  
	if($info>0){  
	   //有违法字符   
	   return false;  
	}else{  
	   //没有违法字符  
	   return true;  
	}	
}

/*webhtml文件列表*/
function filelist($list){
	if($list){
		if(is_serialized($list)){
			$data=unserialize($list);
			foreach($data as $k=>$v){
				echo '<li><img src="'.$v.'"><input name="pics[]" value="'.$v.'" type="hidden"><i class="fa fa-close" onclick="delfile(this)"></i></li>';	
			}
		}else{
			if(in_array($list)){
				foreach($data as $k=>$v){
					echo '<li><img src="'.$v.'"><input name="pics[]" value="'.$v.'" type="hidden"><i class="fa fa-close" onclick="delfile(this)"></i></li>';	
				}
			}
		}	
	}	
}

/*
* zip 打包
* filename 打包后的文件名 不传入 .zip
* dir 需要打包的文件夹
*/
function zippack($filename,$dir){
	if (empty($filename) || empty($dir)) {
       return false;;
    }
	$basename = $filename . '.zip';
	//先存入缓存目录
	$file = RUNTIME_PATH . $basename;
	//创建压缩包
	$zip = new \PclZip($file);
	$path = explode(':', $dir);
	$zip->create($dir, PCLZIP_OPT_REMOVE_PATH, $path[1] ? $path[1] : $path[0]);

	//获取用户客户端UA，用来处理中文文件名
	$ua = $_SERVER["HTTP_USER_AGENT"];
	//从下载文件地址中获取的后缀
	$fileExt = getFileSuffix(basename($file));
	//下载文件名后缀
	$baseNameFileExt = getFileSuffix($basename);
	if (preg_match("/MSIE/", $ua)) {
		$filename = iconv("UTF-8", "GB2312//IGNORE", $baseNameFileExt ? $basename : ($basename . "." . $fileExt));
	} else {
		$filename = $baseNameFileExt ? $basename : ($basename . "." . $fileExt);
	}
	header("Content-type: application/octet-stream");
	$encoded_filename = urlencode($filename);
	$encoded_filename = str_replace("+", "%20", $encoded_filename);
	if (preg_match("/MSIE/", $ua)) {
		header('Content-Disposition: attachment; filename="' . $encoded_filename . '"');
	} else if (preg_match("/Firefox/", $ua)) {
		header("Content-Disposition: attachment; filename*=\"utf8''" . $filename . '"');
	} else {
		header('Content-Disposition: attachment; filename="' . $filename . '"');
	}
	header('Content-Disposition: attachment; filename="' . $filename . '"');
	header("Content-Length: " . filesize($file));
	readfile($file);
}
/*
*列表分页
*$m  表名或者数组
*$num 每页数量 默认20
*$order 排序('id desc')
*$where 条件
*$null 数组分页当前页
*$field 字段
*/
function info_page($m,$num=20,$order='id asc',$where=null,$now=null,$field){ 
	if(empty($m)){return false;}
	if(is_array($m)){
		//数量
		$count = count($m);
		//把一个数组分割为新的数组块
		$m = array_chunk($m, $num, true);
		//根据分页取到对应的模块列表数据
		$res['list'] = $m[intval($now - 1)];
		//进行分页
		$page=page($count,$num);
		$res['page']=$page->show();//显示分页
		$res['count']=$count;
	}else{
		$count=M($m)->where($where)->count();
		$page=page($count,$num);
		$res['page']=$page->show();//显示分页
		$res['list']=M($m)->where($where)->field($field)->limit($page->firstRow.','.$page->listRows)->order($order)->select();//数据
		$res['count']=$count;
	}
	return $res;
}

/**
 * 分页处理
 * @param type $total 信息总数
 * @param type $size 每页数量
 * @param type $config 配置，会覆盖默认设置
 * @return \Page|array
 */
function page($total, $size = 20) { 
    $page=new \Think\Page($total,$size);
    $page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');
    return $page;
}
/**
 * 生成SEO
 * @param $catid        栏目ID
 * @param $title        标题
 * @param $description  描述
 * @param $keyword      关键词
 */
function seo($catid = '', $title = '', $description = '', $keyword = '') {
    if (!empty($title))
        $title = strip_tags($title);
    if (!empty($description))
        $description = strip_tags($description);
    if (!empty($keyword))
        $keyword = str_replace(' ', ',', strip_tags($keyword));
    $site = S("Config");
    $cate = getCate($catid);
	if(!empty($catid) && empty($title)){
		$seo['site_title'] =$cate['catname']."-".$site['title'];	
	}else if(!empty($title) && !empty($catid)){
		$seo['site_title'] =$title."-".$cate['catname']."-".$site['title'];		
	}else if(!empty($title)){
		$seo['site_title']=$title."-".$site['title'];	
	}else{
		$seo['site_title']=$site['title'];		
	}
    $seo['keyword']=empty($keyword)?$site['keywords']:$site['keywords'];
	$seo['description']=empty($description)?$site['content']:$site['content'];
    return $seo;
}


/**
* 友好的时间显示
*
* @param int    $sTime 待显示的时间
* @param string $type  类型. normal | mohu | full | ymd | other
* @param string $alt   已失效
* @return string
*/
function friendlyDate($sTime,$type = 'normal',$alt = 'false') {
        //sTime=源时间，cTime=当前时间，dTime=时间差
        $cTime        =    time();
        $dTime        =    $cTime - $sTime;
        $dDay        =    intval(date("z",$cTime)) - intval(date("z",$sTime));
        //$dDay        =    intval($dTime/3600/24);
        $dYear        =    intval(date("Y",$cTime)) - intval(date("Y",$sTime));
        //normal：n秒前，n分钟前，n小时前，日期
        if($type=='normal'){
            if( $dTime < 60 ){
                return $dTime."秒前";
            }elseif( $dTime < 3600 ){
                return intval($dTime/60)."分钟前";
            //今天的数据.年份相同.日期相同.
            }elseif( $dYear==0 && $dDay == 0  ){
                //return intval($dTime/3600)."小时前";
                return '今天'.date('H:i',$sTime);
            }elseif($dYear==0){
                return date("m月d日 H:i",$sTime);
            }else{
                return date("Y-m-d H:i",$sTime);
            }
        }elseif($type=='mohu'){
            if( $dTime < 60 ){
                return $dTime."秒前";
            }elseif( $dTime < 3600 ){
                return intval($dTime/60)."分钟前";
            }elseif( $dTime >= 3600 && $dDay == 0  ){
                return intval($dTime/3600)."小时前";
            }elseif( $dDay > 0 && $dDay<=7 ){
                return intval($dDay)."天前";
            }elseif( $dDay > 7 &&  $dDay <= 30 ){
                return intval($dDay/7) . '周前';
            }elseif( $dDay > 30 ){
                return intval($dDay/30) . '个月前';
            }
        //full: Y-m-d , H:i:s
        }elseif($type=='full'){
            return date("Y-m-d , H:i:s",$sTime);
        }elseif($type=='ymd'){
            return date("Y-m-d",$sTime);
        }else{
            if( $dTime < 60 ){
                return $dTime."秒前";
            }elseif( $dTime < 3600 ){
                return intval($dTime/60)."分钟前";
            }elseif( $dTime >= 3600 && $dDay == 0  ){
                return intval($dTime/3600)."小时前";
            }elseif($dYear==0){
                return date("Y-m-d H:i:s",$sTime);
            }else{
                return date("Y-m-d H:i:s",$sTime);
            }
        }
}
/*分割*/
function strsToArray($strs) {
	$result = array();
	$array = array();
	$strs = str_replace('，', ',', $strs);
	$strs = str_replace("n", ',', $strs);
	$strs = str_replace("rn", ',', $strs);
	$strs = str_replace(' ', ',', $strs);
	$array = explode(',', $strs);
	foreach ($array as $key => $value) {
		if ('' != ($value = trim($value))) {
			$result[] = $value;
		}
	}
	return $result;
}
/*
*写日志
*/
function setlog($arrx,$url){
	$php="<?php \n /*自动写入配置*/ \n return ".var_export($arrx,true)." \n ?>";
	file_put_contents($url,$php,FILE_APPEND);	
}
/**
* 删除指定文件夹和文件
*/
function deldir($dir){
	//先删除目录下的文件：
  $dh=opendir($dir);
  while ($file=readdir($dh)) {
    if($file!="." && $file!="..") {
      $fullpath=$dir."/".$file;
      if(!is_dir($fullpath)) {
          unlink($fullpath);
      } else {
          deldir($fullpath);
      }
    }
  }
  closedir($dh);
  //删除当前文件夹：
  if(rmdir($dir)) {
    return true;
  } else {
    return false;
  }	
}
//不管是几维数组，都能全转为一维数组
function arrayChange($arr){
	static $arr2;
	foreach($arr as $v){
		if(is_array($v)){
			arrayChange($v);
		}else{
			$arr2[]=$v;
		}
	}
	return $arr2;
}
/**
 * 字符截取
 * @param $string 需要截取的字符串
 * @param $length 长度
 * @param $dot
 */
function str_cut($sourcestr, $length, $dot = '...') {
    $returnstr = '';
    $i = 0;
    $n = 0;
    $str_length = strlen($sourcestr); //字符串的字节数 
    while (($n < $length) && ($i <= $str_length)) {
        $temp_str = substr($sourcestr, $i, 1);
        $ascnum = Ord($temp_str); //得到字符串中第$i位字符的ascii码 
        if ($ascnum >= 224) {//如果ASCII位高与224，
            $returnstr = $returnstr . substr($sourcestr, $i, 3); //根据UTF-8编码规范，将3个连续的字符计为单个字符         
            $i = $i + 3; //实际Byte计为3
            $n++; //字串长度计1
        } elseif ($ascnum >= 192) { //如果ASCII位高与192，
            $returnstr = $returnstr . substr($sourcestr, $i, 2); //根据UTF-8编码规范，将2个连续的字符计为单个字符 
            $i = $i + 2; //实际Byte计为2
            $n++; //字串长度计1
        } elseif ($ascnum >= 65 && $ascnum <= 90) { //如果是大写字母，
            $returnstr = $returnstr . substr($sourcestr, $i, 1);
            $i = $i + 1; //实际的Byte数仍计1个
            $n++; //但考虑整体美观，大写字母计成一个高位字符
        } else {//其他情况下，包括小写字母和半角标点符号，
            $returnstr = $returnstr . substr($sourcestr, $i, 1);
            $i = $i + 1;            //实际的Byte数计1个
            $n = $n + 0.5;        //小写字母和半角标点等与半个高位字符宽...
        }
    }
    if ($str_length > strlen($returnstr)) {
        $returnstr = $returnstr . $dot; //超过长度时在尾处加上省略号
    }
    return $returnstr;
}
/**
 * 获取文件名后缀
 */
function getFileSuffix($fileName) {
	return strtolower(pathinfo($fileName,  PATHINFO_EXTENSION));
}

/**
 * 邮件发送
 * @param type $address 接收人 单个直接邮箱地址，多个可以使用数组
 * @param type $title 邮件标题
 * @param type $message 邮件内容
 */
function SendMail($address, $title, $message) {
    $config = S('Config');
    import('PHPMailer');
    try {
        $mail = new \PHPMailer();
        $mail->IsSMTP();
        // 设置邮件的字符编码，若不指定，则为'UTF-8'
        $mail->CharSet = C("DEFAULT_CHARSET");
        $mail->IsHTML(true);
        // 添加收件人地址，可以多次使用来添加多个收件人
        if (is_array($address)) {
            foreach ($address as $k => $v) {
                if (is_array($v)) {
                    $mail->AddAddress($v[0], $v[1]);
                } else {
                    $mail->AddAddress($v);
                }
            }
        } else {
            $mail->AddAddress($address);
        }
		$mail->Port = $config['mail_port']; 
		$mail->SMTPSecure = 'ssl'; 
        // 设置邮件正文
        $mail->Body = $message;
        // 设置邮件头的From字段。
        $mail->From = $config['mail_from'];
        // 设置发件人名字
        $mail->FromName = $config['mail_fname'];
        // 设置邮件标题
        $mail->Subject = $title;
        // 设置SMTP服务器。
        $mail->Host = $config['mail_server'];
        // 设置为“需要验证”
        if ($config['mail_auth']) {
            $mail->SMTPAuth = true;
        } else {
            $mail->SMTPAuth = false;
        }
        // 设置用户名和密码。
        $mail->Username = $config['mail_user'];
        $mail->Password = $config['mail_password'];
        return $mail->Send();
    } catch (phpmailerException $e) {
        return $e->errorMessage();
    }
}
/**
 * 格式化字节大小
 * @param  number $size      字节数
 * @param  string $delimiter 数字和单位分隔符
 * @return string            格式化后的带单位的大小
 */
function format_bytes($size, $delimiter = '') {
	$units = array('B', 'KB', 'MB', 'GB', 'TB', 'PB');
	for ($i = 0; $size >= 1024 && $i < 5; $i++) $size /= 1024;
	return round($size, 2) . $delimiter . $units[$i];
}
/*
*删除文件指定的行
*$filename 文件地址
*$deline 行数
*/
function delline($filename,$delline){
	$farray=file($filename);//读取文件数据到数组中
	for($i=0;$i < count($farray);$i++){   
		if(strcmp($i+1,$delline)==0){  //判断删除的行,strcmp是比较两个数大小的函   
			continue;
		}
		if(trim($farray[$i])<>""){  //删除文件中的所有空行   
			$newfp.=$farray[$i];    //重新整理后的数据
		}   
	}
	$fp=@fopen($filename,"w");//以写的方式打开文件
	@fputs($fp,$newfp);
	@fclose($fp);	
}
/**
 * 获取指定行内容
 *
 * @param $file 文件路径
 * @param $line 行数
 * @param $length 指定行返回内容长度
 */
function getLine($file, $line, $length = 4096){
    $returnTxt = null; // 初始化返回
    $i = 1; // 行数

    $handle = @fopen($file, "r");
    if ($handle) {
        while (!feof($handle)) {
            $buffer = fgets($handle, $length);
            if($line == $i) $returnTxt = $buffer;
            $i++;
        }
        fclose($handle);
    }
    return $returnTxt;
}
/**
 * 产生一个指定长度的随机字符串,并返回给用户 
 * @param type $len 产生字符串的长度
 * @return string 随机字符串
 */
function genRandomString($len = 6) {
    $chars = array(
        "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k",
        "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v",
        "w", "x", "y", "z", "A", "B", "C", "D", "E", "F", "G",
        "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R",
        "S", "T", "U", "V", "W", "X", "Y", "Z", "0", "1", "2",
        "3", "4", "5", "6", "7", "8", "9"
    );
    $charsLen = count($chars) - 1;
    // 将数组打乱 
    shuffle($chars);
    $output = "";
    for ($i = 0; $i < $len; $i++) {
        $output .= $chars[mt_rand(0, $charsLen)];
    }
    return $output;
}
/**
 * 系统加密方法
 * @param string $data 要加密的字符串
 * @param string $key  加密密钥
 * @param int $expire  过期时间 单位 秒
 * return string
 * @author 麦当苗儿 zuojiazi@vip.qq.com
 */
function Ansel_en($data, $key = '', $expire = 0) {
    $key  = md5(empty($key) ? C('ANSELKEY') : $key);
    $data = base64_encode($data);
    $x    = 0;
    $len  = strlen($data);
    $l    = strlen($key);
    $char = '';
    for ($i = 0; $i < $len; $i++) {
        if ($x == $l) $x = 0;
        $char .= substr($key, $x, 1);
        $x++;
    }
    $str = sprintf('%010d', $expire ? $expire + time():0);
    for ($i = 0; $i < $len; $i++) {
        $str .= chr(ord(substr($data, $i, 1)) + (ord(substr($char, $i, 1)))%256);
    }
    return str_replace(array('+','/','='),array('-','_',''),base64_encode($str));
}
/**
 * 系统解密方法
 * @param  string $data 要解密的字符串 （必须是think_encrypt方法加密的字符串）
 * @param  string $key  加密密钥
 * return string
 * @author 麦当苗儿 zuojiazi@vip.qq.com
 */
function Ansel_de($data, $key = ''){
    $key    = md5(empty($key) ? C('ANSELKEY') : $key);
    $data   = str_replace(array('-','_'),array('+','/'),$data);
    $mod4   = strlen($data) % 4;
    if ($mod4) {
       $data .= substr('====', $mod4);
    }
    $data   = base64_decode($data);
    $expire = substr($data,0,10);
    $data   = substr($data,10);
    if($expire > 0 && $expire < time()) {
        return '';
    }
    $x      = 0;
    $len    = strlen($data);
    $l      = strlen($key);
    $char   = $str = '';
    for ($i = 0; $i < $len; $i++) {
        if ($x == $l) $x = 0;
        $char .= substr($key, $x, 1);
        $x++;
    }
    for ($i = 0; $i < $len; $i++) {
        if (ord(substr($data, $i, 1)) < ord(substr($char, $i, 1))) {
            $str .= chr((ord(substr($data, $i, 1)) + 256) - ord(substr($char, $i, 1)));
        }else{
            $str .= chr(ord(substr($data, $i, 1)) - ord(substr($char, $i, 1)));
        }
    }
    return base64_decode($str);
}
/**
 * 遍历获取目录下的指定类型的文件
 * @param $path
 * @param array $files
 * @return array
 */
function getfiles($path, $allowFiles, $key, &$files = array()){
	if (!is_dir($path)) return null;
	if(substr($path, strlen($path) - 1) != '/') $path .= '/';
	$handle = opendir($path);
	while (false !== ($file = readdir($handle))) {
		if ($file != '.' && $file != '..') {
			$path2 = $path . $file;
			if (is_dir($path2)) {
				getfiles($path2, $allowFiles, $key, $files);
			} else {
				if (preg_match("/\.(".$allowFiles.")$/i", $file) && preg_match("/.*". $key .".*/i", $file)) {
					$files[] = array(
						'url'=> substr($path2, strlen($_SERVER['DOCUMENT_ROOT'])),
						'name'=> $file,
						'mtime'=> filemtime($path2)
					);
				}
			}
		}
	}
	return $files;
}
/**
 * 字符串转换为数组，主要用于把分隔符调整到第二个参数
 * @param  string $str  要分割的字符串
 * @param  string $glue 分割符
 * @return array
 * @author 麦当苗儿 < zuojiazi@vip.qq.com>
 */
function str2arr($str, $glue = ','){
    return explode($glue, $str);
}

/**
 * 数组转换为字符串，主要用于把分隔符调整到第二个参数
 * @param  array  $arr  要连接的数组
 * @param  string $glue 分割符
 * @return string
 * @author 麦当苗儿 < zuojiazi@vip.qq.com>
 */
function arr2str($arr, $glue = ','){
    return implode($glue, $arr);
}

/**
 * 字符串截取，支持中文和其他编码
 * @static
 * @access public
 * @param string $str 需要转换的字符串
 * @param string $start 开始位置
 * @param string $length 截取长度
 * @param string $charset 编码格式
 * @param string $suffix 截断显示字符
 * @return string
 */
function msubstr($str, $start=0, $length, $charset="utf-8", $suffix=true) {
    if(function_exists("mb_substr"))
        $slice = mb_substr($str, $start, $length, $charset);
    elseif(function_exists('iconv_substr')) {
        $slice = iconv_substr($str,$start,$length,$charset);
        if(false === $slice) {
            $slice = '';
        }
    }else{
        $re['utf-8']   = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
        $re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
        $re['gbk']    = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
        $re['big5']   = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
        preg_match_all($re[$charset], $str, $match);
        $slice = join("",array_slice($match[0], $start, $length));
    }
    return $suffix ? $slice.'...' : $slice;
}
/**
 * 获取分类相关信息
 * @param type $catid 分类id
 * @param type $field 返回的字段，默认返回全部，数组
 * @return boolean
 */
function getCate($catid, $field = '') {
    if (empty($catid)) {
        return false;
    }
	//读取数据
	$info = M('sort')->where(array('id' => $catid))->find();
    if ($field) {
        //支持var.property，不过只支持一维数组
        if (false !== strpos($field, '.')) {
            $vars = explode('.', $field);
            return $info[$vars[0]][$vars[1]];
        } else {
            return $info[$field];
        }
    } else {
        return $info;
    }
}
//基于数组创建目录和文件
function create_files($files){
    foreach ($files as $key => $value) {
        if(substr($value, -1) == '/'){
            mkdir($value);
        }else{
            @file_put_contents($value, '');
        }
    }
}
/**
 * 插件模板定位
 * @staticvar array $TemplateFileCache
 * @param type $templateFile
 * @param type $addonPath 插件目录
 * @return type
 */
function parseAddonTemplateFile($templateFile = '', $addonPath) {
    static $TemplateFileCache = array();
    C('TEMPLATE_NAME', $addonPath . 'View/');
    //模板标识
    if ('' == $templateFile) {
        $templateFile = C('TEMPLATE_NAME') . ucwords(ADDON_MODULE_NAME) . '/' . ACTION_NAME . C('TMPL_TEMPLATE_SUFFIX');
    }
    $key = md5($templateFile);
    if (isset($TemplateFileCache[$key])) {
        return $TemplateFileCache[$key];
    }
    if (false === strpos($templateFile, C('TMPL_TEMPLATE_SUFFIX'))) {
        // 解析规则为 模板主题:模块:操作 不支持 跨项目和跨分组调用
        $path = explode(':', $templateFile);
        $action = array_pop($path);
        $module = !empty($path) ? array_pop($path) : ucwords(ADDON_MODULE_NAME);
        $path = C("TEMPLATE_NAME");
        $depr = defined('GROUP_NAME') ? C('TMPL_FILE_DEPR') : '/';
        $templateFile = $path . $module . $depr . $action . C('TMPL_TEMPLATE_SUFFIX');
    }
    //区分大小写的文件判断，如果不存在，尝试一次使用默认主题
    if (!file_exists_case($templateFile)) {
        //记录日志
        $log = '模板:[' . $templateFile . ']不存在！';
        throw_exception($log);
    }
    $TemplateFileCache[$key] = $templateFile;
    return $templateFile;
}


/**
 * 张高元封装var_dump简洁打印输出自带bootstrap样式
 * @param  array $data 数据
 * @return string       带bootstrap样式的数据
 */
function zgy($data)
{
    // 定义样式
    $str='<pre style="display: block;padding: 9.5px;margin: 44px 0 0 0;font-size: 13px;line-height: 1.42857;color: #333;word-break: break-all;word-wrap: break-word;background-color: #F5F5F5;border: 1px solid #CCC;border-radius: 4px;">';
    // 如果是boolean或者null直接显示文字；否则print
    if (is_bool($data)) {
        $show_data=$data ? 'true' : 'false';
    }elseif (is_null($data)) {
        $show_data='null';
    }else{
        $show_data=print_r($data,true);
    }
    $str.=$show_data;
    $str.='</pre>';
    die($str);
}