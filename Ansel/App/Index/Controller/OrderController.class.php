<?php
// +----------------------------------------------------------------------
// | Author: Ansel 3126620990@qq.com  绵阳人维网络科技有限公司
// +---------------------------------------------------------------------- 
namespace Index\Controller;
use Common\Controller\Base;
class OrderController extends Base
{
	public function orderInfo()
	{
		$this->display($this->theme.'order/orderInfo');
	}
}