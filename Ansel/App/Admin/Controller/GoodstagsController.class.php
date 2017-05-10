<?php 
// +----------------------------------------------------------------------
// | Author: Ansel 3126620990@qq.com  绵阳人维网络科技有限公司
// +---------------------------------------------------------------------- 
namespace Admin\Controller; 
use Common\Controller\AdminBase;
class GoodstagsController extends AdminBase{ 

	/**
     * @cc 商品标签列表
     */
	 public function index(){
	 	$goodsTags = D('GoodsTags');
	 	$result = $goodsTags->search();
	 	$this->assign(array(
	 		'data'=>$result['data'],
	 		'page'=>$result['page']
	 	));
	 	$this->display();
	 }


	/**
     * @cc 添加商品标签
     */
	 public function add()
	 {

	 	if(IS_POST)
	 	{
	 		$goodsTags = D('Goods_tags');
	 		if($goodsTags->create(I('post.'),1))
	 		{
	 			if($goodsTags->add())
	 			{
	 				die($this->success('添加成功',U('index'),2));
	 			}
	 		}
	 		$this->error($goodsTags->getError());
	 	}
	 	$this->display();
	 }


	/**
     * @cc 编辑商品标签
     */
	 public function edit(){
	 	$goodsTags = D('Goods_tags');
	 	if(IS_POST){
	 		if($goodsTags->create(I('post.'),2))
	 		{
	 			if(false !==$goodsTags->save())
	 			{
	 				die($this->success('修改成功',U('index'),2));
	 			}
	 		}
	 		die($this->error($goodsTags->getError()));
	 	}
	 	$this->assign('data',$goodsTags->find(I('get.id')));
	 	$this->display();
	 }

	 /**
	  * 状态
	  * @return [type] [description]
	  */
	 public function status(){
	 	if(IS_POST){
            $id=I('post.id')?I('post.id'):$this->error("参数错误");
            $type=I('post.type')?I('post.type'):$this->error("参数错误");
            $status=I('post.status');
            if(M('Goods_tags')->where(array('id'=>$id))->setfield($type,$status)){
                $this->success('更新成功');
            }else{
                $this->error('更新失败');
            }
        }
	 }

	 /**
	  * 标签Ajax排序
	  * @return [type] [description]
	  */
	 public function sort(){
	 	$id = I('post.id','',trim());
	 	$sort = I('post.sort','',trim());
	 	if(IS_AJAX)
	 	{
	 		$res = M('Goods_tags')->where(array('id'=>$id))->save(array('sort'=>$sort));
	 		if($res !== false)
	 		{
                $this->success('更新成功');
            }else{
                $this->error('更新失败');
            }
	 	}
	 }


	 /**
	  * 批量删除
	  * @return [type] [description]
	  */
	 public function batchDelete()
	 {
	 	$Id = I('param.id','',trim());
	 	if(is_array($Id))
	 	{
	 		$where['id'] = array('in',implode(',',$Id));
	 	}else{
	 		$where['id'] = array('eq',$Id);
	 	}
	
	 	if(M('Goods_tags')->where($where)->delete()){
			$this->success('删除成功');	
		}else{
			$this->error('删除失败');	
		}
	 }


}