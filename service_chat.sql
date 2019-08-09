/*
Navicat MySQL Data Transfer

Source Server         : 127.0.0.1
Source Server Version : 50725
Source Host           : 127.0.0.1:33060
Source Database       : service_chat

Target Server Type    : MYSQL
Target Server Version : 50725
File Encoding         : 65001

Date: 2019-06-21 14:49:37
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for bind_secrets
-- ----------------------------
DROP TABLE IF EXISTS `bind_secrets`;
CREATE TABLE `bind_secrets` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '登陆用户账号',
  `secret` char(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '登陆用户绑定的google秘钥',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for chat_messages
-- ----------------------------
DROP TABLE IF EXISTS `chat_messages`;
CREATE TABLE `chat_messages` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `player_id` int(11) NOT NULL COMMENT '玩家ID',
  `service_id` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '客服ID',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '接收状态： 0 未接收，1：已接收',
  `u_type` tinyint(4) NOT NULL COMMENT '发送者类型: 1：玩家 2：客服',
  `msg` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'type:1 时代表玩家发送的消息 type:2 时表示客服回复的消息',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=72 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for password_resets
-- ----------------------------
DROP TABLE IF EXISTS `password_resets`;
CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for player_summaries
-- ----------------------------
DROP TABLE IF EXISTS `player_summaries`;
CREATE TABLE `player_summaries` (
  `player_id` varchar(50) NOT NULL COMMENT '玩家ID 账号',
  `service_id` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '客服ID',
  `send_num` bigint(11) NOT NULL COMMENT '玩家发送问题数量',
  `last_send_time` timestamp NULL DEFAULT NULL COMMENT '玩家 最后发送问题时间',
  `last_replay_time` timestamp NULL DEFAULT NULL COMMENT '客服 最后回复时间',
  `last_send_msg` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT '玩家 最后发送的问题',
  `last_replay_msg` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT '客服 最后回复的信息',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`player_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for punch_cards
-- ----------------------------
DROP TABLE IF EXISTS `punch_cards`;
CREATE TABLE `punch_cards` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `service_time_id` bigint(20) NOT NULL,
  `service_id` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '客服ID',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '打卡状态： 1：上班打卡,2：下班打卡',
  `type` tinyint(4) NOT NULL DEFAULT '0' COMMENT '客服类型:0 普通客服',
  `punch_time` timestamp NULL DEFAULT NULL COMMENT '打卡时间',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for risk_jobs
-- ----------------------------
DROP TABLE IF EXISTS `risk_jobs`;
CREATE TABLE `risk_jobs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `risk_management_id` int(10) NOT NULL COMMENT '风控任务id',
  `last_time` timestamp NULL DEFAULT NULL COMMENT '该任务上次执行时刻',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for risk_managements
-- ----------------------------
DROP TABLE IF EXISTS `risk_managements`;
CREATE TABLE `risk_managements` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `content` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '风控内容说明',
  `rate_minute` int(10) NOT NULL COMMENT '运行频率  eg ： 10  代表10分钟运行1次',
  `type` tinyint(4) NOT NULL COMMENT '风控类型 1：用户风控 2：代理风控 3：平台风控 4：支付风控',
  `status` tinyint(4) NOT NULL COMMENT '是否激活: 1: 激活   0: 未激活 (激活的风控发生时才会产出风控记录)',
  `p1` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '第一个参数',
  `p2` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '第二个参数',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for risk_records
-- ----------------------------
DROP TABLE IF EXISTS `risk_records`;
CREATE TABLE `risk_records` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `status` tinyint(4) NOT NULL COMMENT '是否已读: 1: 已读  0: 未读',
  `content` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '触发内容',
  `case_user` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '引发者',
  `admin_user` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '已读管理员',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for service_achievements
-- ----------------------------
DROP TABLE IF EXISTS `service_achievements`;
CREATE TABLE `service_achievements` (
  `service_id` varchar(50) NOT NULL COMMENT '客服ID 账号',
  `allot_num` bigint(11) NOT NULL COMMENT '分配问题数量',
  `not_handle_num` int(11) NOT NULL COMMENT '未处理数量',
  `work_duration` bigint(11) NOT NULL COMMENT '工作时长 单位 s秒',
  `last_replay_time` timestamp NULL DEFAULT NULL COMMENT '最后回复时间',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`service_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for service_times
-- ----------------------------
DROP TABLE IF EXISTS `service_times`;
CREATE TABLE `service_times` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `service_id` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '客服ID',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '打卡状态： 1：上班打卡,2：下班打卡',
  `on_time` timestamp NULL DEFAULT NULL COMMENT '上班时间',
  `off_time` timestamp NULL DEFAULT NULL COMMENT '下班时间',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for suggestions
-- ----------------------------
DROP TABLE IF EXISTS `suggestions`;
CREATE TABLE `suggestions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip` varchar(255) NOT NULL COMMENT '访问者ip地址',
  `mobile` varchar(20) DEFAULT NULL,
  `content` varchar(255) DEFAULT NULL COMMENT '意见、反馈',
  `width` int(11) DEFAULT NULL COMMENT '屏幕宽度',
  `height` int(11) DEFAULT NULL COMMENT '屏幕高度',
  `renderer` varchar(255) DEFAULT NULL COMMENT '显卡渲染器',
  `vendor` varchar(255) DEFAULT NULL COMMENT '显卡供应商',
  `device_pixel_ratio` varchar(10) DEFAULT NULL COMMENT '设备像素比',
  `platform` varchar(255) DEFAULT NULL COMMENT '硬件平台',
  `appCodeName` varchar(255) DEFAULT NULL COMMENT '浏览器代号',
  `appName` varchar(255) DEFAULT NULL COMMENT '浏览器名称',
  `appVersion` varchar(255) DEFAULT NULL COMMENT '版本信息',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nickname` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `avatar` text COLLATE utf8mb4_unicode_ci,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `is_admin` tinyint(1) NOT NULL DEFAULT '0',
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `github_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `github_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `github_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `weibo_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `weibo_link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `website` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
