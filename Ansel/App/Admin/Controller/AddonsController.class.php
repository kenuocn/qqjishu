<?php
// +----------------------------------------------------------------------
// | 插件管理
// +----------------------------------------------------------------------
// | Author: Ansel 3126620990@qq.com  绵阳人维网络科技有限公司
// +---------------------------------------------------------------------- 
namespace Admin\Controller;
use Common\Controller\AdminBase;
class AddonsController extends AdminBase {
    protected $addons = NULL;
    protected function _initialize() {
        parent::_initialize();
        $this->addons = D('Addons');
    }
    /**
     * @cc 插件管理
     */
    public function index() {
        $list = $this->addons->getList();
        if (!empty($list)) {
            //遍历检查是否有前台
            foreach ($list as $k => $v) {
                $path = $this->addons->getAddonsPath() . "{$v['name']}/Controller/IndexController.class.php";
                if (file_exists($path)) {
                    $list[$k]['url'] = U("Index/{$v['name']}/index");
                } else {
                    $list[$k]['url'] = '';
                }
            }
        }
		$nowpage = max(I('get.'.C('VAR_PAGE'), 0, 'intval'), 1);
		$this->assign('data',info_page($list,'20','','',$nowpage)); 
        $this->display();
    }
	/**
     * @cc 安装插件
     */
    public function install() {
        if(IS_GET){
			$addonName=trim(I('get.addon_name'));
			$class=get_addon_class($addonName);
			if(!class_exists($class)){
				$this->error('插件不存在');
			}
			//检查插件是否安装
			if ($this->addons->isInstall($addonName)) {
				$this->error('该插件已经安装，无需重复安装！');
			}
			$addonObj=new $class;
			//获取插件信息
			$info = $addonObj->info;
			if (empty($info)) {
				$this->error('插件信息获取失败！');
			}
			//安装插件
			$install_ret=$addonObj->install();
			if ($install_ret !== true) {
				if (method_exists($addonObj, 'getError')) {
					$this->error($addonObj->getError() ?: '执行插件预安装操作失败！');
				} else {
					$this->error('执行插件预安装操作失败！');
				}
				return false;
			}			
			$data=$this->addons->create($info);
			//如果插件有自己的后台
			if(is_array($addonObj->admin_menu) && $addonObj->admin_menu !== array()){
				$data['has_adminmenu'] = 1;
			}else{
				$data['has_adminmenu'] = 0;
			}
			if(!$data){
				$this->error($this->addons->getError());
			}
			$data['create_time']=time();//安装时间
			if($this->addons->add($data)){
				$config=array('config'=>serialize($addonObj->getConfig()));
				$this->addons->where(array('name'=>$addonName))->save($config);
				$hooks_update=D('Hooks')->updateHooks($addonName);
				if($hooks_update){
					S('hooks', null);
					//添加菜单
					if ($data['has_adminmenu']) {
						$admin_menu = $addonObj->admin_menu;
						$this->addons->addAddonMenu($data, $admin_menu);
					}
					$this->success('安装成功');
				}else{
					$this->addons->where(array('name'=>$addonName))->delete();
					$this->error('更新钩子处插件失败,请卸载后尝试重新安装');
				}
	
			}else{
				$this->error('写入插件数据失败');
			}
		}
    }
	/**
     * @cc 卸载插件
     */
	public function uninstall(){
		if(IS_GET){
			$id=I('get.id')?I('get.id'):$this->error('参数错误');
			$info=$this->addons->find($id);
			$class=get_addon_class($info['name']);
			if(!$info || !class_exists($class)){
				$this->error('插件不存在');
			}
			//插件标识
			$addonName = $info['name'];
			//检查插件是否安装
			if ($this->addons->isInstall($addonName) == false) {
				$this->error('该插件未安装，无需卸载！');
			}
			$addonObj=new $class;
			//卸载插件 
			$uninstall = $addonObj->uninstall();
			if ($uninstall !== true) {
				if (method_exists($addonObj, 'getError')) {
					$this->error($addonObj->getError() ? $addonObj->getError() : '执行插件预卸载操作失败！');
				} else {
					$this->error('执行插件预卸载操作失败！');
				}
				return false;
			}
			$hooks_update=D('Hooks')->removeHooks($addonName);
			if($hooks_update === false){
				$this->error('卸载插件所挂载的钩子数据失败');
			}
			S('hooks', null);
			$delete = $this->addons->where(array('name'=>$addonName))->delete();
			//删除插件后台菜单
            if ($info['has_adminmenu']) {
                $this->addons->delAddonMenu($info);
            }
			if($delete === false){
				$this->error('卸载插件失败');
			}else{
				$this->success('卸载成功');
			}
		}
    }
	/**
     * @cc 插件设置
     */
    public function config() {
        if (IS_POST) {
			$id=I('post.addons_id')?I('post.addons_id'):$this->error("参数错误");
			$config =I('post.');
			unset($config['addons_id']);
			if(M('Addons')->where(array('id'=>$id))->setField('config',serialize($config)) !== false){
				$this->success('保存成功');
			}else{
				$this->error('保存失败');
			}
        } else {
            //插件名称
            $addonId = I('get.id')?I('get.id'):$this->error('参数错误');
            if (empty($addonId)) {
                $this->error('请选择需要操作的插件！');
            }
            //获取插件信息
            $info = $this->addons->where(array('id' => $addonId))->find();
            if (empty($info)) {
                $this->error('该插件没有安装！');
            }
			$addon_class = get_addon_class($info['name']);
			if(!class_exists($addon_class)){
				$this->error("插件{$info['name']}无法实例化");
			}
			$data=new $addon_class;          
			$info['addon_path'] = $data->addonPath;
			$info['custom_config'] = $data->custom_config;
			$addon['config'] = include $data->configFile;
			$db_config = $info['config'];
			if($db_config){
				$db_config = unserialize($db_config);
				foreach ($addon['config'] as $key => $value) {
					if($value['type'] != 'group'){
						$addon['config'][$key]['value'] = $db_config[$key];
					}else{
						foreach ($value['options'] as $gourp => $options) {
							foreach ($options['options'] as $gkey => $value) {
								$addon['config'][$key]['options'][$gourp]['options'][$gkey]['value'] = $db_config[$gkey];
							}
						}
					}
				}
			}
			$infotitle='设置插件-'.$data->info['title'];
			$this->assign('infotitle',$infotitle);
			$this->assign('data',$addon);
			$this->assign('id',$addonId);
			//如果存在自定义配置模板就先调用自定义的
			if($addon['custom_config']){
				$this->assign('custom_config', $this->fetch($addon['addon_path'].$addon['custom_config']));
			}
			$this->display();
        }
    }
	/**
     * @cc 添加插件
     */ 
 	public function add_addons(){
		if(IS_POST){
			$this->_create_addons();
		}else{
			if(!is_writable(ANSEL_ADDONS)){
				$this->error('您没有创建目录写入权限，无法使用此功能');
			}
			$hooks = M('Hooks')->field('name,remark')->select();
			$this->assign('hooks',$hooks);
			$this->display(); 
		}
	} 
    /**
     * @cc 插件状态
     */ 
 	public function status_addon(){
		if(IS_GET){
			$status=I('get.status');
			$id=I('get.id')?I('get.id'):$this->error('参数错误');
			if(M('Addons')->where(array('id'=>$id))->setField('status',$status)){
				S('hooks', null);
				$this->success("状态更新成功");	
			}else{
				$this->error("状态更新失败");	
			}	
		}
	}

    /**
     * @cc 本地上传
     */
	public function local(){
		if (IS_POST) {
            if (!$_FILES['file']) {
                $this->error("请选择上传文件！");
            }
            //上传临时文件地址
            $filename = $_FILES['file']['tmp_name'];
            if (strtolower(substr($_FILES['file']['name'], -3, 3)) != 'zip') {
                $this->error("上传的文件格式有误！");
            }
            //插件目录
            $addonsDir = ANSEL_ADDONS;
            //检查插件目录是否存在
            if (!file_exists($addonsDir)) {
                //创建
                if (mkdir($addonsDir, 0777, true) == false) {
                    $this->error('插件目录' . $addonsDir . '创建失败！');
                }
            }
            //检查插件目录可写权限
            if (is_writeable($addonsDir) === false) {
                $this->error('插件目录' . $addonsDir . '不可写！');
            }
            //插件名称
            $addonName = pathinfo($_FILES['file']['name']);
            $addonName = $addonName['filename'];
            //检查插件目录是否存在
            if (file_exists($addonsDir . $addonName)) {
                $this->error('该插件目录已经存在！');
            }
            //检查必要信息
            $validate = array(
                array('name', 'require', '插件标识不能为空！', 1, 'regex', 3),
                array('name', '/^[a-zA-Z][a-zA-Z0-9_]+$/i', '插件标识只支持英文、数字、下划线！', 0, 'regex', 3),
                array('name', '', '该插件标识已经存在！', 0, 'unique', 1),
            );
            $data = array('name' => $addonName);
            $data =D('Addons')->validate($validate)->create($data, 1);
            if (!$data) {
                $this->error(D('Addons')->getError());
            }
            $zip = new \PclZip($filename);
            $status = $zip->extract(PCLZIP_OPT_PATH, $addonsDir . $addonName);
            if ($status) {
                $this->success('插件解压成功，可以进入插件管理进行安装！', U('Addons/index'));
            } else {
                $this->error('插件解压失败！');
            }
        } else {
            $this->display();
        }
	}
	/**
     * @cc 打包下载
     */
    public function unpack() {
        $addonName = I('get.addon_name');
        if (empty($addonName)) {
            $this->error('请选择需要打包的插件！');
        }
        //插件目录
        $addonsDir = ANSEL_ADDONS . "{$addonName}/";
		zippack($addonName,$addonsDir);exit;
    }
	/**
     * @cc 插件预览
     */
    public function preview_addons($output = true){
        $data=$_POST;
        $data['status'] =intval($data['status']);
		//强制插件标识首字母为大写
        $data['name'] = ucwords($data['name']);
        $extend=array();
        $custom_config=trim($data['custom_config']);
		//是否有自定义模板
        if($data['has_config'] && $custom_config){
            $custom_config = <<<PHP

	//自定义配置模板
	public \$custom_config = '{$custom_config}';
PHP;
		$extend[] = $custom_config;
	}

	if($data['has_adminmenu']){
		$admin_menu = <<<PHP
		
	//系统后台插件菜单
	public \$admin_menu = array(
		//增加菜单按数组添加
		array(
            //方法名称
            "action" => "index",
            //附加参数 例:groupid=1&type=2 
            "parameter" => "",
            //类型，1-权限控制  0-普通菜单
            "type" => 1,
            //状态，1是显示，0不显示
            "status" => 1,
            //名称
            "name" => "示例后台",
            //备注
            "remark" => "示例后台",
            //排序
            "listorder" => 0,
        ),
	);
PHP;
	   $extend[] = $admin_menu;
	}


	$extend = implode('', $extend);
	$hook = '';
	foreach ($data['hook'] as $value) {
		$hook .= <<<PHP
		
	//实现的{$value}钩子方法
	public function {$value}(\$param){

	}

PHP;
	}

	$tpl = <<<PHP
<?php
// +----------------------------------------------------------------------
// | Author: Ansel 3126620990@qq.com  绵阳人维网络科技有限公司
// +---------------------------------------------------------------------- 
namespace Addon\\{$data['name']};
use Common\Util\Addon;
/**
* {$data['title']}插件
* @author {$data['author']}
*/
class {$data['name']}Addon extends Addon{
	//插件信息
	public \$info = array(
		'name'=>'{$data['name']}',
		'title'=>'{$data['title']}',
		'description'=>'{$data['description']}',
		'status'=>{$data['status']},
		'author'=>'{$data['author']}',
		'version'=>'{$data['version']}'
	);{$extend}
	//插件安装
	public function install(){
		//可以设置安装时的事件
		return true;
	}
	//插件卸载
	public function uninstall(){
		//可以设置写在时的事件
		return true;
	}

{$hook}
}
PHP;
        if($output){
            exit($tpl);
		}else{
            return $tpl;
		}
    }
	/**
     * @cc 创建插件
     */
	private function _create_addons(){
		if(IS_POST){
			$name=I('post.name')?I('post.name'):$this->error("插件名称必须");
			$has_config=I('post.has_config');//是否有配置
			$custom_config=I('post.custom_config');//自定义配置模板
			$has_outurl=I('post.has_outurl');//是否有外部访问连接
			$has_adminmenu=I('post.has_adminmenu');//是否有后台菜单
			
			if(!preg_match("/^[a-z]*$/i",$name)){
				$this->error("插件名称只能是英文名称，并大写开头");
			}
			//转换成大写开头
			$name=ucfirst($name);
			
			//检测插件名是否存在
			if(file_exists(ANSEL_ADDONS.$name)){
				$this->error('插件已经存在了');
			}
			//获取创建的插件代码
			$addonFile=$this->preview_addons(false);	
			$addons_dir=ANSEL_ADDONS;
			//创建目录结构
			$files=array();
			$addon_dir=$addons_dir.$name."/";
			$files[]=$addon_dir;
			$addon_name=$name."Addon.class.php";
			$files[]=$addon_dir.$addon_name;
			//如果有则创建配置文件
			if($has_config == 1){ 
				$files[]=$addon_dir.'config.php';
			}
			//如果有外部访问就创建Index控制器和视图
			if($has_outurl){
				$files[]=$addon_dir."Controller/";
				$files[]=$addon_dir."Controller/IndexController.class.php";
				$files[]=$addon_dir."View/";
				$files[]=$addon_dir."View/Index/";
			}
			//如果有后台菜单
			if($has_adminmenu){
				$files[]=$addon_dir."Controller/";
				$files[]=$addon_dir."Controller/AdminController.class.php";
				$files[]=$addon_dir."View/";
				$files[]=$addon_dir."View/Admin/";
			}
			//创建文件
			create_files($files);
			//写文件
			file_put_contents($addon_dir.$addon_name, $addonFile);
			//如果有后台菜单
			if($has_adminmenu){
				//创建后台控制器
				$AdminAddonController = <<<PHP
<?php
// +----------------------------------------------------------------------
// | Author: Ansel 3126620990@qq.com  绵阳人维网络科技有限公司
// +---------------------------------------------------------------------- 
namespace Addon\\{$name}\Controller;
use Common\Util\Adminaddonbase;
class AdminController extends Adminaddonbase{
	/**
     * 后台首页
     */
	public function index(){
			
	}
}
	
PHP;
				file_put_contents($addon_dir."Controller/AdminController.class.php", $AdminAddonController);
$AdminAddonHtml=<<<PHP
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
				file_put_contents($addon_dir."View/Admin/index.html", $AdminAddonHtml);
			}
            
			//如果有外部访问
			if($has_outurl){	
				//创建前台控制器
				$IndexAddonController = <<<PHP
<?php
// +----------------------------------------------------------------------
// | Author: Ansel 3126620990@qq.com  绵阳人维网络科技有限公司
// +---------------------------------------------------------------------- 
namespace Addon\\{$name}\Controller;
use Common\Util\AddonsBase;
class IndexController extends AddonsBase{
	/**
     * 前台首页
     */
	public function index(){
			
	}
}
	
PHP;
				file_put_contents($addon_dir."Controller/IndexController.class.php", $IndexAddonController);
			}
			
			
			//如果有配置
			if($has_config == 1){
				file_put_contents($addon_dir."config.php",$_POST['config']);
			}
			$this->success('创建成功',U('Addons/index'));
		}
	}

}
