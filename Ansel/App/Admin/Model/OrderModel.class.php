<?php
// +----------------------------------------------------------------------
// | Author: Ansel   <3126620990@qq.com>  绵阳人维网络科技有限公司
// +----------------------------------------------------------------------
namespace Common\Model;
class OrderModel extends \Think\Model {
	protected $_validate = array(
		array('customer_id', 'customer', '请选择客户','1','callback','1'),
		array('title', 'require', '请输入订单名称'),
		array('title', '', '订单名称已存在', 0, 'unique', 1), 
		array('signing_time', 'require', '请输入签订时间'),
		array('finish_time', 'require', '请输入完成时间'),
		array('amount', '/^\d+(\.\d+)?$/', '请输入正确的合同金额'), 
		array('the_money', '/^\d+(\.\d+)?$/', '请输入正确的已收款金额'), 
    );
	//array(填充字段,填充内容,[填充条件,附加规则])
    protected $_auto = array(
		array('uid', 'getuid',self::MODEL_BOTH, 'callback'),
		array('signing_time', 'strtotime', self::MODEL_INSERT, 'function'), 
		array('finish_time', 'strtotime', self::MODEL_BOTH, 'function'), 
		array('other_info', 'other_info', self::MODEL_BOTH, 'callback'), 
		array('order_file', 'order_file', self::MODEL_BOTH, 'callback'), 
		array('creat_time', 'time',self::MODEL_BOTH, 'function'),
	);
	/* 获取当前用户id */
    public function getuid() {
		return session('userinfo.uid');
    }
	//验证客户
	public function customer(){
		$uid=session('userinfo.uid'); 
		$auth=authGroup($userid);
		$customer_id=I('post.customer_id','','intval');
		if(empty($customer_id)){
			return false;	
		}else{
			if(!$auth){
				$where=array('uid'=>$uid,'is_del'=>0,'id'=>$customer_id);	
			}else{
				$where=array('is_del'=>0,'id'=>$customer_id);	
			}
			return M('customer')->where($where)->find()?true:false;	
		}
	}
	//分隔订单其他信息
	public function other_info(){
		$info=trim(I('post.other_info'));
		if($info){	
			$info = explode("\r\n", $info);
			foreach($info as $k=>$v){
				$vs=preg_split("/(:|：)/", $v); 
				$other_info[$vs[0]]=$vs[1];	
			}
			return serialize($other_info);
		}else{
			return null;	
		}
	}
	//附件
	public function order_file(){
		$order_file=I('post.fileurl');
		if($order_file){
			foreach($order_file as $k=>$v){
				$fileurl[]=$v;		
			}
			return serialize($fileurl);
		}else{
			return null;	
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
					return true;	
				}	
			}else{
				$id = $this->add();
				if ($id) {
					//M('auth_group_access')->add(array('uid'=>$id,'group_id'=>$data['group']));
					return $id;
				}
				return false;
			}
        }else{
            return false;
        }		
    }
} 