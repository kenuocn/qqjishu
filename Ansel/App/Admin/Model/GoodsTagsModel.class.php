<?php 
namespace Common\Model;
use Think\Model;
class GoodsTagsModel extends Model
{

	//添加时调用create方法允许接收的字段
	protected $insertFields = 'goods_tags_name,goods_tags_name_english,goods_tags_font,add_time,is_full,sort'; //新增数据的时候允许写入的字段

	//修改时调用create方法允许接收的字段
	protected $updateFields = 'id,goods_tags_name,goods_tags_name_english,goods_tags_font,is_full,sort'; //修改数据的时候允许写入的字段

	//自动验证
	 protected $_validate = array(
	 	array('goods_tags_name','require','商品标签名称不能为空',1),  				// 验证商品名称不能为空
	 	array('cat_id','require','商品分类不能为空',1),  					// 验证商品分类不能为空
	 	array('goods_tags_name', '1,120', '商品标签名称的值最长不能超过 128 个字符！', 1, 'length', 3),
	 	array('goods_tags_name_english', '1,500', '商品标签名称的值最长不能超过 500 个字符！', 0, 'length', 3),
	 	array('goods_tags_font', '1,255', '商品价格的值最长不能超过 255 个字符！', 0, 'length', 3),
	 	array('sort','currency','排序必须是数字',1),				// 验证市场价格是否合法
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

	}


	/**
	 * 获取全部商品标签
	 * @return [type] [description]
	 */
	public function getAllGoodsTags()
	{
		return $this->where(array('status'=>1))->getField('id,goods_tags_name,goods_tags_name_english');
	}

	public function search($pageSize = 20){
		$count = $this->count();
		$page = page($count,$pageSize);
		$data = $this->order('sort asc,id asc')->limit($page->firstRow.','.$page->listRows)->select();
		return array(
				'data'=>$data,
				'page'=>$page->show(),
				'count'=>$count
			);
	}
}