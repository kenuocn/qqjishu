<?php
// +----------------------------------------------------------------------
// | 菜单管理
// +----------------------------------------------------------------------
// | Author: Ansel   <3126620990@qq.com>  绵阳人维网络科技有限公司
// +----------------------------------------------------------------------
namespace Admin\Controller;
use Common\Controller\AdminBase;
class MenuController extends AdminBase {
   /**
     * @cc 系统菜单列表
     */
    public function index(){
		$op=I('op');
		if($op=='cache'){
			S('Admin_menu',null);
        	$this->success('更新缓存成功');
		}
		if(IS_POST){
			$listorder=I('post.listorder',0,'intval');
			foreach($listorder as $k=>$v){
				D('menu')->where(array('id'=>$k))->setField('listorder',$v);
			}
			S('Admin_menu',null);
			$this->success('排序成功');
		}
		$this->assign('list',$this->_getmenu());
		$this->display();		
    }

	/**
     * @cc 添加系统菜单
     */
	public function add(){
		if(IS_POST){
			if(D('Menu')->menu_add($_POST)){
				S('Admin_menu',null);
				$this->success('添加成功',U('Menu/index'));	
			}else{
				$this->error(D('Menu')->getError());	
			}
		}else{
			$this->assign('pid',I('get.pid'));
			$this->assign('list',$this->_getmenu());
			$this->display();		
		}
	}
	/**
     * @cc 编辑系统菜单
     */
	public function edit(){
		if(IS_POST){
			$id=I('post.id')?I('post.id'):$this->error("参数错误");
			$pid=I('post.pid');
			if($pid==$id){
				$this->error('不能设置当前菜单为上级菜单');
			}
			$ppid=D('menu')->where(array('id'=>$pid))->getfield('pid');
			if($ppid==$id){
				$this->error('上级菜单越级错误');	
			}
			if(D('menu')->where(array('id'=>$id))->save($_POST)!==false){
				//更新缓存
				S('Admin_menu',null);
				$this->success('编辑成功',U('Menu/index'));
			}else{
				$this->error('编辑失败');	
			}
		}else{
			$id=I('get.id');
			$info=D('Menu')->where(array('id'=>$id))->find();
			$this->assign('info',$info);
			$this->assign('list',$this->_getmenu());
			$this->display();		
		}
	}
	/**
     * @cc 删除系统菜单
     */
	public function del(){
		$id=I('get.id')?I('get.id'):$this->error("参数错误");
		if(D('menu')->where(array('pid'=>$id))->find()){
			$this->error('已存在子菜单，不能删除');	
		}else{
			if(D('menu')->where(array('id'=>$id))->delete()){
				S('Admin_menu',null);
				$this->success('删除成功');
			}else{
				$this->error('删除失败');	
			}
		}	
	}
    /**
     * @cc 菜单状态
     */
    public function status(){
        if(IS_POST){
            $id=I('post.id')?I('post.id'):$this->error("参数错误");
            $type=I('post.type')?I('post.type'):$this->error("参数错误");
            $status=I('post.status');
            if(D('Menu')->where(array('id'=>$id))->setfield($type,$status)){
                S('Admin_menu',null);
                $this->success('更新成功');
            }else{
                $this->error('更新失败');
            }
        }
    }
    /**
     * @cc 系统菜单类型设置
     */
    public function type(){
        $type=I('get.type');
        $id=I('get.id')?I('get.id'):$this->error("参数错误");
        if(D('menu')->where(array('id'=>$id))->setfield('type',$type)){
            S('Admin_menu',null);
            $this->success('更新成功');
        }else{
            $this->error('更新失败');
        }
    }
	/**
     * @cc 获取系统菜单列表
     */
	private function _getmenu(){
	 if(S('Admin_menu')==false){
		   $menu=D('Menu')->where()->order('listorder ASC')->select();
		   $tree = new \Tree;  
		   $tree->tree($menu); 
		   $tree->icon = array('&nbsp;&nbsp;&nbsp;│ ', '&nbsp;&nbsp;&nbsp;├─ ', '&nbsp;&nbsp;&nbsp;└─ ');
		   $Admin_menu=$tree->getArray();
		   S('Admin_menu',$Admin_menu);
		   return $Admin_menu;
	  }else{
           return S('Admin_menu');
      }
	}	 

}