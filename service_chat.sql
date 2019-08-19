/*
 Navicat MySQL Data Transfer

 Source Server         : local
 Source Server Type    : MySQL
 Source Server Version : 50727
 Source Host           : localhost:3306
 Source Schema         : service_chat

 Target Server Type    : MySQL
 Target Server Version : 50727
 File Encoding         : 65001

 Date: 19/08/2019 17:10:17
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for bind_secrets
-- ----------------------------
DROP TABLE IF EXISTS `bind_secrets`;
CREATE TABLE `bind_secrets`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '登陆用户账号',
  `secret` char(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '登陆用户绑定的google秘钥',
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of bind_secrets
-- ----------------------------
INSERT INTO `bind_secrets` VALUES (1, 'nidaye2', 'HCBUXTQ5P7LPT4DQ', '2019-07-12 19:11:42', '2019-07-12 19:11:42');

-- ----------------------------
-- Table structure for chat_messages
-- ----------------------------
DROP TABLE IF EXISTS `chat_messages`;
CREATE TABLE `chat_messages`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `player_id` int(11) NOT NULL COMMENT '玩家ID',
  `service_id` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '客服ID',
  `status` tinyint(4) NOT NULL DEFAULT 0 COMMENT '接收状态： 0 未接收，1：已接收',
  `u_type` tinyint(4) NOT NULL COMMENT '发送者类型: 1：玩家 2：客服',
  `msg` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'type:1 时代表玩家发送的消息 type:2 时表示客服回复的消息',
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 148 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of chat_messages
-- ----------------------------
INSERT INTO `chat_messages` VALUES (72, 1, 'kftest', 1, 1, 'hello', '2019-07-15 18:17:00', '2019-07-15 18:50:02');
INSERT INTO `chat_messages` VALUES (73, 1, 'kftest', 1, 1, 'hi there', '2019-07-15 18:46:03', '2019-07-15 19:05:01');
INSERT INTO `chat_messages` VALUES (74, 1, 'kftest', 1, 2, 'yes', '2019-07-15 19:01:50', '2019-07-15 19:01:50');
INSERT INTO `chat_messages` VALUES (75, 1, 'kftest', 1, 2, '请问什么问题', '2019-07-15 19:02:32', '2019-07-15 19:02:32');
INSERT INTO `chat_messages` VALUES (76, 1, 'kftest', 1, 1, 'tomorrow', '2019-07-15 19:03:07', '2019-07-15 19:03:07');
INSERT INTO `chat_messages` VALUES (77, 1, 'kftest', 1, 2, 'ok', '2019-07-15 19:03:28', '2019-07-15 19:03:28');
INSERT INTO `chat_messages` VALUES (78, 1, 'kftest', 1, 1, 'morning', '2019-07-16 11:10:46', '2019-07-16 11:10:46');
INSERT INTO `chat_messages` VALUES (79, 1, 'kftest', 1, 2, 'good morning', '2019-07-16 11:11:37', '2019-07-16 11:11:37');
INSERT INTO `chat_messages` VALUES (80, 1, 'kftest', 1, 1, 'question1', '2019-07-16 11:13:04', '2019-07-16 11:45:01');
INSERT INTO `chat_messages` VALUES (81, 1, 'kftest', 1, 1, 'aaa', '2019-07-16 11:44:23', '2019-07-16 11:55:02');
INSERT INTO `chat_messages` VALUES (82, 1, 'kftest', 1, 1, 'abc', '2019-07-16 11:53:35', '2019-07-16 11:53:35');
INSERT INTO `chat_messages` VALUES (83, 1, 'kftest', 1, 1, 'aaa', '2019-07-16 11:57:39', '2019-07-16 11:57:39');
INSERT INTO `chat_messages` VALUES (84, 1, 'kftest', 1, 1, 'aaa', '2019-07-16 11:58:09', '2019-07-16 16:44:17');
INSERT INTO `chat_messages` VALUES (85, 1, 'kftest', 1, 1, 'aaa', '2019-07-16 16:24:40', '2019-07-16 16:24:40');
INSERT INTO `chat_messages` VALUES (86, 1, 'kftest', 1, 1, 'bmw', '2019-07-16 16:44:43', '2019-07-16 16:44:43');
INSERT INTO `chat_messages` VALUES (87, 1, 'kftest', 1, 1, 'aaa', '2019-07-16 18:05:52', '2019-07-16 18:05:52');
INSERT INTO `chat_messages` VALUES (88, 1, 'kftest', 1, 2, 'ok', '2019-07-17 16:14:36', '2019-07-17 16:14:36');
INSERT INTO `chat_messages` VALUES (89, 1, 'kftest', 1, 1, 'hi', '2019-07-18 18:48:22', '2019-07-18 18:48:22');
INSERT INTO `chat_messages` VALUES (90, 1, 'kftest', 1, 2, 'yes', '2019-07-18 18:48:36', '2019-07-18 18:48:36');
INSERT INTO `chat_messages` VALUES (91, 1, 'kftest', 1, 1, 'good', '2019-07-19 15:24:01', '2019-07-19 15:24:01');
INSERT INTO `chat_messages` VALUES (92, 1, 'kftest', 1, 2, 'yes', '2019-07-19 15:24:12', '2019-07-19 15:24:12');
INSERT INTO `chat_messages` VALUES (93, 1, 'kftest', 1, 2, 'aaaa', '2019-07-19 15:24:25', '2019-07-19 15:24:25');
INSERT INTO `chat_messages` VALUES (94, 1, 'kftest', 1, 1, 'aaaaa', '2019-07-19 17:21:11', '2019-07-19 17:21:11');
INSERT INTO `chat_messages` VALUES (95, 1, 'kftest', 1, 1, 'aavas', '2019-07-19 17:25:09', '2019-07-19 17:25:09');
INSERT INTO `chat_messages` VALUES (96, 1, 'kftest', 1, 1, 'aasdsds', '2019-07-19 17:31:10', '2019-07-19 17:31:10');
INSERT INTO `chat_messages` VALUES (97, 1, 'kftest', 1, 1, 'ffff', '2019-07-19 17:32:12', '2019-07-19 17:32:12');
INSERT INTO `chat_messages` VALUES (98, 1, 'kftest', 1, 2, 'ssss', '2019-07-19 17:34:18', '2019-07-19 17:34:18');
INSERT INTO `chat_messages` VALUES (99, 1, 'kftest', 1, 2, 'dssss', '2019-07-19 17:34:40', '2019-07-19 17:34:40');
INSERT INTO `chat_messages` VALUES (100, 1, 'kftest', 1, 1, 'aass', '2019-07-19 18:03:44', '2019-07-19 18:03:44');
INSERT INTO `chat_messages` VALUES (101, 1, 'kftest', 1, 1, 'wertrt', '2019-07-19 18:04:04', '2019-07-19 18:20:02');
INSERT INTO `chat_messages` VALUES (102, 1, 'kftest', 1, 1, 'aa', '2019-07-19 18:15:52', '2019-07-19 18:15:52');
INSERT INTO `chat_messages` VALUES (103, 1, 'kftest', 1, 1, 'fghj', '2019-07-19 18:15:56', '2019-07-19 18:15:56');
INSERT INTO `chat_messages` VALUES (104, 1, 'kftest', 1, 2, 'asdfd', '2019-07-19 18:16:19', '2019-07-19 18:16:19');
INSERT INTO `chat_messages` VALUES (105, 1, 'kftest', 1, 1, 'aaaa', '2019-07-20 15:55:52', '2019-07-20 16:28:14');
INSERT INTO `chat_messages` VALUES (106, 1, 'kftest', 1, 1, 'a', '2019-07-20 15:57:07', '2019-07-20 16:28:14');
INSERT INTO `chat_messages` VALUES (107, 1, 'kftest', 1, 1, 'aaaaaaaaa', '2019-07-20 15:59:31', '2019-07-20 16:28:14');
INSERT INTO `chat_messages` VALUES (108, 1, 'kftest', 1, 1, 'qq', '2019-07-20 16:08:46', '2019-07-20 16:28:14');
INSERT INTO `chat_messages` VALUES (109, 1, 'kftest', 1, 1, 'aa', '2019-07-20 16:27:25', '2019-07-20 16:28:14');
INSERT INTO `chat_messages` VALUES (110, 1, 'kftest', 1, 1, 'cc', '2019-07-20 16:28:52', '2019-07-22 14:29:05');
INSERT INTO `chat_messages` VALUES (111, 1, 'kftest', 1, 1, 'aaa', '2019-07-22 14:29:01', '2019-07-22 19:55:02');
INSERT INTO `chat_messages` VALUES (112, 1, 'kftest', 1, 1, 'test', '2019-07-22 19:51:26', '2019-07-22 19:51:26');
INSERT INTO `chat_messages` VALUES (113, 1, 'kftest', 1, 2, 'success', '2019-07-22 19:51:43', '2019-07-22 19:51:43');
INSERT INTO `chat_messages` VALUES (114, 1, 'kftest', 1, 1, 'ok', '2019-07-22 19:52:18', '2019-07-22 19:52:18');
INSERT INTO `chat_messages` VALUES (115, 1, 'kftest', 1, 2, 'ok', '2019-07-22 19:56:27', '2019-07-22 19:56:27');
INSERT INTO `chat_messages` VALUES (116, 1, 'kftest', 1, 1, 'aaa', '2019-07-22 20:07:48', '2019-07-22 20:08:23');
INSERT INTO `chat_messages` VALUES (117, 1, 'kftest', 1, 1, 'fghj', '2019-07-22 20:07:57', '2019-07-22 20:08:23');
INSERT INTO `chat_messages` VALUES (118, 1, 'kftest', 1, 1, 'aa', '2019-07-22 20:08:40', '2019-07-23 12:00:02');
INSERT INTO `chat_messages` VALUES (119, 1, 'kftest', 1, 1, 'ok', '2019-07-23 11:55:22', '2019-07-23 11:55:22');
INSERT INTO `chat_messages` VALUES (120, 1, 'kftest', 1, 1, 'kk', '2019-07-23 11:56:24', '2019-07-23 14:55:02');
INSERT INTO `chat_messages` VALUES (121, 1, 'kftest', 1, 2, 'kk', '2019-07-23 14:50:58', '2019-07-23 14:50:58');
INSERT INTO `chat_messages` VALUES (122, 1, 'kftest', 1, 1, 'haha你好', '2019-07-23 18:34:44', '2019-07-23 18:34:44');
INSERT INTO `chat_messages` VALUES (123, 1, 'kftest', 1, 2, '你好', '2019-07-23 18:35:18', '2019-07-23 18:35:18');
INSERT INTO `chat_messages` VALUES (124, 1, 'kftest', 1, 1, 'aa', '2019-07-29 19:21:26', '2019-08-10 18:38:48');
INSERT INTO `chat_messages` VALUES (125, 1, 'kftest', 1, 1, 'aaa', '2019-08-10 17:45:07', '2019-08-10 18:38:48');
INSERT INTO `chat_messages` VALUES (126, 1, 'kftest', 1, 1, 'aaaa', '2019-08-10 17:45:56', '2019-08-10 18:38:48');
INSERT INTO `chat_messages` VALUES (127, 1, 'kftest', 1, 1, 'aa', '2019-08-10 18:04:44', '2019-08-10 18:38:48');
INSERT INTO `chat_messages` VALUES (128, 1, 'kftest', 1, 1, 'abc', '2019-08-10 18:07:42', '2019-08-10 18:38:48');
INSERT INTO `chat_messages` VALUES (129, 1, 'kftest', 1, 1, 'aa', '2019-08-10 18:08:34', '2019-08-10 18:38:48');
INSERT INTO `chat_messages` VALUES (130, 1, 'kftest', 1, 1, 'aaa', '2019-08-10 18:29:56', '2019-08-10 18:38:48');
INSERT INTO `chat_messages` VALUES (131, 1, 'kftest', 1, 1, 'ok', '2019-08-10 18:39:39', '2019-08-10 18:50:01');
INSERT INTO `chat_messages` VALUES (132, 1, 'kftest', 1, 1, 'av', '2019-08-10 18:47:18', '2019-08-10 18:47:18');
INSERT INTO `chat_messages` VALUES (133, 1, 'kftest', 1, 1, 'aa', '2019-08-10 18:48:01', '2019-08-10 19:00:02');
INSERT INTO `chat_messages` VALUES (134, 1, 'kftest', 1, 1, 'aaa', '2019-08-10 18:55:51', '2019-08-10 18:55:51');
INSERT INTO `chat_messages` VALUES (135, 1, 'kftest', 1, 1, 'aaa', '2019-08-10 18:56:27', '2019-08-12 14:40:43');
INSERT INTO `chat_messages` VALUES (136, 1, 'kftest', 1, 1, 'aaa', '2019-08-12 14:32:42', '2019-08-12 14:40:43');
INSERT INTO `chat_messages` VALUES (137, 1, 'kftest', 1, 1, 'ass', '2019-08-12 14:35:07', '2019-08-12 14:40:43');
INSERT INTO `chat_messages` VALUES (138, 1, 'kftest', 1, 1, 'fasdf', '2019-08-12 14:36:05', '2019-08-12 14:40:43');
INSERT INTO `chat_messages` VALUES (139, 1, 'kftest', 1, 1, 'as', '2019-08-12 14:37:34', '2019-08-12 14:40:43');
INSERT INTO `chat_messages` VALUES (140, 1, 'kftest', 1, 1, 'aaa', '2019-08-12 14:41:36', '2019-08-12 14:41:36');
INSERT INTO `chat_messages` VALUES (141, 1, 'kftest', 1, 1, 'cc', '2019-08-12 14:47:06', '2019-08-12 15:00:02');
INSERT INTO `chat_messages` VALUES (142, 1, 'kftest', 1, 1, 'kk', '2019-08-12 14:56:02', '2019-08-12 14:56:02');
INSERT INTO `chat_messages` VALUES (143, 1, 'kftest', 1, 1, 'aa', '2019-08-12 15:01:06', '2019-08-12 15:20:02');
INSERT INTO `chat_messages` VALUES (144, 1, '0', 0, 1, 'ddadsff', '2019-08-12 15:15:10', '2019-08-12 15:25:02');
INSERT INTO `chat_messages` VALUES (145, 1, '0', 0, 1, 'a', '2019-08-12 15:45:47', '2019-08-12 15:45:47');
INSERT INTO `chat_messages` VALUES (146, 1, '0', 0, 1, 'asd', '2019-08-12 15:45:54', '2019-08-12 15:45:54');
INSERT INTO `chat_messages` VALUES (147, 1, '0', 0, 1, 'aaa', '2019-08-12 15:46:27', '2019-08-12 15:46:27');

-- ----------------------------
-- Table structure for merchants
-- ----------------------------
DROP TABLE IF EXISTS `merchants`;
CREATE TABLE `merchants`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `merchant_no` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '商户id',
  `token` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '商户token',
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `merchant_no`(`merchant_no`) USING BTREE,
  UNIQUE INDEX `token`(`token`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of merchants
-- ----------------------------
INSERT INTO `merchants` VALUES (1, '5d49145f1d008', 'cdcc194682f9879d8ee8b540369e6255', '2019-08-01 15:24:12', '2019-08-01 15:24:15');

-- ----------------------------
-- Table structure for migrations
-- ----------------------------
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of migrations
-- ----------------------------
INSERT INTO `migrations` VALUES (1, '2014_10_12_000000_create_users_table', 1);
INSERT INTO `migrations` VALUES (2, '2014_10_12_100000_create_password_resets_table', 1);
INSERT INTO `migrations` VALUES (3, '2018_08_03_102540_create_chat_message_table', 1);

-- ----------------------------
-- Table structure for password_resets
-- ----------------------------
DROP TABLE IF EXISTS `password_resets`;
CREATE TABLE `password_resets`  (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  INDEX `password_resets_email_index`(`email`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for player_summaries
-- ----------------------------
DROP TABLE IF EXISTS `player_summaries`;
CREATE TABLE `player_summaries`  (
  `player_id` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '玩家ID 账号',
  `service_id` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '客服ID',
  `send_num` bigint(11) NOT NULL COMMENT '玩家发送问题数量',
  `last_send_time` timestamp(0) NULL DEFAULT NULL COMMENT '玩家 最后发送问题时间',
  `last_replay_time` timestamp(0) NULL DEFAULT NULL COMMENT '客服 最后回复时间',
  `last_send_msg` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT '玩家 最后发送的问题',
  `last_replay_msg` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT '客服 最后回复的信息',
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`player_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of player_summaries
-- ----------------------------
INSERT INTO `player_summaries` VALUES ('1', 'kftest', 61, '2019-08-12 15:46:27', '2019-07-23 18:35:18', 'aaa', '你好', '2019-07-15 18:17:00', '2019-08-12 15:46:27');

-- ----------------------------
-- Table structure for punch_cards
-- ----------------------------
DROP TABLE IF EXISTS `punch_cards`;
CREATE TABLE `punch_cards`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `service_time_id` bigint(20) NOT NULL,
  `service_id` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '客服ID',
  `status` tinyint(4) NOT NULL DEFAULT 0 COMMENT '打卡状态： 1：上班打卡,2：下班打卡',
  `type` tinyint(4) NOT NULL DEFAULT 0 COMMENT '客服类型:0 普通客服,1 聊天客服,2 出款客服',
  `punch_time` timestamp(0) NULL DEFAULT NULL COMMENT '打卡时间',
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 125 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of punch_cards
-- ----------------------------
INSERT INTO `punch_cards` VALUES (1, 1, 'yykf001', 1, 1, '2019-07-15 12:13:39', '2019-07-15 12:13:39', '2019-07-15 12:13:39');
INSERT INTO `punch_cards` VALUES (2, 1, 'yykf001', 2, 1, '2019-07-15 12:14:26', '2019-07-15 12:14:26', '2019-07-15 12:14:26');
INSERT INTO `punch_cards` VALUES (3, 2, 'kftest', 1, 1, '2019-07-15 14:13:40', '2019-07-15 14:13:40', '2019-07-15 14:13:40');
INSERT INTO `punch_cards` VALUES (4, 3, 'kftest', 1, 1, '2019-07-16 10:46:18', '2019-07-16 10:46:18', '2019-07-16 10:46:18');
INSERT INTO `punch_cards` VALUES (5, 3, 'kftest', 2, 1, '2019-07-16 11:05:33', '2019-07-16 11:05:33', '2019-07-16 11:05:33');
INSERT INTO `punch_cards` VALUES (6, 4, 'kftest', 1, 1, '2019-07-16 11:06:46', '2019-07-16 11:06:46', '2019-07-16 11:06:46');
INSERT INTO `punch_cards` VALUES (7, 5, 'yykf001', 1, 1, '2019-07-16 16:44:17', '2019-07-16 16:44:17', '2019-07-16 16:44:17');
INSERT INTO `punch_cards` VALUES (8, 6, 'kftest', 1, 1, '2019-07-17 10:09:32', '2019-07-17 10:09:32', '2019-07-17 10:09:32');
INSERT INTO `punch_cards` VALUES (9, 7, 'kftest', 1, 1, '2019-07-18 17:25:07', '2019-07-18 17:25:07', '2019-07-18 17:25:07');
INSERT INTO `punch_cards` VALUES (10, 8, 'kftest', 1, 1, '2019-07-19 10:17:52', '2019-07-19 10:17:52', '2019-07-19 10:17:52');
INSERT INTO `punch_cards` VALUES (11, 9, 'kftest', 1, 1, '2019-07-20 16:28:14', '2019-07-20 16:28:14', '2019-07-20 16:28:14');
INSERT INTO `punch_cards` VALUES (12, 10, 'kftest', 1, 1, '2019-07-22 14:29:05', '2019-07-22 14:29:05', '2019-07-22 14:29:05');
INSERT INTO `punch_cards` VALUES (13, 10, 'kftest', 2, 1, '2019-07-22 18:27:01', '2019-07-22 18:27:01', '2019-07-22 18:27:01');
INSERT INTO `punch_cards` VALUES (14, 11, 'kftest', 1, 1, '2019-07-22 18:47:55', '2019-07-22 18:47:55', '2019-07-22 18:47:55');
INSERT INTO `punch_cards` VALUES (15, 11, 'kftest', 2, 1, '2019-07-22 20:07:43', '2019-07-22 20:07:43', '2019-07-22 20:07:43');
INSERT INTO `punch_cards` VALUES (16, 12, 'kftest', 1, 1, '2019-07-22 20:08:22', '2019-07-22 20:08:22', '2019-07-22 20:08:22');
INSERT INTO `punch_cards` VALUES (17, 13, 'kftest', 1, 1, '2019-07-23 10:32:36', '2019-07-23 10:32:36', '2019-07-23 10:32:36');
INSERT INTO `punch_cards` VALUES (18, 13, 'kftest', 2, 1, '2019-07-23 11:44:16', '2019-07-23 11:44:16', '2019-07-23 11:44:16');
INSERT INTO `punch_cards` VALUES (19, 14, 'kftest', 1, 1, '2019-07-23 11:44:41', '2019-07-23 11:44:41', '2019-07-23 11:44:41');
INSERT INTO `punch_cards` VALUES (20, 14, 'kftest', 2, 1, '2019-07-23 17:16:01', '2019-07-23 17:16:01', '2019-07-23 17:16:01');
INSERT INTO `punch_cards` VALUES (21, 15, 'kftest', 1, 1, '2019-07-23 17:17:41', '2019-07-23 17:17:41', '2019-07-23 17:17:41');
INSERT INTO `punch_cards` VALUES (22, 15, 'kftest', 2, 1, '2019-07-23 17:18:01', '2019-07-23 17:18:01', '2019-07-23 17:18:01');
INSERT INTO `punch_cards` VALUES (23, 16, 'kftest', 1, 1, '2019-07-23 17:22:49', '2019-07-23 17:22:49', '2019-07-23 17:22:49');
INSERT INTO `punch_cards` VALUES (24, 16, 'kftest', 2, 1, '2019-07-23 17:23:01', '2019-07-23 17:23:01', '2019-07-23 17:23:01');
INSERT INTO `punch_cards` VALUES (25, 17, 'kftest', 1, 1, '2019-07-23 17:36:52', '2019-07-23 17:36:52', '2019-07-23 17:36:52');
INSERT INTO `punch_cards` VALUES (26, 17, 'kftest', 2, 1, '2019-07-23 17:37:01', '2019-07-23 17:37:01', '2019-07-23 17:37:01');
INSERT INTO `punch_cards` VALUES (27, 18, 'kftest', 1, 1, '2019-07-23 17:39:26', '2019-07-23 17:39:26', '2019-07-23 17:39:26');
INSERT INTO `punch_cards` VALUES (28, 18, 'kftest', 2, 1, '2019-07-23 17:43:01', '2019-07-23 17:43:01', '2019-07-23 17:43:01');
INSERT INTO `punch_cards` VALUES (29, 19, 'kftest', 1, 1, '2019-07-23 17:45:09', '2019-07-23 17:45:09', '2019-07-23 17:45:09');
INSERT INTO `punch_cards` VALUES (30, 19, 'kftest', 2, 1, '2019-07-23 18:10:01', '2019-07-23 18:10:01', '2019-07-23 18:10:01');
INSERT INTO `punch_cards` VALUES (31, 20, 'kftest', 1, 1, '2019-07-23 18:29:37', '2019-07-23 18:29:37', '2019-07-23 18:29:37');
INSERT INTO `punch_cards` VALUES (32, 21, 'kftest', 1, 1, '2019-07-29 19:21:29', '2019-07-29 19:21:29', '2019-07-29 19:21:29');
INSERT INTO `punch_cards` VALUES (33, 22, 'kftest', 1, 1, '2019-07-30 16:23:29', '2019-07-30 16:23:29', '2019-07-30 16:23:29');
INSERT INTO `punch_cards` VALUES (34, 23, 'kftest', 1, 1, '2019-07-31 13:57:52', '2019-07-31 13:57:52', '2019-07-31 13:57:52');
INSERT INTO `punch_cards` VALUES (35, 24, 'kftest', 1, 1, '2019-08-01 16:30:53', '2019-08-01 16:30:53', '2019-08-01 16:30:53');
INSERT INTO `punch_cards` VALUES (36, 25, 'kftest', 1, 1, '2019-08-02 11:02:23', '2019-08-02 11:02:23', '2019-08-02 11:02:23');
INSERT INTO `punch_cards` VALUES (37, 25, 'kftest', 2, 1, '2019-08-02 11:02:30', '2019-08-02 11:02:30', '2019-08-02 11:02:30');
INSERT INTO `punch_cards` VALUES (38, 26, 'kftest', 1, 1, '2019-08-02 11:03:36', '2019-08-02 11:03:36', '2019-08-02 11:03:36');
INSERT INTO `punch_cards` VALUES (39, 27, 'kftest', 1, 1, '2019-08-03 13:36:44', '2019-08-03 13:36:44', '2019-08-03 13:36:44');
INSERT INTO `punch_cards` VALUES (40, 27, 'kftest', 2, 1, '2019-08-03 14:25:40', '2019-08-03 14:25:40', '2019-08-03 14:25:40');
INSERT INTO `punch_cards` VALUES (41, 28, 'kftest', 1, 1, '2019-08-03 14:30:15', '2019-08-03 14:30:15', '2019-08-03 14:30:15');
INSERT INTO `punch_cards` VALUES (42, 28, 'kftest', 2, 1, '2019-08-03 18:43:45', '2019-08-03 18:43:45', '2019-08-03 18:43:45');
INSERT INTO `punch_cards` VALUES (43, 29, 'kftest', 1, 1, '2019-08-03 19:15:12', '2019-08-03 19:15:12', '2019-08-03 19:15:12');
INSERT INTO `punch_cards` VALUES (46, 1, 'kftest', 1, 2, '2019-08-05 15:08:25', '2019-08-05 15:08:25', '2019-08-05 15:08:25');
INSERT INTO `punch_cards` VALUES (48, 1, 'kftest', 2, 2, '2019-08-05 15:27:17', '2019-08-05 15:27:17', '2019-08-05 15:27:17');
INSERT INTO `punch_cards` VALUES (49, 2, 'kftest', 1, 2, '2019-08-05 15:46:01', '2019-08-05 15:46:01', '2019-08-05 15:46:01');
INSERT INTO `punch_cards` VALUES (51, 2, 'kftest', 2, 2, '2019-08-05 17:30:41', '2019-08-05 17:30:41', '2019-08-05 17:30:41');
INSERT INTO `punch_cards` VALUES (52, 3, 'kftest', 1, 2, '2019-08-05 17:32:41', '2019-08-05 17:32:41', '2019-08-05 17:32:41');
INSERT INTO `punch_cards` VALUES (53, 3, 'kftest', 2, 2, '2019-08-05 17:32:54', '2019-08-05 17:32:54', '2019-08-05 17:32:54');
INSERT INTO `punch_cards` VALUES (54, 4, 'kftest', 1, 2, '2019-08-05 17:33:27', '2019-08-05 17:33:27', '2019-08-05 17:33:27');
INSERT INTO `punch_cards` VALUES (55, 5, 'kftest', 1, 2, '2019-08-06 10:35:57', '2019-08-06 10:35:57', '2019-08-06 10:35:57');
INSERT INTO `punch_cards` VALUES (56, 5, 'kftest', 2, 2, '2019-08-06 16:35:42', '2019-08-06 16:35:42', '2019-08-06 16:35:42');
INSERT INTO `punch_cards` VALUES (57, 7, 'kftest', 1, 2, '2019-08-06 16:35:53', '2019-08-06 16:35:53', '2019-08-06 16:35:53');
INSERT INTO `punch_cards` VALUES (58, 8, 'kftest', 1, 2, '2019-08-07 18:28:28', '2019-08-07 18:28:28', '2019-08-07 18:28:28');
INSERT INTO `punch_cards` VALUES (59, 9, 'kftest', 1, 2, '2019-08-09 16:34:01', '2019-08-09 16:34:01', '2019-08-09 16:34:01');
INSERT INTO `punch_cards` VALUES (60, 1, 'kftest', 1, 2, '2019-08-10 15:40:47', '2019-08-10 15:40:47', '2019-08-10 15:40:47');
INSERT INTO `punch_cards` VALUES (61, 1, 'kftest', 2, 1, '2019-08-10 17:46:12', '2019-08-10 17:46:12', '2019-08-10 17:46:12');
INSERT INTO `punch_cards` VALUES (62, 2, 'kftest', 1, 1, '2019-08-10 17:48:00', '2019-08-10 17:48:00', '2019-08-10 17:48:00');
INSERT INTO `punch_cards` VALUES (63, 2, 'kftest', 2, 1, '2019-08-10 18:28:06', '2019-08-10 18:28:06', '2019-08-10 18:28:06');
INSERT INTO `punch_cards` VALUES (64, 3, 'kftest', 1, 1, '2019-08-10 18:29:35', '2019-08-10 18:29:35', '2019-08-10 18:29:35');
INSERT INTO `punch_cards` VALUES (65, 3, 'kftest', 2, 1, '2019-08-10 18:33:41', '2019-08-10 18:33:41', '2019-08-10 18:33:41');
INSERT INTO `punch_cards` VALUES (66, 4, 'kftest', 1, 1, '2019-08-10 18:34:23', '2019-08-10 18:34:23', '2019-08-10 18:34:23');
INSERT INTO `punch_cards` VALUES (67, 4, 'kftest', 2, 1, '2019-08-10 18:38:30', '2019-08-10 18:38:30', '2019-08-10 18:38:30');
INSERT INTO `punch_cards` VALUES (68, 30, 'kftest', 1, 1, '2019-08-10 18:38:48', '2019-08-10 18:38:48', '2019-08-10 18:38:48');
INSERT INTO `punch_cards` VALUES (69, 5, 'kftest', 1, 2, '2019-08-12 10:39:52', '2019-08-12 10:39:52', '2019-08-12 10:39:52');
INSERT INTO `punch_cards` VALUES (70, 5, 'kftest', 2, 2, '2019-08-12 11:33:56', '2019-08-12 11:33:56', '2019-08-12 11:33:56');
INSERT INTO `punch_cards` VALUES (71, 6, 'kftest', 1, 2, '2019-08-12 11:35:46', '2019-08-12 11:35:46', '2019-08-12 11:35:46');
INSERT INTO `punch_cards` VALUES (72, 6, 'kftest', 2, 2, '2019-08-12 11:35:52', '2019-08-12 11:35:52', '2019-08-12 11:35:52');
INSERT INTO `punch_cards` VALUES (73, 7, 'kftest', 1, 2, '2019-08-12 11:36:18', '2019-08-12 11:36:18', '2019-08-12 11:36:18');
INSERT INTO `punch_cards` VALUES (74, 7, 'kftest', 2, 2, '2019-08-12 11:52:19', '2019-08-12 11:52:19', '2019-08-12 11:52:19');
INSERT INTO `punch_cards` VALUES (75, 8, 'kftest', 1, 2, '2019-08-12 11:58:02', '2019-08-12 11:58:02', '2019-08-12 11:58:02');
INSERT INTO `punch_cards` VALUES (76, 8, 'kftest', 2, 2, '2019-08-12 11:58:07', '2019-08-12 11:58:07', '2019-08-12 11:58:07');
INSERT INTO `punch_cards` VALUES (77, 9, 'kftest', 1, 2, '2019-08-12 11:58:29', '2019-08-12 11:58:29', '2019-08-12 11:58:29');
INSERT INTO `punch_cards` VALUES (78, 9, 'kftest', 2, 2, '2019-08-12 11:58:43', '2019-08-12 11:58:43', '2019-08-12 11:58:43');
INSERT INTO `punch_cards` VALUES (79, 10, 'kftest', 1, 2, '2019-08-12 12:10:31', '2019-08-12 12:10:31', '2019-08-12 12:10:31');
INSERT INTO `punch_cards` VALUES (80, 10, 'kftest', 2, 2, '2019-08-12 13:36:45', '2019-08-12 13:36:45', '2019-08-12 13:36:45');
INSERT INTO `punch_cards` VALUES (81, 11, 'kftest', 1, 2, '2019-08-12 13:36:58', '2019-08-12 13:36:58', '2019-08-12 13:36:58');
INSERT INTO `punch_cards` VALUES (82, 11, 'kftest', 2, 2, '2019-08-12 13:37:38', '2019-08-12 13:37:38', '2019-08-12 13:37:38');
INSERT INTO `punch_cards` VALUES (83, 12, 'kftest', 1, 2, '2019-08-12 14:18:15', '2019-08-12 14:18:15', '2019-08-12 14:18:15');
INSERT INTO `punch_cards` VALUES (84, 12, 'kftest', 2, 2, '2019-08-12 14:18:18', '2019-08-12 14:18:18', '2019-08-12 14:18:18');
INSERT INTO `punch_cards` VALUES (85, 13, 'kftest', 1, 2, '2019-08-12 14:18:22', '2019-08-12 14:18:22', '2019-08-12 14:18:22');
INSERT INTO `punch_cards` VALUES (86, 13, 'kftest', 2, 2, '2019-08-12 14:31:40', '2019-08-12 14:31:40', '2019-08-12 14:31:40');
INSERT INTO `punch_cards` VALUES (87, 14, 'kftest', 1, 2, '2019-08-12 14:31:51', '2019-08-12 14:31:51', '2019-08-12 14:31:51');
INSERT INTO `punch_cards` VALUES (88, 14, 'kftest', 2, 1, '2019-08-12 14:38:41', '2019-08-12 14:38:41', '2019-08-12 14:38:41');
INSERT INTO `punch_cards` VALUES (89, 31, 'kftest', 1, 1, '2019-08-12 14:40:43', '2019-08-12 14:40:43', '2019-08-12 14:40:43');
INSERT INTO `punch_cards` VALUES (90, 31, 'kftest', 2, 2, '2019-08-12 15:14:41', '2019-08-12 15:14:41', '2019-08-12 15:14:41');
INSERT INTO `punch_cards` VALUES (91, 15, 'kftest', 1, 2, '2019-08-12 15:15:18', '2019-08-12 15:15:18', '2019-08-12 15:15:18');
INSERT INTO `punch_cards` VALUES (92, 31, 'kftest', 2, 1, '2019-08-12 15:28:02', '2019-08-12 15:28:02', '2019-08-12 15:28:02');
INSERT INTO `punch_cards` VALUES (93, 16, 'kftest', 1, 2, '2019-08-12 15:46:44', '2019-08-12 15:46:44', '2019-08-12 15:46:44');
INSERT INTO `punch_cards` VALUES (94, 17, 'kftest', 1, 2, '2019-08-13 13:58:06', '2019-08-13 13:58:06', '2019-08-13 13:58:06');
INSERT INTO `punch_cards` VALUES (95, 17, 'kftest', 2, 2, '2019-08-13 14:06:26', '2019-08-13 14:06:26', '2019-08-13 14:06:26');
INSERT INTO `punch_cards` VALUES (96, 18, 'kftest', 1, 2, '2019-08-13 14:30:22', '2019-08-13 14:30:22', '2019-08-13 14:30:22');
INSERT INTO `punch_cards` VALUES (97, 18, 'kftest', 2, 2, '2019-08-13 14:30:41', '2019-08-13 14:30:41', '2019-08-13 14:30:41');
INSERT INTO `punch_cards` VALUES (98, 19, 'kftest', 1, 2, '2019-08-13 14:36:39', '2019-08-13 14:36:39', '2019-08-13 14:36:39');
INSERT INTO `punch_cards` VALUES (99, 19, 'kftest', 2, 2, '2019-08-13 17:09:24', '2019-08-13 17:09:24', '2019-08-13 17:09:24');
INSERT INTO `punch_cards` VALUES (100, 20, 'shenhekefu2', 1, 2, '2019-08-13 17:21:03', '2019-08-13 17:21:03', '2019-08-13 17:21:03');
INSERT INTO `punch_cards` VALUES (101, 20, 'shenhekefu2', 2, 2, '2019-08-13 17:21:12', '2019-08-13 17:21:12', '2019-08-13 17:21:12');
INSERT INTO `punch_cards` VALUES (102, 21, 'shenhekefu2', 1, 2, '2019-08-13 17:29:08', '2019-08-13 17:29:08', '2019-08-13 17:29:08');
INSERT INTO `punch_cards` VALUES (103, 21, 'shenhekefu2', 2, 2, '2019-08-13 17:29:32', '2019-08-13 17:29:32', '2019-08-13 17:29:32');
INSERT INTO `punch_cards` VALUES (104, 22, 'shenhekefu2', 1, 2, '2019-08-13 17:56:57', '2019-08-13 17:56:57', '2019-08-13 17:56:57');
INSERT INTO `punch_cards` VALUES (105, 23, 'kftest', 1, 2, '2019-08-13 19:45:19', '2019-08-13 19:45:19', '2019-08-13 19:45:19');
INSERT INTO `punch_cards` VALUES (106, 23, 'kftest', 2, 2, '2019-08-13 20:53:17', '2019-08-13 20:53:17', '2019-08-13 20:53:17');
INSERT INTO `punch_cards` VALUES (107, 24, 'kftest', 1, 2, '2019-08-13 20:53:30', '2019-08-13 20:53:30', '2019-08-13 20:53:30');
INSERT INTO `punch_cards` VALUES (108, 25, 'kftest', 1, 2, '2019-08-14 10:17:43', '2019-08-14 10:17:43', '2019-08-14 10:17:43');
INSERT INTO `punch_cards` VALUES (109, 25, 'kftest', 2, 2, '2019-08-14 16:04:08', '2019-08-14 16:04:08', '2019-08-14 16:04:08');
INSERT INTO `punch_cards` VALUES (110, 1, 'kftest', 1, 2, '2019-08-14 16:18:00', '2019-08-14 16:18:00', '2019-08-14 16:18:00');
INSERT INTO `punch_cards` VALUES (111, 1, 'kftest', 2, 2, '2019-08-14 16:37:31', '2019-08-14 16:37:31', '2019-08-14 16:37:31');
INSERT INTO `punch_cards` VALUES (112, 2, 'kftest', 1, 2, '2019-08-14 17:06:34', '2019-08-14 17:06:34', '2019-08-14 17:06:34');
INSERT INTO `punch_cards` VALUES (113, 2, 'kftest', 2, 2, '2019-08-14 17:10:21', '2019-08-14 17:10:21', '2019-08-14 17:10:21');
INSERT INTO `punch_cards` VALUES (114, 3, 'kftest', 1, 2, '2019-08-14 17:10:43', '2019-08-14 17:10:43', '2019-08-14 17:10:43');
INSERT INTO `punch_cards` VALUES (115, 3, 'kftest', 2, 2, '2019-08-14 17:11:25', '2019-08-14 17:11:25', '2019-08-14 17:11:25');
INSERT INTO `punch_cards` VALUES (116, 4, 'kftest', 1, 2, '2019-08-14 17:11:45', '2019-08-14 17:11:45', '2019-08-14 17:11:45');
INSERT INTO `punch_cards` VALUES (117, 4, 'kftest', 2, 2, '2019-08-14 17:27:04', '2019-08-14 17:27:04', '2019-08-14 17:27:04');
INSERT INTO `punch_cards` VALUES (118, 5, 'kftest', 1, 2, '2019-08-14 17:49:48', '2019-08-14 17:49:48', '2019-08-14 17:49:48');
INSERT INTO `punch_cards` VALUES (119, 5, 'kftest', 2, 2, '2019-08-14 17:58:57', '2019-08-14 17:58:57', '2019-08-14 17:58:57');
INSERT INTO `punch_cards` VALUES (120, 6, 'kftest', 1, 2, '2019-08-14 18:01:32', '2019-08-14 18:01:32', '2019-08-14 18:01:32');
INSERT INTO `punch_cards` VALUES (121, 6, 'kftest', 2, 2, '2019-08-14 18:02:20', '2019-08-14 18:02:20', '2019-08-14 18:02:20');
INSERT INTO `punch_cards` VALUES (122, 7, 'kftest', 1, 2, '2019-08-14 18:14:09', '2019-08-14 18:14:09', '2019-08-14 18:14:09');
INSERT INTO `punch_cards` VALUES (123, 7, 'kftest', 2, 2, '2019-08-14 18:36:17', '2019-08-14 18:36:17', '2019-08-14 18:36:17');
INSERT INTO `punch_cards` VALUES (124, 8, 'kftest', 1, 2, '2019-08-14 18:36:41', '2019-08-14 18:36:41', '2019-08-14 18:36:41');

-- ----------------------------
-- Table structure for risk_jobs
-- ----------------------------
DROP TABLE IF EXISTS `risk_jobs`;
CREATE TABLE `risk_jobs`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `risk_management_id` int(10) NOT NULL COMMENT '风控任务id',
  `last_time` timestamp(0) NULL DEFAULT NULL COMMENT '该任务上次执行时刻',
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for risk_managements
-- ----------------------------
DROP TABLE IF EXISTS `risk_managements`;
CREATE TABLE `risk_managements`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `content` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '风控内容说明',
  `rate_minute` int(10) NOT NULL COMMENT '运行频率  eg ： 10  代表10分钟运行1次',
  `type` tinyint(4) NOT NULL COMMENT '风控类型 1：用户风控 2：代理风控 3：平台风控 4：支付风控',
  `status` tinyint(4) NOT NULL COMMENT '是否激活: 1: 激活   0: 未激活 (激活的风控发生时才会产出风控记录)',
  `p1` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '第一个参数',
  `p2` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '第二个参数',
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for risk_records
-- ----------------------------
DROP TABLE IF EXISTS `risk_records`;
CREATE TABLE `risk_records`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `status` tinyint(4) NOT NULL COMMENT '是否已读: 1: 已读  0: 未读',
  `content` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '触发内容',
  `case_user` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '引发者',
  `admin_user` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '已读管理员',
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for service_achievements
-- ----------------------------
DROP TABLE IF EXISTS `service_achievements`;
CREATE TABLE `service_achievements`  (
  `service_id` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '客服ID 账号',
  `allot_num` bigint(11) NOT NULL COMMENT '分配问题数量',
  `not_handle_num` int(11) NOT NULL COMMENT '未处理数量',
  `work_duration` bigint(11) NOT NULL COMMENT '工作时长 单位 s秒',
  `last_replay_time` timestamp(0) NULL DEFAULT NULL COMMENT '最后回复时间',
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`service_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of service_achievements
-- ----------------------------
INSERT INTO `service_achievements` VALUES ('kftest', 37, 0, 63145, '2019-07-23 18:35:18', '2019-08-10 00:00:01', '2019-08-10 00:00:01');

-- ----------------------------
-- Table structure for service_times
-- ----------------------------
DROP TABLE IF EXISTS `service_times`;
CREATE TABLE `service_times`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `service_id` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '客服ID',
  `status` tinyint(4) NOT NULL DEFAULT 0 COMMENT '打卡状态： 1：上班打卡,2：下班打卡',
  `on_time` timestamp(0) NULL DEFAULT NULL COMMENT '上班时间',
  `off_time` timestamp(0) NULL DEFAULT NULL COMMENT '下班时间',
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 32 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of service_times
-- ----------------------------
INSERT INTO `service_times` VALUES (1, 'yykf005', 2, '2019-07-15 12:13:39', '2019-07-15 12:14:26', '2019-07-15 12:13:39', '2019-07-15 12:14:26');
INSERT INTO `service_times` VALUES (2, 'yykf004', 2, '2019-07-15 14:13:40', NULL, '2019-07-15 14:13:40', '2019-07-15 14:13:40');
INSERT INTO `service_times` VALUES (3, 'yykf003', 2, '2019-07-16 10:46:18', '2019-07-16 11:05:33', '2019-07-16 10:46:18', '2019-07-16 11:05:33');
INSERT INTO `service_times` VALUES (4, 'yykf002', 2, '2019-07-16 11:06:46', '2019-08-10 18:38:30', '2019-07-16 11:06:46', '2019-08-10 18:38:30');
INSERT INTO `service_times` VALUES (5, 'yykf001', 2, '2019-07-16 16:44:17', NULL, '2019-07-16 16:44:17', '2019-07-16 16:44:17');
INSERT INTO `service_times` VALUES (6, 'kftest', 2, '2019-07-17 10:09:32', NULL, '2019-07-17 10:09:32', '2019-07-17 10:09:32');
INSERT INTO `service_times` VALUES (7, 'kftest', 1, '2019-07-18 17:25:07', NULL, '2019-07-18 17:25:07', '2019-07-18 17:25:07');
INSERT INTO `service_times` VALUES (8, 'kftest', 1, '2019-07-19 10:17:52', NULL, '2019-07-19 10:17:52', '2019-07-19 10:17:52');
INSERT INTO `service_times` VALUES (9, 'kftest', 1, '2019-07-20 16:28:14', NULL, '2019-07-20 16:28:14', '2019-07-20 16:28:14');
INSERT INTO `service_times` VALUES (10, 'kftest', 2, '2019-07-22 14:29:05', '2019-07-22 18:27:01', '2019-07-22 14:29:05', '2019-07-22 18:27:01');
INSERT INTO `service_times` VALUES (11, 'kftest', 2, '2019-07-22 18:47:55', '2019-07-22 20:07:43', '2019-07-22 18:47:55', '2019-07-22 20:07:43');
INSERT INTO `service_times` VALUES (12, 'kftest', 1, '2019-07-22 20:08:22', NULL, '2019-07-22 20:08:22', '2019-07-22 20:08:22');
INSERT INTO `service_times` VALUES (13, 'kftest', 2, '2019-07-23 10:32:36', '2019-07-23 11:44:16', '2019-07-23 10:32:36', '2019-07-23 11:44:16');
INSERT INTO `service_times` VALUES (14, 'kftest', 2, '2019-07-23 11:44:41', '2019-08-12 14:38:41', '2019-07-23 11:44:41', '2019-08-12 14:38:41');
INSERT INTO `service_times` VALUES (15, 'kftest', 2, '2019-07-23 17:17:41', '2019-07-23 17:18:01', '2019-07-23 17:17:41', '2019-07-23 17:18:01');
INSERT INTO `service_times` VALUES (16, 'kftest', 2, '2019-07-23 17:22:49', '2019-07-23 17:23:01', '2019-07-23 17:22:49', '2019-07-23 17:23:01');
INSERT INTO `service_times` VALUES (17, 'kftest', 2, '2019-07-23 17:36:52', '2019-07-23 17:37:01', '2019-07-23 17:36:52', '2019-07-23 17:37:01');
INSERT INTO `service_times` VALUES (18, 'kftest', 2, '2019-07-23 17:39:26', '2019-07-23 17:43:01', '2019-07-23 17:39:26', '2019-07-23 17:43:01');
INSERT INTO `service_times` VALUES (19, 'kftest', 2, '2019-07-23 17:45:09', '2019-07-23 18:10:01', '2019-07-23 17:45:09', '2019-07-23 18:10:01');
INSERT INTO `service_times` VALUES (20, 'kftest', 1, '2019-07-23 18:29:37', NULL, '2019-07-23 18:29:37', '2019-07-23 18:29:37');
INSERT INTO `service_times` VALUES (21, 'kftest', 1, '2019-07-29 19:21:29', NULL, '2019-07-29 19:21:29', '2019-07-29 19:21:29');
INSERT INTO `service_times` VALUES (22, 'kftest', 1, '2019-07-30 16:23:29', NULL, '2019-07-30 16:23:29', '2019-07-30 16:23:29');
INSERT INTO `service_times` VALUES (23, 'kftest', 1, '2019-07-31 13:57:52', NULL, '2019-07-31 13:57:52', '2019-07-31 13:57:52');
INSERT INTO `service_times` VALUES (24, 'kftest', 1, '2019-08-01 16:30:53', NULL, '2019-08-01 16:30:53', '2019-08-01 16:30:53');
INSERT INTO `service_times` VALUES (25, 'kftest', 2, '2019-08-02 11:02:23', '2019-08-02 11:02:30', '2019-08-02 11:02:23', '2019-08-02 11:02:30');
INSERT INTO `service_times` VALUES (26, 'kftest', 1, '2019-08-02 11:03:36', NULL, '2019-08-02 11:03:36', '2019-08-02 11:03:36');
INSERT INTO `service_times` VALUES (27, 'kftest', 2, '2019-08-03 13:36:44', '2019-08-03 14:25:40', '2019-08-03 13:36:44', '2019-08-03 14:25:40');
INSERT INTO `service_times` VALUES (28, 'kftest', 2, '2019-08-03 14:30:15', '2019-08-03 18:43:45', '2019-08-03 14:30:15', '2019-08-03 18:43:45');
INSERT INTO `service_times` VALUES (29, 'kftest', 1, '2019-08-03 19:15:12', NULL, '2019-08-03 19:15:12', '2019-08-03 19:15:12');
INSERT INTO `service_times` VALUES (30, 'kftest', 1, '2019-08-10 18:38:48', NULL, '2019-08-10 18:38:48', '2019-08-10 18:38:48');
INSERT INTO `service_times` VALUES (31, 'kftest', 2, '2019-08-12 14:40:43', '2019-08-12 15:28:02', '2019-08-12 14:40:43', '2019-08-12 15:28:02');

-- ----------------------------
-- Table structure for suggestions
-- ----------------------------
DROP TABLE IF EXISTS `suggestions`;
CREATE TABLE `suggestions`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '访问者ip地址',
  `mobile` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `content` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '意见、反馈',
  `width` int(11) NULL DEFAULT NULL COMMENT '屏幕宽度',
  `height` int(11) NULL DEFAULT NULL COMMENT '屏幕高度',
  `renderer` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '显卡渲染器',
  `vendor` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '显卡供应商',
  `device_pixel_ratio` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '设备像素比',
  `platform` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '硬件平台',
  `appCodeName` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '浏览器代号',
  `appName` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '浏览器名称',
  `appVersion` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '版本信息',
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nickname` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `avatar` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 0,
  `is_admin` tinyint(1) NOT NULL DEFAULT 0,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `github_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `github_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `github_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `weibo_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `weibo_link` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `website` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `users_email_unique`(`email`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for withdrawal_service_times
-- ----------------------------
DROP TABLE IF EXISTS `withdrawal_service_times`;
CREATE TABLE `withdrawal_service_times`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `service_id` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '客服ID',
  `status` tinyint(4) NOT NULL DEFAULT 0 COMMENT '打卡状态： 1：上班打卡,2：下班打卡',
  `on_time` timestamp(0) NULL DEFAULT NULL COMMENT '上班时间',
  `off_time` timestamp(0) NULL DEFAULT NULL COMMENT '下班时间',
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `status`(`status`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 9 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of withdrawal_service_times
-- ----------------------------
INSERT INTO `withdrawal_service_times` VALUES (1, 'kftest', 2, '2019-08-14 16:18:00', '2019-08-14 16:37:31', '2019-08-14 16:18:00', '2019-08-14 16:37:31');
INSERT INTO `withdrawal_service_times` VALUES (2, 'kftest', 2, '2019-08-14 17:06:34', '2019-08-14 17:10:21', '2019-08-14 17:06:34', '2019-08-14 17:10:21');
INSERT INTO `withdrawal_service_times` VALUES (3, 'kftest', 2, '2019-08-14 17:10:43', '2019-08-14 17:11:25', '2019-08-14 17:10:43', '2019-08-14 17:11:25');
INSERT INTO `withdrawal_service_times` VALUES (4, 'kftest', 2, '2019-08-14 17:11:45', '2019-08-14 17:27:04', '2019-08-14 17:11:45', '2019-08-14 17:27:04');
INSERT INTO `withdrawal_service_times` VALUES (5, 'kftest', 2, '2019-08-14 17:49:48', '2019-08-14 17:58:57', '2019-08-14 17:49:48', '2019-08-14 17:58:57');
INSERT INTO `withdrawal_service_times` VALUES (6, 'kftest', 2, '2019-08-14 18:01:32', '2019-08-14 18:02:20', '2019-08-14 18:01:32', '2019-08-14 18:02:20');
INSERT INTO `withdrawal_service_times` VALUES (7, 'kftest', 2, '2019-08-14 18:14:09', '2019-08-14 18:36:17', '2019-08-14 18:14:09', '2019-08-14 18:36:17');
INSERT INTO `withdrawal_service_times` VALUES (8, 'kftest', 1, '2019-08-14 18:36:41', NULL, '2019-08-14 18:36:41', '2019-08-14 18:36:41');

-- ----------------------------
-- Table structure for withdrawals
-- ----------------------------
DROP TABLE IF EXISTS `withdrawals`;
CREATE TABLE `withdrawals`  (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `merchant_id` int(5) NOT NULL COMMENT '商户ID',
  `merchant_order_id` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '商户订单号',
  `order_id` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '平台订单号',
  `bill_price` decimal(10, 2) NULL DEFAULT NULL COMMENT '订单金额',
  `user_id` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '用户ID',
  `name` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '用户真实姓名',
  `bank_card` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '银行卡号',
  `bank_code` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '银行代码',
  `status` tinyint(1) NULL DEFAULT 0 COMMENT '出款状态: 0 待处理, 1 受理中, 2 已拒绝, 3 已出款',
  `notify_url` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '回调通知地址',
  `tel` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '用户电话',
  `device_ip` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '设备IP',
  `device_type` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '设备类型',
  `device_id` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '设备编号',
  `extra` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '备注信息',
  `service_id` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '0' COMMENT '操作人',
  `holder` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '受理人',
  `notify_status` tinyint(1) NULL DEFAULT 0 COMMENT '通知状态: 0 待通知, 1 成功, 2 失败',
  `process_time` int(10) NULL DEFAULT NULL COMMENT '平出处理时间(秒)',
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `merchant_id_merchant_order_id`(`merchant_id`, `merchant_order_id`) USING BTREE,
  UNIQUE INDEX `order_id`(`order_id`) USING BTREE,
  INDEX `service_id`(`service_id`) USING BTREE,
  INDEX `status`(`status`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 8 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of withdrawals
-- ----------------------------
INSERT INTO `withdrawals` VALUES (1, 1, '1565769829', '2019081416034969864', 100.55, 'user_001', '张三', '11112222333344455', 'ICBC', 0, 'http://self.example.com/callback_url', '13912345678', '1.2.3.4', 'android', 'D3K9K78J4 ', 'extra  info', 'kftest', NULL, 0, NULL, '2019-08-14 16:03:49', '2019-08-14 18:36:41');
INSERT INTO `withdrawals` VALUES (2, 1, '1565770686', '2019081416180623887', 100.55, 'user_001', '张三', '11112222333344455', 'ICBC', 0, 'http://self.example.com/callback_url', '13912345678', '1.2.3.4', 'android', 'D3K9K78J4 ', 'extra  info', 'kftest', NULL, 0, NULL, '2019-08-14 16:18:06', '2019-08-14 18:36:41');
INSERT INTO `withdrawals` VALUES (3, 1, '1565771785', '2019081416362573963', 100.55, 'user_001', '张三', '11112222333344455', 'ICBC', 2, 'http://self.example.com/callback_url', '13912345678', '1.2.3.4', 'android', 'D3K9K78J4 ', 'extra  info', 'kftest', 'kftest', 0, 1, '2019-08-14 16:36:25', '2019-08-14 18:36:48');
INSERT INTO `withdrawals` VALUES (4, 1, '1565773859', '2019081417105989642', 100.55, 'user_001', '张三', '11112222333344455', 'ICBC', 1, 'http://self.example.com/callback_url', '13912345678', '1.2.3.4', 'android', 'D3K9K78J4 ', 'extra  info', 'kftest', 'kftest', 0, NULL, '2019-08-14 17:10:59', '2019-08-14 17:11:11');
INSERT INTO `withdrawals` VALUES (5, 1, '1565774811', '2019081417265180198', 100.55, 'user_001', '张三', '11112222333344455', 'ICBC', 2, 'http://self.example.com/callback_url', '13912345678', '1.2.3.4', 'android', 'D3K9K78J4 ', 'extra  info', 'kftest', 'kftest', 0, 4318, '2019-08-14 17:26:51', '2019-08-14 18:38:51');
INSERT INTO `withdrawals` VALUES (6, 1, '1565776300', '2019081417514094346', 100.55, 'user_001', '张三', '11112222333344455', 'ICBC', 3, 'http://self.example.com/callback_url', '13912345678', '1.2.3.4', 'android', 'D3K9K78J4 ', 'extra  info', 'kftest', 'kftest', 0, 45, '2019-08-14 17:51:40', '2019-08-14 18:16:10');
INSERT INTO `withdrawals` VALUES (7, 1, '1565776759', '2019081417591928899', 100.55, 'user_001', '张三', '11112222333344455', 'ICBC', 3, 'http://self.example.com/callback_url', '13912345678', '1.2.3.4', 'android', 'D3K9K78J4 ', 'extra  info', 'kftest', 'kftest', 0, 2, '2019-08-14 17:59:19', '2019-08-14 18:14:40');

SET FOREIGN_KEY_CHECKS = 1;
