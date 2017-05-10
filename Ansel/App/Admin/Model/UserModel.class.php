<?php
// +----------------------------------------------------------------------
// | Author: Ansel   <3126620990@qq.com>  绵阳人维网络科技有限公司
// +----------------------------------------------------------------------
namespace Common\Model;
class UserModel extends \Think\Model {
	protected $_validate = array(
		//array(验证字段1,验证规则,错误提示,[验证条件,附加规则,验证时间]),
		array('username', '/^[^@]{5,20}$/i', '账号长度在5-20位'),
		array('username', '', '该用户名已存在', 0, 'unique', 1), 
		array('password', '/^[a-z]\w{6,10}$/i', '密码格式不正确',0,''),
		array('name', 'require', '请输入用户姓名'),
		array('name', '', '该用户姓名已存在', 0, 'unique', 1), 
		array('email','email','email格式错误'),
		array('email', '', '邮箱已存在', 0, 'unique', 1), 
    );
	//array(填充字段,填充内容,[填充条件,附加规则])
    protected $_auto = array(
		array('password', 'encrypt',1, 'callback'),
		array('last_time', 'time', 1, 'function'),
        array('last_ip', 'get_client_ip', 3, 'function'),
	);
	/* 给密码加密 */
    public function encrypt($password){
		return md5($password.C('ANSELKEY'));
    }
	 
	public function ins_up_data($data,$type) {
		if (empty($data)) {
            $this->error = '没有数据！';
            return false;
        }
		if ($this->create($data,$type)) {
			if($data['id']){
				if($this->save()!==false){
					M('auth_group_access')->where(array('uid'=>$data['id']))->setfield('group_id',$data['group_id']);
					return true;	
				}	
			}else{
				$id = $this->add();
				if ($id) {
					M('auth_group_access')->add(array('uid'=>$id,'group_id'=>$data['group_id']));
					return $id;
				}
				return false;
			}
        }else{
            return false;
        }		
    }
} 