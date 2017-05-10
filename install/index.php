<?php
// +----------------------------------------------------------------------
// | Ansel 系统  安装程序
// +----------------------------------------------------------------------
// | Author: Ansel 3126620990@qq.com  绵阳人维网络科技有限公司
// +----------------------------------------------------------------------


/**
 * 安装向导
 */
header('Content-type:text/html;charset=utf-8');
// 检测是否安装过
if (file_exists('./install.lock') && @$_GET['step']!='success') {
   echo '你已经安装过该系统，请删除./install/下的install.lock文件';
   die;
}
// 同意协议页面
if(@!isset($_GET['step']) || @$_GET['step']=='index'){
    require './html/index.html';
}
// 检测环境页面
if(@$_GET['step']=='check'){
	require './html/check.html';
}
// 创建数据库页面
if(@$_GET['step']=='create'){
    require './html/create.html';
}
// 验证数据库密码
if(@$_GET['step']=='testdbpwd'){
    $dbHost = $_POST['dbhost'] . ':' . $_POST['dbport'];
	$conn = @mysql_connect($dbHost, $_POST['dbuser'], $_POST['dbpwd']);
	if ($conn) {
		exit("1");
	} else {
		exit("");
	}
}
// 安装成功页面
if(@$_GET['step']=='mysql'){
    // 判断是否为post
    if($_SERVER['REQUEST_METHOD']=='POST'){
        $dbhost = trim($_POST['dbhost']);
        $dbport = trim($_POST['dbport']);
        $dbname = trim($_POST['dbname']);
        $dbhost = empty($dbport) || $dbport == 3306 ? $dbhost : $dbhost . ':' . $dbport;
        $dbuser = trim($_POST['dbuser']);
        $dbpwd = trim($_POST['dbpwd']);
        $dbprefix = empty($_POST['dbprefix']) ? 'an_' : trim($_POST['dbprefix']);
        $username = trim($_POST['username']);
		$email = trim($_POST['email']);
        $password = trim($_POST['password']);
        //网站名称
        $title = addslashes(trim($_POST['title']));
        //描述
        $content = trim($_POST['content']);
        //关键词
        $keywords = trim($_POST['keywords']);
		
		$conn=@new mysqli("{$dbhost}:{$dbport}",$dbuser,$dbpwd);
		
        // 获取错误信息
        $error=$conn->connect_error;
        if (!is_null($error)) {
			$arr['msg'] = "连接数据库失败!".$error;
			$arr['status']=0;
            echo json_encode($arr);
            exit;
        }
		// 设置字符集
        $conn->query("SET NAMES 'utf8'");
		$version = $conn->server_info;
        if ($version < 5.0) {
            $arr['msg'] = '数据库版本太低!';
			$arr['status']=0;
            echo json_encode($arr);
            exit;
        }
        // 创建数据时同时设置编码
        if(!$conn->select_db($dbname)){
            $create_sql='CREATE DATABASE IF NOT EXISTS '.$dbname.' DEFAULT CHARACTER SET utf8;';
			if (!$conn->query($create_sql)) {
                $arr['msg'] = '数据库 ' . $dbname . ' 不存在，也没权限创建新的数据库！';
				$arr['status']=0;
                echo json_encode($arr);
                exit;
            }
            $conn->select_db($dbname);
        }
		// 导入sql数据并创建表
        $sqldata=file_get_contents('./data/data.sql');
        $sqlarray=preg_split("/;[\r\n]+/", str_replace('an_',$dbprefix,$sqldata));
        foreach ($sqlarray as $k => $v) {
            if (!empty($v)) {
                $conn->query($v);
            }
        }
		//更新配置信息
        $conn->query("UPDATE `{$dbprefix}config` SET  `value` = '$title' WHERE name='title'");
        $conn->query("UPDATE `{$dbprefix}config` SET  `value` = '$content' WHERE name='content'");
        $conn->query("UPDATE `{$dbprefix}config` SET  `value` = '$keywords' WHERE name='keywords'");
		
		//读取配置文件，并替换真实配置数据
        //$strConfig = file_get_contents('./data/config.php');
        $strConfig = file_get_contents('../Ansel/Common/Conf/config.php');
		$strConfig = str_replace('#DB_HOST#', $dbhost, $strConfig);
        $strConfig = str_replace('#DB_NAME#', $dbname, $strConfig);
        $strConfig = str_replace('#DB_USER#', $dbuser, $strConfig);
        $strConfig = str_replace('#DB_PWD#', $dbpwd, $strConfig);
        $strConfig = str_replace('#DB_PORT#', $dbport, $strConfig);
        $strConfig = str_replace('#DB_PREFIX#', $dbprefix, $strConfig);
        $strConfig = str_replace('#AUTHCODE#', genRandomString(18), $strConfig);
        $strConfig = str_replace('#COOKIE_PREFIX#', genRandomString(3) . "_", $strConfig);
        $strConfig = str_replace('#DATA_CACHE_PREFIX#', genRandomString(3) . "_", $strConfig);
        @file_put_contents('../Ansel/Common/Conf/config.php', $strConfig);
		
		 //插入管理员
        $time = time();
        $ip = '0.0.0.0';
        $password = md5($password . 'FC0BAC628E649CBA8C1833433F10D93E9EE1C8D4');
        $query = "INSERT INTO `{$dbprefix}user` VALUES ('1', '{$username}','{$password}', '{$username}','','{$email}','超级管理员','{$ip}','{$time}','1','0','');";
        $conn->query($query);
        $conn->close();
		@touch('./install.lock');
		$_SESSION['install']='installok';
        $arr['msg'] = '数据库 ' . $dbname . '创建成功！';
		$arr['status']=1;
		echo json_encode($arr);
		exit;
    }
}
// 创建数据库页面
if(@$_GET['step']=='success'){
    require './html/success.html';
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
