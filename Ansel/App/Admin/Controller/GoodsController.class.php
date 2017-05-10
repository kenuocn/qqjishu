<?php 
// +----------------------------------------------------------------------
// | Author: Ansel 3126620990@qq.com  绵阳人维网络科技有限公司
// +---------------------------------------------------------------------- 
namespace Admin\Controller; 
use Common\Controller\AdminBase;
class GoodsController extends AdminBase{ 
	/**
     * @cc 商品管理
     */
	 public function index(){
	 	$goods = D('Goods');
	 	$goodsTags = D('GoodsTags');
	 	$result = $goods->search();
	 	$this->assign(array(
	 		'goodsInfo'=>$result['data'],
	 		'page'=>$result['page'],
	 		'goodstags'=>$goodsTags->getAllGoodsTags(),
	 		'sort'=>M('sort')->where(array('status'=>1))->select()
	 	));
	 	$this->display();
	 }
	/**
     * @cc 添加商品
     */
	 public function add(){
	 	if(IS_POST)
	 	{
	 		$Goods = D('Goods');
	 		/** 开启事物 */
	 		$Goods->startTrans();
	 		//判断是否验证成功
			if($Goods->create(I('post.'),1))
			{
				if($Goods->add())
				{
					die($this->success('添加成功',U('index'),2));
				}
			}
			/** 失败回滚数据 */
			$Goods->rollback();
			$this->error($Goods->getError());
	 	}
	 	
		/** 获取全部商品标签 */
		$goodsTags = D('GoodsTags');
		$this->assign(array(
			'goodstags'=>$goodsTags->getAllGoodsTags(),
			'sort'=>M('sort')->where(array('status'=>1))->select()
		));
	 	$this->display();
	 }


	 public function edit()
	 {
	 	$goods = D('Goods');
	 	if(IS_POST){
	 		zgy(I('post.'));
	 		if($goods->create(I('post.'),2))
	 		{
	 			if(false !== $goods->save()){
					die($this->success('修改成功',U('index'),2));
				}
	 		}
	 		die($this->error($goods->getError()));
	 	}
	 	$goodsTags = D('GoodsTags');
	 	$this->assign(array(
	 		'goodsInfo'=>$goods->getAllGoods(I('get.id')),
	 		'goodstags'=>$goodsTags->getAllGoodsTags(),
	 		'sort'=>M('sort')->where(array('status'=>1))->select()
	 	));
	 	$this->display();
	 }


	 /**
	  * 修改状态
	  * @return [type] [description]
	  */
	 public function status(){
	 	if(IS_POST){
            $id=I('post.id')?I('post.id'):$this->error("参数错误");
            $type=I('post.type')?I('post.type'):$this->error("参数错误");
            $status=I('post.status');
            if(M('Goods')->where(array('id'=>$id))->setfield($type,$status)){
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
	 		$res = M('Goods')->where(array('id'=>$id))->setField(array('sort_num'=>$sort));
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
	
	 	if(M('Goods')->where($where)->delete()){
			$this->success('删除成功');	
		}else{
			$this->error('删除失败');	
		}
	 }
}