<?php
// +----------------------------------------------------------------------
// | Author: 黄靖   <3126620990@qq.com> 绵阳人维网络科技有限公司
// +----------------------------------------------------------------------

namespace Admin\Model;
class LoginlogModel extends \Think\Model {

     //array(填充字段,填充内容,[填充条件,附加规则]) 
    protected $_auto = array(
        array('logintime', 'time', 1, 'function'),
        array('loginip', 'get_client_ip', 3, 'function'),  
    );

    /**
     * 删除一个月前的日志
     * @return boolean
     */
    public function deleteAMonthago() {
        $status = $this->where(array("logintime" => array("lt", time() - (86400 * 30))))->delete();
        return $status !== false ? true : false;
    }

    /**
     * 添加登录日志
     * @param array $data
     * @return boolean
     */
    public function addLoginLogs($data) {
        $this->create($data);
        return $this->add() !== false ? true : false;
    }
	
}
