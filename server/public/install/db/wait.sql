SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for wait_article
-- ----------------------------
DROP TABLE IF EXISTS `wait_article`;
CREATE TABLE `wait_article` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `cid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '类目',
  `title` varchar(100) NOT NULL DEFAULT '' COMMENT '标题',
  `image` varchar(200) NOT NULL DEFAULT '' COMMENT '封面',
  `intro` varchar(200) NOT NULL DEFAULT '' COMMENT '简介',
  `content` text COMMENT '内容',
  `browse` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '浏览',
  `collect` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '收藏',
  `sort` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `is_topping` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否置顶: [0=否, 1=是]',
  `is_recommend` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否推荐: [0=否, 1=是]',
  `is_show` tinyint(255) unsigned NOT NULL DEFAULT '0' COMMENT '是否显示: [0=否, 1=是]',
  `is_delete` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否删除: [0=否, 1=是]',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `delete_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='文章内容表';

-- ----------------------------
-- Table structure for wait_article_category
-- ----------------------------
DROP TABLE IF EXISTS `wait_article_category`;
CREATE TABLE `wait_article_category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `name` varchar(20) NOT NULL DEFAULT '' COMMENT '类目名称',
  `sort` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '类目排序',
  `is_disable` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否禁用: [0=否, 1=是]',
  `is_delete` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否删除: [0=否, 1=是]',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `delete_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='文章类目表';

-- ----------------------------
-- Table structure for wait_attach
-- ----------------------------
DROP TABLE IF EXISTS `wait_attach`;
CREATE TABLE `wait_attach` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `cid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '分类ID',
  `file_type` tinyint(2) unsigned NOT NULL DEFAULT '10' COMMENT '文件类型: [10=图片, 20=视频]',
  `file_name` varchar(200) NOT NULL DEFAULT '' COMMENT '文件名称',
  `file_path` varchar(200) NOT NULL DEFAULT '' COMMENT '文件路径',
  `file_ext` varchar(10) NOT NULL DEFAULT '' COMMENT '文件扩展',
  `file_size` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '文件大小',
  `is_delete` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否删除: [0=否, 1=是]',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `delete_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='附件文件表';

-- ----------------------------
-- Table structure for wait_attach_cate
-- ----------------------------
DROP TABLE IF EXISTS `wait_attach_cate`;
CREATE TABLE `wait_attach_cate` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `name` varchar(20) NOT NULL DEFAULT '' COMMENT '分类名称',
  `type` tinyint(2) unsigned NOT NULL DEFAULT '10' COMMENT '分类类型: [10=图片, 20=视频]',
  `is_delete` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否删除: [0=否, 1=是]',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `delete_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='附件类目表';

-- ----------------------------
-- Table structure for wait_auth_admin
-- ----------------------------
DROP TABLE IF EXISTS `wait_auth_admin`;
CREATE TABLE `wait_auth_admin` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `role_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '账号角色',
  `dept_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '部门主键',
  `post_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '岗位主键',
  `nickname` varchar(32) NOT NULL DEFAULT '' COMMENT '账号昵称',
  `username` varchar(32) NOT NULL DEFAULT '' COMMENT '登录账号',
  `password` varchar(32) NOT NULL DEFAULT '' COMMENT '登录密码',
  `salt` varchar(32) NOT NULL DEFAULT '' COMMENT '加密盐巴',
  `avatar` varchar(200) NOT NULL DEFAULT '' COMMENT '用户头像',
  `phone` varchar(100) NOT NULL DEFAULT '' COMMENT '用户电话',
  `email` varchar(100) NOT NULL DEFAULT '' COMMENT '电子邮箱',
  `last_login_ip` varchar(100) NOT NULL DEFAULT '' COMMENT '登录地址',
  `last_login_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '登录时间',
  `is_disable` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否禁用: [0=否, 1=是]',
  `is_delete` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否删除: [0=否, 1=是]',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `delete_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='用户管理表';

-- ----------------------------
-- Table structure for wait_auth_dept
-- ----------------------------
DROP TABLE IF EXISTS `wait_auth_dept`;
CREATE TABLE `wait_auth_dept` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `pid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级主键',
  `name` varchar(100) NOT NULL DEFAULT '' COMMENT '部门名称',
  `duty` varchar(30) NOT NULL DEFAULT '' COMMENT '负责人名',
  `mobile` varchar(30) NOT NULL DEFAULT '' COMMENT '部门电话',
  `sort` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '排序编号',
  `level` smallint(5) unsigned NOT NULL DEFAULT '1' COMMENT '关系层级',
  `relation` varchar(500) NOT NULL DEFAULT '' COMMENT '关系链条',
  `is_disable` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否禁用: [0=否, 1=是]',
  `is_delete` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否删除: [0=否, 1=是]',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `delete_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='部门管理表';

-- ----------------------------
-- Table structure for wait_auth_menu
-- ----------------------------
DROP TABLE IF EXISTS `wait_auth_menu`;
CREATE TABLE `wait_auth_menu` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `pid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '菜单父级',
  `module` varchar(100) NOT NULL DEFAULT '' COMMENT '菜单模块',
  `title` varchar(100) NOT NULL DEFAULT '' COMMENT '菜单名称',
  `icon` varchar(100) NOT NULL DEFAULT '' COMMENT '菜单标题',
  `perms` varchar(100) NOT NULL DEFAULT '' COMMENT '菜单权限',
  `sort` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '菜单排序: [0=后, 1=前]',
  `is_menu` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否菜单: [0=否, 1=是]',
  `is_disable` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否禁用: [0=否, 1=是]',
  `is_delete` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否删除: [0=否, 1=是]',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `delete_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='菜单管理表';

-- ----------------------------
-- Table structure for wait_auth_perm
-- ----------------------------
DROP TABLE IF EXISTS `wait_auth_perm`;
CREATE TABLE `wait_auth_perm` (
  `role_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '角色主键',
  `menu_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '菜单主键'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='权限管理表';

-- ----------------------------
-- Table structure for wait_auth_post
-- ----------------------------
DROP TABLE IF EXISTS `wait_auth_post`;
CREATE TABLE `wait_auth_post` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `code` varchar(30) NOT NULL DEFAULT '' COMMENT '岗位编码',
  `name` varchar(30) NOT NULL DEFAULT '' COMMENT '岗位名称',
  `remarks` varchar(200) NOT NULL DEFAULT '' COMMENT '岗位备注',
  `sort` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '岗位排序',
  `is_disable` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否禁用: [0=否, 1=是]',
  `is_delete` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否删除: [0=否, 1=是]',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `delete_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='岗位管理表';

-- ----------------------------
-- Table structure for wait_auth_role
-- ----------------------------
DROP TABLE IF EXISTS `wait_auth_role`;
CREATE TABLE `wait_auth_role` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `name` varchar(20) CHARACTER SET utf16 NOT NULL DEFAULT '' COMMENT '角色名称',
  `describe` varchar(200) NOT NULL DEFAULT '' COMMENT '角色描述',
  `sort` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '角色排序',
  `is_disable` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否禁用: [0=否, 1=是]',
  `is_delete` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否删除: [0=否, 1=是]',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `delete_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='角色管理表';

-- ----------------------------
-- Table structure for wait_dev_banner
-- ----------------------------
DROP TABLE IF EXISTS `wait_dev_banner`;
CREATE TABLE `wait_dev_banner` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `position` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '轮播位置',
  `title` varchar(200) NOT NULL DEFAULT '' COMMENT '轮播标题',
  `image` varchar(250) NOT NULL DEFAULT '' COMMENT '轮播图片',
  `target` varchar(250) NOT NULL DEFAULT '' COMMENT '跳转方式',
  `url` varchar(250) NOT NULL DEFAULT '' COMMENT '跳转链接',
  `sort` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '排序编号',
  `is_disable` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否禁用: [0=否, 1=是]',
  `is_delete` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否删除: [0=否, 1=是]',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `delete_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='轮播管理表';

-- ----------------------------
-- Table structure for wait_dev_links
-- ----------------------------
DROP TABLE IF EXISTS `wait_dev_links`;
CREATE TABLE `wait_dev_links` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `title` varchar(200) NOT NULL DEFAULT '' COMMENT '友链名称',
  `target` varchar(250) NOT NULL DEFAULT '' COMMENT '跳转方式',
  `url` varchar(250) NOT NULL DEFAULT '' COMMENT '跳转链接',
  `sort` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '排序编号',
  `is_disable` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否禁用: [0=否, 1=是]',
  `is_delete` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否删除: [0=否, 1=是]',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `delete_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='友链管理表';

-- ----------------------------
-- Table structure for wait_dev_navigation
-- ----------------------------
DROP TABLE IF EXISTS `wait_dev_navigation`;
CREATE TABLE `wait_dev_navigation` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `pid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '父级',
  `name` varchar(30) NOT NULL DEFAULT '' COMMENT '名称',
  `target` varchar(30) NOT NULL DEFAULT '' COMMENT '跳转方式',
  `url` varchar(300) NOT NULL DEFAULT '' COMMENT '跳转链接',
  `sort` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '排序编号',
  `is_disable` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否禁用: [0=否, 1=是]',
  `is_delete` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否删除: [0=否, 1=是]',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `delete_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='导航管理表';

-- ----------------------------
-- Table structure for wait_gen_table
-- ----------------------------
DROP TABLE IF EXISTS `wait_gen_table`;
CREATE TABLE `wait_gen_table` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `table_name` varchar(200) NOT NULL DEFAULT '' COMMENT '表名称',
  `table_engine` varchar(200) NOT NULL DEFAULT '' COMMENT '表引擎',
  `table_comment` varchar(200) NOT NULL DEFAULT '' COMMENT '表描述',
  `table_alias` varchar(100) NOT NULL DEFAULT '' COMMENT '表别名',
  `author` varchar(100) NOT NULL DEFAULT '' COMMENT '作者名',
  `tpl_type` char(4) NOT NULL DEFAULT 'curd' COMMENT '模板类型: [curd=单表, tree=树表]',
  `gen_type` char(4) NOT NULL DEFAULT 'down' COMMENT '生成方式: [down=下载, code=覆盖]',
  `gen_class` varchar(100) NOT NULL DEFAULT '' COMMENT '生成类名',
  `gen_module` varchar(100) NOT NULL DEFAULT '' COMMENT '生成模块',
  `gen_folder` varchar(100) NOT NULL DEFAULT '' COMMENT '生成目录',
  `menu_type` char(4) NOT NULL DEFAULT 'auto' COMMENT '菜单构建: [auto=自动, hand=手动]',
  `menu_name` varchar(100) NOT NULL DEFAULT '' COMMENT '菜单名称',
  `menu_icon` varchar(100) NOT NULL DEFAULT '' COMMENT '菜单图标',
  `menu_pid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '菜单父级',
  `join_status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '关联状态: [0=关闭, 1=开启]',
  `join_array` text COMMENT '关联内容',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='生成代码表';

-- ----------------------------
-- Table structure for wait_gen_table_column
-- ----------------------------
DROP TABLE IF EXISTS `wait_gen_table_column`;
CREATE TABLE `wait_gen_table_column` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `table_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '表外键',
  `column_name` varchar(200) NOT NULL DEFAULT '' COMMENT '列名称',
  `column_comment` varchar(200) NOT NULL DEFAULT '' COMMENT '列描述',
  `column_length` varchar(100) NOT NULL DEFAULT '' COMMENT '列长度',
  `column_type` varchar(100) NOT NULL DEFAULT '' COMMENT '列类型',
  `model_type` varchar(100) NOT NULL DEFAULT '' COMMENT '模型类型',
  `is_pk` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否主键: [0=否, 1=是]',
  `is_increment` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否自增: [0=否, 1=是]',
  `is_required` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否必填: [0=否, 1=是]',
  `is_insert` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否插入: [0=否, 1=是]',
  `is_edit` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否编辑: [0=否, 1=是]',
  `is_list` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否列表: [0=否, 1=是]',
  `is_query` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否查询: [0=否, 1=是]',
  `query_type` varchar(30) NOT NULL DEFAULT '' COMMENT '查询条件',
  `html_type` varchar(30) NOT NULL DEFAULT '' COMMENT '显示类型',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='生成字段表';

-- ----------------------------
-- Table structure for wait_sys_config
-- ----------------------------
DROP TABLE IF EXISTS `wait_sys_config`;
CREATE TABLE `wait_sys_config` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `type` varchar(100) NOT NULL DEFAULT '' COMMENT '类型',
  `key` varchar(100) NOT NULL DEFAULT '' COMMENT '键名',
  `value` text COMMENT '键值',
  `remarks` varchar(100) NOT NULL DEFAULT '' COMMENT '备注',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='系统配置表';

-- ----------------------------
-- Table structure for wait_sys_crontab
-- ----------------------------
DROP TABLE IF EXISTS `wait_sys_crontab`;
CREATE TABLE `wait_sys_crontab` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `name` varchar(64) NOT NULL DEFAULT '' COMMENT '任务名称',
  `command` varchar(64) NOT NULL DEFAULT '' COMMENT '执行命令',
  `params` varchar(64) NOT NULL DEFAULT '' COMMENT '附带参数',
  `rules` varchar(64) NOT NULL DEFAULT '' COMMENT '运行规则',
  `remarks` varchar(255) NOT NULL DEFAULT '' COMMENT '备注信息',
  `error` text COMMENT '错误提示',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '执行状态: [1=运行, 2=暂停, 3=错误]',
  `exe_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '执行时长',
  `max_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最大执行时长',
  `last_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后执行时间',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='系统任务表';

-- ----------------------------
-- Table structure for wait_sys_log
-- ----------------------------
DROP TABLE IF EXISTS `wait_sys_log`;
CREATE TABLE `wait_sys_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `admin_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '操作人员',
  `method` varchar(30) NOT NULL DEFAULT '' COMMENT '请求方法',
  `url` varchar(100) NOT NULL DEFAULT '' COMMENT '请求路由',
  `ip` varchar(100) NOT NULL DEFAULT '' COMMENT '请求IP',
  `ua` varchar(200) NOT NULL DEFAULT '' COMMENT '请求UA',
  `params` text COMMENT '请求参数',
  `error` text COMMENT '错误信息',
  `trace` text COMMENT '错误异常',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '执行状态: 1=成功, 2=失败',
  `start_time` varchar(20) NOT NULL DEFAULT '0' COMMENT '开始时间: 毫秒',
  `end_time` varchar(20) NOT NULL DEFAULT '0' COMMENT '结束时间: 毫秒',
  `task_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '耗时时间: 毫秒',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '操作时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='系统日志表';

-- ----------------------------
-- Table structure for wait_user
-- ----------------------------
DROP TABLE IF EXISTS `wait_user`;
CREATE TABLE `wait_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `group_id` int(10) NOT NULL COMMENT '用户分组',
  `sn` varchar(20) NOT NULL DEFAULT '' COMMENT '用户编号',
  `avatar` varchar(200) NOT NULL DEFAULT '' COMMENT '用户头像',
  `nickname` varchar(32) NOT NULL DEFAULT '' COMMENT '用户名称',
  `username` varchar(32) NOT NULL DEFAULT '' COMMENT '用户账号',
  `password` varchar(32) NOT NULL DEFAULT '' COMMENT '登录密码',
  `salt` varchar(32) NOT NULL DEFAULT '' COMMENT '加密盐巴',
  `sex` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '用户性别',
  `mobile` varchar(20) NOT NULL DEFAULT '' COMMENT '手机号码',
  `email` varchar(100) NOT NULL DEFAULT '' COMMENT '电子邮箱',
  `last_login_ip` varchar(100) NOT NULL DEFAULT '' COMMENT '最后登录IP',
  `last_login_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后登录时间',
  `is_disable` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否禁用: [0=否, 1=是]',
  `is_delete` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否删除: [0=否, 1=是]',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `delete_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='用户管理表';

-- ----------------------------
-- Table structure for wait_user_group
-- ----------------------------
DROP TABLE IF EXISTS `wait_user_group`;
CREATE TABLE `wait_user_group` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `name` varchar(30) NOT NULL DEFAULT '' COMMENT '名称',
  `remarks` varchar(200) NOT NULL DEFAULT '' COMMENT '备注',
  `sort` smallint(1) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `is_delete` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否删除: [0=否, 1=是]',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `delete_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='用户分组表';

SET FOREIGN_KEY_CHECKS = 1;


-- ----------------------------
-- INSTALL
-- ----------------------------
BEGIN;
INSERT INTO `wait_auth_menu` VALUES (1, 0, 'app', '首页', 'layui-icon icon-home-fill', '', 0, 1, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (10, 0, 'app', '权限', 'layui-icon icon-member-manage', '', 40, 1, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (11, 10, 'app', '管理员', '', 'auth.admin/index', 0, 1, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (12, 11, 'app', '管理员列表', '', 'auth.admin/index', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (13, 11, 'app', '管理员新增', '', 'auth.admin/add', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (14, 11, 'app', '管理员编辑', '', 'auth.admin/edit', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (15, 11, 'app', '管理员删除', '', 'auth.admin/del', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (16, 11, 'app', '管理员状态', '', 'auth.admin/status', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (20, 10, 'app', '角色管理', '', 'auth.role/index', 0, 1, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (21, 20, 'app', '角色列表', '', 'auth.role/index', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (22, 20, 'app', '角色新增', '', 'auth.role/add', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (23, 20, 'app', '角色编辑', '', 'auth.role/edit', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (24, 20, 'app', '角色删除', '', 'auth.role/del', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (25, 20, 'app', '角色状态', '', 'auth.role/status', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (30, 10, 'app', '菜单管理', '', 'auth.menu/index', 0, 1, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (31, 30, 'app', '菜单列表', '', 'auth.menu/index', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (32, 30, 'app', '菜单新增', '', 'auth.menu/add', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (33, 30, 'app', '菜单编辑', '', 'auth.menu/edit', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (34, 30, 'app', '菜单删除', '', 'auth.menu/del', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (40, 10, 'app', '部门管理', '', 'auth.dept/index', 0, 1, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (41, 40, 'app', '部门列表', '', 'auth.dept/index', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (42, 40, 'app', '部门新增', '', 'auth.dept/add', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (43, 40, 'app', '部门编辑', '', 'auth.dept/edit', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (44, 40, 'app', '部门删除', '', 'auth.dept/del', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (50, 10, 'app', '岗位管理', '', 'auth.post/index', 0, 1, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (51, 50, 'app', '岗位列表', '', 'auth.post/index', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (52, 50, 'app', '岗位新增', '', 'auth.post/add', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (53, 50, 'app', '岗位编辑', '', 'auth.post/edit', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (54, 50, 'app', '岗位删除', '', 'auth.post/del', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (200, 0, 'app', '设置', 'layui-icon icon-setup-fill', '', 50, 1, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (210, 200, 'app', '基础设置', '', 'setting.basics/index', 0, 1, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (211, 210, 'app', '配置页面', '', 'setting.basics/index', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (212, 210, 'app', '配置保存', '', 'setting.basics/save', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (213, 200, 'app', '存储设置', '', 'setting.storage/index', 0, 1, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (214, 213, 'app', '配置页面', '', 'setting.storage/index', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (215, 213, 'app', '配置保存', '', 'setting.storage/save', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (216, 200, 'app', '水印设置', '', 'setting.watermark/index', 0, 1, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (217, 216, 'app', '配置页面', '', 'setting.watermark/index', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (218, 216, 'app', '配置保存', '', 'setting.watermark/save', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (220, 200, 'app', '导航菜单', '', 'setting.navigation/index', 0, 1, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (221, 220, 'app', '导航列表', '', 'setting.navigation/index', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (222, 220, 'app', '导航新增', '', 'setting.navigation/add', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (223, 220, 'app', '导航编辑', '', 'setting.navigation/edit', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (224, 220, 'app', '导航删除', '', 'setting.navigation/del', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (225, 200, 'app', '轮播图片', '', 'setting.banner/index', 0, 1, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (226, 225, 'app', '轮播列表', '', 'setting.banner/index', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (227, 225, 'app', '轮播新增', '', 'setting.banner/add', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (228, 225, 'app', '轮播编辑', '', 'setting.banner/edit', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (229, 225, 'app', '轮播删除', '', 'setting.banner/del', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (230, 200, 'app', '友情链接', '', 'setting.links/index', 0, 1, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (231, 230, 'app', '友链列表', '', 'setting.links/index', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (232, 230, 'app', '友链新增', '', 'setting.links/add', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (233, 230, 'app', '友链编辑', '', 'setting.links/edit', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (234, 230, 'app', '友链删除', '', 'setting.links/del', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (400, 0, 'app', '系统', 'layui-icon icon-system', '', 60, 1, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (401, 400, 'app', '计划任务', '', 'system.crontab/index', 0, 1, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (402, 401, 'app', '任务列表', '', 'system.crontab/index', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (403, 401, 'app', '任务详情', '', 'system.crontab/detail', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (404, 401, 'app', '任务新增', '', 'system.crontab/add', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (405, 401, 'app', '任务编辑', '', 'system.crontab/edit', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (406, 401, 'app', '任务删除', '', 'system.crontab/del', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (407, 401, 'app', '任务启动', '', 'system.crontab/run', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (408, 401, 'app', '任务停止', '', 'system.crontab/stop', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (410, 400, 'app', '清除缓存', '', 'system.clear/index', 0, 1, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (411, 410, 'app', '操作页面', '', 'system.clear/index', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (412, 410, 'app', '立即清除', '', 'system.clear/clean', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (415, 400, 'app', '系统日志', '', 'system.log/index', 0, 1, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (416, 415, 'app', '日志列表', '', 'system.log/index', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (417, 415, 'app', '日志详情', '', 'system.log/detail', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (418, 400, 'app', '附件管理', '', 'system.attach/index', 0, 1, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (419, 418, 'app', '附件列表', '', 'attach/index', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (420, 418, 'app', '附件命名', '', 'attach/rename', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (421, 418, 'app', '附件移动', '', 'attach/move', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (422, 418, 'app', '附件删除', '', 'attach/del', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (423, 418, 'app', '分组列表', '', 'attach/cateLists', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (424, 418, 'app', '分组新增', '', 'attach/cateAdd', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (425, 418, 'app', '分组命名', '', 'attach/cateRename', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (426, 418, 'app', '分组删除', '', 'attach/cateDel', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (430, 400, 'app', '上传管理', '', '', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (431, 430, 'app', '附件上传', '', 'upload/attach', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (432, 430, 'app', '直达上传', '', 'upload/through', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (433, 430, 'app', '临时上传', '', 'upload/temporary', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (700, 0, 'app', '内容', 'layui-icon icon-text-doc', '', 20, 1, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (701, 700, 'app', '分类管理', '', 'content.category/index', 20, 1, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (702, 701, 'app', '分类列表', '', 'content.category/index', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (703, 701, 'app', '分类详情', '', 'content.category/detail', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (704, 701, 'app', '分类新增', '', 'content.category/add', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (705, 701, 'app', '分类编辑', '', 'content.category/edit', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (706, 701, 'app', '分类删除', '', 'content.category/del', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (710, 700, 'app', '文章管理', '', 'content.article/index', 10, 1, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (711, 710, 'app', '文章列表', '', 'content.article/index', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (712, 710, 'app', '文章详情', '', 'content.article/detail', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (713, 710, 'app', '文章新增', '', 'content.article/add', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (714, 710, 'app', '文章编辑', '', 'content.article/edit', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (715, 710, 'app', '文章删除', '', 'content.article/del', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (900, 0, 'app', '用户', 'layui-icon icon-member-user', '', 30, 1, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (901, 900, 'app', '用户管理', '', 'user.users/index', 0, 1, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (902, 901, 'app', '用户详情', '', 'user.users/detail', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (903, 901, 'app', '设置分组', '', 'user.users/group', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (950, 900, 'app', '用户分组', '', 'user.group/index', 0, 1, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (951, 950, 'app', '分组列表', '', 'user.group/index', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (952, 950, 'app', '分组详情', '', 'user.group/detail', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (953, 950, 'app', '分组新增', '', 'user.group/add', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (954, 950, 'app', '分组编辑', '', 'user.group/edit', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (955, 950, 'app', '分组删除', '', 'user.group/del', 0, 0, 0, 0, 1648696695, 1648696695, 0);
COMMIT;

BEGIN;
INSERT INTO `wait_sys_config` VALUES (1, 'storage', 'default', 'local', '存储引擎', 1659423543, 1670084303);
INSERT INTO `wait_sys_config` VALUES (2, 'storage', 'local', '[]', '本地存储', 1659423543, 1670084303);
INSERT INTO `wait_sys_config` VALUES (3, 'storage', 'qiniu', '{\"bucket\":\"\",\"accessKey\":\"\",\"secretKey\":\"\",\"domain\":\"\"}', '七牛存储', 1659423543, 1670084303);
INSERT INTO `wait_sys_config` VALUES (4, 'storage', 'aliyun', '{\"bucket\":\"\",\"accessKey\":\"\",\"secretKey\":\"\",\"domain\":\"\"}', '阿里存储', 1659423543, 1670084303);
INSERT INTO `wait_sys_config` VALUES (5, 'storage', 'qcloud', '{\"bucket\":\"\",\"region\":\"\",\"accessKey\":\"\",\"secretKey\":\"\",\"domain\":\"\"}', '腾讯存储', 1659423543, 1670084303);
INSERT INTO `wait_sys_config` VALUES (10, 'website', 'website_title', 'WaitAdmin开源后台框架', '网站标题', 1660901552, 1670084287);
INSERT INTO `wait_sys_config` VALUES (11, 'website', 'website_logo', '', '网站logo', 1670064259, 1670084287);
INSERT INTO `wait_sys_config` VALUES (12, 'website', 'website_copyright', '© 2022-2024. All Rights Reserved. WaitAdmin', '网站版权', 1665319505, 1670084287);
INSERT INTO `wait_sys_config` VALUES (13, 'website', 'website_icp', '', 'ICP备案', 1660901552, 1670084287);
INSERT INTO `wait_sys_config` VALUES (14, 'website', 'website_pcp', '', '公安备案', 1660901552, 1670084287);
INSERT INTO `wait_sys_config` VALUES (15, 'website', 'website_analyse', '', '统计代码', 1660901552, 1670084287);
INSERT INTO `wait_sys_config` VALUES (30, 'mail', 'mail_type', 'smtp', '邮件方式', 1660901552, 1670084287);
INSERT INTO `wait_sys_config` VALUES (31, 'mail', 'mail_smtp_host', 'smtp.163.com', 'SMTP服务', 1660901552, 1670084287);
INSERT INTO `wait_sys_config` VALUES (32, 'mail', 'mail_smtp_port', '25', 'SMTP端口', 1660901552, 1670084287);
INSERT INTO `wait_sys_config` VALUES (33, 'mail', 'mail_smtp_user', '', 'SMTP账号', 1660901552, 1670084287);
INSERT INTO `wait_sys_config` VALUES (34, 'mail', 'mail_smtp_pass', '', 'SMTP密码', 1660901552, 1670084287);
INSERT INTO `wait_sys_config` VALUES (35, 'mail', 'mail_from_user', '', 'SMTP验证', 1660901552, 1670084287);
INSERT INTO `wait_sys_config` VALUES (36, 'mail', 'mail_verify_type', '', '发件人邮箱', 1660901552, 1670084287);
INSERT INTO `wait_sys_config` VALUES (40, 'seo', 'seo_title', '', 'SEO的标题', 1660901552, 1670084287);
INSERT INTO `wait_sys_config` VALUES (41, 'seo', 'seo_keywords', '', 'SEO关键字', 1660901552, 1670084287);
INSERT INTO `wait_sys_config` VALUES (42, 'seo', 'seo_description', '', 'SEO的描述', 1660901552, 1670084287);
INSERT INTO `wait_sys_config` VALUES (200, 'watermark', 'status', '0', '水印功能状态', 1665319505, 1670083476);
INSERT INTO `wait_sys_config` VALUES (201, 'watermark', 'type', 'text', '水印文件类型', 1665319505, 1670083476);
INSERT INTO `wait_sys_config` VALUES (202, 'watermark', 'icon', 'static/common/watermark.png', '水印图片文件', 1665319505, 1670083476);
INSERT INTO `wait_sys_config` VALUES (203, 'watermark', 'fonts', 'WaitAdmin', '水印字体文字', 1665319505, 1670083476);
INSERT INTO `wait_sys_config` VALUES (204, 'watermark', 'color', '#000000', '水印字体颜色', 1665319505, 1670083476);
INSERT INTO `wait_sys_config` VALUES (205, 'watermark', 'size', '20', '水印字体大小', 1665319505, 1670083476);
INSERT INTO `wait_sys_config` VALUES (206, 'watermark', 'offset', '0', '水印字体偏移', 1665319505, 1670083476);
INSERT INTO `wait_sys_config` VALUES (207, 'watermark', 'angle', '0', '水印字体倾斜', 1665319505, 1670083476);
INSERT INTO `wait_sys_config` VALUES (208, 'watermark', 'alpha', '100', '水印图透明度', 1665319505, 1670083476);
INSERT INTO `wait_sys_config` VALUES (209, 'watermark', 'position', '1', '水印所在位置', 1665319505, 1670083476);
COMMIT;