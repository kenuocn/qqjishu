<?php
// +----------------------------------------------------------------------
// | Author: Ansel   <3126620990@qq.com>  绵阳人维网络科技有限公司
// +----------------------------------------------------------------------
namespace Common\Model;
class ArticleModel extends \Think\Model {
	protected $_validate = array(
		array('title', 'require', '请输入订单名称'),
		array('content', 'require', '请输入文章内容'),
    );
	//array(填充字段,填充内容,[填充条件,附加规则])
    protected $_auto = array(
		array('uid', 'getuid',self::MODEL_INSERT, 'function'),
		array('tags', 'tags', self::MODEL_BOTH, 'callback'), 
		array('thumb', 'thumb', self::MODEL_BOTH, 'callback'), 
		array('time', 'time',self::MODEL_BOTH, 'function'),
	);
	public function tags(){
		$tags=I('post.tags');
		if($tags){
			return $tags;
		}else{
			return '';	
		}
	}
	public function thumb(){
		$pic=I('post.pics');
		if($pic){
			return serialize($pic);	
		}else{
			return '';	
		}	
	}
	public function ins_up_data($data,$type) {
		if (empty($data)) {
            $this->error = '没有数据！';
            return false;
        }
		if ($this->create($data,$type)) {
			if($data['id']){
				if($this->save()!==false){
					$tagss=str_replace("，",",",$data['tags']);
					$tags_arr=explode(",",$tagss);
					$tagmodel=M('tag');
					$tags=$tagmodel->where(array('aid'=>$data['id']))->field('tag,aid')->select();
					foreach($tags as $k=>$v){
						if(!in_array($v['tag'],$tags_arr)){
							$tagmodel->where(array('tag'=>$v['tag'],'aid'=>$data['id']))->delete();
						}	
					}
					$this->tagss($data['tags'],$data['id'],$data['title']);
					return true;	
				}	
			}else{
				$id = $this->add();
				if ($id) {
					$this->tagss($data['tags'],$id,$data['title']);
					return $id;
				}
				return false;
			}
        }else{
            return false;
        }		
    }
	public function tagss($tags,$aid,$title){
		$tags=str_replace("，",",",$tags);
		$tags_arr=explode(",",$tags);

		$tagmodel=M('tag');
		foreach($tags_arr as $k=>$v){
			if(!$tagmodel->where(array('tag'=>$v,'aid'=>$aid))->find()){
				M('tag')->add(array('tag'=>$v,'aid'=>$aid,'title'=>$title));	 
			}
		}	
	}
} 