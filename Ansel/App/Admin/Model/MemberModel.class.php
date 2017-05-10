<?php
// +----------------------------------------------------------------------
// | Author: Ansel   <3126620990@qq.com>  绵阳人维网络科技有限公司
// +----------------------------------------------------------------------
namespace Common\Model;
class MemberModel extends \Think\Model {
	protected $_validate = array(
		array('username', '/^[^@]{5,20}$/i', '账号长度在5-20位'),
		array('username', '', '该用户名已存在', 0, 'unique', 1), 
		array('password', '/^[a-z]\w{6,10}$/i', '密码格式不正确',self::EXISTS_VALIDATE,'',self::MODEL_INSERT),
		array('password', '/^[a-z]\w{6,10}$/i', '密码格式不正确',self::VALUE_VALIDATE,'',self::MODEL_UPDATE),
		array('name', 'require', '请输入用户姓名'),
		array('name', '', '该用户姓名已存在', 0, 'unique', 1), 
		array('email','email','email格式错误'),
		array('email', '', '邮箱已存在', 0, 'unique', 1), 
    );
	//array(填充字段,填充内容,[填充条件,附加规则])
    protected $_auto = array(
		array('password', 'encrypt', self::MODEL_BOTH, 'callback'),
		array('regtime', 'time', 1, 'function'), 
		array('lasttime', 'time', 1, 'function'),
        array('lastip', 'get_client_ip', 3, 'function'),
	);
	/* 给密码加密 */
    public function encrypt() {
		return md5(I('post.password').C('ANSELKEY'));
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
					return $id;
				}
				return false;
			}
        }else{
            return false;
        }		
    }
} 