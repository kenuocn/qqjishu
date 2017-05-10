<?php
// +----------------------------------------------------------------------
// | Author: Ansel 3126620990@qq.com  绵阳人维网络科技有限公司
// +---------------------------------------------------------------------- 

//获取栏目url
function caturl($catid){
	$islink=getCate($catid,'islink');
	if($islink){
		return $islink; 	
	}else{
		if(C('URL_ROUTER_ON') && C('URL_MODEL')==2){
			$url=U('/lists/'.$catid);	 
		}else{
			$url=U('Index/index/lists',array('catid'=>$catid));	  	
		}
		return $catid?$url:false;
	}
}
//获取文章url
function arurl($id){
	if(C('URL_ROUTER_ON') && C('URL_MODEL')==2){
		$url=U('/show/'.$id);	 
	}else{
		$url=U('Index/index/show',array('id'=>$id));		
	}
	return $id?$url:false;
}
//标签url
function tagurl($tag){
	if(C('URL_ROUTER_ON') && C('URL_MODEL')==2){
		$url=U('/tag/'.$tag);	 
	}else{
		$url=U('Index/index/tag',array('tag'=>$tag));		
	}
	return $tag?$url:false; 
}

//获取当前用户信息
function getUser($uid){
	if(empty($uid)){
		return false;	
	}else{
		$uinfo=M('Member')->where(array('id'=>$uid))->find();
		return $uinfo?$uinfo:false;	
	}
}
//获取会员所在分组
function getGroup($uid){
	$uid=$uid?$uid:session('uinfo.uid');
	return M()->table(C('DB_PREFIX').'member a,'.C('DB_PREFIX').'group b')->where('a.id='.$uid.' and b.id=a.gid')->field("b.*")->find();	
}