<?php
// +----------------------------------------------------------------------
// | Author: Ansel 3126620990@qq.com  绵阳人维网络科技有限公司
// +---------------------------------------------------------------------- 
namespace Common\Model;
class AddonsModel extends \Think\Model {
    //插件所处目录路径
    protected $addonsPath = NULL;
    protected function _initialize() {
        parent::_initialize();
        //插件目录
        $this->addonsPath = ANSEL_ADDONS;
    }
    /**
     * 获取插件列表
     */
    public function getList() {
        //取得模块目录名称
        $dirs = array_map('basename', glob($this->addonsPath . '*', GLOB_ONLYDIR));
        if ($dirs === FALSE || !file_exists($this->addonsPath)) {
            $this->error = '插件目录不可读或者不存在';
            return false;
        }
        $addons = array();
        if (!empty($dirs)) {
            //取得已安装插件列表
            $list = $this->where(array('name' => array('in', $dirs)))->select();
        } else {
            $list = array();
        }

        foreach ($list as $addon) {
            $addon['uninstall'] = 0;
            $addon['config'] = $addon['config'];
            $addons[$addon['name']] = $addon;
        }

        //获取插件列表
        foreach ($dirs as $value) {
            if(!isset($addons[$value])){
                $class = get_addon_class($value);
                if(!class_exists($class)){ // 实例化插件失败忽略执行
                    \Think\Log::record('插件'.$value.'的入口文件不存在！');
                    continue;
                }
                $obj=new $class;
                $addons[$value]	= $obj->info;
                if($addons[$value]){
                    $addons[$value]['uninstall'] = 1;
                    unset($addons[$value]['status']);
                }
            }
        }
        return $addons;
    }
    /**
     * 检查插件是否已经安装
     */
    public function isInstall($name) {
        if (empty($name)) {
            return false;
        }
        $count = $this->where(array('name' => $name))->count('id');
        return $count ? true : false;
    }

    /**
     * 获取插件目录
     */
    public function getAddonsPath() {
        return $this->addonsPath;
    }

    /**
     * 检查插件目录是否存在
     */
    public function exists($name) {
        return is_dir($this->addonsPath . $name) ? true : false;
    }

    /**
     * 删除对应插件菜单和权限
     */
    public function delAddonMenu($info) {
        if (empty($info)) {
            return false;
        }
        //获取插件后台菜单的id用作pid
        $menuId = M("Menu")->where(array("app" => "Admin", "controller" => "Addons", "action" => "adminmenu"))->getField('id');
        if (empty($menuId)) {
            return false;
        }
        //删除对应菜单
        $id=M("Menu")->where(array('app' => 'Admin', 'controller' => $info['name']))->delete();
        S('Admin_menu',null);
        return true;
    }

    /**
     * 添加插件后台管理菜单
     */
    public function addAddonMenu($info, $adminlist = NULL) {
        if (empty($info)) {
            return false;
        }
        //获取插件后台菜单的id用作pid
        $menuId = M('Menu')->where(array("app" => "Admin", "controller" => "Addons", "action" => "adminmenu"))->getField('id');
        if (empty($menuId)) {
            return false;
        }
        $data = array(
            //父ID
            "pid" => $menuId,
            //模块目录名称，也是项目名称
            "app" => "Admin",
            //插件名称
            "controller" => $info['name'],
            //方法名称
            "action" => "index",
            //附加参数 例:groupid=1&type=2 
            "parameter" => "",//"isadmin=1",
            //类型，1-权限控制  0-普通菜单
            "type" => 1,
            //状态，1是显示，0是不显示
            "status" => 1,
            //名称
            "name" => $info['title'],
            //备注
            "remark" => $info['title'] . "插件管理后台！",
            //排序
            "listorder" => 0,
        );
        //添加插件后台
        $pid = M("Menu")->add($data);
        if (!$pid) {
            return false;
        }
        //插件具体菜单
        if (!empty($adminlist)) {
            foreach ($adminlist as $key => $menu) {
                //检查参数是否存在
                if (empty($menu['name']) || empty($menu['action'])) {
                    continue;
                }
                //如果是index，跳过，因为已经有了。。。
                if ($menu['action'] == 'index') {
                    continue;
                }
                $data = array(
                    //父ID
                    "pid" => $pid,
                    //模块目录名称，也是项目名称
                    "app" => "Admin",
                    //插件标识
                    "controller" => $info['name'],
                    //方法名称
                    "action" => $menu['action'],
                    //附加参数 例:groupid=1&type=2
                    "parameter" => '',//'isadmin=1',
                    //类型，1-权限控制  0-普通菜单
                    "type" => $menu['type'] ? (int)$menu['type'] : 1,
                    //状态，1是显示，0是不显示
                    "status" => $menu['status'],
                    //名称
                    "name" => $menu['name'],
                    //备注
                    "remark" => $menu['remark'] ?: '',
                    //排序
                    "listorder" => $menu['listorder'],
                );
                M("Menu")->add($data);
            }
        }
        S('Admin_menu',null);
        return true;
    }
}
