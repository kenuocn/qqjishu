<?php
// +----------------------------------------------------------------------
// | Author: Ansel   <3126620990@qq.com>  绵阳人维网络科技有限公司
// +----------------------------------------------------------------------
namespace Common\Model;
class AuthGroupModel extends \Think\Model {
	protected $_validate = array(
        array('title', 'require', '请输入角色名称'),
		array('title', '', '角色已存在', 0, 'unique', 1), 
    );
	//array(填充字段,填充内容,[填充条件,附加规则])
    protected $_auto = array();
	
	public function ins_up_data($data) {
		if (empty($data)) {
            $this->error = '没有数据！';
            return false;
        }
		if ($this->create($data)) {
			if($data['id']){
				if($this->save()!==false){
					return true;	
				}	
			}else{
				$id = $this->add();
				if ($id) {
					return $id;
				}
				return false;
			}
        }else{
            return false;
        }		
    }
	//检查是否设置权限
	public function isrules($group_id,$rules_id,$type=0){
	    $field=$type?'menu_rules':'rules';
        $rules=$this->where(array('id'=>$group_id))->getfield($field);
        if(empty($rules)){
            return false;
        }else{
            $rules=explode(',',$rules);
            if(in_array($rules_id,$rules)){
                return true;
            }else{
                return false;
            }
        }
	}
} 