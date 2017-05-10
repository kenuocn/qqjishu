<?php
// +----------------------------------------------------------------------
// | 文件管理
// +----------------------------------------------------------------------
// | Author: Ansel 3126620990@qq.com  绵阳人维网络科技有限公司
// +---------------------------------------------------------------------- 
namespace Admin\Controller; 
use Common\Controller\AdminBase;
class FileController extends AdminBase { 
	/**
     * @cc 文件管理
     */ 
 	public function index(){
		//图标目录
        $ext = './statics/images/ext/';
        //访问地址
        $extUrl = './statics/images/ext/';
        //获取图标数组
        $extList = glob($ext . '*.*');
        $FileExtList = array();
        $dirico = 'dir.gif';
		//当前目录路径
        $dir = isset($_GET['dir']) && trim($_GET['dir']) ? str_replace(array('..\\', '../', './', '.\\', '.',), '', trim(urldecode($_GET['dir']))) : '';
        if ($dir == ".") {
            $dir = "";
        }
        $dir = str_replace('-', "/", $dir);
        $filepath = SITE_PATH . $dir;
		$list = glob($filepath . '/*');
        if (!empty($list)) {
            ksort($list);
        }
        $local = str_replace(array('//'), array('/'), $filepath);
        if (substr($local, -1, 1) == '.') {
            $local = substr($local, 0, (strlen($local) - 1));
        }
        foreach ($list as $k => $v) {
            if (basename($v) == 'Thumbs.db') {
                unset($list[$k]);
            } else {
                //获取拓展名
                $thisExt = pathinfo($filepath . $v, PATHINFO_EXTENSION);
                //如果获取为空说明这是文件夹
                $thisExt == '' && $thisExt = 'dir';
                //检测是否有此类型的试图文件
                in_array($ext . $thisExt . '.jpg', $extList) && $FileExtList[$v] = $extUrl . $thisExt . '.jpg';
                in_array($ext . $thisExt . '.gif', $extList) && $FileExtList[$v] = $extUrl . $thisExt . '.gif';
                in_array($ext . $thisExt . '.png', $extList) && $FileExtList[$v] = $extUrl . $thisExt . '.png';
                in_array($ext . $thisExt . '.bmp', $extList) && $FileExtList[$v] = $extUrl . $thisExt . '.bmp';
                //兼容不存在视图的文件
                (!in_array($FileExtList[$v], $FileExtList) || $FileExtList[$v] == '') && $FileExtList[$v] = $extUrl. 'hlp.gif';
            }
        }
		$this->assign('list',$list);
		$this->assign('dir', $dir);
        $this->assign('local', $local);
        $this->assign('FileExtList', $FileExtList);
        $this->assign('dirico', $dirico);
		$this->display();
	} 
	/**
     * @cc 编辑文件
     */ 
 	public function edit(){
		$input = new \Input;  
		if(IS_POST){
			//文件
            $file = I('post.file', '', '');
            //目录
            $dir = I('post.dir', '', '');
            $dir = str_replace(array("//"), array("/"), $dir);
            //完整路径
            $path = SITE_PATH . $dir . "/" . $file;
            $path = str_replace(array("//"), array("/"), $path);
            if (!file_exists($path)) {
                $this->error("文件 {$path} 不存在！");
            }
            //检查文件是否可写
            if (!is_writable($path)) {
                $this->error("文件 {$path} 不可写！");
            }
            //模板内容
            $content = $input->getVar(I('post.content', '', ''));
            $status = file_put_contents($path, htmlspecialchars_decode($content));
            if ($status) {
                $this->success("保存成功！");
            } else {
                $this->error("保存失败，请检查文件是否有可写权限！");
            }
            exit;
		}else{
			//取得目录路径
            $dir = isset($_GET['dir']) && trim($_GET['dir']) ? str_replace(array('..\\', '../', './', '.\\'), '', urldecode(trim($_GET['dir']))) : '';
            $dir = str_replace("-", "/", $dir);
            //文件名
            $file = isset($_GET['file']) && trim($_GET['file']) ? trim($_GET['file']) : '';
            //完整路径
            $path = SITE_PATH . $dir . "/" . $file;
			//检查文件是否存在
			if (!file_exists($path)) {
				$this->error("文件 {$path} 不存在！");
			}
			//检查文件是否可写
			if (!is_writable($path)) {
				$this->error("文件 {$path} 不可写！");
			}
			$content = file_get_contents($path);
			$content=$input->forTarea($content);
			$this->assign("content", $content); 
			$this->assign("dir", $dir);
            $this->assign("file", $file);
			$this->display();	
		}
		
	} 
}