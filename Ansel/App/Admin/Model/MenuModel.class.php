<?php
// +----------------------------------------------------------------------
// | Author: Ansel   <3126620990@qq.com>  绵阳人维网络科技有限公司
// +----------------------------------------------------------------------
namespace Common\Model;
class MenuModel extends \Think\Model {
	protected $_validate = array(
        array('name', 'require', '请输入菜单名称'),
    );
	//array(填充字段,填充内容,[填充条件,附加规则])
    protected $_auto = array();
	//添加菜单
	public function menu_add($data) {
		if (empty($data)) {
            $this->error = '没有数据！';
            return false;
        }
		$data['module']=ucfirst($data['module']);
		$data['app']=ucfirst($data['app']);
		$data['controller']=ucfirst($data['controller']);
		$data['remark']=$data['remark']?$data['remark']:$data['name'];
		$app=$data['app'];
		$controller=$data['controller'];
		if($data['a_c_a']==1){
			//判断同一个模块不能有相同控制器和方法
			if($this->where(array('app'=>$app,'controller'=>$controller,'action'=>$data['action']))->find()){
				$this->error="一个模块下不能存在相同控制器和方法";
			}	
			//判断统一模块不能存在相同菜单名称
			if($this->where(array('app'=>$app,'name'=>$data['name']))->find()){
				$this->error="一个模块下不能存在相同菜单名称";
			}
		}
		if ($this->create($data)) {
           $id = $this->add();
           if ($id) {
				if($data['a_c_a']==1){
					$dirs = glob(APP_PATH . '*');//取得模块目录名称
					foreach ($dirs as $path) {
						if (is_dir($path)) {
							//目录名称
							$path = basename($path);
							$dirs_arr[] = $path;
						}
					}
					//判断是否存在模块，不存在就创建
					if(!in_array($app, $dirs_arr)){ 
						$this->mkdirApp($app,$controller,$data['action'],$data['remark']); 
						return $id;//结束不继续执行
					}
					//如果存在模块就判断是否存在控制器，不存在就添加控制器
					$file_url_Controller=APP_PATH.$app.'/Controller/'.$controller.'Controller.class.php';//控制器路径
					if (file_exists($file_url_Controller)) {
						$this->mkdirAction($file_url_Controller,$data['action'],$data['remark']);//如果控制器存在就直接在控制器后面追加方法
						$this->mkdirView($app,$controller,$data['action']);//追加了方法后生成视图文件	
					}else{//控制器不存在
						$this->mkdirController($app,$controller);//生成控制器
						$this->mkdirAction($file_url_Controller,$data['action'],$data['remark']);//生成控制器方法	
						$this->mkdirView($app,$controller,$data['action']);//追加了方法后生成视图文件	
					}
				}
                return $id;
            }
            return false;
        }else{
            return false;
        }		
    }
	/*
	*生成控制器文件
	*$app 模块名
	*$controller 控制器名称
	*/
	public function mkdirController($app,$controller,$remark="人维网"){
		$file_url_controller=APP_PATH.$app.'/Controller/'.$controller.'Controller.class.php';	
		if($app=='Admin'){
			$base='AdminBase';
		}else{
			$base='Ansel';	
		}
		$info=<<<PHP
<?php 
// +----------------------------------------------------------------------
// | Author: Ansel 3126620990@qq.com  绵阳人维网络科技有限公司
// +---------------------------------------------------------------------- 
namespace {$app}\Controller; 
use Common\Controller\\{$base};
class {$controller}Controller extends {$base}{ 

}

PHP;
		
		file_put_contents($file_url_controller,$info,FILE_APPEND);
		return true;		
	}
	/*
	*控制器追加方法
	*$file_url_Controller 控制器路径
	*$action 控制器方法
	*$remark 备注
	*/
	private function mkdirAction($file_url_Controller,$action,$remark){
		$tmplContent = file($file_url_Controller);
		delline($file_url_Controller,count($tmplContent));//删除文件最后一行
		$fp = fopen("$file_url_Controller","a");
		$str=<<<PHP
	/**
     * @cc {$remark}
     */
	 public function {$action}(){

	 }
}
PHP;
		if(fwrite($fp, $str)){
			return true;	
		}else{
			$this->mkdirAction($file_url_Controller,$action,$remark);	
		}	
	}
	/*
	*生成视图目录文件
	*$app 模块名称
	*$controller 控制器名称
	*$action 方法名称
	*/
	public function mkdirView($app,$controller,$action){
		$file_url_view=APP_PATH.$app.'/View/'.$controller;//视图控制器目录
		$file=$file_url_view.'/'.$action.'.html';//视图文件
		$str=<<<PHP
<include file="Public/header"/>
<div class="wrapper wrapper-content animated {:C('Animation')}">
  <div class="row">
    <div class="col-sm-12">
      <div class="ibox float-e-margins">
        <div class="ibox-title">
          <include file="Public/nav"/>
        </div>
        <div class="ibox-content">
          
        </div>
      </div>
    </div>
  </div>
</div>
<script src="\$public/js/common.js"></script>
</body>
</html> 
PHP;
		if(is_dir($file_url_view)){
			if(!file_exists($file)) {
				file_put_contents($file,$str,FILE_APPEND);	
				return true;
			}
			return true;
		}else{
			mkdir($file_url_view);
			file_put_contents($file,$str,FILE_APPEND);
			return true;
		}
	}
	
	/*
	*创建基础模块
	*$app 模块名
	*$controller 控制器名称
	*$action 方法名称
	*/
	public function mkdirApp($app,$controller,$action,$remark){
		//创建目录
		$dir=APP_PATH;
		$files[]=$dir.$app."/";//模块目录
		$files[]=$dir.$app."/Controller/";//模块目录/Controller	
		$files[]=$dir.$app."/Common/";//模块目录/Common	
		$files[]=$dir.$app."/Conf/";//模块目录/Conf	
		$files[]=$dir.$app."/Model/";//模块目录/Model	
		$files[]=$dir.$app."/View/";//视图目录/View	
		//创建相应文件
		$files[]=$dir.$app."/Controller/".$controller."Controller.class.php";
		$files[]=$dir.$app."/Common/function.php";
		$files[]=$dir.$app."/Conf/config.php";
		$files[]=$dir.$app."/Model/".$app."Model.class.php";
		create_files($files);
		//生成模块下的控制器文件
		$file_url_Controller=$dir.$app."/Controller/".$controller."Controller.class.php";//创建控制器文件路径
$Controlle_info=<<<PHP
<?php
// +----------------------------------------------------------------------
// | Author: Ansel 3126620990@qq.com  绵阳人维网络科技有限公司
// +---------------------------------------------------------------------- 
namespace {$app}\Controller; 
use Common\Controller\Ansel;
class {$controller}Controller extends Ansel { 

}
PHP;
		file_put_contents($file_url_Controller,$Controlle_info,FILE_APPEND);	//写入数据
		
		//生成模块下的function.php函数库
		$file_url_Function=$dir.$app."/Common/"."function.php";//创建模块配置文件路径
		$Function_info=<<<PHP
<?php
// +----------------------------------------------------------------------
// | Author: Ansel 3126620990@qq.com  绵阳人维网络科技有限公司
// +---------------------------------------------------------------------- 

PHP;
		file_put_contents($file_url_Function,$Function_info,FILE_APPEND);	//写入数据
		
		//生成模块下的config.php配置文件
		$file_url_Config=$dir.$app."/Conf/"."config.php";//创建模块配置文件路径
		$Config_info=<<<PHP
<?php
// +----------------------------------------------------------------------
// | Author: Ansel 3126620990@qq.com  绵阳人维网络科技有限公司
// +---------------------------------------------------------------------- 
return array (
	
);
PHP;
		file_put_contents($file_url_Config,$Config_info,FILE_APPEND);	//写入数据
		
		//生成模块下的Model文件
		$file_url_Model=$dir.$app."/Model/".$app."Model.class.php";//创建模块配置文件路径
		$Model_info=<<<PHP
<?php
// +----------------------------------------------------------------------
// | Author: Ansel 3126620990@qq.com  绵阳人维网络科技有限公司
// +---------------------------------------------------------------------- 
namespace Common\Model;
class {$app}Model extends \Think\Model{
	protected \$_validate = array();
	protected \$_auto = array();
}
PHP;
		file_put_contents($file_url_Model,$Model_info,FILE_APPEND);	//写入数据	
		
		$this->mkdirAction($file_url_Controller,$action,$remark);
		$this->mkdirView($app,$controller,$action);//生成视图文件
	}
	
	
	//获取导航
	public function getmenunav(){
		$menuid = I('get.menuid', 0, 'intval');
		$menuid = $menuid ? $menuid : cookie("menuid", "", array("prefix" =>MODULE_NAME."/".CONTROLLER_NAME."/".ACTION_NAME."_"));
		if(empty($menuid)){
			$menuid=$this->where(array('app'=>MODULE_NAME,'controller'=>CONTROLLER_NAME,'action'=>ACTION_NAME))->getfield('pid');
		}
		$field='id,action,app,controller,pid,parameter,type,ajax,width,height,name';
       	$info = $this->where(array("id" => $menuid))->getField($field);
		$find = $this->where(array("pid" => $menuid, "status" => 1))->getField($field);
        if ($find) {
            array_unshift($find, $info[$menuid]);
        } else {
            $find = $info;
        }
        foreach ($find as $k => $v) {
             $find[$k]['parameter'] = "menuid={$menuid}&open={$find[$k]['ajax']}&{$find[$k]['parameter']}";
        }
        return $find;
	}
} 