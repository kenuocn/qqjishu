<?php
// +----------------------------------------------------------------------
// | Author: Ansel 3126620990@qq.com  绵阳人维网络科技有限公司
// +---------------------------------------------------------------------- 


namespace Index\Controller;
use Common\Controller\Base;
class IndexController extends Base {

	/**
     * @cc 首页
     */
    public function index(){
        $this->assign("SEO",seo());
		$this->display();
    }
	/**
     * @cc 列表页
     */
    public function lists(){
		//分类ID
        $catid = I('get.catid', 0, 'intval');
		
		//获取栏目数据
        $sort = getCate($catid);
        if (empty($sort)) {
            send_http_status(404);
            exit;
        }
		//检查是否禁止访问动态页
        if (empty($sort['status'])) {
            send_http_status(404);
            exit;
        }
		if($sort['type']==1){//单页
			$template=$sort['page_tmp'];
		}else{ //分类
			$template=$sort['list_tmp'];	
		}
		$this->assign("SEO",seo($catid));
		$this->assign("catid",$catid);
		$this->display($template);
    }
	/**
     * @cc 内容页
     */
    public function show(){
		$id = I('get.id', 0, 'intval');
        if (empty($id)) {
            send_http_status(404);
            exit;
        }
		//获取文章所在分类
		$sort=M()->table(C('DB_PREFIX').'article a,'.C('DB_PREFIX').'sort b')->where('a.id='.$id.' and b.id=a.catid')->field("b.id,b.show_tmp")->find();
		if (empty($sort)) {
            send_http_status(404);
            exit;
        } 
		$Article=M('article');
		$info=$Article->where(array('id'=>$id))->find();
		if (empty($info)) {
            send_http_status(404);
            exit;
        }
		$Article->where(array('id'=>$id))->setInc('views',1);
		$info['thumb']=unserialize($info['thumb']);
		$info['aid']=$info['id'];
		//分配解析后的文章数据到模板 
        $this->assign($info);
		$this->assign("SEO",seo($sort['id'],$info['title']));
		$this->display($sort['show_tmp']);
    }
	/**
     * @cc 标签页
     */
    public function tag(){
		$tag = I('get.tag');
        if (empty($tag)) {
            send_http_status(404);
            exit;
        }
		$this->assign("SEO",seo('',$tag));
		$this->assign('tag',$tag);
		$this->display();
    }
	/**
     * @cc 搜索功能
     */
    public function search(){
		$skey = I('skey');
		if (empty($skey)) {
            send_http_status(404);
            exit;
        }
		$this->assign("SEO",seo('','搜索关键词:'.$skey));
		$this->assign('skey',$skey);
		$this->display();
    }
	/**
     * @cc 文章评论
     */
    public function comments(){
		$this->error("错误信息");
    }

}