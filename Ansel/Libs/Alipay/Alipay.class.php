<?php
/**
 *  支付宝插件
 */
class Alipay{
	
    /**
     * 生成支付代码
     * @param   array   $order      订单信息
     * @param   array   $payment    支付方式信息
	 * @param   $notify_url    	服务器异步通知页面路径  需http://格式的完整路径，不允许加?id=123这类自定义参数
	 * @param   $callback_url    页面跳转同步通知页面路径 需http://格式的完整路径，不允许加?id=123这类自定义参数
     */
    function get_code($order, $payment,$notify_url='',$callback_url=''){
		
		//合作伙伴身份
		$alipay_config['partner'] = $payment['partner'];
		//安全检验码，以数字和字母组成的32位字符
		//如果签名方式设置为“MD5”时，请设置该参数
		$alipay_config['key'] = $payment['keys'];
		//签名方式 不需修改
		$alipay_config['sign_type']    = 'MD5';
		//字符编码格式 目前支持 gbk 或 utf-8
		$alipay_config['input_charset']= 'utf-8';
		//ca证书路径地址，用于curl中ssl校验
		$alipay_config['cacert']='';
		//访问模式,根据自己的服务器是否支持ssl访问，若支持请选择https；若不支持请选择http
		$alipay_config['transport']    = 'http';

		$format = "xml";
		//必填，不需要修改
		//返回格式
		$v = "2.0";
		//必填，不需要修改
		//请求号
		$req_id = date('Ymdhis');
		//商家账号
		$seller_email = $payment['seller'];
		//商户订单号
		$out_trade_no = $order['order_sn'];
		//商户网站订单系统中唯一订单号，必填
		//订单名称
		$subject = $order['title'];
		//必填
		//付款金额
		$total_fee = $order['order_amount'];
		//必填
		$body = $order['body'];
		//请求业务参数详细
    	$req_data = '<direct_trade_create_req><notify_url>' . $notify_url . '</notify_url><call_back_url>' . $callback_url . '</call_back_url><seller_account_name>' . $seller_email . '</seller_account_name><out_trade_no>' . $out_trade_no . '</out_trade_no><subject>' . $subject . '</subject><total_fee>' . $total_fee . '</total_fee><body>'.$body.'</body></direct_trade_create_req>';
		//构造要请求的参数数组，无需改动
		$para_token = array(
			"service" => "alipay.wap.trade.create.direct",
			"partner" =>  trim($alipay_config['partner']),
			"sec_id" =>  trim($alipay_config['sign_type']),
			"format"	=> $format,
			"v"	=> $v,
			"req_id"	=> $req_id,
			"req_data"	=> $req_data,
			"_input_charset"	=> trim(strtolower($alipay_config['input_charset']))
		);
		//引入 AlipaySubmit 类
		require_once("Alipay_submit.class.php");
		//建立请求
		$alipaySubmit = new AlipaySubmit($alipay_config);
		$html_text = $alipaySubmit->buildRequestHttp($para_token);
		//URLDECODE返回的信息
		$html_text = urldecode($html_text);
		//解析远程模拟提交后返回的信息
		$para_html_text = $alipaySubmit->parseResponse($html_text);
		//获取request_token
		$request_token = $para_html_text['request_token'];
		/**************************根据授权码token调用交易接口alipay.wap.auth.authAndExecute**************************/
		//业务详细
		$req_data = '<auth_and_execute_req><request_token>' . $request_token . '</request_token></auth_and_execute_req>';
		//必填
		//构造要请求的参数数组，无需改动
		$parameter = array(
				"service" => "alipay.wap.auth.authAndExecute",
				"partner" => trim($alipay_config['partner']),
				"v"	=> $v,
				"sec_id" => trim($alipay_config['sign_type']),
				"format"	=> $format,
				"req_id"	=> $req_id,
				"req_data"	=> $req_data,
				"_input_charset"	=> trim(strtolower($alipay_config['input_charset']))
		);
		//建立请求
		$alipaySubmit = new AlipaySubmit($alipay_config);
		$html_text = $alipaySubmit->buildRequestForm($parameter, 'post', '确认');
		return $html_text; 
		
    }
}