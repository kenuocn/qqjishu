<?php
// +----------------------------------------------------------------------
// | Author: Ansel   <3126620990@qq.com>  绵阳人维网络科技有限公司
// +----------------------------------------------------------------------
namespace Common\Model;
class MemberModel extends \Think\Model {
	protected $_validate = array(
		array('username', '/^[^@]{5,20}$/i', '账号长度在5-20位'),
		array('username', '', '该用户名已存在', 0, 'unique', 1), 
		array('name', 'require', '请输入用户姓名'),
		array('name', '', '该用户姓名已存在', 0, 'unique', 1), 
		array('email','email','email格式错误'),
		array('email', '', '邮箱已存在', 0, 'unique', 1), 
		array('password', '/^[a-z]\w{6,10}$/i', '密码格式不正确',self::EXISTS_VALIDATE,'',self::MODEL_INSERT),
		array('password', '/^[a-z]\w{6,10}$/i', '密码格式不正确',self::VALUE_VALIDATE,'',self::MODEL_UPDATE),
		
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

	public function reg($data) {
		if (empty($data)) {
            $this->error = '没有数据！';
            return false;
        }
		$data['online']=1;
		if ($this->create($data)) {
			$id = $this->add();
			if ($id) {
				//发送邮件验证
				$val=Ansel_en($id);
				$title="Ansel-博客会员中心注册验证";
				$msg="尊敬的会员".$data['name']." 您好! </br> 点击下方连接验证您的账号吧： http://".$_SERVER['HTTP_HOST']."/email_test/".$val.".html";
				SendMail($data['email'],$title,$msg);
				session('uid',$id);
				return $id;
			}
			return false;
        }else{
            return false;
        }		
    }
} 