<?php
// +----------------------------------------------------------------------
// | 模板设置
// +----------------------------------------------------------------------
// | Author: Ansel 3126620990@qq.com  绵阳人维网络科技有限公司
// +---------------------------------------------------------------------- 
namespace Admin\Controller; 
use Common\Controller\AdminBase;
class TemplateController extends AdminBase { 
	protected $ThemePc;//电脑端模板
	protected $ThemeMobile; //手机模板
	protected function _initialize() {
		parent::_initialize();
		$this->ThemePc=M('config')->where(array('name'=>'ThemePc'))->getfield('value');
		$this->ThemeMobile=M('config')->where(array('name'=>'ThemeMobile'))->getfield('value');
	}
	/**
     * @cc 电脑端模板
     */ 
 	public function temp_pc(){
		$ThemePc = $this->ThemePc;
		$list=$this->_gettemp('pc');
		$page = max(I('get.p', 0, 'intval'), 1);
		$this->assign('type','pc');
		$this->assign('data',info_page($list,10,'','',$page));
		$this->assign('def_tmp',$ThemePc);
		$this->display();
	} 
	/**
     * @cc 手机模板
     */ 
 	public function temp_mobile(){
		$ThemeMobile = $this->ThemeMobile;
		$list=$this->_gettemp('mobile');
		$page = max(I('get.p', 0, 'intval'), 1);
		$this->assign('type','mobile');
		$this->assign('data',info_page($list,10,'','',$page));
		$this->assign('def_tmp',$ThemeMobile);
		$this->display('temp_pc');
	} 
	/**
     * @cc 获取模板
     */ 
	private function _gettemp($type){
		$arr = scandir("Template/$type/");
		foreach($arr as $key => $val){
            if($val == '.' || $val == '..' ){
				continue;    
			}            
			$list[$val] = include "Template/$type/$val/config.php";	
			$list[$val]['opt']=file_exists("Template/$type/$val/options.php")?1:2;
			$list[$val]['seturl']="Template/$type/$val/options.php";
       	}
	    return $list;
	}
	/**
     * @cc 切换模板
     */ 
 	public function temp_switch(){
		if(IS_GET){
			$type=I('get.type')?I('get.type'):$this->error('参数错误');
			$m = ($type == 'pc') ? 'ThemePc' : 'ThemeMobile';
			$name=I('get.key')?I('get.key'):$this->error('参数错误');
			$config=M('config');
			if(!$config->where(array('name'=>$m))->find()){
				$config->add(array('name'=>$m,'info'=>"PC端模板",'value'=>$name));	
			}else{
				$config->where(array('name'=>$m))->save(array("value"=>$name));	
			}
			$this->success("模板切换成功，请及时更新缓存"); 
		}
	} 
	/**
     * @cc 删除模板
     */ 
 	public function temp_del(){
		if(IS_GET){
			$name=I('get.key')?I('get.key'):$this->error('参数错误');
			$type=I('get.type')?I('get.type'):$this->error('参数错误');
			$dir="./Template/$type/$name";
			deldir($dir);
			$this->success('删除成功');
		}
	} 
	/**
     * @cc 上传模板
     */ 
 	public function temp_upload(){
		if(IS_POST){
			$title = $_FILES['tplzip']['name']; 
			$file = $_FILES['tplzip']['tmp_name']; 
			$type=I('post.type')?I('post.type'):$this->error('请选择模板类型');
			$path="./Template/$type/";
			if (getFileSuffix($title) != 'zip') {
				$this->error('必须上传zip格式的压缩包');	
			}
			$res=temp_ezip($file,$path);
			if($res['err']){
				$this->success($res['info']);
			}else{
				$this->error($res['info']);	
			}
		}else{
			$this->display();	
		}
	} 
	/**
     * @cc 模板设置
     */ 
 	public function setting(){
		if(IS_POST){
			$info=array_filter($_POST);
			$data['name']=$info['theme']?$info['theme']:$this->error("参数错误");
			$data['type']=$info['type']?$info['type']:$this->error("参数错误");
			$data['config']=serialize($info);
			$model=M('theme_set');
			$where=array('name'=>$data['name'],'type'=>$data['type']);
			if($model->where($where)->find()){
				$model->where($where)->save($data);	
				$this->success("配置保存成功"); 
			}else{
				$model->where($where)->add($data);	
				$this->success("配置保存成功");  	
			}
		}else{
			$seturl=I('get.seturl');
			$type=I('get.type');
			$theme=I('get.theme');
			if(empty($seturl) || empty($type) || empty($theme)){
				send_http_status(404);
				exit;	
			}
			$opt = include($seturl);	
			foreach($opt as $k=>$v){
				if($v['type']=='editor'){
					$editor=1;	
				}
			}
			$model=M('theme_set');
			$where=array('name'=>$theme,'type'=>$type);
			$info=$model->where($where)->getfield("config");
			$info=unserialize($info);
			if($info){
				foreach($opt as $k=>$v){
					$opt[$k]['val']=$info[$k];	
				}
			}
	
			$this->assign('type',$type);
			$this->assign('theme',$theme);
			$this->assign('edirot',$editor);
			$this->assign('sets',$opt);
			$this->display();
		}
	} 
}