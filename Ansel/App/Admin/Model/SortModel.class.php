<?php
// +----------------------------------------------------------------------
// | Author: Ansel   <3126620990@qq.com>  绵阳人维网络科技有限公司
// +----------------------------------------------------------------------
namespace Common\Model;
class SortModel extends \Think\Model {
	protected $_validate = array(
		array('catname', 'require', '请输入分类名称'),
		array('catname', '', '分类名称已存在', 0, 'unique', 1), 
		array('catdir', '/^([a-z\s]*)$/isU', '请输入英文名称'),
		array('catdir', '', '英文名称已存在', 0, 'unique', 1), 
    );
	//array(填充字段,填充内容,[填充条件,附加规则])
    protected $_auto = array(
	);
	
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
					return $id;
				}
				return false;
			}
        }else{
            return false;
        }		
    }
} 