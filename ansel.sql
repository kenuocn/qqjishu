/*
Navicat MySQL Data Transfer

Source Server         : 虫洞翻翻
Source Server Version : 50527
Source Host           : localhost:3306
Source Database       : ansel

Target Server Type    : MYSQL
Target Server Version : 50527
File Encoding         : 65001

Date: 2017-05-07 13:26:29
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for an_addons
-- ----------------------------
DROP TABLE IF EXISTS `an_addons`;
CREATE TABLE `an_addons` (
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
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='插件表';

-- ----------------------------
-- Table structure for an_article
-- ----------------------------
DROP TABLE IF EXISTS `an_article`;
CREATE TABLE `an_article` (
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
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=gbk COMMENT='文章表';

-- ----------------------------
-- Table structure for an_attachment
-- ----------------------------
DROP TABLE IF EXISTS `an_attachment`;
CREATE TABLE `an_attachment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `file_name` varchar(100) CHARACTER SET utf8mb4 NOT NULL,
  `file_extension` varchar(100) CHARACTER SET utf8mb4 NOT NULL,
  `file_size` int(11) NOT NULL,
  `file_path` varchar(300) CHARACTER SET utf8mb4 NOT NULL,
  `time` int(11) NOT NULL,
  `md5` varchar(100) CHARACTER SET utf8mb4 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='附件表';

-- ----------------------------
-- Table structure for an_auth_group
-- ----------------------------
DROP TABLE IF EXISTS `an_auth_group`;
CREATE TABLE `an_auth_group` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `title` char(100) NOT NULL DEFAULT '',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `rules` varchar(300) NOT NULL DEFAULT '',
  `menu_rules` varchar(100) NOT NULL COMMENT '菜单权限',
  `describe` char(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='角色分组';

-- ----------------------------
-- Table structure for an_auth_group_access
-- ----------------------------
DROP TABLE IF EXISTS `an_auth_group_access`;
CREATE TABLE `an_auth_group_access` (
  `uid` mediumint(8) unsigned NOT NULL,
  `group_id` mediumint(8) unsigned NOT NULL,
  UNIQUE KEY `uid_group_id` (`uid`,`group_id`),
  KEY `uid` (`uid`),
  KEY `group_id` (`group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='角色分组关系表';

-- ----------------------------
-- Table structure for an_auth_rule
-- ----------------------------
DROP TABLE IF EXISTS `an_auth_rule`;
CREATE TABLE `an_auth_rule` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` char(80) NOT NULL DEFAULT '',
  `title` char(20) NOT NULL DEFAULT '',
  `type` tinyint(1) NOT NULL DEFAULT '1',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `condition` char(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='权限规则表';

-- ----------------------------
-- Table structure for an_comment
-- ----------------------------
DROP TABLE IF EXISTS `an_comment`;
CREATE TABLE `an_comment` (
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='评论';

-- ----------------------------
-- Table structure for an_config
-- ----------------------------
DROP TABLE IF EXISTS `an_config`;
CREATE TABLE `an_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `info` varchar(100) NOT NULL,
  `value` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=24 DEFAULT CHARSET=utf8 COMMENT='配置表';

-- ----------------------------
-- Table structure for an_group
-- ----------------------------
DROP TABLE IF EXISTS `an_group`;
CREATE TABLE `an_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL COMMENT '分组名',
  `remark` varchar(100) NOT NULL COMMENT '描述',
  `status` int(11) NOT NULL COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='会员分组';

-- ----------------------------
-- Table structure for an_hooks
-- ----------------------------
DROP TABLE IF EXISTS `an_hooks`;
CREATE TABLE `an_hooks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `remark` varchar(100) NOT NULL,
  `type` int(11) NOT NULL DEFAULT '1',
  `addons` varchar(300) NOT NULL COMMENT '钩子挂载的插件',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='钩子';

-- ----------------------------
-- Table structure for an_loginlog
-- ----------------------------
DROP TABLE IF EXISTS `an_loginlog`;
CREATE TABLE `an_loginlog` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '日志ID',
  `username` char(30) NOT NULL DEFAULT '' COMMENT '登录帐号',
  `logintime` int(10) NOT NULL DEFAULT '0' COMMENT '登录时间戳',
  `loginip` char(20) NOT NULL DEFAULT '' COMMENT '登录IP',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态,1为登录成功，0为登录失败',
  `password` varchar(30) NOT NULL DEFAULT '' COMMENT '尝试错误密码',
  `info` varchar(255) NOT NULL DEFAULT '' COMMENT '其他说明',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='后台登陆日志表';

-- ----------------------------
-- Table structure for an_member
-- ----------------------------
DROP TABLE IF EXISTS `an_member`;
CREATE TABLE `an_member` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `gid` int(11) NOT NULL DEFAULT '1' COMMENT '分组id',
  `fid` varchar(100) NOT NULL COMMENT '好友id数组',
  `username` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `sign` varchar(100) NOT NULL COMMENT '签名',
  `avatar` varchar(100) NOT NULL DEFAULT '/statics/images/demo.jpg' COMMENT '头像',
  `qq` varchar(60) NOT NULL COMMENT 'QQ号',
  `email` varchar(100) NOT NULL,
  ` level` int(11) NOT NULL DEFAULT '1',
  `score` int(11) NOT NULL DEFAULT '0',
  `regtime` int(11) NOT NULL,
  `lasttime` int(11) NOT NULL,
  `lastip` varchar(100) NOT NULL,
  `email_test` int(11) NOT NULL COMMENT '0-未验证 1-已验证',
  `status` int(11) NOT NULL DEFAULT '1' COMMENT '0-禁用  1-启用',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=gbk COMMENT='会员表';

-- ----------------------------
-- Table structure for an_menu
-- ----------------------------
DROP TABLE IF EXISTS `an_menu`;
CREATE TABLE `an_menu` (
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
) ENGINE=MyISAM AUTO_INCREMENT=108 DEFAULT CHARSET=gbk COMMENT='后台菜单';

-- ----------------------------
-- Table structure for an_message
-- ----------------------------
DROP TABLE IF EXISTS `an_message`;
CREATE TABLE `an_message` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mine_id` int(11) NOT NULL COMMENT '发送消息的id',
  `to_id` int(11) NOT NULL COMMENT '接收消息的用户id',
  `content` text NOT NULL COMMENT '消息内容',
  `status` int(11) NOT NULL COMMENT '0-已接受到  1-未接受',
  `time` int(11) NOT NULL COMMENT '发送时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='消息内容表';

-- ----------------------------
-- Table structure for an_operationlog
-- ----------------------------
DROP TABLE IF EXISTS `an_operationlog`;
CREATE TABLE `an_operationlog` (
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
) ENGINE=MyISAM AUTO_INCREMENT=81 DEFAULT CHARSET=utf8 COMMENT='后台操作日志表';

-- ----------------------------
-- Table structure for an_problem
-- ----------------------------
DROP TABLE IF EXISTS `an_problem`;
CREATE TABLE `an_problem` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mid` int(11) NOT NULL COMMENT '会员id',
  `type` int(11) NOT NULL DEFAULT '1' COMMENT '1-bug 2-建议',
  `title` varchar(100) NOT NULL COMMENT '标题',
  `info` text NOT NULL COMMENT '内容',
  `status` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='bug和建议';

-- ----------------------------
-- Table structure for an_sort
-- ----------------------------
DROP TABLE IF EXISTS `an_sort`;
CREATE TABLE `an_sort` (
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
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='文章分类';

-- ----------------------------
-- Table structure for an_tag
-- ----------------------------
DROP TABLE IF EXISTS `an_tag`;
CREATE TABLE `an_tag` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tag` varchar(100) NOT NULL,
  `aid` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='标签表';

-- ----------------------------
-- Table structure for an_theme_set
-- ----------------------------
DROP TABLE IF EXISTS `an_theme_set`;
CREATE TABLE `an_theme_set` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(11) NOT NULL,
  `name` varchar(100) NOT NULL COMMENT '模板名称',
  `config` text NOT NULL COMMENT '模板配置',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='模板设置';

-- ----------------------------
-- Table structure for an_user
-- ----------------------------
DROP TABLE IF EXISTS `an_user`;
CREATE TABLE `an_user` (
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
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='用户表';


create table `an_order`
(
  `id` mediumint unsigned not null auto_increment comment 'id',
  `member_id` mediumint unsigned not null comment '会员ID',
  `add_time` varchar(64) not null default '' comment '下单时间',
  `pay_status` enum('1','0') not null default '0' comment '支付状态',
  `pay_time` varchar(64) not null default '' comment '支付时间',
  `total_price` decimal(10,2) not null comment '订单总价',
  `best_time` varchar(30) not null default '' comment '发货时间',
  `post_status` tinyint unsigned not null default '0' comment '发货状态 : 0 => 未发货 , 1 => 已发货, 2 => 已收货',
  `buyer_msg` text not null default '' comment '买家留言',
  `pay_code` varchar(30) not null default '' comment '支付方式',
  `status` int not null default '1' comment '订单状态,1正常,0逻辑删除',
  primary key(`id`),
  key member_id(`member_id`),
  key add_time(`add_time`),
  key pay_status(`pay_status`),
  key pay_time(`pay_time`),
  key total_price(`total_price`),
  key best_time(`best_time`),
  key post_status(`post_status`),
  key pay_code(`pay_code`), 
  key status(`status`)
)engine=InnoDB default charset=utf8 comment '订单基本信息表';


---------------------------------------------订单商品表---------------------------------------
----------------------------------------------------------------------------------------------
create table `an_order_goods`
(
  `id` mediumint unsigned not null auto_increment comment 'id',
  `order_id` mediumint unsigned not null comment '订单ID',
  `goods_id` mediumint unsigned not null comment '商品ID',
  `goods_number` mediumint unsigned not null comment '购买数量',
  `price` decimal(10,2) not null comment '购买价格',
  primary key(`id`),
  key order_id(`order_id`),
  key goods_id(`goods_id`)
)engine=InnoDB default charset=utf8 comment '订单商品表';



create table `an_goods`
(
  `id` mediumint unsigned not null auto_increment comment 'id',
  `goods_name` varchar(120) not null comment '商品名称',
  `shop_price` decimal(10,2) not null comment '商品价格',
  `market_price` decimal(10,2) not null comment '市场价格',
  `status` enum('是','否') not null default '是' comment '是否上架',
  `add_time` int(11)  default null comment '添加时间',
  `is_new` enum('1','0') NOT NULL DEFAULT '0' COMMENT '是否新品',
  `is_hot` enum('1','0') NOT NULL DEFAULT '0' COMMENT '是否热卖',
  `is_best` enum('1','0') NOT NULL DEFAULT '0' COMMENT '是否精品',
  `cat_id`  mediumint unsigned not null default '0' comment '分类id',
  `sort_num` tinyint(255) unsigned NOT NULL DEFAULT '50' COMMENT '默认排序',
  primary key(`id`),
  key shop_price(`shop_price`),
  key add_time(`add_time`),
  key is_on_sale(`is_on_sale`),
  key brand_id(`cat_id`),
  KEY `is_new` (`is_new`) USING BTREE,
  KEY `is_hot` (`is_hot`) USING BTREE,
  KEY `is_best` (`is_best`) USING BTREE,
  KEY `sort_num` (`sort_num`) USING BTREE
)engine=InnoDB default charset=utf8 comment '商品表';


create table `an_goods_thum`
(
  `id` mediumint unsigned not null auto_increment comment 'id',
  `pic` varchar(150) not null default '' comment '原图',
  `sm_thum` varchar(150) not null default '' comment '小图',
  `mid_thum` varchar(150) not null default '' comment '中图',
  `big_thum` varchar(150) not null default '' comment '大图',
  `goods_id`  mediumint unsigned not null comment '商品ID',
  primary key(`id`),
  key goods_id(`goods_id`)
)engine=InnoDB  default charset=utf8 comment '商品缩略图';


create table `an_goods_desc`
(
  `id` mediumint unsigned not null auto_increment comment 'id',
  `goods_desc` longtext comment '商品描述',
  `goods_id`  mediumint unsigned not null comment '商品ID',
  primary key(`id`),
  key goods_id(`goods_id`)
)engine=InnoDB  default charset=utf8 comment '商品描述表';

create table `an_goods_tags`
(
  `id` mediumint unsigned not null auto_increment comment 'id',
  `goods_tags` varchar(500) not null default '' comment '商品标签',
  primary key(`id`),
  key goods_tags(`goods_tags`)
)engine=InnoDB  default charset=utf8 comment '商品标签表';

create table `an_goods_tag_relation`
(
  `id` mediumint unsigned not null auto_increment comment 'id',
  `goods_tag_id` mediumint not null  comment '商品标签',
  `goods_id` mediumint not null  comment '商品标签',
  primary key(`id`),
  key goods_tag_id(`goods_tag_id`),
  key goods_id(`goods_id`)
)engine=InnoDB  default charset=utf8 comment '商品标签关联表';

---------------------------------------------商品相册---------------------------------------
--------------------------------------------------------------------------------------------
create table `an_goods_pic`
(
  `id` mediumint unsigned not null auto_increment comment 'id',
  `pic` varchar(150) not null default '' comment '原图',
  `sm_pic` varchar(150) not null default '' comment '小图',
  `mid_pic` varchar(150) not null default '' comment '中图',
  `big_pic` varchar(150) not null default '' comment '大图',
  `goods_id`  mediumint unsigned not null comment '商品ID',
  primary key(`id`),
  key goods_id(`goods_id`)
)engine=InnoDB  default charset=utf8 comment '商品相册';


create table `an_goods_download`
(
  `id` mediumint unsigned not null auto_increment comment 'id',
  `down_url` varchar(256) not null default '' comment '网盘地址',
  `ex_password` varchar(60) not null default '' comment '提取密码',
  `down_desc` text comment '下载说明',
  `goods_id`  mediumint unsigned not null comment '商品ID',
  primary key(`id`),
  key url(`url`),
  key ex_password(`ex_password`),
  key goods_id(`goods_id`)
)engine=InnoDB  default charset=utf8 comment '商品下载表';


