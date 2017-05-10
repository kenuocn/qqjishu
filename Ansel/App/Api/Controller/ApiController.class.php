<?php 
namespace Api\Controller;

use Think\Controller;
use Think\Chinese;

class ApiController extends Controller

{

	/**
	 * 获取QQ信息
	 * @return [type] [description]
	 */
	public function qqInfo(){
		$type = I('get.type','1',trim());
		$qq = I('get.qq','1402992668',trim());
		$url = "http://www.qqzhus.com/get.php";
		$params = array(
		      "type" => $type,
		      "qq" => $qq,
		);
		$paramstring = http_build_query($params);
		$content = juhecurl($url,$paramstring,0);
		zgy($content);
	}

	/**
	 * 汉字转拼音接口
	 * @param 参数1: chinese  内容
	 * @param 参数2: type     0=>全拼;1=>首字母
	 * @return json  string
	 */
	public function chineseTransliteration()
	{
		$chinese = I('post.chinese','',trim());
		$type = I('post.type',0,trim());
		$pinyin = new Chinese();
		$data = $pinyin->strtopin($chinese,$type);
		return $this->ajaxReturn($data);
	}
}