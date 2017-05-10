<?php
// +----------------------------------------------------------------------
// |  内容解析标签处理类  Author: Ansel 3126620990@qq.com  绵阳人维网络科技有限公司
// +----------------------------------------------------------------------
namespace Index\TagLib;
class Index {
    public $db,$where;

    /**
     * 组合查询条件
     * @param type $attr
     * @return type
     */
    public function where($attr) {
        $where = array();
        //设置SQL where 部分
        if (isset($attr['where']) && $attr['where']) {
            $where['_string'] = $attr['where'];
        }
        //栏目id条件
        if (isset($attr['catid']) && (int) $attr['catid']) {
            $catid = (int) $attr['catid'];
			//是否有子栏目
			$child = M('sort')->where(array('pid'=>$catid))->field("id")->select();
            if ($child) {
				$child = arrayChange($child);
				$child[]=$catid;
				$child=implode(",",$child);
                $where['catid'] = array("IN", $child);
            } else {
                $where['catid'] = array("EQ", $catid);
            }
        }
        //缩略图
        if (isset($attr['thumb'])) {
            if ($attr['thumb']) {
                $where['thumb'] = array("NEQ", "");
            } else {
                $where['thumb'] = array("EQ", "");
            }
        }
        //审核状态
        $where['status'] = array("EQ", 1);
		
        $this->where = $where;
        return $this->where;
    }


    /**
     * 统计
     */
    public function count($data) {
        if ($data['m'] == 'lists') { 
			$db = M('Article');
            return $db->where($this->where($data))->count();
        }
    }
    /**
     * 统计
     */
    public function tags_count($data) {
		if ($data['m'] == 'tags'){
			$db_tag = M('Tag');
			$tag_article=$db_tag->where(array('tag'=>$data['tag']))->field('aid')->select();
			foreach($tag_article as $v){
				$ids[]=$v['aid'];	
			}
			return count($ids);
		}
    }
	/**
     * 统计
     */
    public function skey_count($data) {
        if ($data['m'] == 'search') { 
			$db = M('Article');
			$skey=$data['skey'];
			$where="status=1 and title like '%$skey%'";
            return $db->where($where)->count();
        }
    }
    /**
     * 内容列表（lists）
     * 参数名	 是否必须	 默认值	 说明
     * catid	 否	 null	 调用栏目ID
     * where	 否	 null	 sql语句的where部分
     * thumb	 否	 0	 是否仅必须缩略图
     * order	 否	 null	 排序类型
     * num	 是	 null	 数据调用数量
     * @param $data
     */
    public function lists($data) {
        //if (!$data['catid']) {
          //  return false;
        //}
        $this->where($data);
        //判断是否启用分页，如果没启用分页则显示指定条数的内容
        if (!isset($data['limit'])) {
            $data['limit'] = (int) $data['num'] == 0 ? 10 : (int) $data['num'];
        }
        //排序
        if (empty($data['order'])) {
            $data['order'] = array('time' => 'DESC', 'id' => 'DESC');
        }
		$db = M('Article');
        $dataList = $db->where($this->where)->limit($data['limit'])->order($data['order'])->select();
		foreach($dataList as $k=>$v){
			$dataList[$k]['thumb']=unserialize($v['thumb']);
			$dataList[$k]['thumb_num']=count($dataList[$k]['thumb']);
			$dataList[$k]['url']=arurl($v['id']);
			$dataList[$k]['caturl']=caturl($v['catid']);
			$dataList[$k]['catname']=getCate($v['catid'],'catname');
		}
        return $dataList;
    }
	/**
     * 标签文章（tags）
     * 参数名	 是否必须	 默认值	 说明
     * where	 否	 null	 sql语句的where部分
     * thumb	 否	 0	 是否仅必须缩略图
     * order	 否	 null	 排序类型
     * num	 是	 null	 数据调用数量
     * @param $data
     */
    public function tags($data) {
        if (!$data['tag']) {
           return false;
        }
        $this->where($data);
        //判断是否启用分页，如果没启用分页则显示指定条数的内容
        if (!isset($data['limit'])) {
            $data['limit'] = (int) $data['num'] == 0 ? 10 : (int) $data['num'];
        }
        //排序
        if (empty($data['order'])) {
            $data['order'] = array('time' => 'DESC', 'id' => 'DESC');
        }
		$db = M('Article');
		$db_tag = M('Tag');
		$tag_article=$db_tag->where(array('tag'=>$data['tag']))->field('aid')->select();
		foreach($tag_article as $v){
			$ids[]=$v['aid'];	
		}
        $dataList = $db->where(array('id'=>array('IN',$ids)))->limit($data['limit'])->order($data['order'])->select();
		foreach($dataList as $k=>$v){
			$dataList[$k]['thumb']=unserialize($v['thumb']);
			$dataList[$k]['thumb_num']=count($dataList[$k]['thumb']);
			$dataList[$k]['url']=arurl($v['id']);
			$dataList[$k]['caturl']=caturl($v['catid']);
			$dataList[$k]['catname']=getCate($v['catid'],'catname');
		}
        return $dataList;
    }
	/**
     * 搜索列表（skey）
     * 参数名	 是否必须	 默认值	 说明
     * where	 否	 null	 sql语句的where部分
     * thumb	 否	 0	 是否仅必须缩略图
     * order	 否	 null	 排序类型
     * num	 是	 null	 数据调用数量
     * @param $data
     */
    public function search($data) {
        if (!$data['skey']) {
           return false;
        }
        $this->where($data);
        //判断是否启用分页，如果没启用分页则显示指定条数的内容
        if (!isset($data['limit'])) {
            $data['limit'] = (int) $data['num'] == 0 ? 10 : (int) $data['num'];
        }
        //排序
        if (empty($data['order'])) {
            $data['order'] = array('time' => 'DESC', 'id' => 'DESC');
        }
		$skey=$data['skey'];
		$db = M('Article');
		$where="status=1 and title like '%$skey%'";
        $dataList = $db->where($where)->limit($data['limit'])->order($data['order'])->select();
		foreach($dataList as $k=>$v){
			$dataList[$k]['thumb']=unserialize($v['thumb']);
			$dataList[$k]['thumb_num']=count($dataList[$k]['thumb']);
			$dataList[$k]['url']=arurl($v['id']);
			$dataList[$k]['caturl']=caturl($v['catid']);
			$dataList[$k]['catname']=getCate($v['catid'],'catname');
		}
        return $dataList;
    }
	/**
     * 排行榜标签
     * 参数名	 是否必须	 默认值	 说明
     * catid	 否	 null	 调用栏目ID，只支持单栏目
     * where	 否	 null	 sql语句的where部分
     * day	 否	 0	 调用多少天内的排行
     * order	 否	 null	 排序类型（本月排行- monthviews DESC 、本周排行 - weekviews DESC、今日排行 - dayviews DESC）
     * num	 是	 null	 数据调用数量
     * @param $data
     */
    public function hits($data) {
        $catid = intval($data['catid']);
        $where = $array = array();
        //设置SQL where 部分
        if (isset($data['where']) && $data['where']) {
            $where['_string'] = $data['where'];
        }
        //排序
        $order = $data['order'];
        if (!$order) {
            $order = array('views' => 'DESC');
        }
        //条数
        $num = (int) $data['num'];
        if ($num < 1) {
            $num = 10;
        }
        if ($catid) {
            $where['catid'] = array('EQ', $catid);
        }
		//如果调用的栏目是存在子栏目的情况下
		$child = M('sort')->where(array('pid'=>$catid))->field("id")->select();
		if ($child) {
			$child = arrayChange($child);
			$child[]=$catid;
			$child=implode(",",$child);
			$where['catid'] = array("IN", $child);
		}
        //调用多少天内
        if (isset($data['day'])) {
            $time = time() - (intval($data['day']) * 86400);
            $where['time'] = array('GT', $time);
        }
		$db = M('Article');
        $dataList =$db->where($where)->order($order)->limit($num)->select();
		foreach($dataList as $k=>$v){
			if(empty($v['thumb'])){
				continue;
			}	
			$dataList[$k]['thumb']=unserialize($v['thumb']);
		}
        return $dataList;
    }
    /**
     * 分类列表（sort）
     * 参数名	 是否必须	 默认值	 说明
     * catid	 否	 0	 调用该栏目下的所有栏目 ，默认0，调用一级栏目
     * order	 否	 null	 排序方式、一般按照listorder ASC排序，即栏目的添加顺序
     * @param $data
     */
    public function sort($data) {
        $data['catid'] = intval($data['catid']);
        $where = $array = array();
        //设置SQL where 部分
        if (isset($data['where']) && $data['where']) {
            $where['_string'] = $data['where'];
        }
        $db = M('Sort');
        $num = (int) $data['num'];
        if (isset($data['catid'])) {
            $where['status'] = 1;
            $where['pid'] = $data['catid'];
        }
		
        //如果条件不为空，进行查库
        if (!empty($where)) {
            if ($num) {
				
                $sort = $db->where($where)->limit($num)->order($data['order'])->select();
            } else {
                $sort = $db->where($where)->order($data['order'])->select();
            }
        }
		
		foreach($sort as $k=>$v){
			$sort[$k]['url']=caturl($v['id']);	
		}
        return $sort;
    }

}
