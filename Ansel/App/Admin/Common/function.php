<?php
// +----------------------------------------------------------------------
// | Author: Ansel 3126620990@qq.com  绵阳人维网络科技有限公司
// +----------------------------------------------------------------------

/*返回当前登录的用户id*/
function getuid(){
	return session("userinfo.uid");
}
/*返回当前登录的用户的session_id*/
function getsid(){
	$sid=M('user')->where(array('id'=>session("userinfo.uid")))->getfield('session_id');
	return $sid?$sid:false;
}
/**
* 权限验证
* @param rule string|array  需要验证的规则列表,支持逗号分隔的权限规则或索引数组
* @param uid  int           认证用户的id
* @param string mode        执行check的模式
* @param relation string    如果为 'or' 表示满足任一条规则即通过验证;如果为 'and'则表示需满足所有规则才能通过验证
* @return boolean           通过验证返回true;失败返回false
*/
function authCheck($rule,$uid,$type=1, $mode='url', $relation='or'){
	//超级管理员跳过验证
	$auth=new \Auth();
	//获取当前uid所在的角色组id
	$groups_id=getGroups($uid);
	if(authGroup($uid)){ //对应的用户组无需验证
		return true;
	}else{
	    $arr=explode('/',$rule);
	    if($arr[0]==MODULE_NAME && $arr[1]==CONTROLLER_NAME && $arr[2]==ACTION_NAME){
            $menu=M('menu')->where(array('app'=>$arr[0],'controller'=>$arr[1],'action'=>$arr[2]))->field('id,type')->find();
	        if($menu){
	            if($menu['type']==0){
	                return true;
                }
	            $menu_rules=M('auth_group')->where(array('id'=>$groups_id))->getfield('menu_rules');
	            $menu_rules=substr($menu_rules,0,strlen($menu_rules)-1);
	            $menu_rules=explode(',',$menu_rules);
	            return in_array($menu['id'],$menu_rules)?true:false;
            }
        }
		return $auth->check($rule,$uid,$type,$mode,$relation)?true:false;
	}
}
//判断当前用户的用户组是否在不验证权限里面
function authGroup($uid){
	$uid=$uid?$uid:getuid();
	if(in_array(getGroups($uid), C('ADMINISTRATOR'))){
		return true;
	}else{
		return false;	
	}	
}
//判断指定用户组是否在不验证权限组里面
function authGroupid($gid){
	if(in_array($gid, C('ADMINISTRATOR'))){
		return true;
	}else{
		return false;	
	}	
}

/**
* 根据用户id判断用户角色
*/
function getGroupName($uid){
	$uid=$uid?$uid:session('userinfo.uid');
	$name=M()->table(C('DB_PREFIX').'auth_group a,'.C('DB_PREFIX').'auth_group_access b')->where('a.id=b.group_id and b.uid='.$uid.'')->getfield("a.title");	
	return $name;
}
/**
* 根据用户id获取用户姓名
*/
function getUserName($uid){
	$s_uid=session('userinfo.uid');
	if($uid==$s_uid){
		return session("userinfo.name");	
	}else{
		return M('user')->where(array('id'=>$uid))->getfield("name");	
	}
}
/**
 * 根据用户id获取用户组
 */
function getGroups($uid) {
	$uid=$uid?$uid:session('userinfo.uid');
	$groups_id = M()
		->table(C('DB_PREFIX').'auth_group_access a')
		->where("a.uid='$uid' and g.status='1'")
		->join(C('DB_PREFIX')."auth_group g on a.group_id=g.id")
		->getfield('id');
	return $groups_id=$groups_id?$groups_id:false;
}
//判断用户是否被禁用
function isuser($uid){
	$uid=$uid?$uid:session('userinfo.uid');
	return M('user')->where(array('id'=>$uid,'status'=>1))->find()?true:false;	
}
//根据会员组id获取会员组名称
function getMemberGroup($gid){
	return $gid?M('group')->where(array('id'=>$gid))->getfield('name'):false;	
}
/**
 * select返回的数组进行整数映射转换
 *
 * @param array $map  映射关系二维数组  array(
 *                                          '字段名1'=>array(映射关系数组),
 *                                          '字段名2'=>array(映射关系数组),
 *                                           ......
 *                                       )
 * @author 朱亚杰 <zhuyajie@topthink.net>
 * @return array
 *
 *  array(
 *      array('id'=>1,'title'=>'标题','status'=>'1','status_text'=>'正常')
 *      ....
 *  )
 *
 */
function int_to_string(&$data,$map=array('status'=>array(1=>'正常',-1=>'删除',0=>'禁用',2=>'未审核',3=>'草稿'))) {
    if($data === false || $data === null ){
        return $data;
    }
    $data = (array)$data;
    foreach ($data as $key => $row){
        foreach ($map as $col=>$pair){
            if(isset($row[$col]) && isset($pair[$row[$col]])){
                $data[$key][$col.'_text'] = $pair[$row[$col]];
            }
        }
    }
    return $data;
}
/**
 * 解压上传模板
 */
function temp_ezip($zipfile, $path){
	$zip = new ZipArchive();
	if (@$zip->open($zipfile) !== TRUE) {
		$res['err']=0;
		$res['info']="无法打开该文件";
	}
	$r = explode('/', $zip->getNameIndex(0), 2);
	$dir = isset($r[0]) ? $r[0] . '/' : '';
	$re = $zip->getFromName($dir . 'config.php');
	if (!$re){
		//模板配置文件不存在";
		$res['err']=0;
		$res['info']="配置文件不存在";
	}else{
		if (true === @$zip->extractTo($path)) {
			$zip->close();
			$res['err']=1;
			$res['info']="模板安装成功";
		} else {
			$res['err']=0;
			$res['info']="模板安装失败"; 
		}
	}
	return $res;
}