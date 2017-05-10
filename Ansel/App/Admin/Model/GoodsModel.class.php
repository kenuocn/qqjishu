<?php
// +----------------------------------------------------------------------
// | Author: Ansel   <3126620990@qq.com>  绵阳人维网络科技有限公司
// +----------------------------------------------------------------------
namespace Common\Model;
class GoodsModel extends \Think\Model {
	//添加时调用create方法允许接收的字段
	protected $insertFields = 'cat_id,goods_name,shop_price,market_price,goods_desc,sort_num,status,goods_tags_id,down_url,ex_password,down_desc,is_new,is_hot,is_best'; //新增数据的时候允许写入的字段

	//修改时调用create方法允许接收的字段
	protected $updateFields = 'id,cat_id,goods_name,shop_price,market_price,goods_desc,sort_num,status,goods_tags_id,down_url,ex_password,down_desc,is_new,is_hot,is_best'; //修改数据的时候允许写入的字段

	protected $_validate = array(
		array('goods_name', 'require', '请输入商品名称',1),
		array('goods_name', '1,120', '商品名称的值最长不能超过 120 个字符！', 1, 'length', 3),
		array('shop_price', 'require', '请输入商品价格',1),
		array('shop_price','currency','商品价格必须是数字',1),
		array('market_price', 'require', '请输入商品市场价格',1),
		array('market_price','currency','市场价格必须是数字',1),
		array('sort_num', 'require', '请输入排序顺序',1),
		array('sort_num','currency','市场价格必须是数字',1),
		array('goods_name', '', '商品名称已存在', 1, 'unique', 1), 
		array('down_url', '1,256', '网盘地址不能超过256 个字符！', 2, 'length', 3),
		array('ex_password', '1,60', '网盘提取密码不能超过60 个字符！', 2, 'length', 3),
    );

    /**
	  * [_before_insert 在添加前需要调用 => 钩子方法]
	  * @param  [type] &$data  [参数1:表单中将要插入到数据库中的数据 => 数据方式]
	  * @param  [type] $option [获取模型和表名]
	  */
	protected function _before_insert(&$data,$option)
	{
		//获取当前时间
		$data['add_time'] = time();
	}

	/**
	 * [_after_insert 在添加后需要调用 => 钩子方法]
	 * @param  [type] $data  [参数1:表单中将要插入到数据库中的数据 => 数据方式]
	 * @param  [type] $option [获取模型和表名]
	*/
	protected function _after_insert($data,$option)
	{
		/** 商品描述 */
		$goodsDescData = I('post.goods_desc','',trim());
		if(!empty($goodsDescData))
		{
			$goodsDesc = M('Goods_desc');
			$goodsDescres = $goodsDesc->add(array(
				'goods_desc'=>$goodsDescData,
				'goods_id'=>$data['id'],
			));

			if(!$goodsDescres)
			{
				$this->rollback();
				return false;
			}
		}

		/** 商品标签关联表 */
		$goodsTagsData = I('post.goods_tags_id','',trim());
		if(!empty($goodsTagsData))
		{
			$goodsTags = M('Goods_tags_relation');
			$goodsTagsValue = array_unique($goodsTagsData);
			foreach ($goodsTagsValue as $key => $val)
			{
				if(empty($val))
				{
					continue;
				}
				
				if(!($goodsTags->add(array(
					'goods_tags_id'=>$val,
					'goods_id'=>$data['id'],
				))))
				{
					$this->rollback();
					return false;
				}
			}

		}

		/** 商品下载表 */
		$down_url = I('post.down_url','',trim());
		$ex_password = I('post.ex_password','',trim());
		$down_desc = I('post.down_desc','',trim());
		/**　当网盘地址或者下载说明任一不为空就插入数据　**/
		if(!empty($down_url) || !empty($down_desc))
		{
			$goodsDown = M('Goods_download');
			$goodsDownRes = $goodsDown->add(array(
				'down_url'=> $down_url,
				'ex_password'=> $ex_password,
				'down_desc'=> $down_desc,
				'goods_id'=> $data['id']
			));

			if(!$goodsDownRes)
			{
				$this->rollback();
				return false;
			}
		}
		
		$this->commit();
		return true;
	}

	 /**
	  * [_before_insert 在修改之前或者之后需要调用 => 钩子方法]
	  * @param  [type] &$data  [参数1:表单中将要插入到数据库中的数据 => 数据方式]
	  * @param  [type] $option [获取模型和表名]
	  */

	 protected function _before_update(&$data,$option)
	 {	
	 	$id=$option['where']['id'];
	 	/** 修改商品描述 */
	 	$goods_desc = M('Goods_desc');
	 	$goodsDescRes = $goods_desc->where(array('goods_id'=>$id))->save(array(
	 		'goods_desc'=>I('post.goods_desc')
	 	));

	 	if($goodsDescRes === false){
	 		$this->rollback();
			return false;
	 	}

	 	/** 商品附件表 */
	 	$goodsDown = M('Goods_download');
	 	$goodsDownRes = $goodsDown->where(array('goods_id'=>$id))->save(I('post.'));
	 	if($goodsDownRes === false)
	 	{
	 		$this->rollback();
			return false;
	 	}

	 	/** 商品标签表 */
	 	$goodsTags = M('Goods_tags_relation');
	 	$goodsTags->where(array('goods_id'=>$id))->delete();
	 	$goodsTagsValue = array_unique(I('post.goods_tags_id'));
		foreach ($goodsTagsValue as $key => $val)
		{
			if(empty($val))
			{
				continue;
			}
			
			if(!($goodsTags->add(array(
				'goods_tags_id'=>$val,
				'goods_id'=>$id,
			))))
			{
				$this->rollback();
				return false;
			}
		}
	 }


	/**
	 * 获取商品信息并加搜索功能
	 * @param  integer $pageSize 每页显示多个数据
	 * @return [type]            [description]
	 */
	public function search($pageSize = 20)
	{
		/** 拼接where条件 */
		$where['g.status'] = array('eq',1);
		$cat_id = I('post.cat_id','',trim());
		if(!empty($cat_id))
		{
			$where['g.cat_id'] = array('eq',$cat_id);
		}

		$min_price = I('post.min_price','',trim());
		$max_price = I('post.max_price','',trim());
		if(!empty($min_price) && $max_price)
		{
			$where['g.shop_price'] = array('between','$min_price,$max_price');
		}else if($min_price){
			$where['g.shop_price'] = array('egt',$min_price);
		}else if($max_price){
			$where['g.shop_price'] = array('elt',$max_price);
		}

		$start_time = strtotime(I('post.start_time','',trim()));
		$end_time = strtotime(I('post.end_time','',trim()));
		if($start_time && $end_time)
		{
			$where['g.add_time'] = array('between','$start_time,$end_time');
		}else if($start_time){
			$where['g.add_time'] = array('egt',$start_time);
		}else if($end_time){
			$where['g.add_time'] = array('elt',$end_time);
		}

		$goods_name = I('post.goods_name','',trim());
		if(!empty($goods_name))
		{
			$where['g.goods_name'] = array('like',"%$goods_name%");
		}

		$count = $this->alias('g')->where($where)->count();
		$page = page($count,$pageSize);
		$data = $this->alias('g')
		->field('g.*,s.id sid,s.catname,GROUP_CONCAT(gt.goods_tags_name) goods_tags_name')
		->where($where)
		->join('LEFT JOIN __SORT__ s  ON s.id=g.cat_id')
		->join('LEFT JOIN __GOODS_TAGS_RELATION__ gtr ON gtr.goods_id=g.id')
		->join('LEFT JOIN __GOODS_TAGS__ gt ON gt.id=gtr.goods_tags_id')
		->group('g.id')
		->order('g.sort_num asc,g.id asc')
		->limit($page->firstRow.','.$page->listRows)
		->select();
		return array(
				'data'=>$data,
				'page'=>$page->show(),
				'count'=>$count
			);
	}


	/**
	 * 根据goodsId获取商品的全部信息
	 * @return [type] [description]
	 */
	public function getAllGoods($goodsId)
	{
		$goodsData = $this->alias('g')
		->field('g.*,gd.goods_desc,GROUP_CONCAT(gtr.goods_tags_id) goods_tags_id,gdown.*')
		->join('LEFT JOIN __GOODS_DESC__ gd ON g.id=gd.goods_id')
		->join('LEFT JOIN __GOODS_TAGS_RELATION__ gtr ON gtr.goods_id=g.id')
		->join('LEFT JOIN __GOODS_TAGS__ gt ON gt.id=gtr.goods_tags_id')
		->join('LEFT JOIN __GOODS_DOWNLOAD__ gdown ON g.id=gdown.goods_id')
		->where(array('g.id'=>$goodsId))
		->group('g.id')
		->find();

		/** 处理商品标签 */
		$goodsTagsId = explode(',',$goodsData['goods_tags_id']);
		$goodsData['goods_tags_id'] = $goodsTagsId;
	
		return $goodsData;
	}

}