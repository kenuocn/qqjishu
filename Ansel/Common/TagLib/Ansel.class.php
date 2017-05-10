<?php
// +----------------------------------------------------------------------
// | Author: Ansel 3126620990@qq.com  绵阳人维网络科技有限公司
// +----------------------------------------------------------------------
namespace Common\TagLib;
use Think\Template\TagLib;
class Ansel extends TagLib {
	
	
	// 数据库where表达式
    protected $comparisonlvyecms = array(
        '{eq}' => '=',
        '{neq}' => '<>',
        '{elt}' => '<=',
        '{egt}' => '>=',
        '{gt}' => '>',
        '{lt}' => '<',
    );
    // 标签定义
    protected $tags   =  array(
    	// 标签定义： attr 属性列表 close 是否闭合（0 或者1 默认1） alias 标签别名 level 嵌套层次
		/*网站数据*/
		'Ansel'=>array('attr'=>'m,catid,skey,tag,num,order,where,thumb,page','level'=>3),
		/*文章标签*/
		'tag'=>array('attr'=>'tag'),
		'taglist'=>array('attr'=>'num'),
		/*上一篇*/
        'pre' => array('attr' => 'catid,id,msg'),
        /*下一篇*/
        'next' => array('attr' => 'catid,id,msg'),
		/*百度*/
		'webhtml'=>array('attr'=>'name,btn,num,value','close'=>0),
		/*模板权限管理*/
		'auth'      =>  array('attr'=>'rule','close'=>1,'level'=>2), 
		//前台模板标签
		'temp' => array('attr' => 'file', 'close' => 0),
    );
	/**
     * 数据列表
	 * m 方法：sort[分类]-catid num order where thumb
	 * m 方法：lists[文章列表]-catid num order where thumb page
	 * m 方法：tags[标签文章列表]-tag num order where thumb page
	 * m 方法：search[搜索列表]-skey num order where thumb page
     */
	public function _Ansel($tag,$content){
		 //指定分类id
		$tag['catid'] = $catid = $tag['catid'];
		//每页显示总数
        $tag['num'] = $num = (int) $tag['num'];
		//分页变量
		$tag['page'] = $page = empty($tag['page']) ? "pages" : $tag['page'];
		//数据返回变量
        $tag['return'] = $return = "data";
		//方法
        $tag['m'] = $m = trim($tag['m']);
        //sql语句的where部分
        if (isset($tag['where']) && $tag['where']) {
            $tag['where'] = $this->parseSqlCondition($tag['where']);
        }
		//拼接php代码
        $parseStr = '<?php';
        $parseStr .= ' $content_tag = \Think\Think::instance("\Index\TagLib\Index");' . "\r\n";
		//如果有传入$page参数，则启用分页。
        if ($page && in_array($m, array('lists','search','tags'))) {
            //分页配置处理
            $pageConfig = $this->resolvePageParameter($tag);
            //进行信息数量统计 需要 action catid where
			if($m=='lists'){
				$page_url=C('LIST_PAGE_URL');
            	$parseStr .= ' $count = $content_tag->count(' . self::arr_to_html($tag) . ');' . "\r\n";
			}else if($m=='tags'){
				$page_url=C('TAG_PAGE_URL');
				$parseStr .= ' $count = $content_tag->tags_count(' . self::arr_to_html($tag) . ');' . "\r\n";
			}else if($m=='search'){
				$page_url=C('SEARCH_PAGE_URL');
				$parseStr .= ' $count = $content_tag->skey_count(' . self::arr_to_html($tag) . ');' . "\r\n";	
			}
            //分页函数
            $parseStr .= ' $page = page($count ,' . $num . ');';
            $tag['count'] = '$count';
            $tag['limit'] = '$page->firstRow.",".$page->listRows';
            if(C('URL_ROUTER_ON') && C('URL_MODEL')==2){
				//显示分页导航
				$parseStr .= '$page->url="'.$page_url.'/'.C("VAR_PAGE").'";';
			}
			$parseStr .= '$pages = $page->show();';
        }
        $parseStr .= ' if(method_exists($content_tag, "' . $m . '")){';
        $parseStr .= ' $' . $return . ' = $content_tag->' . $m . '(' . self::arr_to_html($tag) . ');';
        $parseStr .= ' }';
        $parseStr .= ' ?>';
        //解析模板
        $parseStr .= $this->tpl->parse($content); 
		return $parseStr;
	}
	/**
     * 文章标签
     */
	public function _tag($tag,$content){
		$tags=$tag['tag'];	
		$parsestr='<?php ';
		$parsestr .= '$tagsarr=str_replace("，",",",$tags);';
		$parsestr .= '$tagsarr=explode(",",$tagsarr);';
		$parsestr .= 'foreach ($tagsarr as $tag_v):';
		$parsestr .= '$tag=$tag_v;';
		$parsestr .= '$url=tagurl($tag_v);?>';
		$parsestr .= $content;
		$parsestr .='<?php endforeach;?>'; 
        return $parsestr;
	}
	/**
     * 文章标签
     */
	public function _taglist($tag,$content){
		$tag['num'] = $num = (int) $tag['num'];
		$parsestr='<?php ';
		$parsestr .= '$taglist=M("Tag")->field("tag")->limit($num)->select();';
		$parsestr .= 'foreach($taglist as $v):';
		$parsestr .= '$tags_r[]=$v["tag"];'; 
		$parsestr .= 'endforeach;';
		$parsestr .= '$tags_r=array_unique($tags_r);';
		$parsestr .= 'foreach($tags_r as $v):';
		$parsestr .= '$num=M("tag")->where(array("tag"=>$v))->count();';
		$parsestr .= '$tag=$v;';
		$parsestr .= '$url=tagurl($v);?>';
		$parsestr .= $content;
		$parsestr .='<?php endforeach;?>'; 
        return $parsestr;
	}
	/**
     * 获取上一篇标签
     */
    public function _pre($tag, $content) {
        //当没有内容时的提示语
        $msg = !empty($tag['msg']) ? $tag['msg'] : '已经没有了';
        $parsestr = '<?php ';
        $parsestr .= '$article = M("article")->where(array("catid"=>' . $tag['catid'] . ',"status"=>1,"id"=>array("LT",' . $tag['id'] . ')))->order(array("id" => "DESC"))->field("id,title")->find(); ';
		$parsestr .= '$title=$article?$article["title"]:'.$msg.';';
		$parsestr .= '$url=$article?arurl($article["id"]):"javascript:;";?>';
		$parsestr .= $content;
        return $parsestr;
    }

    /**
     * 获取下一篇标签
     */
    public function _next($tag, $content) {
        //当没有内容时的提示语
        $msg = !empty($tag['msg']) ? $tag['msg'] : '已经没有了';
        $parsestr = '<?php ';
        $parsestr .= '$article =M("article")->where(array("catid"=>' . $tag['catid'] . ',"status"=>1,"id"=>array("GT",' . $tag['id'] . ')))->order(array("id" => "ASC"))->field("id,title")->find(); ';
        $parsestr .= '$title=$article?$article["title"]:'.$msg.';';
		$parsestr .= '$url=$article?arurl($article["id"]):"javascript:;";?>';
		$parsestr .= $content;
        return $parsestr; 
    }
	/**
     * 百度上传插件标签解析
     * name 文本框name名称
     * btn  上传按钮显示文字
     * num  允许上传数量
	 * auto 是否自动上传  true-自动  false-手动
     * type 上传类型
     */
	public function _webhtml($tag){
		$name=isset($tag['name'])?$tag['name']:'image';
		$btn=isset($tag['btn'])?$tag['btn']:'上传附件';
		$num=isset($tag['num'])?$tag['num']:'1';
		$auto=isset($tag['auto'])?$tag['auto']:true;
		$type=isset($tag['type'])?$tag['type']:'image';
		$value=$this->parseCondition($tag['value']); 
		if($type=='image'){
			$filetext="filetext";	
		}
		$url=U('Admin/Attachment/upload',array('num'=>$num,'auto'=>$auto,'type'=>$type));
		if($num<=1){
		$html=<<<php
		<div class="input-group">
		  <input class="form-control {$filetext}" type="text" value="{$value}" name="{$name}">
		  <span class="input-group-btn"><button type="button" onclick="uphtml(this,'btn_{:genRandomString()}','{$url}')" class="btn btn-primary">{$btn}</button></span> 
		</div>
php;
		}else{
		$html=<<<php
		<div class="input-group">
		  <input class="form-control" type="text" placeholder="上传文件" disabled name="{$name}">
		  <span class="input-group-btn"><button type="button" onclick="uphtml(this,'btn_{:genRandomString()}','{$url}')" class="btn btn-primary">{$btn}</button></span> 
		</div>
		<div class="pics"><ul>{:filelist($value)}</ul></div> 
php;
		}
		return $html;
	}
	
    /**
     * authIn标签解析
     * 格式：
     * <auth rule="Admin/User/add" fullrule="true" >显示按钮<else />不显示按钮</auth>
     * <auth rule="add">显示按钮<else />不显示按钮</auth>
     * @return string
     */
    public function _auth($tag,$content) {
        $rule  =   $this->parseCondition($tag['rule']); 
		$auth = new \Think\Auth();
		//获取当前uid所在的角色组id
		$uid=session('userinfo.uid');
		$groups_id=getGroups($uid);
		if(in_array($groups_id, C('ADMINISTRATOR'))){ //对应的用户组无需验证
			$rs=true;
		}else{			
			$rs = $auth->check($rule,$uid) ? 'true' : 'false';
		}
        $parseStr   =   '<?php if( '.$rs.' ): ?>'.$content.'<?php endif; ?>';
        return $parseStr;
    }
	 /**
     * 加载前台模板
     * 格式：<template file="footer.html"/>
     * @staticvar array $_templateParseCache
     * @param type $attr file
     * @param type $content
     * @return string|array 返回模板解析后的内容
     */
    public function _temp($attr, $content) {
        $templateFile = $attr['file'];
		$config=S('Config');
		//判断是否开启手机版
		if($config['ismobile']==1){
			if(ismobile()){
				$theme=$config['ThemeMobile']; 	
			}else{
				$theme=$config['ThemePc']; 
			}
		}else{
			$theme=$config['ThemePc']; 
		}
		$file=C('VIEW_PATH').$theme.'/'.$templateFile; 
        //判断模板是否存在
        if (!file_exists_case($file)) {
             return '';
        }
        //读取内容
        $tmplContent = file_get_contents($file);
        //解析模板
        $parseStr = $this->tpl->parse($tmplContent);
        return $parseStr;
    }
    /**
     * 转换数据为HTML代码
     * @param array $data 数组
     */
    private static function arr_to_html($data) {
        if (is_array($data)) {
            $str = 'array(';
            foreach ($data as $key => $val) {
                if (is_array($val)) {
                    $str .= "'$key'=>" . self::arr_to_html($val) . ",";
                } else {
                    //如果是变量的情况
                    if (strpos($val, '$') === 0) {
                        $str .= "'$key'=>$val,";
                    } else if (preg_match("/^([a-zA-Z_].*)\(/i", $val, $matches)) {//判断是否使用函数
                        if (function_exists($matches[1])) {
                            $str .= "'$key'=>$val,";
                        } else {
                            $str .= "'$key'=>'" . self::newAddslashes($val) . "',";
                        }
                    } else {
                        $str .= "'$key'=>'" . self::newAddslashes($val) . "',";
                    }
                }
            }
            return $str . ')';
        }
        return false;
    }

    /**
     * 返回经addslashes处理过的字符串或数组
     * @param $string 需要处理的字符串或数组
     * @return mixed
     */
    protected static function newAddslashes($string) {
        if (!is_array($string))
            return addslashes($string);
        foreach ($string as $key => $val)
            $string[$key] = $this->newAddslashes($val);
        return $string;
    }
    /**
     * 解析条件表达式
     * @access public
     * @param string $condition 表达式标签内容
     * @return array
     */
    protected function parseSqlCondition($condition) {
        $condition = str_ireplace(array_keys($this->comparisonlvyecms), array_values($this->comparisonlvyecms), $condition);
        return $condition;
    }

    /**
     * 解析分页参数
     * @param type $tag
     * @return type\
     */
    protected function resolvePageParameter(&$tag) {
        if (empty($tag)) {
            return array();
        }
        //分页设置
        $config = array();
        foreach ($tag as $key => $value) {
            if ($key && substr($key, 0, 5) == "page_") {
                //配置名称
                $name = str_replace('page_', '', $key);
                if (substr($value, 0, 1) == '$') {
                    $config[$name] = $value;
                } else {
                    $config[$name] = $this->parseSqlCondition($value);
                }
                unset($tag[$key]);
            }
        }
        //兼容 pagetp 参数
        if (!empty($tag['pagetp'])) {
            $config['tpl'] = (substr($tag['pagetp'], 0, 1) == '$') ? $tag['pagetp'] : '';
        }
        //标签默认开启自定义分页规则
        $config['isrule'] = true;
        return $config;
    }
}