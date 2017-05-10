<?php
// +----------------------------------------------------------------------
// | Author: Ansel 3126620990@qq.com  绵阳人维网络科技有限公司
// +---------------------------------------------------------------------- 
// | 支付宝手机支付测试
// +---------------------------------------------------------------------- 
namespace Index\Controller;
class AlipayController extends \Think\Controller {

	protected $seller="986004469@qq.com"; //支付宝账号
	protected $partner='2088121750729086';//合作伙伴身份（PID）
	protected $keys='uf2f43k3kx7ktw638zt13xei771liwae';//支付key


	//接收参数页面
	public function index(){
		$pay_obj = new \Alipay();
		$payment['seller']=$this->seller;
		$payment['partner']=$this->partner;
		$payment['keys']=$this->keys;
		$order=array(
			'order_sn'     => time(),
			'order_amount' => '0.01',
			'title'		   => '测试订单名称',
			'body'         => "测试商品描述", 
		);
		$notify_url=(is_ssl() ? 'https://' : 'http://').$_SERVER['HTTP_HOST'].U('Index/Alipay/notify');//回调地址 地址必须加  http 或者 https
		$callback_url=(is_ssl() ? 'https://' : 'http://').$_SERVER['HTTP_HOST'].U('Index/Alipay/callback');//通知地址 地址必须加  http 或者 https
		$online=$pay_obj->get_code($order,$payment,$notify_url,$callback);
		$this->show($online);
	}
	
}