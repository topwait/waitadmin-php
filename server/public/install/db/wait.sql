SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for wait_article
-- ----------------------------
DROP TABLE IF EXISTS `wait_article`;
CREATE TABLE `wait_article`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键',
  `cid` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '类目',
  `title` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '标题',
  `image` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '封面',
  `intro` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '简介',
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '内容',
  `browse` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '浏览',
  `collect` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '收藏',
  `sort` smallint(5) UNSIGNED NOT NULL DEFAULT 0 COMMENT '排序',
  `is_topping` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否置顶: [0=否, 1=是]',
  `is_recommend` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否推荐: [0=否, 1=是]',
  `is_show` tinyint(255) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否显示: [0=否, 1=是]',
  `is_delete` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否删除: [0=否, 1=是]',
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `update_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  `delete_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '文章内容表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for wait_article_category
-- ----------------------------
DROP TABLE IF EXISTS `wait_article_category`;
CREATE TABLE `wait_article_category`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键',
  `name` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '类目名称',
  `sort` smallint(5) UNSIGNED NOT NULL DEFAULT 0 COMMENT '类目排序',
  `is_disable` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否禁用: [0=否, 1=是]',
  `is_delete` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否删除: [0=否, 1=是]',
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `update_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  `delete_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '文章类目表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for wait_article_collect
-- ----------------------------
DROP TABLE IF EXISTS `wait_article_collect`;
CREATE TABLE `wait_article_collect`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键',
  `user_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '用户ID',
  `article_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '文章ID',
  `is_delete` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否删除: [0=否, 1=是]',
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `update_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  `delete_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '用户收藏表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for wait_attach
-- ----------------------------
DROP TABLE IF EXISTS `wait_attach`;
CREATE TABLE `wait_attach`  (
    `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键',
    `uid` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '用户ID',
    `cid` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '分类ID',
    `quote` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '引用次数',
    `file_type` tinyint(2) UNSIGNED NOT NULL DEFAULT 10 COMMENT '文件类型: [10=图片, 20=视频, 30=压缩, 40=文件]',
    `file_name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '文件名称',
    `file_path` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '文件路径',
    `file_ext` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '文件扩展',
    `file_size` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '文件大小',
    `is_user` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '用户上传: [0=否, 1=是]',
    `is_attach` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '仓库附件: [0=否, 1=是]',
    `is_delete` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否删除: [0=否, 1=是]',
    `create_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
    `update_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
    `delete_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '删除时间',
    PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '附件文件表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for wait_attach_cate
-- ----------------------------
DROP TABLE IF EXISTS `wait_attach_cate`;
CREATE TABLE `wait_attach_cate`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键',
  `name` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '分类名称',
  `type` tinyint(2) UNSIGNED NOT NULL DEFAULT 10 COMMENT '分类类型: [10=图片, 20=视频, 30=压缩, 40=文件]',
  `is_delete` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否删除: [0=否, 1=是]',
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `update_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  `delete_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '附件类目表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for wait_auth_admin
-- ----------------------------
DROP TABLE IF EXISTS `wait_auth_admin`;
CREATE TABLE `wait_auth_admin`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键',
  `role_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '账号角色',
  `dept_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '部门主键',
  `post_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '岗位主键',
  `nickname` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '账号昵称',
  `username` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '登录账号',
  `password` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '登录密码',
  `salt` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '加密盐巴',
  `avatar` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '用户头像',
  `phone` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '用户电话',
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '电子邮箱',
  `last_login_ip` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '登录地址',
  `last_login_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '登录时间',
  `is_disable` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否禁用: [0=否, 1=是]',
  `is_delete` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否删除: [0=否, 1=是]',
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `update_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  `delete_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '用户管理表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for wait_auth_dept
-- ----------------------------
DROP TABLE IF EXISTS `wait_auth_dept`;
CREATE TABLE `wait_auth_dept`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键',
  `pid` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '上级主键',
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '部门名称',
  `duty` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '负责人名',
  `mobile` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '部门电话',
  `sort` smallint(5) UNSIGNED NOT NULL DEFAULT 0 COMMENT '排序编号',
  `level` smallint(5) UNSIGNED NOT NULL DEFAULT 1 COMMENT '关系层级',
  `relation` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '关系链条',
  `is_disable` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否禁用: [0=否, 1=是]',
  `is_delete` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否删除: [0=否, 1=是]',
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `update_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  `delete_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '部门管理表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for wait_auth_menu
-- ----------------------------
DROP TABLE IF EXISTS `wait_auth_menu`;
CREATE TABLE `wait_auth_menu`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键',
  `pid` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '菜单父级',
  `module` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '菜单模块',
  `title` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '菜单名称',
  `icon` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '菜单标题',
  `perms` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '菜单权限',
  `sort` smallint(5) UNSIGNED NOT NULL DEFAULT 0 COMMENT '菜单排序: [0=后, 1=前]',
  `is_menu` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否菜单: [0=否, 1=是]',
  `is_disable` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否禁用: [0=否, 1=是]',
  `is_delete` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否删除: [0=否, 1=是]',
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `update_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  `delete_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '菜单管理表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for wait_auth_perm
-- ----------------------------
DROP TABLE IF EXISTS `wait_auth_perm`;
CREATE TABLE `wait_auth_perm`  (
  `role_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '角色主键',
  `menu_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '菜单主键'
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '权限管理表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for wait_auth_post
-- ----------------------------
DROP TABLE IF EXISTS `wait_auth_post`;
CREATE TABLE `wait_auth_post`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键',
  `code` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '岗位编码',
  `name` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '岗位名称',
  `remarks` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '岗位备注',
  `sort` smallint(5) UNSIGNED NOT NULL DEFAULT 0 COMMENT '岗位排序',
  `is_disable` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否禁用: [0=否, 1=是]',
  `is_delete` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否删除: [0=否, 1=是]',
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `update_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  `delete_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '岗位管理表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for wait_auth_role
-- ----------------------------
DROP TABLE IF EXISTS `wait_auth_role`;
CREATE TABLE `wait_auth_role`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键',
  `name` varchar(20) CHARACTER SET utf16 COLLATE utf16_general_ci NOT NULL DEFAULT '' COMMENT '角色名称',
  `describe` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '角色描述',
  `sort` smallint(5) UNSIGNED NOT NULL DEFAULT 0 COMMENT '角色排序',
  `is_disable` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否禁用: [0=否, 1=是]',
  `is_delete` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否删除: [0=否, 1=是]',
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `update_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  `delete_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '角色管理表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for wait_dev_banner
-- ----------------------------
DROP TABLE IF EXISTS `wait_dev_banner`;
CREATE TABLE `wait_dev_banner`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键',
  `position` tinyint(2) UNSIGNED NOT NULL DEFAULT 0 COMMENT '轮播位置',
  `title` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '轮播标题',
  `image` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '轮播图片',
  `target` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '跳转方式',
  `url` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '跳转链接',
  `sort` smallint(5) UNSIGNED NOT NULL DEFAULT 0 COMMENT '排序编号',
  `is_disable` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否禁用: [0=否, 1=是]',
  `is_delete` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否删除: [0=否, 1=是]',
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `update_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  `delete_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '轮播管理表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for wait_dev_links
-- ----------------------------
DROP TABLE IF EXISTS `wait_dev_links`;
CREATE TABLE `wait_dev_links`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键',
  `title` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '友链名称',
  `target` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '跳转方式',
  `url` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '跳转链接',
  `sort` smallint(5) UNSIGNED NOT NULL DEFAULT 0 COMMENT '排序编号',
  `is_disable` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否禁用: [0=否, 1=是]',
  `is_delete` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否删除: [0=否, 1=是]',
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `update_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  `delete_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '友链管理表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for wait_dev_navigation
-- ----------------------------
DROP TABLE IF EXISTS `wait_dev_navigation`;
CREATE TABLE `wait_dev_navigation`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键',
  `pid` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '父级',
  `name` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '名称',
  `target` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '跳转方式',
  `url` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '跳转链接',
  `sort` smallint(5) UNSIGNED NOT NULL DEFAULT 0 COMMENT '排序编号',
  `is_disable` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否禁用: [0=否, 1=是]',
  `is_delete` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否删除: [0=否, 1=是]',
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `update_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  `delete_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '导航管理表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for wait_curd_table
-- ----------------------------
DROP TABLE IF EXISTS `wait_addons_curd_table`;
CREATE TABLE `wait_addons_curd_table`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键',
  `table_name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '表名称',
  `table_engine` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '表引擎',
  `table_comment` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '表描述',
  `table_alias` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '表别名',
  `author` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '作者名',
  `tpl_type` char(4) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'curd' COMMENT '模板类型: [curd=单表, tree=树表]',
  `gen_type` char(4) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'down' COMMENT '生成方式: [down=下载, code=覆盖]',
  `gen_class` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '生成类名',
  `gen_module` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '生成模块',
  `gen_folder` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '生成目录',
  `menu_type` char(4) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'auto' COMMENT '菜单构建: [auto=自动, hand=手动]',
  `menu_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '菜单名称',
  `menu_icon` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '菜单图标',
  `menu_pid` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '菜单父级',
  `join_status` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '关联状态: [0=关闭, 1=开启]',
  `join_array` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '关联内容',
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `update_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '生成代码表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for wait_addons_curd_table_column
-- ----------------------------
DROP TABLE IF EXISTS `wait_addons_curd_table_column`;
CREATE TABLE `wait_addons_curd_table_column`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键',
  `table_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '表外键',
  `column_name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '列名称',
  `column_comment` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '列描述',
  `column_length` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '列长度',
  `column_type` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '列类型',
  `model_type` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '模型类型',
  `is_pk` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否主键: [0=否, 1=是]',
  `is_increment` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否自增: [0=否, 1=是]',
  `is_required` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否必填: [0=否, 1=是]',
  `is_insert` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否插入: [0=否, 1=是]',
  `is_edit` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否编辑: [0=否, 1=是]',
  `is_list` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否列表: [0=否, 1=是]',
  `is_query` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否查询: [0=否, 1=是]',
  `query_type` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '查询条件',
  `html_type` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '显示类型',
  `dict_type` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '字典类型',
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `update_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '生成字段表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for wait_notice_record
-- ----------------------------
DROP TABLE IF EXISTS `wait_notice_record`;
CREATE TABLE `wait_notice_record`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键',
  `scene` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '通知场景',
  `user_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '接收用户',
  `account` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '接收账号',
  `title` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '通知标题',
  `code` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '验证编码',
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '通知内容',
  `error` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '失败原因',
  `sender` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '发送类型: [1=系统, 2=邮件, 3=短信, 4=公众号, 5=小程序]',
  `receiver` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '接收对象: [1=用户, 2=平台]',
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '通知状态: [0=等待, 1=成功, 2=失败]',
  `is_read` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '已读状态: [0=未读, 1=已读]',
  `is_captcha` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是验证码: [0=否的, 1=是的]',
  `is_delete` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否删除: [0=否的, 1=是的]',
  `expire_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '失效时间',
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `update_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  `delete_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '通知记录表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for wait_notice_setting
-- ----------------------------
DROP TABLE IF EXISTS `wait_notice_setting`;
CREATE TABLE `wait_notice_setting`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键',
  `scene` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '场景编码',
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '场景名称',
  `remarks` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '场景描述',
  `variable` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '场景变量',
  `sys_template` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '系统通知模板',
  `sms_template` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '短信通知模板',
  `ems_template` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '邮件通知模板',
  `get_client` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '接收端口: [1=用户, 2=平台]',
  `is_captcha` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是验证码: [0=否的, 1=是的]',
  `is_delete` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否删除: [0=否的, 1=是的]',
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `update_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  `delete_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '通知设置表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for wait_sys_config
-- ----------------------------
DROP TABLE IF EXISTS `wait_sys_config`;
CREATE TABLE `wait_sys_config`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键',
  `type` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '类型',
  `key` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '键名',
  `value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '键值',
  `remarks` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '备注',
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `update_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '系统配置表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for wait_sys_crontab
-- ----------------------------
DROP TABLE IF EXISTS `wait_sys_crontab`;
CREATE TABLE `wait_sys_crontab`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `name` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '任务名称',
  `command` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '执行命令',
  `params` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '附带参数',
  `rules` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '运行规则',
  `remarks` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '备注信息',
  `error` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '错误提示',
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '执行状态: [1=运行, 2=暂停, 3=错误]',
  `exe_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '执行时长',
  `max_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '最大执行时长',
  `last_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '最后执行时间',
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `update_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '系统任务表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for wait_sys_log
-- ----------------------------
DROP TABLE IF EXISTS `wait_sys_log`;
CREATE TABLE `wait_sys_log`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键',
  `admin_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '操作人员',
  `method` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '请求方法',
  `url` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '请求路由',
  `ip` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '请求IP',
  `ua` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '请求UA',
  `params` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '请求参数',
  `error` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '错误信息',
  `trace` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '错误异常',
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '执行状态: 1=成功, 2=失败',
  `start_time` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '0' COMMENT '开始时间: 毫秒',
  `end_time` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '0' COMMENT '结束时间: 毫秒',
  `task_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '耗时时间: 毫秒',
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '操作时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '系统日志表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for wait_sys_visitor
-- ----------------------------
DROP TABLE IF EXISTS `wait_sys_visitor`;
CREATE TABLE `wait_sys_visitor` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '操作人员',
  `module` varchar(30) NOT NULL DEFAULT '' COMMENT '所属模块',
  `method` varchar(30) NOT NULL DEFAULT '' COMMENT '请求方法',
  `url` varchar(100) NOT NULL DEFAULT '' COMMENT '请求路由',
  `ip` varchar(100) NOT NULL DEFAULT '' COMMENT '请求IP',
  `ua` varchar(500) NOT NULL DEFAULT '' COMMENT '请求UA',
  `browser` varchar(100) NOT NULL DEFAULT '' COMMENT '请求内核',
  `params` text COMMENT '请求参数',
  `error` text COMMENT '错误信息',
  `trace` text COMMENT '错误异常',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '执行状态: 1=成功, 2=失败',
  `start_time` varchar(20) NOT NULL DEFAULT '0' COMMENT '开始时间: 毫秒',
  `end_time` varchar(20) NOT NULL DEFAULT '0' COMMENT '结束时间: 毫秒',
  `task_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '耗时时间: 毫秒',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '操作时间',
PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1064 DEFAULT CHARSET=utf8mb4 COMMENT='浏览日志表';

-- ----------------------------
-- Table structure for wait_sys_dict_data
-- ----------------------------
DROP TABLE IF EXISTS `wait_sys_dict_data`;
CREATE TABLE `wait_sys_dict_data` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `type_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '类型',
  `name` varchar(100) NOT NULL DEFAULT '' COMMENT '键名',
  `value` varchar(200) NOT NULL DEFAULT '' COMMENT '数值',
  `remark` varchar(200) NOT NULL DEFAULT '' COMMENT '备注',
  `sort` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `is_enable` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否启用: [0=否, 1=是]',
  `is_delete` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否删除: [0=否, 1=是]',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `delete_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COMMENT='字典数据表';

-- ----------------------------
-- Table structure for wait_sys_dict_type
-- ----------------------------
DROP TABLE IF EXISTS `wait_sys_dict_type`;
CREATE TABLE `wait_sys_dict_type` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `name` varchar(100) NOT NULL DEFAULT '' COMMENT '字典名称',
  `type` varchar(100) NOT NULL DEFAULT '' COMMENT '字典类型',
  `remark` varchar(200) NOT NULL DEFAULT '' COMMENT '字典备注',
  `is_enable` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否启用: [0=否, 1=是]',
  `is_delete` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否删除: [0=否, 1=是]',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `delete_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COMMENT='字典类型表';

-- ----------------------------
-- Table structure for wait_user
-- ----------------------------
DROP TABLE IF EXISTS `wait_user`;
CREATE TABLE `wait_user`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键',
  `group_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '用户分组',
  `sn` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '用户编号',
  `account` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '用户账号',
  `password` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '登录密码',
  `nickname` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '用户名称',
  `avatar` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '用户头像',
  `sign` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '个性签名',
  `salt` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '加密盐巴',
  `gender` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '用户性别',
  `mobile` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '手机号码',
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '电子邮箱',
  `last_login_ip` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '最后登录IP',
  `last_login_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '最后登录时间',
  `is_disable` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否禁用: [0=否, 1=是]',
  `is_delete` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否删除: [0=否, 1=是]',
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `update_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  `delete_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '用户管理表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for wait_user_auth
-- ----------------------------
DROP TABLE IF EXISTS `wait_user_auth`;
CREATE TABLE `wait_user_auth`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `user_id` int(10) UNSIGNED NOT NULL COMMENT '用户ID',
  `openid` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT 'openid',
  `unionid` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT 'unionid',
  `terminal` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '客户端[1=微信小程序, 2=微信公众号, 3=H5, 4=PC, 5=安卓, 6=苹果]',
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `update_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '用户授权表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for wait_user_group
-- ----------------------------
DROP TABLE IF EXISTS `wait_user_group`;
CREATE TABLE `wait_user_group`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键',
  `name` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '名称',
  `remarks` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '备注',
  `sort` smallint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '排序',
  `is_delete` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否删除: [0=否, 1=是]',
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `update_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  `delete_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '用户分组表' ROW_FORMAT = Dynamic;

SET FOREIGN_KEY_CHECKS = 1;

INSERT INTO `wait_article_category` VALUES (1, '行业资讯', 0, 0, 0, 1676559290, 1679048617, 0);
INSERT INTO `wait_article_category` VALUES (2, '技术分享', 0, 0, 0, 1676559361, 1679048627, 0);

INSERT INTO `wait_sys_crontab` VALUES (1, '垃圾清理', 'gc', '', '0 2 * * *', '清理临时文件(凌晨2点: 0 2 * * * )', '', 1, 6, 6, 1685781545, 1685781516, 1685781609);

INSERT INTO `wait_article` VALUES (1, 1, '0基础学PHP到底有多难', 'static/common/images/init/article01.jpg', '但是任何一门技术，如果轻易就能够让人学会，那也不会称作为技术，因为那样，工作的可替代性太强了技术，只有难学才会更有价值。对于零基础的同学来说，学习php肯定是非常需要毅力的，任何语言的学习都不可能一蹴而就，而是需要花大量时间，消耗大量精力才能学会的！', '<!DOCTYPE html>\n<html>\n<head>\n</head>\n<body>\n<p>PHP作为WEB端最佳的开发语言，没有华而不实，而是经受住了时间考验，是一门非常值得学习的编程语言。</p>\n<p>目前市场上各种网站、管理系统、小程序、APP等，基本都是使用PHP开发的，也侧面反映了PHP的需求以及学习的必要性！</p>\n<p>对于刚接触PHP的同学，作为过来人，这里分享一下PHP的一些学习经验！</p>\n<p>&nbsp;</p>\n<p><strong>一、学php难吗？</strong></p>\n<p>难！</p>\n<p>但是任何一门技术，如果轻易就能够让人学会，那也不会称作为技术，因为那样，工作的可替代性太强了技术，只有难学才会更有价值。对于零基础的同学来说，学习php肯定是非常需要毅力的，任何语言的学习都不可能一蹴而就，而是需要花大量时间，消耗大量精力才能学会的！</p>\n<p>&nbsp;</p>\n<p><strong>二、学php有没有技巧？</strong></p>\n<p>当然有，这也是这篇文章想说明的</p>\n<p>&nbsp;</p>\n<ul>\n<li>\n<p><strong>php学习第一要点：心态</strong></p>\n</li>\n</ul>\n<p>虽然目前php语言市场火爆，而且php语言相对于其他语言来说也更容易学习，但是千万别把php想的太简单，不要全信培训学校的宣传，仿佛零基础的人分分钟钟，随随便便就将php学会。</p>\n<p>失败的案例肯定不少。</p>\n<p>当然我也不要把php想的太难，既然你想从事这方面的工作，那么就要准备全力以赴，破釜沉舟。3个月学会不会，那就坚持到4个月，4个月还不会，那就坚持到5个月（需要一点点乌龟精神），总有一天，会全面掌握php知识，拿到自己满意的薪酬。</p>\n<p>&nbsp;</p>\n<ul>\n<li>\n<p><strong>php学习的第二要点：就是学习方法</strong></p>\n</li>\n</ul>\n<p>这里，建议大家还是报一个班，比如php中文网，他们家也有线上直播班，口碑一直不错。</p>\n<p>一个人学习php太难，如果说有一群人一起来学习，就能够营造出一种学习php氛围，有老师教，学习php碰到问题也可以得到解决。</p>\n<p>这里，就会碰到一个问题，那就是一个班，有零基础的、有从事过这方面工作的，php水平可谓是层次不齐，如果是一个零基础的同学学习php，如何跟上学校的讲课进度？</p>\n<p>&nbsp;</p>\n<ul>\n<li>\n<p><strong>php学习的第三要点：那就是坚持、坚持、再坚持，抵御诱惑</strong></p>\n</li>\n</ul>\n<p>PHP是最适合新手入门学习的首选语言，但是很多新手刚接触编程无所适从，也许学了一半PHP，又开始打C#主意，或者有人说JA VA 很强，这个时候的绝对不能动摇，哪怕我真想学，也得学会了PHP然后再学。</p>\n<p>见异思迁，是最不可取的！狗熊掰玉米就是这个道理，如果经常中途放弃，只能是一无所获，还浪费了N多的时间和经历。当我花费了大量精力后却又放弃了php，相信心里面会很难过，对未来又会陷入到迷茫中，严重打击自信心。</p>\n<p>&nbsp;</p>\n<p><strong>三、如果不想有这种体验，那就坚持学会php吧！</strong></p>\n</body>\n</html>', 4, 0, 0, 1, 1, 1, 0, 1676559819, 1679128015, 0);
INSERT INTO `wait_article` VALUES (2, 1, '28岁了打算入行PHP还来得及吗', 'static/common/images/init/article02.jpg', '来不来得及不用考虑，只要你技术学的扎实，找一份工作还是没问题。你要考虑的是转行成本：如果你现在只是拿着四五千的薪资，工作也没什么成长性，即使辞了这份工作，很容易也能找到差不多的，那你可以考虑入行PHP。', '<!DOCTYPE html>\n<html>\n<head>\n</head>\n<body>\n<p>来不来得及不用考虑，只要你技术学的扎实，找一份工作还是没问题。</p>\n<p><strong>你要考虑的是转行成本：</strong></p>\n<p>如果你现在只是拿着五六千的薪资，工作也没什么成长性，即使辞了这份工作，很容易也能找到差不多的，那你可以考虑入行PHP。</p>\n<p>虽然没有年龄上的优势，但是同样的，你在28岁这个年纪，就算转入任何一个行业皆是如此。</p>\n<p>就看你自己有没有破釜沉舟的决心，以及能不能抗住学习的压力和刚进公司的压力。</p>\n<p><strong>至少在目前迈进PHP行业的门槛并不高：</strong></p>\n<p>1、会一些简单的&nbsp;<code>HTML+CSS+JavaScript&nbsp;</code>就可以完成简单的页面布局；</p>\n<p>2、掌握基本的<code>PHP+MySQL</code>，学习如何将<code>PHP</code>与<code>前端</code>结合起来，完成简单的动态页面。</p>\n<p>3、学习一些主流前后端框架，比如<code>ThinkPHP</code>、<code>LayUI</code>，提升开发效率， 最终完成一个功能齐全的动态站点。</p>\n<p>&nbsp;</p>\n<p>另外这个行业并不是只能一开始就来，后来的都上不了车，不像医生，老师，律师，金融等行业一样，把车门都给你焊的死死的。</p>\n<p>&nbsp;</p>\n<p><strong>只要肯努力，多做项目，多学习，一年肯定能称得上别人三年的经验，成为 &ldquo; 后起之秀 &rdquo;！</strong></p>\n<p><strong>加油！</strong></p>\n</body>\n</html>', 1, 0, 0, 1, 1, 1, 0, 1676559854, 1679069781, 0);
INSERT INTO `wait_article` VALUES (3, 1, '初中级的PHPer应该具备这些技能', 'static/common/images/init/article03.jpg', '又到了，金三银四，换工作季。我没准备换工作，倒是上网翻了翻招聘信息，由于我做PHP，就看了下当下3-5年的招聘需求，发现这些招聘信息都有如下要求', '<!DOCTYPE html>\n<html>\n<head>\n</head>\n<body>\n<p><strong>3-5年PHP需要具备：</strong></p>\n<ul>\n<li><code>TCP/UDP</code>协议，&nbsp;<code>socket</code>通信，熟练使用<code>workman</code>，<code>swoole</code>，<code>swoft</code>等rpc框架</li>\n<li>精通<code>PHP</code>，另外最好熟悉一门其他编程语言</li>\n<li>熟悉<code>html</code>，<code>css</code>，<code>javascript</code>，会<code>nodejs</code>，<code>vue</code>优先</li>\n<li><code>mysql</code>， 以及<code>SQL</code>优化，熟悉索引应用和优化，独立设计数据库、数据表</li>\n<li><code>nosql</code>，<code>mongodb</code>，&nbsp;<code>redis</code>，<code>memcache</code>缓存。熟悉后端缓存技术、了解缓存使用场景，高并发、高性能服务系统设计经验及能力，熟悉大规模集群系统的开发</li>\n<li>常用<code>Linux</code>，<code>shell</code>命令编写，熟悉云、容器使用</li>\n<li>精通<code>LNMP</code>架构，熟悉<code>http&nbsp;</code>协议，<code>RestFul API</code>开发，熟悉<code>tp</code>，<code>laravel</code>，<code>yii</code>主流框架。</li>\n<li>熟练使用<code>svn</code>，<code>git</code>，<code>Hg</code>版本管理工具，</li>\n<li>良好的书写习惯，注释，设计模式，编写高质量的，整洁简单，可维护性的代码，遵循公司研发规范，产品技术文档的整理</li>\n<li>分析和快速排查定位解决线上问题，保障系统功能的稳定性，优化现有系统，提升运作性能</li>\n<li>主导/参与项目的架构设计、技术选型、架构原型实现以及服务端核心模块的开发，与各技术人员紧密合作，完成工作任务</li>\n<li>有个人博客，个人开源项目，有个人独立完成项目。</li>\n<li>乐于持续学习，乐观开朗，抗压性强，良好的沟通能力和合作精神，自我驱动力强，有强烈的事业心和责任感</li>\n</ul>\n<p>大家可以看看自己是否达到了主流的用人标准，如果你是超出预期，那么你可以选择跳得更高。</p>\n<p>3-5年时间，足够把一个学生培养成一个合格的打工人了。可以看到企业还是把PHPer当作多面手看待，希望不仅需要精通PHP，还需要掌握前端和运维等方面的知识。对技术高低的评判主要是对高性能、高并发的设计，这个时候会不会用第三方工具（Redis，ES），了不了解限流、队列、削峰、缓存这些原理就尤为重要。</p>\n<p>3-5年的phper，企业还希望有一定的带团能力，由此可见phper的成熟期是较短的。</p>\n<p>我同时对比了3-5年的NodeJS，Python、Java、Golang就职要求，要求本科以上，至少4年以上经验，更侧重逻辑算法，门槛比较高。</p>\n<p>或许去年疫情情况，让很多人觉得PHP岗位是不是少了，其实疫情对于IT公司多少都有所影响，各个技术岗位裁员也是难免的，不过这些只是短暂的变动。</p>\n</body>\n</html>', 4, 0, 0, 1, 1, 1, 0, 1678282068, 1679127970, 0);
INSERT INTO `wait_article` VALUES (4, 1, '别再学这五个要被淘汰的编程语言了', 'static/common/images/init/article04.jpg', '编程语言都有自己的生命周期，对某些语言来说，属于它们的时代似乎已经结束了，今天，我们就来盘下一下目前前景最黯淡的5种语言。', '<!DOCTYPE html>\n<html>\n<head>\n</head>\n<body>\n<p>每个编码的人都有自己喜欢的语言。拥有一种首选语言有很多原因，但是，我们的语言有时会变得很单调，它不再由制造它的公司维护，或者人们出于某种无法解释的原因放弃使用它。但是，也有的编程语言例外，比如C语言，它就经受住了时间的考验，在许多情况下仍然是最流行的编程语言。</p>\n<p>编程语言都有自己的生命周期，对某些语言来说，属于它们的时代似乎已经结束了，今天，我们就来盘下一下目前前景最黯淡的5种语言：</p>\n<p>&nbsp;</p>\n<p><strong>1. Visual Basic .NET</strong></p>\n<p>Microsoft Visual Basic.NET 是 Microsoft Visual Basic 6.0 的后续版本，它是基于 .NET 框架重新设计的，在1991年，微软增强了BASIC语言，将其包含到语言中，形成了Visual Basic，后来发生了一些事情：德尔福（Borland）的负责人安德斯&middot;海尔斯伯格（Anders Hejlsberg）离开了公司，加入了微软，在那里他开始了C#项目。</p>\n<p>这种语言在许多方面与Java相似，一段时间后，C#成为了微软的新语言标准。与c#诞生同时，微软程序员发明了VisualBasic . net，它的语法与BASIC相同，但代码模仿了C#。这两种语言都广为人知，但c#似乎赢得了流行度的竞赛。因此，Visual Basic似乎注定要消亡。</p>\n<p>&nbsp;</p>\n<p><strong>2.Delphi</strong></p>\n<p>Delphi，也就是Pascal + Objects，最有可能被淘汰，即便Embarcadero已经尝试支持它，新版本仍在发布中。这主要归结于Borland的一系列战略失误。</p>\n<p>首先，，他们把名字改成了Imprise。然而，这并没有起作用，于是又回到了之前的名字，并突然将他们的数据库工具从编程工具中分离出来。</p>\n<p>后者被重新命名为CodeGear，但出于某种原因，人们开始怀疑出了什么问题：如此频繁的名称更改，如此频繁的战略更改，让这门语言的拥护者离他而去。</p>\n<p>Embarcadero的持续努力是否能让Delphi继续下去还有待观察，但很明显Delphi在编程世界中正在失去青睐。也许是时候换一个不同的平台了。</p>\n<p>&nbsp;</p>\n<p><strong>3.Perl</strong></p>\n<p>曾经有一段时间，每个人都用Perl编程，但是后来发生了一些事情，开发者开始在不知道原因的情况下添加越来越大的功能，也许这增加了了问题的复杂性。甚至它的作者似乎已经含蓄地解释了Perl的一些问题，并选择停止从2000年开始的Perl 6开发，关键是，似乎现在也没人想要在用Perl。</p>\n<p>&nbsp;</p>\n<p><strong>4. Adobe Flash</strong></p>\n<p>我们这里讨论的不是语言，而是平台。当史蒂夫&middot;乔布斯选择不在苹果的移动设备上使用Adobe Flash时，Adobe Flash的丧钟就敲响了。</p>\n<p>如果其中一个新平台，比如苹果的平板电脑，不支持Flash应用程序，开发者将不得不使用Javascript、HTML5或其他苹果批准的平台来创建这些应用程序。结果，Flash尽管不断进步，却开始衰落。如今，它还是避免不了消亡。</p>\n<p>&nbsp;</p>\n<p><strong>5.Ruby</strong></p>\n<p>Ruby在大约10年前风靡一时，它在1995年首次亮相后就有了一大批的拥护者，很多人会拿Ruby和C类语言做比较。</p>\n<p>毫无疑问，这是一种非常棒的编程语言，尽管它的发展速度很慢，例如，Twitter有许多用Ruby构建的东西，但由于效率低下而放弃了它，而这一发现的那天很可能就是Ruby开始消亡的那天。</p>\n</body>\n</html>', 7, 0, 0, 1, 1, 1, 0, 1678282108, 1679127967, 0);
INSERT INTO `wait_article` VALUES (5, 2, '怎么用php实现多对一通讯录', 'static/common/images/init/article05.jpg', '随着移动互联网的快速发展，人们使用手机和电脑联系和交流的方式已经越来越多样化。电话、邮件、短信、社交媒体应用等，使得人们可以从各种角度与朋友、家人、同事等联系。', '<!DOCTYPE html>\n<html>\n<head>\n</head>\n<body>\n<p>随着移动互联网的快速发展，人们使用手机和电脑联系和交流的方式已经越来越多样化。电话、邮件、短信、社交媒体应用等，使得人们可以从各种角度与朋友、家人、同事等联系。然而，这些手段有时解决不了一些需要快速协作和沟通的问题，比如企业内部联系人的管理、学校老师和学生之间的通讯录共享等。这时，多对一的通讯录就成为了必需品。本文将介绍如何利用PHP语言实现多对一的通讯录功能。</p>\n<p>&nbsp;</p>\n<p><strong>一. 通讯录的基本功能</strong></p>\n<ul>\n<li>\n<p>添加联系人：包括联系人的姓名、电话、邮箱、公司、职位等基本信息。</p>\n</li>\n<li>\n<p>编辑联系人：对已有联系人的信息进行修改。</p>\n</li>\n<li>\n<p>删除联系人：删除已有联系人的信息。</p>\n</li>\n<li>\n<p>按条件查找联系人：可以通过关键字或者类别等方式来查找已有的联系人。</p>\n</li>\n<li>\n<p>导出联系人：将通讯录的联系人信息导出为Excel或CSV格式存储在电脑或手机上供离线使用。</p>\n</li>\n<li>\n<p>备份通讯录：定期备份通讯录的联系人信息，防止数据丢失。</p>\n</li>\n</ul>\n<p>&nbsp;</p>\n<p><strong>二. 多对一通讯录的设计思路</strong></p>\n<p>在多对一的通讯录中，多个用户共享同一个通讯录的数据。为了保证数据的安全性和用户能够顺利访问通讯录，需要进行以下设计：</p>\n<ul>\n<li>\n<p>数据库的设计：利用关系型数据库存储通讯录的联系人信息并进行数据权限控制，防止未经授权的人员访问通讯录。</p>\n</li>\n<li>\n<p>用户认证：为了保证对通讯录的访问权限，需要在应用中添加用户认证功能。</p>\n</li>\n<li>\n<p>用户管理：建立一个用户管理界面，可以对用户的权限进行管理，以及对用户进行添加、修改和删除等操作。</p>\n</li>\n<li>\n<p>数据展示：从数据库中提取通讯录信息显示在页面上。可以通过搜索、分类、排序等方式实现通讯录中的信息管理。</p>\n</li>\n<li>\n<p>权限控制：根据不同用户的权限，控制用户可以看到的通讯录信息。</p>\n</li>\n</ul>\n<p>&nbsp;</p>\n<p><strong>三. 实现多对一通讯录的具体步骤</strong></p>\n<p>以下是实现多对一通讯录的具体步骤：</p>\n<ul>\n<li>\n<p>创建数据库并建立适当的表结构，包括用户表和联系人表等。</p>\n</li>\n<li>\n<p>开发用户认证功能和用户管理功能，采用常见的用户认证方式，比如用户名和密码认证等。</p>\n</li>\n<li>\n<p>开发联系人的添加、编辑和删除功能，并实现数据的有效性验证和存储。</p>\n</li>\n<li>\n<p>实现通讯录的筛选、排序、分类等功能。可以采用Ajax技术，提高用户体验。</p>\n</li>\n<li>\n<p>开发导出联系人和备份通讯录的功能。可以通过第三方软件或类库实现。</p>\n</li>\n<li>\n<p>实现权限控制功能，根据用户的权限将数据进行展示。</p>\n</li>\n</ul>\n<p>&nbsp;</p>\n<p><strong>四. 总结</strong></p>\n<p>本文介绍了如何利用PHP语言实现多对一的通讯录功能。一个好的通讯录应当具有方便、实用、安全等特点，更好的实现信息管理的需要。本文提供了一些设计思路和实现步骤，希望能够为PHP开发者提供帮助和启示。要想实现一个完整的多对一通讯录功能，还需要不断地学习和研究相关技术和工具，提高自己的编程能力和实践经验。</p>\n</body>\n</html>', 4, 0, 0, 0, 1, 1, 0, 1678282149, 1679127963, 0);
INSERT INTO `wait_article` VALUES (6, 2, 'PHP如何实现不模糊包含表达式', 'static/common/images/init/article06.jpg', '不模糊包含表达式是指匹配字符串时必须完全匹配，而不是只匹配部分字符。在 PHP 中，可以使用 preg_match 函数来实现正则表达式匹配。', '<!DOCTYPE html>\n<html>\n<head>\n</head>\n<body>\n<p>不模糊包含表达式是指匹配字符串时必须完全匹配，而不是只匹配部分字符。在 PHP 中，可以使用 preg_match 函数来实现正则表达式匹配。</p>\n<p>&nbsp;</p>\n<p>例如，假设需要匹配的字符串为 \"hello world\"，则可以使用如下正则表达式：</p>\n<pre class=\"language-php\"><code>/^hello world$/</code></pre>\n<p>其中，^ 表示匹配字符串开头，$ 表示匹配字符串结尾。这样就可以确保只匹配完全相同的字符串，而不会模糊包含。</p>\n</body>\n</html>', 0, 0, 0, 0, 1, 1, 0, 1679048572, 1679070208, 0);
INSERT INTO `wait_article` VALUES (7, 2, 'PHP中Linux文件路径是否存在怎么判断?', '/static/common/images/init/article07.jpg', 'php linux文件路径是否存在的判断方法：1、使用linux命令“[ -f qipa250.txt ] && echo yes || echo no”判断文件是否存在；2、通过php调用linux命令，代码是“$pdf_file_exists = \'[ -f \' . $pdf_file_url . \' ] && echo true || echo false\';”', '<!DOCTYPE html>\n<html>\n<head>\n</head>\n<body>\n<blockquote>\n<p>php linux文件路径是否存在的判断方法：1、使用linux命令&ldquo;[ -f qipa250.txt ] &amp;&amp; echo yes || echo no&rdquo;判断文件是否存在；2、通过php调用linux命令，代码是&ldquo;$pdf_file_exists = \'[ -f \' . $pdf_file_url . \' ] &amp;&amp; echo true || echo false\';&rdquo;</p>\n</blockquote>\n<p>&nbsp;</p>\n<p>本教程操作环境：linux5.9.8系统、PHP8.1、Dell G3电脑。</p>\n<p><strong>php使用linux命令判断文件是否存在</strong></p>\n<p>Linux一句命令之判断文件是否存在</p>\n<pre class=\"language-php\"><code>[ -f qipa250.txt ] &amp;&amp; echo yes || echo no</code></pre>\n<p>-f 文件名字文件存在则为真。</p>\n<p>执行[ -f qipa250.txt ]为真则执行echo yes，由于或语句||的存在echo no不再执行。</p>\n<p>特别注意的是，这里的逻辑与和逻辑或值得仔细思考。</p>\n<p>php调用linux命令方法</p>\n<pre class=\"language-php\"><code>//指定文件路径\n\n$pdf_file_url=\'/data/web/QipaFile/qipa250.pdf\';\n\n//命令\n\n$pdf_file_exists = \'[ -f \' . $pdf_file_url . \' ] &amp;&amp; echo true || echo false\';\n\n//执行\n\necho $pdf_file_exists_result = system($pdf_file_exists);</code></pre>\n<p>&nbsp;</p>\n</body>\n</html>', 1, 0, 0, 0, 1, 1, 0, 1679048996, 1679127927, 0);
INSERT INTO `wait_article` VALUES (8, 2, 'PHP中多态性是什么意思?', 'static/common/images/init/article08.jpg', '在PHP中，多态性是指同一个操作作用于不同的类的实例，将产生不同的执行结果。也即不同类的对象收到相同的消息时，将得到不同的结果；不同的对象，收到同一消息将可以产生不同的结果，这种现象称为多态性。多态性允许每个对象以适合自身的方式去响应共同的消息；多态性增强了软件的灵活性和重用性。', '<!DOCTYPE html>\n<html>\n<head>\n</head>\n<body>\n<blockquote>\n<p>在PHP中，多态性是指同一个操作作用于不同的类的实例，将产生不同的执行结果。也即不同类的对象收到相同的消息时，将得到不同的结果；不同的对象，收到同一消息将可以产生不同的结果，这种现象称为多态性。多态性允许每个对象以适合自身的方式去响应共同的消息；多态性增强了软件的灵活性和重用性。</p>\n</blockquote>\n<p>&nbsp;</p>\n<h2><strong>PHP 多态性</strong></h2>\n<p>多态性是指相同的操作或函数、过程可作用于多种类型的对象上并获得不同的结果。不同的对象，收到同一消息将可以产生不同的结果，这种现象称为多态性。</p>\n<p>多态性允许每个对象以适合自身的方式去响应共同的消息。多态性增强了软件的灵活性和重用性。</p>\n<p>在面向对象的软件开发中，多态性是最为重要的部分之一。面向对象编程并不只是将相关的方法与数据简单的结合起来，而是采用面向对象编程中的各种要素将现实生活中的各种情况清晰的描述出来。这一小节将对面向对象编程中的多态性作详细的讲解。</p>\n<p>&nbsp;</p>\n<p><strong>1.什么是多态</strong></p>\n<p>多 态（Polymorphism）按字面上意思理解就是&ldquo;多种形状&rdquo;。可以理解为多种表现形式，也即&ldquo;一个对外接口，多个内部实现方法&rdquo;。在面向对象的理论 中，多态性的一般定义为：同一个操作作用于不同的类的实例，将产生不同的执行结果。也即不同类的对象收到相同的消息时，将得到不同的结果。</p>\n<p>在实际的应用开发中，采用面向对象中的多态主要在于可以将不同的子类对象都当作一个父类来处理，并且可以屏蔽不同子类对象之间所存在的差异，写出通用的代码，做出通用的编程，以适应需求的不断变化。</p>\n<p>&nbsp;</p>\n<p><strong>2. 多态的应用设计</strong></p>\n<p>在实际的应用开发中，通常为了使项目能够在以后的时间里的轻松实现扩展与升级，需要通过继承实现可复用模块进行轻松升级。在进行可复用模块设计时，就需要尽可能的减少使用流程控制语句。此时就可以采用多态实现该类设计。</p>\n</body>\n</html>', 4, 0, 0, 0, 1, 1, 0, 1679049080, 1679127920, 0);

INSERT INTO `wait_dev_banner` VALUES (1, 1, '别再学这五个被淘汰的语言了', 'static/common/images/init/carousel01.jpg', '_blank', '/frontend/article/detail?id=4', 0, 0, 0, 1678552333, 1679124590, 0);
INSERT INTO `wait_dev_banner` VALUES (2, 1, 'WaitAdmin开源快速开发框架', 'static/common/images/init/carousel02.jpg', '_blank', 'https://gitee.com/wafts/WaitAdmin', 0, 0, 0, 1679073756, 1679124611, 0);
INSERT INTO `wait_dev_banner` VALUES (3, 2, '阿里云特惠服务器推荐', 'static/common/images/init/gd01.jpg', '_blank', 'https://www.aliyun.com/minisite/goods?userCode=m5k1mahd&share_source=copy_link', 0, 0, 0, 1679073786, 1679124684, 0);
INSERT INTO `wait_dev_banner` VALUES (4, 2, 'WaitShop开源电商系统', 'static/common/images/init/gd02.jpg', '_blank', 'https://gitee.com/wafts/WaitShop', 0, 0, 0, 1679073793, 1679124639, 0);

INSERT INTO `wait_dev_links` VALUES (1, 'WaitShop', '_blank', 'https://gitee.com/wafts/WaitShop', 0, 0, 0, 1679070539, 1679070539, 0);
INSERT INTO `wait_dev_links` VALUES (2, 'Layui', '_blank', 'https://layui.github.io/', 0, 0, 0, 1679070297, 1679070455, 0);
INSERT INTO `wait_dev_links` VALUES (3, 'ThinkPHP', '_blank', 'https://www.thinkphp.cn/', 0, 0, 0, 1679070358, 1679070460, 0);

INSERT INTO `wait_dev_navigation` VALUES (1, 0, '首页', '_self', '/', 4, 0, 0, 1679124219, 1679124427, 0);
INSERT INTO `wait_dev_navigation` VALUES (2, 0, '网站资讯', '_self', '/frontend/article/lists', 3, 0, 0, 1679124269, 1679124451, 0);
INSERT INTO `wait_dev_navigation` VALUES (3, 0, '开发手册', '_blank', 'https://www.waitadmin.cn/docs/', 2, 0, 0, 1679124359, 1679124455, 0);
INSERT INTO `wait_dev_navigation` VALUES (4, 0, '源码下载', '_blank', 'https://github.com/topwait/waitadmin', 1, 0, 0, 1679124410, 1679124470, 0);

INSERT INTO `wait_notice_setting` VALUES (1, 101, '免密登录验证码', '\r\n用户手机号码登录时发送', '{\"code\":\"验证码\"}', '[]', '{\"status\":\"1\",\"content\":\"您的验证码：${code}，您正进行身份验证，打死不告诉别人！\",\"template_code\":\"SMS_182535543\"}', '[]', 1, 1, 0, 1677394201, 1677394201, 0);
INSERT INTO `wait_notice_setting` VALUES (2, 102, '账号注册验证码', '用户注册登录账号时发送', '{\"code\":\"验证码\"}', '[]', '{\"status\":\"1\",\"content\":\"您的验证码：${code}，您正进行身份验证，打死不告诉别人！\",\"template_code\":\"SMS_182535543\"}', '[]', 1, 1, 0, 1677394201, 1677394201, 0);
INSERT INTO `wait_notice_setting` VALUES (3, 103, '找回密码验证码', '用户找回登录密码时发送', '{\"code\":\"验证码\"}', '[]', '{\"status\":\"1\",\"content\":\"您的验证码：${code}，您正进行身份验证，打死不告诉别人！\",\"template_code\":\"SMS_182535543\"}', '[]', 1, 1, 0, 1677394201, 1677394201, 0);
INSERT INTO `wait_notice_setting` VALUES (4, 104, '变更手机验证码', '用户变更手机号码时发送', '{\"code\":\"验证码\"}', '[]', '{\"status\":\"1\",\"content\":\"您的验证码：${code}，您正进行身份验证，打死不告诉别人！\",\"template_code\":\"SMS_182535543\"}', '[]', 1, 1, 0, 1677394201, 1677394201, 0);
INSERT INTO `wait_notice_setting` VALUES (5, 105, '绑定手机验证码', '用户绑定手机号码时发送', '{\"code\":\"验证码\"}', '[]', '{\"status\":\"1\",\"content\":\"您的验证码：${code}，您正进行身份验证，打死不告诉别人！\",\"template_code\":\"SMS_182535543\"}', '[]', 1, 1, 0, 1677394201, 1677394201, 0);
INSERT INTO `wait_notice_setting` VALUES (6, 106, '绑定邮箱验证码', '用户绑定邮箱号码时发送', '{\"code\":\"验证码\"}', '[]', '[]', '{\"status\":\"1\",\"content\":\"您的验证码：${code}，您正进行身份验证，打死不告诉别人！\",\"template_code\":\"SMS_182535543\"}', 1, 1, 0, 1677394201, 1677394201, 0);

INSERT INTO `wait_sys_config` VALUES (1, 'storage', 'default', 'local', '存储引擎', 1665319505, 1679128285);
INSERT INTO `wait_sys_config` VALUES (2, 'storage', 'local', '[]', '本地存储', 1665319505, 1679128285);
INSERT INTO `wait_sys_config` VALUES (3, 'storage', 'qiniu', '{\"bucket\":\"\",\"accessKey\":\"\",\"secretKey\":\"\",\"domain\":\"\"}', '七牛存储', 1665319505, 1679128285);
INSERT INTO `wait_sys_config` VALUES (4, 'storage', 'aliyun', '{\"bucket\":\"\",\"accessKey\":\"\",\"secretKey\":\"\",\"domain\":\"\"}', '阿里存储', 1665319505, 1679128285);
INSERT INTO `wait_sys_config` VALUES (5, 'storage', 'qcloud', '{\"bucket\":\"\",\"region\":\"\",\"accessKey\":\"\",\"secretKey\":\"\",\"domain\":\"\"}', '腾讯存储', 1665319505, 1679128285);
INSERT INTO `wait_sys_config` VALUES (10, 'sms', 'default', 'aliyun', '短信引擎', 1665319505, 1665319505);
INSERT INTO `wait_sys_config` VALUES (11, 'sms', 'aliyun', '{\"sign\":\"\",\"access_key_id\":\"\",\"access_secret\":\"\"}', '阿里短信', 1665319505, 1665319505);
INSERT INTO `wait_sys_config` VALUES (12, 'sms', 'tencent', '{\"sign\":\"\",\"app_id\":\"\",\"secret_id\":\"\",\"secret_key\":\"\"}', '腾讯短信', 1665319505, 1665319505);
INSERT INTO `wait_sys_config` VALUES (20, 'website', 'copyright', '© 2023-2024 WaitAdmin开源团队工作室 版权所有 · www.waitadmin.cn', '版权信息', 1665319505, 1679125300);
INSERT INTO `wait_sys_config` VALUES (21, 'website', 'icp', '粤ICP备1843666号', 'ICP备案', 1665319505, 1679125300);
INSERT INTO `wait_sys_config` VALUES (22, 'website', 'pcp', '', '公安备案', 1665319505, 1679125300);
INSERT INTO `wait_sys_config` VALUES (23, 'website', 'analyse', '', '统计代码', 1665319505, 1679125300);
INSERT INTO `wait_sys_config` VALUES (40, 'pc', 'title', 'WaitAdmin开源管理系统', 'PC标题', 1665319505, 1679125300);
INSERT INTO `wait_sys_config` VALUES (41, 'pc', 'keywords', 'ThinkPHP通用后台,Layui后台,CMS内容管理系统,微信小程序,CMS管理系统', '关键词组', 1665319505, 1679125300);
INSERT INTO `wait_sys_config` VALUES (42, 'pc', 'description', 'WaitAdmin是基于ThinkPHP6+Layui开发的一套快速开发通用管理后台，集成Layui常用组件，RBAC权限管理， 让开发变的简单，界面简洁清爽，支持两种菜单结构可满足不同人群的需求，非常适合二开做项目。', '网站描述', 1665319505, 1679125300);
INSERT INTO `wait_sys_config` VALUES (43, 'pc', 'logo', 'static/common/images/init/logo_pc.png', 'PC图标', 1665319505, 1679125300);
INSERT INTO `wait_sys_config` VALUES (50, 'h5', 'title', 'WaitAdmin开源系统', 'H5标题', 1665319505, 1679125300);
INSERT INTO `wait_sys_config` VALUES (51, 'h5', 'logo', 'static/common/images/init/logo_h5.png', 'H5图标', 1665319505, 1679125300);
INSERT INTO `wait_sys_config` VALUES (52, 'h5', 'status', '1', 'H5状态', 1665319505, 1679125300);
INSERT INTO `wait_sys_config` VALUES (53, 'h5', 'close_url', '', 'H5关闭页面', 1665319505, 1679125300);
INSERT INTO `wait_sys_config` VALUES (60, 'login', 'is_agreement', '1', '显示登录协议', 1665319505, 1665319505);
INSERT INTO `wait_sys_config` VALUES (61, 'login', 'force_mobile', '1', '强制绑定手机', 1665319505, 1665319505);
INSERT INTO `wait_sys_config` VALUES (62, 'login', 'auths_mobile', '0', '微信授权手机', 1665319505, 1665319505);
INSERT INTO `wait_sys_config` VALUES (63, 'login', 'login_modes', '[\"1\",\"2\"]', '通用登录方式', 1665319505, 1665319505);
INSERT INTO `wait_sys_config` VALUES (64, 'login', 'login_other', '[\"1\"]', '第三方登录', 1665319505, 1665319505);
INSERT INTO `wait_sys_config` VALUES (70, 'mail', 'smtp_type', 'smtp', '邮件方式', 1665319505, 1679046920);
INSERT INTO `wait_sys_config` VALUES (71, 'mail', 'smtp_host', 'smtp.163.com', 'SMTP服务', 1665319505, 1679046920);
INSERT INTO `wait_sys_config` VALUES (72, 'mail', 'smtp_port', '25', 'SMTP端口', 1665319505, 1679046920);
INSERT INTO `wait_sys_config` VALUES (73, 'mail', 'smtp_user', 'waitadmin@163.com', 'SMTP账号', 1665319505, 1679046920);
INSERT INTO `wait_sys_config` VALUES (74, 'mail', 'smtp_pass', '', 'SMTP密码', 1665319505, 1679046920);
INSERT INTO `wait_sys_config` VALUES (75, 'mail', 'from_user', 'waitadmin@163.com', '发件人邮箱', 1665319505, 1679046920);
INSERT INTO `wait_sys_config` VALUES (76, 'mail', 'verify_type', '', 'SMTP验证', 1665319505, 1679046920);
INSERT INTO `wait_sys_config` VALUES (80, 'policy', 'service', '\n', '服务协议', 1665319505, 1665319505);
INSERT INTO `wait_sys_config` VALUES (81, 'policy', 'privacy', '', '隐私政策', 1665319505, 1665319505);
INSERT INTO `wait_sys_config` VALUES (200, 'watermark', 'status', '0', '水印功能状态', 1665319505, 1665319505);
INSERT INTO `wait_sys_config` VALUES (201, 'watermark', 'type', 'text', '水印文件类型', 1665319505, 1665319505);
INSERT INTO `wait_sys_config` VALUES (202, 'watermark', 'icon', 'static/common/images/watermark.png', '水印图片文件', 1665319505, 1665319505);
INSERT INTO `wait_sys_config` VALUES (203, 'watermark', 'fonts', 'WaitAdmin', '水印字体文字', 1665319505, 1665319505);
INSERT INTO `wait_sys_config` VALUES (204, 'watermark', 'color', '#000000', '水印字体颜色', 1665319505, 1665319505);
INSERT INTO `wait_sys_config` VALUES (205, 'watermark', 'size', '20', '水印字体大小', 1665319505, 1665319505);
INSERT INTO `wait_sys_config` VALUES (206, 'watermark', 'offset', '0', '水印字体偏移', 1665319505, 1665319505);
INSERT INTO `wait_sys_config` VALUES (207, 'watermark', 'angle', '0', '水印字体倾斜', 1665319505, 1665319505);
INSERT INTO `wait_sys_config` VALUES (208, 'watermark', 'alpha', '100', '水印图透明度', 1665319505, 1665319505);
INSERT INTO `wait_sys_config` VALUES (209, 'watermark', 'position', '1', '水印所在位置', 1665319505, 1665319505);
INSERT INTO `wait_sys_config` VALUES (210, 'wx_channel', 'name', '', '小程序名称', 1665319505, 1665319505);
INSERT INTO `wait_sys_config` VALUES (211, 'wx_channel', 'qr_code', '', '二维码', 1665319505, 1665319505);
INSERT INTO `wait_sys_config` VALUES (212, 'wx_channel', 'original_id', '', '原始ID', 1665319505, 1665319505);
INSERT INTO `wait_sys_config` VALUES (213, 'wx_channel', 'app_id', '', 'AppID', 1665319505, 1665319505);
INSERT INTO `wait_sys_config` VALUES (214, 'wx_channel', 'app_secret', '', 'AppSecret', 1665319505, 1665319505);
INSERT INTO `wait_sys_config` VALUES (215, 'oa_channel', 'name', '', '公众号名称', 1665319505, 1665319505);
INSERT INTO `wait_sys_config` VALUES (216, 'oa_channel', 'qr_code', '', '二维码', 1665319505, 1665319505);
INSERT INTO `wait_sys_config` VALUES (217, 'oa_channel', 'original_id', '', '原始ID', 1665319505, 1665319505);
INSERT INTO `wait_sys_config` VALUES (218, 'oa_channel', 'app_id', '', 'AppID', 1665319505, 1665319505);
INSERT INTO `wait_sys_config` VALUES (219, 'oa_channel', 'app_secret', '', 'AppSecret', 1665319505, 1665319505);
INSERT INTO `wait_sys_config` VALUES (220, 'oa_channel', 'token', 'waitadmin', 'Token', 1665319505, 1665319505);
INSERT INTO `wait_sys_config` VALUES (221, 'oa_channel', 'aes_key', '', 'EncodingAESKey', 1665319505, 1665319505);
INSERT INTO `wait_sys_config` VALUES (222, 'oa_channel', 'encryption_type', '1', '消息加密方式', 1665319505, 1665319505);
INSERT INTO `wait_sys_config` VALUES (230, 'op_channel', 'app_id', '', 'AppID', 1665319505, 1665319505);
INSERT INTO `wait_sys_config` VALUES (231, 'op_channel', 'app_secret', '', 'AppSecret', 1665319505, 1665319505);
INSERT INTO `wait_sys_config` VALUES (235, 'h5_channel', 'status', '0', '渠道状态', 1665319505, 1665319505);
INSERT INTO `wait_sys_config` VALUES (236, 'h5_channel', 'close_url', '', '关闭页面', 1665319505, 1665319505);
INSERT INTO `wait_sys_config` VALUES (800, 'diy', 'tabbar', '{\"style\":{\"selectedColor\":\"#2979ff\",\"unselectedColor\":\"#333333\"},\"list\":[{\"text\":\"\\u9996\\u9875\",\"iconPath\":\"/static/common/images/init/tab_home.png\",\"selectedIconPath\":\"/static/common/images/init/tab_home_no.png\"},{\"text\":\"\\u8d44\\u8baf\",\"iconPath\":\"/static/common/images/init/tab_archive.png\",\"selectedIconPath\":\"/static/common/images/init/tab_archive_no.png\"},{\"text\":\"\\u6211\\u7684\",\"iconPath\":\"/static/common/images/init/tab_user.png\",\"selectedIconPath\":\"/static/common/images/init/tab_user_no.png\"}]}', 'tabBar导航', 1665319505, 1665319505);
INSERT INTO `wait_sys_config` VALUES (801, 'diy', 'person', '{\"service\":{\"base\":{\"layout\":\"col\",\"number\":\"5\",\"title\":\"\\u6211\\u7684\\u670d\\u52a1\"},\"list\":[{\"image\":\"/static/common/images/init/me_user.png\",\"name\":\"\\u4e2a\\u4eba\\u8bbe\\u7f6e\",\"link\":\"\\/pages\\/user\\/intro\"},{\"image\":\"/static/common/images/init/me_customer.png\",\"name\":\"\\u8054\\u7cfb\\u5ba2\\u670d\",\"link\":\"\\/pages\\/index\\/customer\"},{\"image\":\"/static/common/images/init/me_about.png\",\"name\":\"\\u5173\\u4e8e\\u6211\\u4eec\",\"link\":\"\\/pages\\/index\\/about\"}]},\"adv\":{\"base\":{\"open\":1},\"list\":[{\"image\":\"/static/common/images/init/adv01.jpg\",\"name\":\"\\u5c31\\u5c06\\u8ba1\\u5c31\\u8ba1\",\"link\":\"\\/pages\\/article\\/detail?id=1\"},{\"image\":\"/static/common/images/init/adv02.jpg\",\"name\":\"\\u5c31\\u5c06\\u8ba1\\u5c31\\u8ba1\",\"link\":\"\\/pages\\/article\\/detail?id=1\"}]}}', '个人中心装修', 1665319505, 1665319505);
INSERT INTO `wait_sys_config` VALUES (802, 'diy', 'contact', '{\"title\":\"\\u6dfb\\u52a0\\u5ba2\\u670d\\u4e8c\\u7ef4\\u7801\",\"datetime\":\"\\u65e9\\u4e0a 9:00 - \\u665a\\u4e0a17:00\",\"mobile\":\"13800138000\",\"qq\":\"2474369941\",\"image\":\"static/common/images/init/contact_qr.png\"}', '联系客服装修', 1665319505, 1665319505);
INSERT INTO `wait_sys_config` VALUES (803, 'diy', 'theme', '{\"type\":\"blue\",\"color\":\"#2b85e4\"}', '主题风格装修', 1665319505, 1688914676);

INSERT INTO `wait_auth_menu` VALUES (1, 0, 'app', '首页', 'layui-icon icon-home-fill', 'index/console', 0, 1, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (10, 0, 'app', '权限', 'layui-icon icon-member-manage', '', 50, 1, 0, 0, 1648696695, 1678629659, 0);
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
INSERT INTO `wait_auth_menu` VALUES (200, 0, 'app', '设置', 'layui-icon icon-setup-fill', '', 60, 1, 0, 0, 1648696695, 1678629649, 0);
INSERT INTO `wait_auth_menu` VALUES (201, 200, 'app', '系统设置', '', 'setting.basics/index', 0, 1, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (202, 201, 'app', '配置页面', '', 'setting.basics/index', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (203, 201, 'app', '配置保存', '', 'setting.basics/save', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (205, 200, 'app', '渠道设置', '', 'setting.channel/index', 0, 1, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (206, 205, 'app', '配置页面', '', 'setting.channel/index', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (207, 205, 'app', '配置保存', '', 'setting.channel/save', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (210, 200, 'app', '存储设置', '', 'setting.storage/index', 0, 1, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (211, 210, 'app', '配置页面', '', 'setting.storage/index', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (212, 210, 'app', '配置保存', '', 'setting.storage/save', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (215, 200, 'app', '水印设置', '', 'setting.watermark/index', 0, 1, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (216, 215, 'app', '配置页面', '', 'setting.watermark/index', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (217, 215, 'app', '配置保存', '', 'setting.watermark/save', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (220, 200, 'app', '邮件设置', '', 'setting.email/index', 0, 1, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (221, 220, 'app', '配置页面', '', 'setting.email/index', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (222, 220, 'app', '配置保存', '', 'setting.email/save', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (225, 200, 'app', '登录设置', '', 'setting.login/index', 0, 1, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (226, 225, 'app', '配置页面', '', 'setting.login/index', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (227, 225, 'app', '配置 保存', '', 'setting.login/save', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (230, 200, 'app', '短信设置', '', 'setting.sms/index', 0, 1, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (231, 230, 'app', '配置页面', '', 'setting.sms/index', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (232, 230, 'app', '配置保存', '', 'setting.sms/save', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (235, 200, 'app', '通知设置', '', 'setting.notice/index', 0, 1, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (236, 235, 'app', '通知列表', '', 'setting.notice/index', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (237, 235, 'app', '通知编辑', '', 'setting.notice/edit', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (240, 200, 'app', '政策协议', '', 'setting.policy/index', 0, 1, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (241, 240, 'app', '配置页面', '', 'setting.policy/index', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (242, 240, 'app', '配置保存', '', 'setting.policy/save', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (300, 200, 'app', '电脑端', '', '', 100, 1, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (301, 300, 'app', '导航菜单', '', 'setting.pc.navigation/index', 0, 1, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (302, 301, 'app', '导航列表', '', 'setting.pc.navigation/index', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (303, 301, 'app', '导航新增', '', 'setting.pc.navigation/add', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (304, 301, 'app', '导航编辑', '', 'setting.pc.navigation/edit', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (305, 301, 'app', '导航删除', '', 'setting.pc.navigation/del', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (310, 300, 'app', '轮播图片', '', 'setting.pc.banner/index', 0, 1, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (311, 310, 'app', '轮播列表', '', 'setting.pc.banner/index', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (312, 310, 'app', '轮播新增', '', 'setting.pc.banner/add', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (313, 310, 'app', '轮播编辑', '', 'setting.pc.banner/edit', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (314, 310, 'app', '轮播删除', '', 'setting.pc.banner/del', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (320, 300, 'app', '友情链接', '', 'setting.pc.links/index', 0, 1, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (321, 320, 'app', '友链列表', '', 'setting.pc.links/index', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (322, 320, 'app', '友链新增', '', 'setting.pc.links/add', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (323, 320, 'app', '友链编辑', '', 'setting.pc.links/edit', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (324, 320, 'app', '友链删除', '', 'setting.pc.links/del', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (3000, 0, 'app', '系统', 'layui-icon icon-system', '', 70, 1, 0, 0, 1648696695, 1678629644, 0);
INSERT INTO `wait_auth_menu` VALUES (3001, 3000, 'app', '计划任务', '', 'system.crontab/index', 0, 1, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (3002, 3001, 'app', '任务列表', '', 'system.crontab/index', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (3003, 3001, 'app', '任务详情', '', 'system.crontab/detail', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (3004, 3001, 'app', '任务新增', '', 'system.crontab/add', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (3005, 3001, 'app', '任务编辑', '', 'system.crontab/edit', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (3006, 3001, 'app', '任务删除', '', 'system.crontab/del', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (3007, 3001, 'app', '任务启动', '', 'system.crontab/run', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (3008, 3001, 'app', '任务停止', '', 'system.crontab/stop', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (3010, 3000, 'app', '清除缓存', '', 'system.clear/index', 0, 1, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (3011, 3010, 'app', '操作页面', '', 'system.clear/index', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (3012, 3010, 'app', '立即清除', '', 'system.clear/clean', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (3015, 3000, 'app', '系统日志', '', 'system.log/index', 0, 1, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (3016, 3015, 'app', '日志列表', '', 'system.log/index', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (3017, 3015, 'app', '日志详情', '', 'system.log/detail', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (3020, 3000, 'app', '附件管理', '', 'system.attach/index', 0, 1, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (3021, 3020, 'app', '附件列表', '', 'attach/index', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (3022, 3020, 'app', '附件命名', '', 'attach/rename', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (3023, 3020, 'app', '附件移动', '', 'attach/move', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (3024, 3020, 'app', '附件删除', '', 'attach/del', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (3025, 3020, 'app', '分组列表', '', 'attach/cateLists', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (3026, 3020, 'app', '分组新增', '', 'attach/cateAdd', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (3027, 3020, 'app', '分组命名', '', 'attach/cateRename', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (3028, 3020, 'app', '分组删除', '', 'attach/cateDel', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (3030, 3000, 'app', '上传管理', '', '', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (3031, 3030, 'app', '附件上传', '', 'upload/attach', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (3032, 3030, 'app', '临时上传', '', 'upload/temporary', 0, 0, 0, 0, 1648696695, 1678713499, 0);
INSERT INTO `wait_auth_menu` VALUES (3050, 3000, 'app', '字典管理', '', 'system.dictType/index', 0, 1, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (3051, 3050, 'app', '字典类型列表', '', 'system.dictType/index', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (3052, 3050, 'app', '字典类型新增', '', 'system.dictType/add', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (3053, 3050, 'app', '字典类型编辑', '', 'system.dictType/edit', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (3054, 3050, 'app', '字典类型删除', '', 'system.dictType/del', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (3055, 3050, 'app', '字典数据列表', '', 'system.dictData/index', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (3056, 3050, 'app', '字典数据新增', '', 'system.dictData/add', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (3057, 3050, 'app', '字典数据编辑', '', 'system.dictData/edit', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (3058, 3050, 'app', '字典数据删除', '', 'system.dictData/del', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (3500, 0, 'app', '内容', 'layui-icon icon-text-doc', '', 20, 1, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (3501, 3500, 'app', '分类管理', '', 'content.category/index', 20, 1, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (3502, 3501, 'app', '分类列表', '', 'content.category/index', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (3503, 3501, 'app', '分类详情', '', 'content.category/detail', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (3504, 3501, 'app', '分类新增', '', 'content.category/add', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (3505, 3501, 'app', '分类编辑', '', 'content.category/edit', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (3506, 3501, 'app', '分类删除', '', 'content.category/del', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (3510, 3500, 'app', '文章管理', '', 'content.article/index', 10, 1, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (3511, 3510, 'app', '文章列表', '', 'content.article/index', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (3512, 3510, 'app', '文章详情', '', 'content.article/detail', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (3513, 3510, 'app', '文章新增', '', 'content.article/add', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (3514, 3510, 'app', '文章编辑', '', 'content.article/edit', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (3515, 3510, 'app', '文章删除', '', 'content.article/del', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (4000, 0, 'app', '用户', 'layui-icon icon-member-user', '', 30, 1, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (4001, 4000, 'app', '用户管理', '', 'user.user/index', 0, 1, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (4002, 4001, 'app', '用户列表', '', 'user.user/index', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (4003, 4001, 'app', '用户详情', '', 'user.user/detail', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (4004, 4001, 'app', '设置分组', '', 'user.user/group', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (4100, 4000, 'app', '用户分组', '', 'user.group/index', 0, 1, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (4101, 4100, 'app', '分组列表', '', 'user.group/index', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (4102, 4100, 'app', '分组详情', '', 'user.group/detail', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (4103, 4100, 'app', '分组新增', '', 'user.group/add', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (4104, 4100, 'app', '分组编辑', '', 'user.group/edit', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (4105, 4100, 'app', '分组删除', '', 'user.group/del', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (4110, 4000, 'app', '用户足迹', '', 'user.visitor/index', 0, 1, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (4111, 4110, 'app', '足迹列表', '', 'user.visitor/index', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (4112, 4110, 'app', '足迹详情', '', 'user.visitor/detail', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (5700, 0, 'app', '装修', 'layui-icon layui-icon-windows', '', 40, 1, 0, 0, 1648696695, 1678629666, 0);
INSERT INTO `wait_auth_menu` VALUES (5720, 5700, 'app', '底部导航', '', 'diy.tabbar/index', 0, 1, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (5721, 5720, 'app', '底部装修页面', '', 'diy.tabbar/index', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (5722, 5720, 'app', '底部装修保存', '', 'diy.tabbar/save', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (5730, 5700, 'app', '个人中心', '', 'diy.person/index', 0, 1, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (5731, 5730, 'app', '个人装修页面', '', 'diy.person/index', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (5732, 5730, 'app', '个人装修保存', '', 'diy.person/save', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (5740, 5700, 'app', '客服设置', '', 'diy.contact/index', 0, 1, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (5741, 5740, 'app', '客服装修页面', '', 'diy.contact/index', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (5742, 5740, 'app', '客服装修保存', '', 'diy.contact/save', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (5745, 5700, 'app', '主题风格', '', 'diy.theme/index', 0, 1, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (5746, 5745, 'app', '主题装修页面', '', 'diy.theme/index', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (5747, 5745, 'app', '主题装修保存', '', 'diy.theme/save', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (6000, 3000, 'curd', '代码生成', 'layui-icon icon-seckill-flash', 'addons/curd/gen/index', 0, 1, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (6001, 6000, 'curd', '生成列表', '', 'addons/curd/gen/index', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (6002, 6000, 'curd', '查看库表', '', 'addons/curd/gen/tables', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (6003, 6000, 'curd', '更新库表', '', 'addons/curd/gen/update', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (6004, 6000, 'curd', '销毁库表', '', 'addons/curd/gen/destroy', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (6005, 6000, 'curd', '同步库表', '', 'addons/curd/gen/synchrony', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (6006, 6000, 'curd', '导入库表', '', 'addons/curd/gen/imports', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (6007, 6000, 'curd', '导出生成', '', 'addons/curd/gen/exports', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (6008, 6000, 'curd', '下载生成', '', 'addons/curd/gen/download', 0, 0, 0, 0, 1648696695, 1648696695, 0);
INSERT INTO `wait_auth_menu` VALUES (6009, 6000, 'curd', '预览生成', '', 'addons/curd/gen/preview', 0, 0, 0, 0, 1648696695, 1648696695, 0);
