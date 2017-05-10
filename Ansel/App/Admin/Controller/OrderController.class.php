<?php
// +----------------------------------------------------------------------
// | 插件管理
// +----------------------------------------------------------------------
// | Author: Ansel 3126620990@qq.com  绵阳人维网络科技有限公司
// +---------------------------------------------------------------------- 
namespace Admin\Controller;
use Common\Controller\AdminBase;
class OrderController extends AdminBase
{
    protected function _initialize() {
        parent::_initialize();
        $this->model = D('Order');
    }
 
	/**
     * @cc 订单列表
     */
	 public function index(){
	 	$this->display();
	 }
	/**
     * @cc 编辑订单
     */
	 public function edit(){
	 	$this->display();
	 }
}