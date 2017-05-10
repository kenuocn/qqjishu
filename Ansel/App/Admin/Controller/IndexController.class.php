<?php
// +----------------------------------------------------------------------
// | 系统首页
// +----------------------------------------------------------------------
// | Author: Ansel   <3126620990@qq.com>  绵阳人维网络科技有限公司
// +----------------------------------------------------------------------

namespace Admin\Controller;
use Common\Controller\AdminBase;
use Think\Hook;
class IndexController extends AdminBase {
	/**
     * @cc 系统主页
     */
    public function index(){ 
		$this->assign('menu',$this->_menu());
		$this->display();
    } 
	/**
     * @cc 系统主页
     */
	public function home(){
		//已审核文章
		$article_count_1=M('article')->where(array('status'=>1))->count();
		//待审核文章
		$article_count_0=M('article')->where(array('status'=>0))->count();
		//统计会员
		$user_count=M('member')->count();
		//统计评论数
		
		$this->assign('article_count_1',$article_count_1);
		$this->assign('article_count_0',$article_count_0);
		$this->assign('user_count',$user_count);
		$this->display();
	}
	
	/**
     * @cc 清除缓存
     */
	public function cache(){
		if(IS_POST){
			$type=I('post.type')?I('post.type'):'site';
			$Dir = new \Dir();
			switch ($type) {
				case "site":
					//删除缓存目录下的文件
					$Dir->del(RUNTIME_PATH);
					$Dir->delDir(RUNTIME_PATH . "Cache/");
					$Dir->delDir(RUNTIME_PATH . "Temp/");
					$Dir->delDir(RUNTIME_PATH . "Logs/");
					$Dir->delDir(RUNTIME_PATH . "Data/");
					$this->success("站点缓存清理成功！");
				break;
				case "template":
					//删除缓存目录下的文件
					$Dir->del(RUNTIME_PATH);
					$Dir->delDir(RUNTIME_PATH . "Cache/");
					$Dir->delDir(RUNTIME_PATH . "Temp/");
					$this->success("模板缓存清理成功！");
				break;	
				case "logs":
					$Dir->delDir(RUNTIME_PATH . "Logs/");
                    $this->success("站点日志清理成功！");
				break;	
			}
			
		}
	}
	/*
	 * 表单设计
	 */
	public function form_builder(){
	    $this->display();
    }
	/**
     * @cc 获取系统菜单
     */
	private function _menu(){
		$gid=getGroups();
        $field='id,action,app,controller,pid,parameter,type,fonts,ajax,name';
		if(!authGroupid($gid)){
			$rules=M('auth_group')->where(array('id'=>$gid))->getfield('menu_rules');
			$rules=substr($rules,0,strlen($rules)-1);
			$where['id']=array('IN',$rules);
            $where['type']=0;
            $where['_logic'] = 'or';
            $map['_complex'] = $where;
            $map['pid']=0;
            $map['status']=1;
            $menu=M('menu')->where($map)->order('listorder DESC')->select();
            foreach($menu as $k=>$v){
                $map['pid']=$v['id'];
                $sub_menu=M('menu')->where($map)->field($field)->order('listorder DESC')->select();
                $menu[$k]['sub_menu']=$sub_menu;
            }
			return $menu;
		}else{
			$menu=M('menu')->where(array('status'=>1,'pid'=>0))->field($field)->order('listorder DESC')->select();
			foreach($menu as $k=>$v){
				$sub_menu=M('menu')->where(array('status'=>1,'pid'=>$v['id']))->field($field)->order('listorder DESC')->select();	
				$menu[$k]['sub_menu']=$sub_menu;
			}
			return $menu;	
		}
	}
}