<?php
// +----------------------------------------------------------------------
// | 网站内容管理
// +----------------------------------------------------------------------
// | Author: Ansel 3126620990@qq.com  绵阳人维网络科技有限公司
// +---------------------------------------------------------------------- 
namespace Admin\Controller; 
use Common\Controller\AdminBase;
class ContentController extends AdminBase { 
	/**
     * @cc 分类管理
     */ 
 	public function sort(){
		if(IS_POST){
			$listorder=I('post.listorder',0,'intval');
			foreach($listorder as $k=>$v){
				M('sort')->where(array('id'=>$k))->setField('listorder',$v);
			}
			$this->success('排序成功');
		}else{
			$sort=M('sort')->order('listorder ASC')->select();
			$tree = new \Tree;  
			$tree->tree($sort); 
			$tree->icon = array('   │ ', '   ├─ ', '   └─ ');
			$list=$tree->getArray();
			$this->assign('list',$list); 
			$this->display();	
		}
	} 
	/**
     * @cc 添加分类
     */ 
 	public function add_sort(){
		if(IS_POST){
			$Sort=D('Sort');
			if ($Sort->ins_up_data($_POST,1)){
				$this->success('添加成功',U('Content/sort'));		
			}else{
				$this->error($Sort->getError());	
			}
		}else{
			$info['pid']=I('get.pid');
			$this->assign('info',$info);
			$this->assign('sort',M('sort')->where(array('status'=>1))->select());
			$this->display();	
		}
	} 
	/**
     * @cc 编辑分类
     */ 
 	public function edit_sort(){
		if(IS_POST){
			$Sort=D('Sort');
			if ($Sort->ins_up_data($_POST)){
				$this->success('保存成功',U('Content/sort'));	
			}else{
				$this->error($Sort->getError());	
			}
		}else{
			$id=I('get.id')?I('get.id'):$this->error("参数错误");
			$info=D('Sort')->where(array('id'=>$id))->find();
			$this->assign("info",$info);
			$sort=M('sort')->where(array('status'=>1))->select();
			foreach($sort as $k=>$v){
				if($id==$v['id']){
					unset($sort[$k]); 	
				}	
			}
			$this->assign('sort',$sort);
			$this->display('add_sort');	
		}
	} 
	/**
     * @cc 删除分类
     */ 
 	public function del_sort(){
		if(IS_GET){
			$id=I('get.id','','intval')?I('get.id','','intval'):$this->error('参数错误');
			if(M('article')->where(array('catid'=>$id))->count){
				$this->error("该分类已存在文章，不能删除");	
			}else{
				if(M('sort')->where(array('id'=>$id))->delete()){
					$this->success("删除成功");	
				}else{
					$this->error("删除失败");		
				}
				
			}	
		}
	} 
	/**
     * @cc 分类类型
     */ 
 	public function type_sort(){
		if(IS_GET){
			$type=I('get.type');
			$id=I('get.id')?I('get.id'):$this->error('参数错误');
			if(M('sort')->where(array('id'=>$id))->save(array('type'=>$type))){
				$this->success("类型更新成功");	
			}else{
				$this->error("类型更新失败");	
			}	
		}
	} 
	/**
     * @cc 分类状态
     */ 
 	public function status_sort(){
		if(IS_POST){
			$id=I('post.id')?I('post.id'):$this->error("参数错误");
			$type=I('post.type')?I('post.type'):$this->error("参数错误");
			$status=I('post.status');
			if(M('sort')->where(array('id'=>$id))->setfield($type,$status)){
				$this->success('修改成功');	
			}else{
				$this->error('修改失败');	
			}
		}
	} 
	/**
     * @cc 文章管理
     */ 
 	public function article(){
		if(IS_POST){
			$listorder=I('post.listorder',0,'intval');
			foreach($listorder as $k=>$v){
				M('article')->where(array('id'=>$k))->setField('listorder',$v);
			}
			$this->success('排序成功');
		}else{
			$where="id>0";	
			$catid=I('get.catid');
			$starttime=strtotime(I('get.starttime'));
			$endtime=strtotime(I('get.endtime'));
			$title=I('get.title');
			if($catid){
				$where.=" and catid='$catid'";	
			}
			if(!empty($starttime)){
				$where.=" and time>='$starttime' and time<'$endtime'";	
			}
			if(!empty($endtime)){
				$where.=" and time>='$starttime' and time<'$endtime'";
			}
			if(!empty($title)){
				$where.=" and title like '%$title%'";
			}
			$data=info_page('article','5','id desc',$where);
			foreach($data['list'] as $k=>$v){
				$data['list'][$k]['catname']=getCate($v['catid'],'catname');	
			}
			$this->assign('data',$data); 
			$this->assign('sort',$this->_getsort("id,catname"));
			$this->assign('catid',$catid);
			$this->assign('starttime',date("Y-m-d",$starttime?$starttime:''));
			$this->assign('endtime',date("Y-m-d",$endtime?$endtime:''));
			$this->assign('title',$title);
			$this->display();	
		}
	} 
	/**
     * @cc 添加文章
     */ 
 	public function add_article(){
		if(IS_POST){
			$Article=D('Article');
			if ($Article->ins_up_data($_POST,1)){
				$this->success('添加成功',U('Content/article'));		
			}else{
				$this->error($Article->getError());	
			}
		}else{
			$config=S('Config');
			$this->assign('author',$config['author']);
			$this->assign('sort',$this->_getsort("id,catname"));
			$this->display();	
		}
	} 
	/**
     * @cc 编辑文章
     */ 
 	public function edit_article(){
		if(IS_POST){
			$Article=D('Article');
			if ($Article->ins_up_data($_POST)){
				$this->success('保存成功',U('Content/article'));	
			}else{
				$this->error($Article->getError());	
			}
		}else{
			$id=I('get.id')?I('get.id'):$this->error("参数错误");
			$info=D('Article')->where(array('id'=>$id))->find();
			$this->assign("info",$info);
			$config=S('Config');
			$this->assign('author',$config['author']);
			$this->assign('sort',$this->_getsort("id,catname"));
			$this->display('add_article');	
		}
	} 
	/**
     * @cc 删除文章
     */ 
 	public function del_article(){
		if(IS_GET){
			$id=I('get.id')?I('get.id'):$this->error("参数错误");
			if(M('Article')->where(array('id'=>$id))->delete()){
				$this->success('删除成功');	
			}else{
				$this->error('删除失败');	
			}
		}
	} 
	/**
     * @cc 文章状态设置
     */ 
 	public function status_article(){
		if(IS_POST){
			$id=I('post.id')?I('post.id'):$this->error("参数错误");
			$type=I('post.type')?I('post.type'):$this->error("参数错误");
			$status=I('post.status');
			if(M('Article')->where(array('id'=>$id))->setfield($type,$status)){
				$this->success('修改成功');	
			}else{
				$this->error('修改失败');	
			}
		}
	} 
	/*获取分类*/
	private function _getsort($field){
		return M('sort')->where(array('islink'=>''))->field($field)->select();	
	}
}