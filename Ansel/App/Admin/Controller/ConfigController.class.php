<?php
// +----------------------------------------------------------------------
// | 网站配置
// +----------------------------------------------------------------------
// | Author: Ansel 3126620990@qq.com  绵阳人维网络科技有限公司
// +---------------------------------------------------------------------- 
namespace Admin\Controller; 
use Common\Controller\AdminBase;
class ConfigController extends AdminBase { 
	/**
     * @cc 站点配置
     */ 
 	public function site(){
		if(IS_POST){
			$data=I('post.');
			foreach ($data as $k => $v) {
				if (empty($k)) {
					continue;
				}
				$saveData = array();
				$saveData["value"] = trim($v);
				if (M('config')->where(array("name" => $k))->save($saveData) === false) {
					$this->error("更新到{$k}项时，更新失败！");
					return false;
				}
			} 
			$this->success("更新成功！");
		}else{
			$configList = M('config')->getField("name,value");
			$this->assign('site', $configList);
			$this->display();	 
		}
	} 
	/**
     * @cc URL设置
     */ 
 	public function url(){
		if(IS_POST){
			//配置文件地址
			$set_type=$_POST['set_type']?$_POST['set_type']:$this->error("参数错误");
			$filename = APP_PATH .$set_type. '/Conf/url.php';
			//检查文件是否可写
			if (is_writable($filename) == false) {
				$this->error('请检查[' . APP_PATH .$set_type. '/Conf/url.php' . ']文件权限是否可写！');
				return false;
			}
			//URL区分大小写设置
        	$data['URL_CASE_INSENSITIVE'] = (int) $_POST['URL_CASE_INSENSITIVE'] ? true : false;
			//URL访问模式
			$data['URL_MODEL'] = isset($_POST['URL_MODEL']) ? (int) $_POST['URL_MODEL'] : 0;
			// 用于兼容判断PATH_INFO 参数的SERVER替代变量列表
			$data['URL_PATHINFO_FETCH'] = $_POST['URL_PATHINFO_FETCH'] ? $_POST['URL_PATHINFO_FETCH'] : "ORIG_PATH_INFO,REDIRECT_PATH_INFO,REDIRECT_URL";
			//PATHINFO分隔符
			$data['URL_PATHINFO_DEPR'] = $_POST['URL_PATHINFO_DEPR'] ? $_POST['URL_PATHINFO_DEPR'] : "/";
			//是否开启路由
			$data['URL_ROUTER_ON'] = (int) $_POST['URL_ROUTER_ON'] ? true : false;
			//分页变量
			$data['VAR_PAGE'] = $_POST['VAR_PAGE'] ? $_POST['VAR_PAGE'] : 'page';
			//标签文章列表分页
			$data['TAG_PAGE_URL'] = $_POST['TAG_PAGE_URL'] ? $_POST['TAG_PAGE_URL'] : '';
			//普通文章列表分页
			$data['LIST_PAGE_URL'] = $_POST['LIST_PAGE_URL'] ? $_POST['LIST_PAGE_URL'] : '';
			//搜索文章列表分页
			$data['SEARCH_PAGE_URL'] = $_POST['SEARCH_PAGE_URL'] ? $_POST['SEARCH_PAGE_URL'] : '';
			file_exists($filename) or touch($filename);
        	$return = var_export($data, TRUE);
			if ($return) { 
            	if (file_put_contents($filename, "<?php \n return " . $return . ";")) {
                	$this->success("配置保存成功！");
            	} else {
                	 $this->error("配置更新失败！");
            	}
        	} else {
            	 $this->error("配置更新失败！");
        	} 
		}else{
			$set_type=I('get.set_type')?I('get.set_type'):'Index';
			$setlist=array(
				0=>array('mod'=>'Index','title'=>'前台高级设置'),
			);
			$setting = include(APP_PATH . $set_type .'/Conf/url.php');
			$this->assign('setlist',$setlist);	
			$this->assign('set_type',$set_type);
			$this->assign('set',$setting);
			$this->display();	
		}
	} 
	/**
     * @cc 高级设置
     */ 
 	public function addition(){
		if(IS_POST){
			//配置文件地址
        	$filename = COMMON_PATH . 'Conf/addition.php';
			if (is_writable($filename) == false) {
            	 $this->error("配置文件不可写");
        	}
			$PUBLIC_AUTH=array('Admin/Index/index','Admin/Index/home');//系统后台首页必须在
			if(!in_array(C('ADMIN_GID'),$_POST['ADMINISTRATOR'])){
				$this->error("权限外用户组必须包含超级管理员组");
			}
			if(!in_array(C('ADMIN_UID'),$_POST['ADMINUID'])){
				$this->error("权限外用户必须包含超级管理员");
			}
			$data['ADMINISTRATOR']=$_POST['ADMINISTRATOR'];
			$data['ADMINUID']=$_POST['ADMINUID'];
			if($_POST['PUBLIC_AUTH']){
				$data['PUBLIC_AUTH']=array_merge($PUBLIC_AUTH,$_POST['PUBLIC_AUTH']);
			}else{
				$data['PUBLIC_AUTH']=$PUBLIC_AUTH;
			}
			$data['FILTER']=$_POST['FILTER'];
			file_exists($filename) or touch($filename);
        	$return = var_export($data, TRUE);
			if ($return) {
            	if (file_put_contents($filename, "<?php \n return " . $return . ";")) {
                	$this->success("修改成功！");
            	} else {
                	 $this->error("配置更新失败！");
            	}
        	} else {
            	 $this->error("配置更新失败！");
        	} 
		}else{
			$info = include(COMMON_PATH . '/Conf/addition.php');
			//$info['ADMINISTRATOR']implode
			$this->assign('info',$info);
			//查出用户组
			$group=M('auth_group')->select();
			$this->assign('group',$group);
			//查出管理员
			$users=M('user')->where(array('status'=>1))->field('id,username')->select();
			$this->assign('users',$users);
			//查出所有有权限配置的菜单
			$this->assign('menus',$this->_getmenu());
			$this->display();
		}
	} 
	/**
     * @cc 获取系统菜单列表
     */
	private function _getmenu(){
	 if(S('Admin_menu_config')==false){
		   $menu=D('Menu')->where()->order('listorder ASC')->select();
		   $tree = new \Tree;  
		   $tree->tree($menu); 
		   $tree->icon = array('&nbsp;&nbsp;&nbsp;│ ', '&nbsp;&nbsp;&nbsp;├─ ', '&nbsp;&nbsp;&nbsp;└─ ');
		   $Admin_menu=$tree->getArray();
		   S('Admin_menu_config',$Admin_menu);
		   return $Admin_menu;
	  }else{
           return S('Admin_menu_config');
      }
	}
}