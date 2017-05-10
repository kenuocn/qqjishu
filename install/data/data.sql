-- phpMyAdmin SQL Dump
-- version phpStudy 2014
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2017 年 04 月 17 日 08:52
-- 服务器版本: 5.5.53
-- PHP 版本: 5.4.45

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `ansel`
--

-- --------------------------------------------------------

--
-- 表的结构 `an_addons`
--

CREATE TABLE IF NOT EXISTS `an_addons` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `name` varchar(40) NOT NULL COMMENT '插件名或标识',
  `title` varchar(20) NOT NULL DEFAULT '' COMMENT '中文名',
  `description` text COMMENT '插件描述',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态',
  `config` text COMMENT '配置',
  `author` varchar(40) DEFAULT '' COMMENT '作者',
  `version` varchar(20) DEFAULT '' COMMENT '版本号',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '安装时间',
  `has_adminmenu` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否有后台菜单',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='插件表' AUTO_INCREMENT=3 ;

--
-- 转存表中的数据 `an_addons`
--

INSERT INTO `an_addons` (`id`, `name`, `title`, `description`, `status`, `config`, `author`, `version`, `create_time`, `has_adminmenu`) VALUES
(1, 'Language', '系统多语言插件', '用于设置系统多语言插件', 1, 'N;', 'Ansel', '0.1', 1492390190, 0),
(2, 'SystemInfo', '系统环境信息', '用于显示一些服务器的信息', 1, 'a:3:{s:6:"title1";s:12:"系统信息";s:6:"title2";s:12:"版权信息";s:7:"display";s:1:"1";}', 'Ansel', '0.1', 1492390203, 0);

-- --------------------------------------------------------

--
-- 表的结构 `an_article`
--

CREATE TABLE IF NOT EXISTS `an_article` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `listorder` int(11) NOT NULL,
  `catid` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `keywords` varchar(200) NOT NULL,
  `description` varchar(200) NOT NULL,
  `tags` varchar(100) DEFAULT NULL,
  `thumb` varchar(300) NOT NULL,
  `content` text NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  `top` int(11) NOT NULL DEFAULT '0',
  `rec` int(11) NOT NULL DEFAULT '0',
  `views` int(11) NOT NULL DEFAULT '0',
  `comments` int(11) NOT NULL DEFAULT '0',
  `author` varchar(100) NOT NULL DEFAULT 'Ansel',
  `down` varchar(200) NOT NULL,
  `score` int(11) NOT NULL DEFAULT '0',
  `downinfo` varchar(200) NOT NULL,
  `time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=gbk COMMENT='文章表' AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `an_article`
--

INSERT INTO `an_article` (`id`, `uid`, `listorder`, `catid`, `title`, `keywords`, `description`, `tags`, `thumb`, `content`, `status`, `top`, `rec`, `views`, `comments`, `author`, `down`, `score`, `downinfo`, `time`) VALUES
(1, 1, 0, 1, '欢迎使用Ansel系统', '', '', '', '', '<p>欢迎使用Ansel系统</p>', 1, 1, 1, 0, 0, 'Ansel', '', 0, '', 1492167530);

-- --------------------------------------------------------

--
-- 表的结构 `an_attachment`
--

CREATE TABLE IF NOT EXISTS `an_attachment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `file_name` varchar(100) CHARACTER SET utf8mb4 NOT NULL,
  `file_extension` varchar(100) CHARACTER SET utf8mb4 NOT NULL,
  `file_size` int(11) NOT NULL,
  `file_path` varchar(300) CHARACTER SET utf8mb4 NOT NULL,
  `time` int(11) NOT NULL,
  `md5` varchar(100) CHARACTER SET utf8mb4 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='附件表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `an_auth_group`
--

CREATE TABLE IF NOT EXISTS `an_auth_group` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `title` char(100) NOT NULL DEFAULT '',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `rules` varchar(300) NOT NULL DEFAULT '',
  `menu_rules` varchar(100) NOT NULL COMMENT '菜单权限',
  `describe` char(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='角色分组' AUTO_INCREMENT=4 ;

--
-- 转存表中的数据 `an_auth_group`
--

INSERT INTO `an_auth_group` (`id`, `title`, `status`, `rules`, `menu_rules`, `describe`) VALUES
(1, '超级管理员', 1, '', '', '超级管理员'),
(2, '测试用户组', 1, '', '', '测试用户组');

-- --------------------------------------------------------

--
-- 表的结构 `an_auth_group_access`
--

CREATE TABLE IF NOT EXISTS `an_auth_group_access` (
  `uid` mediumint(8) unsigned NOT NULL,
  `group_id` mediumint(8) unsigned NOT NULL,
  UNIQUE KEY `uid_group_id` (`uid`,`group_id`),
  KEY `uid` (`uid`),
  KEY `group_id` (`group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='角色分组关系表';

--
-- 转存表中的数据 `an_auth_group_access`
--

INSERT INTO `an_auth_group_access` (`uid`, `group_id`) VALUES
(1, 1);

-- --------------------------------------------------------

--
-- 表的结构 `an_auth_rule`
--

CREATE TABLE IF NOT EXISTS `an_auth_rule` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` char(80) NOT NULL DEFAULT '',
  `title` char(20) NOT NULL DEFAULT '',
  `type` tinyint(1) NOT NULL DEFAULT '1',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `condition` char(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='权限规则表' AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `an_auth_rule`
--

INSERT INTO `an_auth_rule` (`id`, `name`, `title`, `type`, `status`, `condition`) VALUES
(1, 'showbtn', '测试自定义权限', 1, 1, '');

-- --------------------------------------------------------

--
-- 表的结构 `an_comment`
--

CREATE TABLE IF NOT EXISTS `an_comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pid` varchar(100) NOT NULL COMMENT '上级评论id',
  `type` int(11) NOT NULL DEFAULT '1' COMMENT '1-文章  2-单页',
  `type_id` int(11) NOT NULL COMMENT '内容id-文章或者单页id',
  `mid` varchar(100) NOT NULL COMMENT '评论人id',
  `info` text NOT NULL COMMENT '评论内容',
  `time` int(11) NOT NULL COMMENT '评论时间',
  `status` int(11) NOT NULL COMMENT '审核状态',
  `ip` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='评论' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `an_config`
--

CREATE TABLE IF NOT EXISTS `an_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `info` varchar(100) NOT NULL,
  `value` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='配置表' AUTO_INCREMENT=24 ;

--
-- 转存表中的数据 `an_config`
--

INSERT INTO `an_config` (`id`, `name`, `info`, `value`) VALUES
(1, 'web', '网站状态', '1'),
(2, 'webtip', '网站关闭提示', '站点升级中....'),
(3, 'Animation', '页面动画', 'fadeIn'),
(4, 'Operationlog', '记录操作日志', '1'),
(5, 'loginglog', '记录登陆日志', '1'),
(6, 'title', '站点名称', 'Ansel0.4'),
(7, 'keywords', '网站关键字', 'Ansel0.4'),
(8, 'content', '网站描述', 'Ansel0.4'),
(9, 'icp', '备案号', '蜀ICP备15013210号'),
(10, 'copyright', '版权信息', '© 2017 Ansel-博客'),
(11, 'author', '默认作者', 'Ansel'),
(12, 'article_copyright', '文章版权', '本文为Ansel原创文章,转载无需和我联系,但请注明来自Ansel博客www.95ansel.cc'),
(13, 'ThemePc', 'PC端模板', 'ansel'),
(14, 'ThemeMobile', '手机模板', 'default'),
(16, 'mail_type', '邮件发送模式', '1'),
(15, 'ismobile', '开启手机', '2'),
(17, 'mail_server', '邮件服务器', 'smtp.qq.com'),
(18, 'mail_port', '邮件发送端口', '465'),
(19, 'mail_from', '发件人地址', '3126620990@qq.com'),
(20, 'mail_auth', '密码验证', '1'),
(21, 'mail_user', '邮箱用户名', '3126620990@qq.com'),
(22, 'mail_password', '邮箱密码', '123123'),
(23, 'mail_fname', '发件人名称', 'Ansel');

-- --------------------------------------------------------

--
-- 表的结构 `an_group`
--

CREATE TABLE IF NOT EXISTS `an_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL COMMENT '分组名',
  `remark` varchar(100) NOT NULL COMMENT '描述',
  `status` int(11) NOT NULL COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='会员分组' AUTO_INCREMENT=3 ;

--
-- 转存表中的数据 `an_group`
--

INSERT INTO `an_group` (`id`, `name`, `remark`, `status`) VALUES
(1, '普通会员', '普通会员', 1),
(2, '高级会员', '高级会员', 1);

-- --------------------------------------------------------

--
-- 表的结构 `an_hooks`
--

CREATE TABLE IF NOT EXISTS `an_hooks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `remark` varchar(100) NOT NULL,
  `type` int(11) NOT NULL DEFAULT '1',
  `addons` varchar(300) NOT NULL COMMENT '钩子挂载的插件',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='钩子' AUTO_INCREMENT=3 ;

--
-- 转存表中的数据 `an_hooks`
--

INSERT INTO `an_hooks` (`id`, `name`, `remark`, `type`, `addons`) VALUES
(1, 'Admintopmenu', '系统后台顶部右边菜单', 1, 'Language'),
(2, 'AdminIndex', '后台首页系统信息显示', 1, 'SystemInfo');

-- --------------------------------------------------------

--
-- 表的结构 `an_loginlog`
--

CREATE TABLE IF NOT EXISTS `an_loginlog` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '日志ID',
  `username` char(30) NOT NULL DEFAULT '' COMMENT '登录帐号',
  `logintime` int(10) NOT NULL DEFAULT '0' COMMENT '登录时间戳',
  `loginip` char(20) NOT NULL DEFAULT '' COMMENT '登录IP',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态,1为登录成功，0为登录失败',
  `password` varchar(30) NOT NULL DEFAULT '' COMMENT '尝试错误密码',
  `info` varchar(255) NOT NULL DEFAULT '' COMMENT '其他说明',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='后台登陆日志表' AUTO_INCREMENT=3 ;


-- --------------------------------------------------------

--
-- 表的结构 `an_member`
--

CREATE TABLE IF NOT EXISTS `an_member` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `gid` int(11) NOT NULL DEFAULT '1' COMMENT '分组id',
  `fid` varchar(100) NOT NULL COMMENT '好友id数组',
  `username` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `sign` varchar(100) NOT NULL COMMENT '签名',
  `avatar` varchar(100) NOT NULL DEFAULT '/statics/images/demo.jpg' COMMENT '头像',
  `email` varchar(100) NOT NULL,
  ` level` int(11) NOT NULL DEFAULT '1',
  `score` int(11) NOT NULL DEFAULT '0',
  `regtime` int(11) NOT NULL,
  `lasttime` int(11) NOT NULL,
  `lastip` varchar(100) NOT NULL,
  `email_test` int(11) NOT NULL COMMENT '0-未验证 1-已验证',
  `status` int(11) NOT NULL DEFAULT '1' COMMENT '0-禁用  1-启用',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk COMMENT='会员表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `an_menu`
--

CREATE TABLE IF NOT EXISTS `an_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `app` varchar(100) NOT NULL,
  `controller` varchar(100) NOT NULL,
  `action` varchar(100) NOT NULL,
  `parameter` varchar(100) NOT NULL,
  `remark` varchar(200) NOT NULL,
  `type` int(11) NOT NULL DEFAULT '1',
  `ajax` int(11) NOT NULL DEFAULT '0',
  `width` int(11) NOT NULL COMMENT '弹窗打开的宽度',
  `height` int(11) NOT NULL COMMENT '弹窗打开的高度',
  `fonts` varchar(100) NOT NULL,
  `listorder` int(11) NOT NULL DEFAULT '0',
  `status` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=gbk COMMENT='后台菜单' AUTO_INCREMENT=108 ;

--
-- 转存表中的数据 `an_menu`
--

INSERT INTO `an_menu` (`id`, `pid`, `name`, `app`, `controller`, `action`, `parameter`, `remark`, `type`, `ajax`, `width`, `height`, `fonts`, `listorder`, `status`) VALUES
(1, 0, '系统设置', 'Admin', 'Config', 'index', '', '系统设置', 1, 0, 0, 0, 'gears', 0, 1),
(2, 1, '系统菜单', 'Admin', 'Menu', 'index', '', '系统菜单', 1, 0, 0, 0, 'desktop', 0, 1),
(3, 2, '添加菜单', 'Admin', 'Menu', 'add', '', '添加菜单', 1, 0, 600, 500, 'desktop', 0, 1),
(4, 2, '编辑菜单', 'Admin', 'Menu', 'edit', '', '编辑菜单', 1, 0, 0, 0, 'desktop', 0, 0),
(5, 2, '删除菜单', 'Admin', 'Menu', 'del', '', '删除菜单', 1, 0, 0, 0, 'desktop', 0, 0),
(6, 2, '菜单状态', 'Admin', 'Menu', 'status', '', '菜单状态', 1, 0, 0, 0, 'desktop', 0, 0),
(7, 2, '菜单类型', 'Admin', 'Menu', 'type', '', '菜单类型', 1, 0, 0, 0, 'desktop', 0, 0),
(8, 1, '系统配置', 'Admin', 'Config', 'site', '', '系统配置', 1, 0, 0, 0, 'desktop', 0, 1),
(9, 1, '用户列表', 'Admin', 'User', 'index', '', '用户列表', 1, 0, 0, 0, 'desktop', 0, 1),
(10, 9, '添加用户', 'Admin', 'User', 'add', '', '添加用户', 1, 0, 0, 0, 'desktop', 0, 1),
(11, 9, '编辑用户', 'Admin', 'User', 'edit', '', '编辑用户', 1, 0, 0, 0, 'desktop', 0, 0),
(12, 9, '删除用户', 'Admin', 'User', 'del', '', '删除用户', 1, 0, 0, 0, 'desktop', 0, 0),
(13, 9, '用户状态', 'Admin', 'User', 'status', '', '用户状态', 1, 0, 0, 0, 'desktop', 0, 0),
(14, 1, '用户角色', 'Admin', 'Group', 'index', '', '用户角色', 1, 0, 0, 0, 'desktop', 0, 1),
(15, 14, '添加角色', 'Admin', 'Group', 'add', '', '添加角色', 1, 0, 0, 0, 'desktop', 0, 1),
(16, 14, '编辑角色', 'Admin', 'Group', 'edit', '', '编辑角色', 1, 0, 0, 0, 'desktop', 0, 0),
(17, 14, '删除角色', 'Admin', 'Group', 'del', '', '删除角色', 1, 0, 0, 0, 'desktop', 0, 0),
(18, 14, '角色状态', 'Admin', 'Group', 'status', '', '角色状态', 1, 0, 0, 0, 'desktop', 0, 0),
(19, 1, '操作日志', 'Admin', 'System', 'operation', '', '操作日志', 1, 0, 0, 0, 'desktop', 0, 1),
(20, 19, '删除30天操作日志', 'Admin', 'System', 'operation_del', '', '删除30天操作日志', 1, 1, 0, 0, 'desktop', 0, 1),
(21, 1, '登陆日志', 'Admin', 'System', 'loginlog', '', '登陆日志', 1, 0, 0, 0, 'desktop', 0, 1),
(22, 21, '删除30天登陆日志', 'Admin', 'System', 'loginlog_del', '', '删除30天登陆日志', 1, 1, 0, 0, 'desktop', 0, 1),
(23, 14, '权限设置', 'Admin', 'Group', 'auth', '', '权限设置', 1, 1, 0, 0, 'desktop', 0, 0),
(25, 1, '权限管理', 'Admin', 'Auth', 'index', '', '权限管理', 1, 0, 0, 0, 'desktop', 0, 1),
(26, 25, '添加权限', 'Admin', 'Auth', 'add', '', '添加权限', 1, 0, 0, 0, 'desktop', 0, 1),
(27, 25, '编辑权限', 'Admin', 'Auth', 'edit', '', '编辑权限', 1, 0, 0, 0, 'desktop', 0, 0),
(28, 25, '删除权限', 'Admin', 'Auth', 'del', '', '删除权限', 1, 0, 0, 0, 'desktop', 0, 0),
(29, 25, '权限状态', 'Admin', 'Auth', 'status', '', '权限状态', 1, 0, 0, 0, 'desktop', 0, 0),
(59, 0, '模板管理', 'Admin', 'Template', 'content', '', '模板管理', 1, 0, 0, 0, 'desktop', 0, 1),
(58, 57, '删除标签', 'Admin', 'Tag', 'del', '', '删除标签', 1, 1, 0, 0, 'desktop', 0, 0),
(57, 45, '标签管理', 'Admin', 'Tag', 'index', '', '标签管理', 1, 0, 0, 0, 'desktop', 0, 1),
(56, 52, '文章状态', 'Admin', 'Content', 'status_article', '', '文章状态', 1, 0, 0, 0, 'desktop', 0, 0),
(55, 52, '删除文章', 'Admin', 'Content', 'del_article', '', '删除文章', 1, 0, 0, 0, 'desktop', 0, 0),
(52, 45, '文章管理', 'Admin', 'Content', 'article', '', '文章管理', 1, 0, 0, 0, 'desktop', 0, 1),
(53, 52, '添加文章', 'Admin', 'Content', 'add_article', '', '添加文章', 1, 0, 0, 0, 'desktop', 0, 1),
(60, 59, '电脑端模板', 'Admin', 'Template', 'temp_pc', '', '电脑端模板', 1, 0, 0, 0, 'desktop', 0, 1),
(61, 59, '手机模板', 'Admin', 'Template', 'temp_mobile', '', '手机模板', 1, 0, 0, 0, 'desktop', 0, 1),
(62, 59, '切换模板', 'Admin', 'Template', 'temp_switch', '', '切换模板', 1, 1, 0, 0, 'desktop', 0, 0),
(63, 59, '删除模板', 'Admin', 'Template', 'temp_del', '', '删除模板', 1, 0, 0, 0, 'desktop', 0, 0),
(54, 52, '编辑文章', 'Admin', 'Content', 'edit_article', '', '编辑文章', 1, 0, 0, 0, 'desktop', 0, 0),
(45, 0, '内容管理', 'Admin', 'Content', 'index', '', '内容管理', 1, 0, 0, 0, 'book', 0, 1),
(46, 45, '分类管理', 'Admin', 'Content', 'sort', '', '分类管理', 1, 0, 0, 0, 'desktop', 0, 1),
(47, 46, '添加分类', 'Admin', 'Content', 'add_sort', '', '添加分类', 1, 0, 0, 0, 'desktop', 0, 1),
(48, 46, '编辑分类', 'Admin', 'Content', 'edit_sort', '', '编辑分类', 1, 0, 0, 0, 'desktop', 0, 0),
(49, 46, '删除分类', 'Admin', 'Content', 'del_sort', '', '删除分类', 1, 0, 0, 0, 'desktop', 0, 0),
(50, 46, '分类类型', 'Admin', 'Content', 'type_sort', '', '分类类型', 1, 0, 0, 0, 'desktop', 0, 0),
(51, 46, '分类状态', 'Admin', 'Content', 'status_sort', '', '分类状态', 1, 0, 0, 0, 'desktop', 0, 0),
(65, 0, '会员管理', 'Admin', 'Member', 'content', '', '会员管理', 1, 0, 0, 0, 'users', 0, 1),
(64, 59, '上传模板', 'Admin', 'Template', 'temp_upload', '', '上传模板', 1, 0, 0, 0, 'desktop', 0, 1),
(66, 65, '会员列表', 'Admin', 'Member', 'index', '', '会员列表', 1, 0, 0, 0, 'desktop', 0, 1),
(67, 66, '添加会员', 'Admin', 'Member', 'add_member', '', '添加会员', 1, 2, 700, 400, 'desktop', 0, 1),
(68, 66, '编辑会员', 'Admin', 'Member', 'edit_member', '', '编辑会员', 1, 0, 0, 0, 'desktop', 0, 0),
(69, 66, '删除会员', 'Admin', 'Member', 'del_member', '', '删除会员', 1, 0, 0, 0, 'desktop', 0, 0),
(70, 66, '会员状态', 'Admin', 'Member', 'status_member', '', '会员状态', 1, 0, 0, 0, 'desktop', 0, 0),
(71, 59, '模板设置', 'Admin', 'Template', 'setting', '', '模板设置', 1, 0, 0, 0, 'desktop', 0, 0),
(72, 8, 'URL设置', 'Admin', 'Config', 'url', '', 'URL设置', 1, 0, 0, 0, 'desktop', 0, 1),
(73, 0, '插件管理', 'Admin', 'Addons', 'content', '', '插件管理', 1, 0, 0, 0, 'plug', 0, 1),
(74, 73, '插件列表', 'Admin', 'Addons', 'index', '', '插件列表', 1, 0, 0, 0, 'desktop', 0, 1),
(75, 74, '添加插件', 'Admin', 'Addons', 'add_addons', '', '添加插件', 1, 0, 0, 0, 'desktop', 0, 1),
(76, 65, '会员分组', 'Admin', 'Member', 'group', '', '会员分组', 1, 0, 0, 0, 'desktop', 0, 1),
(77, 76, '添加会员组', 'Admin', 'Member', 'add_group', '', '添加会员组', 1, 2, 700, 400, 'desktop', 0, 1),
(78, 76, '编辑会员组', 'Admin', 'Member', 'edit_group', '', '编辑会员组', 1, 0, 700, 0, 'desktop', 0, 0),
(79, 76, '删除会员组', 'Admin', 'Member', 'del_group', '', '删除会员组', 1, 0, 0, 0, 'desktop', 0, 0),
(80, 76, '会员组状态', 'Admin', 'Member', 'status_group', '', '会员组状态', 1, 0, 0, 0, 'desktop', 0, 0),
(81, 1, '更新缓存', 'Admin', 'Index', 'cache', '', '更新缓存', 1, 0, 0, 0, 'desktop', 0, 0),
(82, 8, '高级设置', 'Admin', 'Config', 'addition', '', '高级设置', 1, 0, 0, 0, 'desktop', 0, 1),
(83, 1, '文件管理', 'Admin', 'File', 'index', '', '文件管理', 1, 0, 0, 0, 'desktop', 0, 1),
(84, 83, '编辑文件', 'Admin', 'File', 'edit', '', '编辑文件', 1, 0, 0, 0, 'desktop', 0, 0),
(85, 66, '修改会员密码', 'Admin', 'Member', 'editpwd', '', '修改会员密码', 1, 0, 0, 0, 'desktop', 0, 0),
(86, 73, '钩子管理', 'Admin', 'Hooks', 'hooks', '', '钩子管理', 1, 0, 0, 0, 'desktop', 0, 1),
(87, 86, '添加钩子', 'Admin', 'Hooks', 'add_hooks', '', '添加钩子', 1, 0, 0, 0, 'desktop', 0, 1),
(88, 86, '编辑钩子', 'Admin', 'Hooks', 'edit_hooks', '', '编辑钩子', 1, 0, 0, 0, 'desktop', 0, 0),
(89, 86, '删除钩子', 'Admin', 'Hooks', 'del_hooks', '', '删除钩子', 1, 0, 0, 0, 'desktop', 0, 0),
(90, 74, '插件状态', 'Admin', 'Addons', 'status_addon', '', '插件状态', 1, 0, 0, 0, 'desktop', 0, 0),
(91, 74, '安装插件', 'Admin', 'Addons', 'install', '', '安装插件', 1, 0, 0, 0, 'desktop', 0, 0),
(92, 74, '卸载插件', 'Admin', 'Addons', 'uninstall', '', '卸载插件', 1, 0, 0, 0, 'desktop', 0, 0),
(93, 74, '插件设置', 'Admin', 'Addons', 'config', '', '插件设置', 1, 0, 0, 0, 'desktop', 0, 0),
(94, 0, '插件后台', 'Admin', 'Addons', 'adminmenu', '', '插件后台菜单', 1, 0, 0, 0, 'desktop', 0, 1),
(95, 74, '插件打包', 'Admin', 'Addons', 'unpack', '', '插件打包', 1, 0, 0, 0, 'desktop', 0, 0),
(96, 74, '本地上传', 'Admin', 'Addons', 'local', '', '本地上传', 1, 0, 0, 0, 'desktop', 0, 1),
(97, 75, '添加预览', 'Admin', 'Addons', 'preview_addons', '', '添加预览', 1, 0, 0, 0, 'desktop', 0, 0),
(98, 1, '插件访问入口', 'Admin', 'Index', 'addon', '', '插件外部访问入口', 1, 0, 0, 0, 'desktop', 0, 0);

-- --------------------------------------------------------

--
-- 表的结构 `an_message`
--

CREATE TABLE IF NOT EXISTS `an_message` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mine_id` int(11) NOT NULL COMMENT '发送消息的id',
  `to_id` int(11) NOT NULL COMMENT '接收消息的用户id',
  `content` text NOT NULL COMMENT '消息内容',
  `status` int(11) NOT NULL COMMENT '0-已接受到  1-未接受',
  `time` int(11) NOT NULL COMMENT '发送时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='消息内容表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `an_operationlog`
--

CREATE TABLE IF NOT EXISTS `an_operationlog` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '日志ID',
  `uid` smallint(6) NOT NULL DEFAULT '0' COMMENT '操作帐号ID',
  `time` int(10) NOT NULL DEFAULT '0' COMMENT '操作时间',
  `ip` char(20) NOT NULL DEFAULT '' COMMENT 'IP',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态,0错误提示，1为正确提示',
  `info` text COMMENT '其他说明',
  `get` varchar(255) DEFAULT NULL COMMENT 'get数据',
  PRIMARY KEY (`id`),
  KEY `status` (`status`),
  KEY `username` (`uid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='后台操作日志表' AUTO_INCREMENT=18 ;

-- --------------------------------------------------------

--
-- 表的结构 `an_problem`
--

CREATE TABLE IF NOT EXISTS `an_problem` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mid` int(11) NOT NULL COMMENT '会员id',
  `type` int(11) NOT NULL DEFAULT '1' COMMENT '1-bug 2-建议',
  `title` varchar(100) NOT NULL COMMENT '标题',
  `info` text NOT NULL COMMENT '内容',
  `status` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='bug和建议' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `an_sort`
--

CREATE TABLE IF NOT EXISTS `an_sort` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(11) NOT NULL,
  `listorder` int(11) NOT NULL,
  `catname` varchar(100) NOT NULL,
  `catdir` varchar(100) NOT NULL,
  `description` varchar(200) DEFAULT NULL,
  `thumb` varchar(100) DEFAULT NULL,
  `font` varchar(100) DEFAULT 'desktop',
  `list_tmp` varchar(100) NOT NULL DEFAULT 'lists' COMMENT '模板',
  `show_tmp` varchar(100) NOT NULL DEFAULT 'show',
  `page_tmp` varchar(100) NOT NULL DEFAULT 'page',
  `islink` varchar(100) DEFAULT NULL,
  `type` int(11) NOT NULL COMMENT '0-分类  1-单页',
  `content` text,
  `status` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='文章分类' AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `an_sort`
--

INSERT INTO `an_sort` (`id`, `pid`, `listorder`, `catname`, `catdir`, `description`, `thumb`, `font`, `list_tmp`, `show_tmp`, `page_tmp`, `islink`, `type`, `content`, `status`) VALUES
(1, 0, 0, '默认分类', 'default', '默认分类', '', '', 'list', 'show', 'page', '', 0, '', 1);

-- --------------------------------------------------------

--
-- 表的结构 `an_tag`
--

CREATE TABLE IF NOT EXISTS `an_tag` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tag` varchar(100) NOT NULL,
  `aid` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='标签表' AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `an_tag`
--

INSERT INTO `an_tag` (`id`, `tag`, `aid`, `title`) VALUES
(1, '', 1, '欢迎使用Ansel系统');

-- --------------------------------------------------------

--
-- 表的结构 `an_theme_set`
--

CREATE TABLE IF NOT EXISTS `an_theme_set` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(11) NOT NULL,
  `name` varchar(100) NOT NULL COMMENT '模板名称',
  `config` text NOT NULL COMMENT '模板配置',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='模板设置' AUTO_INCREMENT=3 ;

--
-- 转存表中的数据 `an_theme_set`
--

INSERT INTO `an_theme_set` (`id`, `type`, `name`, `config`) VALUES
(2, 'pc', 'Ansel', 'a:14:{s:4:"logo";s:34:"/Template/pc/ansel/images/logo.png";s:3:"ewm";s:31:"/d/image/2017/5890877a98967.jpg";s:6:"addimg";s:31:"/d/image/2017/5889f143ca082.png";s:6:"addurl";s:33:"http://www.95ansel.cc/show/1.html";s:7:"adtitle";s:17:"Ansel系统下载";s:6:"adinfo";s:382:"前端H+结合Thinkphp 3.2开发，后台集成权限管理、系统菜单管理、菜单自动生成控制器及方法、模板管理、模板配置、文章管理、标签管理、文章分类，前台集成会员中心、Layim及时通讯..等功能\r\n系统下载请移步本群：\r\n\r\n<a target="_blank" href="https://jq.qq.com/?_wv=1027&k=44S1Qvp">点击加群：Ansel系统</a>";s:5:"adurl";s:33:"http://www.95ansel.cc/show/1.html";s:5:"share";s:1289:"<a class="bds_qzone" data-cmd="qzone" title="分享到QQ空间"></a> \r\n<a class="bds_tsina" data-cmd="tsina" title="分享到新浪微博"></a> \r\n<a class="bds_weixin" data-cmd="weixin" title="分享到微信"></a> \r\n<a class="bds_tqq" data-cmd="tqq" title="分享到腾讯微博"></a> \r\n<a class="bds_sqq" data-cmd="sqq" title="分享到QQ好友"></a> \r\n<a class="bds_bdhome" data-cmd="bdhome" title="分享到百度新首页"></a> \r\n<a class="bds_tqf" data-cmd="tqf" title="分享到腾讯朋友"></a> \r\n<a class="bds_renren" data-cmd="renren" title="分享到人人网"></a> \r\n<a class="bds_diandian" data-cmd="diandian" title="分享到点点网"></a> \r\n<a class="bds_youdao" data-cmd="youdao" title="分享到有道云笔记"></a> \r\n<a class="bds_ty" data-cmd="ty" title="分享到天涯社区"></a> \r\n<a class="bds_kaixin001" data-cmd="kaixin001" title="分享到开心网"></a> \r\n<a class="bds_taobao" data-cmd="taobao"></a> \r\n<a class="bds_douban" data-cmd="douban" title="分享到豆瓣网"></a> \r\n<a class="bds_fbook" data-cmd="fbook" title="分享到Facebook"></a> \r\n<a class="bds_twi" data-cmd="twi" title="分享到Twitter"></a> \r\n<a class="bds_mail" data-cmd="mail" title="分享到邮件分享"></a> \r\n<a class="bds_copy" data-cmd="copy" title="分享到复制网址"></a> ";s:6:"slider";s:3:"off";s:5:"ceshi";a:2:{i:0;s:1:"1";i:1;s:1:"4";}s:6:"select";s:1:"3";s:5:"about";s:21:"<p>撒地方<br/></p>";s:5:"theme";s:5:"Ansel";s:4:"type";s:2:"pc";}');

-- --------------------------------------------------------

--
-- 表的结构 `an_user`
--

CREATE TABLE IF NOT EXISTS `an_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL COMMENT '用户名',
  `password` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL COMMENT '姓名',
  `avatar` varchar(100) DEFAULT NULL,
  `email` varchar(100) NOT NULL COMMENT '邮箱',
  `remark` varchar(300) NOT NULL,
  `last_ip` varchar(100) NOT NULL COMMENT '上次登录ip',
  `last_time` int(11) NOT NULL DEFAULT '0' COMMENT '上次登录时间',
  `status` int(11) NOT NULL DEFAULT '1' COMMENT '状态',
  `online` int(11) NOT NULL DEFAULT '0',
  `session_id` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='用户表' AUTO_INCREMENT=2 ;


/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
