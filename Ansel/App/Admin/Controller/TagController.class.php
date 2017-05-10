<?php
// +----------------------------------------------------------------------
// | 文章标签管理
// +----------------------------------------------------------------------
// | Author: Ansel 3126620990@qq.com  绵阳人维网络科技有限公司
// +---------------------------------------------------------------------- 
namespace Admin\Controller; 
use Common\Controller\AdminBase;
class TagController extends AdminBase { 
	/**
     * @cc 标签管理
     */ 
 	public function index(){

		/*$article=M('article')->where()->field('id,tags,title')->select();
		foreach($article as $k=>$v){
			$tagsarr=str_replace("，",",",$v['tags']);
			$tagsarr=explode(",",$tagsarr);	
			foreach($tagsarr as $kk=>$vv){
				M('tag')->add(array('tag'=>$vv,'aid'=>$v['id'],'title'=>$v['title']));
			}
		}*/
		$this->assign('data',info_page('tag','50','id desc')); 
		$this->display();	
	} 
	/**
     * @cc 删除标签
     */ 
 	public function del(){
		$id=$_REQUEST['id'];
		if(is_array($id)){   
			$where = 'id in('.implode(',',$id).')';  
		}else{  
		    $where = 'id='.$id; 
		}
		if(M('Tag')->where($where)->delete()){
			$this->success('删除成功');	
		}else{
			$this->error('删除失败');	
		}
	} 
}