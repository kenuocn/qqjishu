<?php
// +----------------------------------------------------------------------
// | Author: Ansel   <3126620990@qq.com>  绵阳人维网络科技有限公司
// +----------------------------------------------------------------------
namespace Common\Model;
class CustomerModel extends \Think\Model {
	protected $_validate = array(
		//array(验证字段1,验证规则,错误提示,[验证条件,附加规则,验证时间]),
		/*
		验证条件 （可选）
		包含下面几种情况：
		self::EXISTS_VALIDATE 或者0 存在字段就验证（默认）
		self::MUST_VALIDATE 或者1 必须验证
		self::VALUE_VALIDATE或者2 值不为空的时候验证
		*/
		array('name', 'require', '请输入客户姓名'),
		array('phone', '/^(0|86|17951)?(13[0-9]|15[012356789]|18[0-9]|14[57])[0-9]{8}$/', '手机号码格式不正确',1),
		array('phone', '', '该手机号已存在',1, 'unique', 3), 
		array('qq', '/^[1-9]\d{4,10}$/', 'QQ号码格式不正确',1),
		array('qq', '', '该QQ号已存在',1, 'unique', 3), 
		array('email', '/([a-z0-9]*[-_.]?[a-z0-9]+)*@([a-z0-9]*[-_]?[a-z0-9]+)+[.][a-z]{2,3}([.][a-z]{2})?/i', '邮箱地址格式不正确',2),
		array('email', '', '该邮箱地址已存在', 2, 'unique'), 
		array('age', '/^[0-9]*$/', '请输入客户年龄，格式为数字',1,'',3),
		array('sex', 'require', '请选择客户性别'),
		array('address', 'require', '请输入客户地址'),
		
		array('company_name', 'require', '请输入公司名称，没有请填写客户姓名'),
		array('company_address', 'require', '请输入公司地址，没有请填写客户地址'),
		array('company_tel', 'require', '请输入公司联系电话，没有请填写客户电话'),

    );
	//array(填充字段,填充内容,[填充条件,附加规则])
    protected $_auto = array(
		array('uid', 'getuid',self::MODEL_INSERT, 'callback'),
		array('creat_time', 'time',self::MODEL_INSERT, 'function'),
	);
	/* 获取当前用户id */
    public function getuid() {
		
		return session('userinfo.uid');
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