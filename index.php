<?php
// +----------------------------------------------------------------------
// | Ansel 博客系统
// +----------------------------------------------------------------------
// | Author: Ansel 3126620990@qq.com  绵阳人维网络科技有限公司
// +----------------------------------------------------------------------

      /*
                           _ooOoo_
                          o8888888o
                          88" . "88
                          (| -_- |)
                          O\  =  /O
                       ____/`---'\____
                     .'  \\|     |//  `.
                    /  \\|||  :  |||//  \
                   /  _||||| -:- |||||-  \
                   |   | \\\  -  /// |   |
                   | \_|  ''\---/''  |   |
                   \  .-\__  `-`  ___/-. /
                 ___`. .'  /--.--\  `. . __
              ."" '<  `.___\_<|>_/___.'  >'"".
             | | :  `- \`.;`\ _ /`;.`/ - ` : | |
             \  \ `-.   \_ __\ /__ _/   .-` /  /
        ======`-.____`-.___\_____/___.-`____.-'======
                           `=---='
        ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
                 佛祖保佑       永无BUG
        */

// 检测PHP环境
if(version_compare(PHP_VERSION,'5.3.0','<'))  die('require PHP > 5.3.0 !');
if(file_exists("./install") && !file_exists("./install/install.lock")){
    $url=$_SERVER['HTTP_HOST'].trim($_SERVER['SCRIPT_NAME'],'index.php').'install/index.php';
    header("Location:http://$url");
    die;
}
//当前路径
define('SITE_PATH',getcwd().'/');
//调试模式
define('APP_DEBUG',TRUE);
//项目目录
define('PROJECT_PATH',SITE_PATH.'Ansel/');
define('DIR_SECURE_FILENAME', 'index.html');//安全文件
// 定义目录
define('APP_PATH',PROJECT_PATH.'App/');
define('COMMON_PATH',PROJECT_PATH.'Common/');
define('ANSEL_ADDONS',PROJECT_PATH.'Addon/');
//定义缓存目录
define('RUNTIME_PATH',SITE_PATH.'#runtime/');
//核心目录
define('THINK_PATH',PROJECT_PATH.'Core/');
// 引入ThinkPHP入口文件
require THINK_PATH.'ThinkPHP.php';
