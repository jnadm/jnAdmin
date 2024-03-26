/*
 Navicat Premium Data Transfer

 Source Server         : jia
 Source Server Type    : MySQL
 Source Server Version : 80033
 Source Host           : 118.25.149.111:3306
 Source Schema         : la9

 Target Server Type    : MySQL
 Target Server Version : 80033
 File Encoding         : 65001

 Date: 26/03/2024 09:31:01
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for sys_admin
-- ----------------------------
DROP TABLE IF EXISTS `sys_admin`;
CREATE TABLE `sys_admin`  (
  `id` int(0) UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '用户名',
  `password` char(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '密码',
  `salt` char(6) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '安全码',
  `mobile` char(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '手机号',
  `role_id` int(0) UNSIGNED NOT NULL DEFAULT 0 COMMENT '角色id',
  `nickname` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '昵称',
  `real_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '真实姓名',
  `avatar` int(0) UNSIGNED NOT NULL DEFAULT 0 COMMENT '头像(comm_upload表id)',
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'email地址',
  `status` tinyint(0) NOT NULL DEFAULT 0 COMMENT '状态1启用0停用',
  `fail_nums` int(0) UNSIGNED NOT NULL DEFAULT 0 COMMENT '登录失败次数',
  `login_time` int(0) UNSIGNED NOT NULL DEFAULT 0 COMMENT '最后一次登录时间',
  `login_ip` smallint(0) UNSIGNED NOT NULL DEFAULT 0 COMMENT '登录ip',
  `created_id` int(0) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建人',
  `updated_id` int(0) UNSIGNED NOT NULL DEFAULT 0 COMMENT '修改人',
  `created_at` datetime(0) NOT NULL COMMENT '创建时间',
  `updated_at` datetime(0) NOT NULL COMMENT '更新时间',
  `deleted_at` datetime(0) NULL DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '管理员表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of sys_admin
-- ----------------------------
INSERT INTO `sys_admin` VALUES (1, 'admin', '11f685143271aa5beaae4dcd58566d86', 'a@&*6', '16666666666', 1, '超级管理员', '超级管理员', 0, '', 1, 0, 0, 0, 0, 0, '2024-02-10 00:00:00', '2024-02-10 00:00:00', NULL);

-- ----------------------------
-- Table structure for sys_file
-- ----------------------------
DROP TABLE IF EXISTS `sys_file`;
CREATE TABLE `sys_file`  (
  `id` int(0) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '附件名称',
  `origin_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '附件原名称',
  `ext` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '附件扩展名',
  `size` int(0) UNSIGNED NOT NULL DEFAULT 0 COMMENT '附件大小',
  `path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '存储路径',
  `thumb` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '缩略图路径',
  `table_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '使用附件的表名',
  `field_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '使用附件的字段名',
  `created_id` int(0) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建人',
  `updated_id` int(0) UNSIGNED NOT NULL DEFAULT 0 COMMENT '修改人',
  `created_at` datetime(0) NOT NULL COMMENT '创建时间',
  `updated_at` datetime(0) NOT NULL COMMENT '更新时间',
  `deleted_at` datetime(0) NULL DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '附件存储表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of sys_file
-- ----------------------------

-- ----------------------------
-- Table structure for sys_menu
-- ----------------------------
DROP TABLE IF EXISTS `sys_menu`;
CREATE TABLE `sys_menu`  (
  `id` int(0) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '菜单名称',
  `code` char(8) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '菜单标识',
  `parent_code` char(8) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '菜单父级id',
  `all_parent_codes` json NOT NULL COMMENT '所有上级',
  `all_child_codes` json NOT NULL COMMENT '所有下级',
  `path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '后端路由',
  `component` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '前端路由',
  `type` tinyint(0) NOT NULL DEFAULT 1 COMMENT '类型：1菜单，2按钮',
  `is_show` tinyint(0) NOT NULL DEFAULT 0 COMMENT '是否显示：1是0否',
  `sort` int(0) NOT NULL DEFAULT 0 COMMENT '排序号',
  `created_id` int(0) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建人',
  `updated_id` int(0) UNSIGNED NOT NULL DEFAULT 0 COMMENT '修改人',
  `created_at` datetime(0) NOT NULL COMMENT '创建时间',
  `updated_at` datetime(0) NOT NULL COMMENT '更新时间',
  `deleted_at` datetime(0) NULL DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '菜单表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of sys_menu
-- ----------------------------
INSERT INTO `sys_menu` VALUES (1, '系统管理', 'SYSTEM01', '0', '[]', '[]', '', '', 1, 1, 0, 0, 0, '2024-02-10 00:00:00', '2024-02-10 00:00:00', NULL);
INSERT INTO `sys_menu` VALUES (2, '账号管理', 'SYSTEM02', 'SYSTEM01', '[\"SYSTEM01\"]', '[]', '', '', 1, 1, 0, 0, 0, '2024-02-10 00:00:00', '2024-02-10 00:00:00', NULL);
INSERT INTO `sys_menu` VALUES (3, '角色管理', 'SYSTEM03', 'SYSTEM01', '[\"SYSTEM01\"]', '[]', '', '', 1, 1, 0, 0, 0, '2024-02-10 00:00:00', '2024-02-10 00:00:00', NULL);
INSERT INTO `sys_menu` VALUES (4, '菜单管理', 'SYSTEM05', 'SYSTEM01', '[\"SYSTEM01\"]', '[]', '', '', 1, 1, 0, 0, 0, '2024-02-10 00:00:00', '2024-02-10 00:00:00', NULL);

-- ----------------------------
-- Table structure for sys_permission
-- ----------------------------
DROP TABLE IF EXISTS `sys_permission`;
CREATE TABLE `sys_permission`  (
  `id` int(0) UNSIGNED NOT NULL AUTO_INCREMENT,
  `menu_codes` json NOT NULL COMMENT '权限内容',
  `created_id` int(0) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建人',
  `updated_id` int(0) UNSIGNED NOT NULL DEFAULT 0 COMMENT '修改人',
  `created_at` datetime(0) NOT NULL COMMENT '创建时间',
  `updated_at` datetime(0) NOT NULL COMMENT '更新时间',
  `deleted_at` datetime(0) NULL DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '权限表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of sys_permission
-- ----------------------------

-- ----------------------------
-- Table structure for sys_role
-- ----------------------------
DROP TABLE IF EXISTS `sys_role`;
CREATE TABLE `sys_role`  (
  `id` int(0) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '角色名称',
  `status` tinyint(0) NOT NULL DEFAULT 1 COMMENT '状态：1启用0停用',
  `sort` tinyint(0) NOT NULL DEFAULT 0 COMMENT '序号',
  `created_id` int(0) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建人',
  `updated_id` int(0) UNSIGNED NOT NULL DEFAULT 0 COMMENT '修改人',
  `created_at` datetime(0) NOT NULL COMMENT '创建时间',
  `updated_at` datetime(0) NOT NULL COMMENT '更新时间',
  `deleted_at` datetime(0) NULL DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '角色表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of sys_role
-- ----------------------------
INSERT INTO `sys_role` VALUES (1, '超级管理员', 1, 0, 0, 0, '2024-02-10 00:00:00', '2024-02-10 00:00:00', NULL);

-- ----------------------------
-- Table structure for sys_role_admin
-- ----------------------------
DROP TABLE IF EXISTS `sys_role_admin`;
CREATE TABLE `sys_role_admin`  (
  `id` int(0) UNSIGNED NOT NULL AUTO_INCREMENT,
  `role_id` int(0) UNSIGNED NOT NULL DEFAULT 0 COMMENT '角色id',
  `admin_id` int(0) UNSIGNED NOT NULL DEFAULT 0 COMMENT '管理员id',
  `created_id` int(0) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建人',
  `updated_id` int(0) UNSIGNED NOT NULL DEFAULT 0 COMMENT '修改人',
  `created_at` datetime(0) NOT NULL COMMENT '创建时间',
  `updated_at` datetime(0) NOT NULL COMMENT '更新时间',
  `deleted_at` datetime(0) NULL DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '角色管理员关联表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of sys_role_admin
-- ----------------------------
INSERT INTO `sys_role_admin` VALUES (1, 1, 1, 0, 0, '2024-02-10 00:00:00', '2024-02-10 00:00:00', NULL);

-- ----------------------------
-- Table structure for sys_role_permission
-- ----------------------------
DROP TABLE IF EXISTS `sys_role_permission`;
CREATE TABLE `sys_role_permission`  (
  `id` int(0) UNSIGNED NOT NULL AUTO_INCREMENT,
  `role_id` int(0) UNSIGNED NOT NULL DEFAULT 0 COMMENT '角色id',
  `permission_id` int(0) UNSIGNED NOT NULL DEFAULT 0 COMMENT '权限id',
  `created_id` int(0) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建人',
  `updated_id` int(0) UNSIGNED NOT NULL DEFAULT 0 COMMENT '修改人',
  `created_at` datetime(0) NOT NULL COMMENT '创建时间',
  `updated_at` datetime(0) NOT NULL COMMENT '更新时间',
  `deleted_at` datetime(0) NULL DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '角色权限关联表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of sys_role_permission
-- ----------------------------

SET FOREIGN_KEY_CHECKS = 1;
