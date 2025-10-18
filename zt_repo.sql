/*
 Navicat Premium Dump SQL

 Source Server         : localhost
 Source Server Type    : MySQL
 Source Server Version : 80042 (8.0.42)
 Source Host           : localhost:3306
 Source Schema         : hanslaser

 Target Server Type    : MySQL
 Target Server Version : 80042 (8.0.42)
 File Encoding         : 65001

 Date: 18/10/2025 01:23:43
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for zt_repo
-- ----------------------------
DROP TABLE IF EXISTS `zt_repo`;
CREATE TABLE `zt_repo` (
  `id` mediumint NOT NULL AUTO_INCREMENT,
  `product` varchar(255) NOT NULL DEFAULT '',
  `projects` varchar(255) NOT NULL DEFAULT '',
  `name` varchar(255) NOT NULL DEFAULT '',
  `nameIdent` varchar(255) NOT NULL DEFAULT '',
  `path` varchar(255) NOT NULL DEFAULT '',
  `prefix` varchar(100) NOT NULL DEFAULT '',
  `encoding` varchar(20) NOT NULL DEFAULT '',
  `SCM` varchar(10) NOT NULL DEFAULT '',
  `client` varchar(100) NOT NULL DEFAULT '',
  `serviceHost` varchar(50) NOT NULL DEFAULT '',
  `serviceProject` varchar(100) NOT NULL DEFAULT '',
  `serviceProjectParentUID` varchar(255) NOT NULL DEFAULT '',
  `serviceProjectParentAlias` varchar(255) NOT NULL DEFAULT '',
  `commits` mediumint unsigned NOT NULL DEFAULT '0',
  `account` varchar(30) NOT NULL DEFAULT '',
  `password` varchar(30) NOT NULL DEFAULT '',
  `encrypt` varchar(30) NOT NULL DEFAULT 'plain',
  `acl` text,
  `synced` tinyint(1) NOT NULL DEFAULT '0',
  `gitfoxID` varchar(255) NOT NULL DEFAULT '',
  `lastSync` datetime DEFAULT NULL,
  `lastCommit` datetime DEFAULT NULL,
  `desc` text,
  `extra` char(30) NOT NULL DEFAULT '',
  `preMerge` enum('0','1') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '0',
  `job` mediumint unsigned NOT NULL DEFAULT '0',
  `fileServerUrl` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `fileServerAccount` varchar(40) NOT NULL DEFAULT '',
  `fileServerPassword` varchar(100) NOT NULL DEFAULT '',
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `openedBy` varchar(255) NOT NULL DEFAULT '',
  `openBy` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `idx_service_name` (`serviceProjectParentUID`,`nameIdent`)
) ENGINE=InnoDB AUTO_INCREMENT=149 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- ----------------------------
-- Records of zt_repo
-- ----------------------------
BEGIN;
INSERT INTO `zt_repo` (`id`, `product`, `projects`, `name`, `nameIdent`, `path`, `prefix`, `encoding`, `SCM`, `client`, `serviceHost`, `serviceProject`, `serviceProjectParentUID`, `serviceProjectParentAlias`, `commits`, `account`, `password`, `encrypt`, `acl`, `synced`, `gitfoxID`, `lastSync`, `lastCommit`, `desc`, `extra`, `preMerge`, `job`, `fileServerUrl`, `fileServerAccount`, `fileServerPassword`, `deleted`, `openedBy`, `openBy`) VALUES (144, '324', '758,', 't_0001', '01000soft2510047_1', 'http://127.0.0.1:3000/git/01000soft2510047/01000soft2510047_1', '', 'utf-8', 'GitFox', 'git', '4', '8', '01000soft2510047', 'p002', 0, '', '68ed05fbbd648', 'plain', '{\"acl\":\"private\",\"groups\":[\"\"],\"users\":[\"\"]}', 1, '', NULL, '2025-10-13 22:54:18', '', '8', '0', 0, NULL, '', '', 0, 'admin', '');
INSERT INTO `zt_repo` (`id`, `product`, `projects`, `name`, `nameIdent`, `path`, `prefix`, `encoding`, `SCM`, `client`, `serviceHost`, `serviceProject`, `serviceProjectParentUID`, `serviceProjectParentAlias`, `commits`, `account`, `password`, `encrypt`, `acl`, `synced`, `gitfoxID`, `lastSync`, `lastCommit`, `desc`, `extra`, `preMerge`, `job`, `fileServerUrl`, `fileServerAccount`, `fileServerPassword`, `deleted`, `openedBy`, `openBy`) VALUES (145, '299', '', 'r_001', 't_001_1', 'http://127.0.0.1:3000/git/t_001/t_001_1', '', 'utf-8', 'GitFox', 'git', '4', '10', 't_001', '', 0, '', '68f276fccc12c', 'plain', '{\"acl\":\"private\",\"groups\":[\"\"],\"users\":[\"\"]}', 1, '', NULL, '2025-10-18 01:03:56', '', '10', '0', 0, NULL, '', '', 0, 'admin', '');
INSERT INTO `zt_repo` (`id`, `product`, `projects`, `name`, `nameIdent`, `path`, `prefix`, `encoding`, `SCM`, `client`, `serviceHost`, `serviceProject`, `serviceProjectParentUID`, `serviceProjectParentAlias`, `commits`, `account`, `password`, `encrypt`, `acl`, `synced`, `gitfoxID`, `lastSync`, `lastCommit`, `desc`, `extra`, `preMerge`, `job`, `fileServerUrl`, `fileServerAccount`, `fileServerPassword`, `deleted`, `openedBy`, `openBy`) VALUES (146, '326', '762,', 'r_001', '01000soft2510049_1', 'http://127.0.0.1:3000/git/01000soft2510049/01000soft2510049_1', '', 'utf-8', 'GitFox', 'git', '4', '11', '01000soft2510049', 'i_002', 0, '', '68f27a7f83022', 'plain', '{\"acl\":\"private\",\"groups\":[\"\"],\"users\":[\"\"]}', 1, '', NULL, '2025-10-18 01:18:55', '', '11', '0', 0, NULL, '', '', 0, 'admin', '');
INSERT INTO `zt_repo` (`id`, `product`, `projects`, `name`, `nameIdent`, `path`, `prefix`, `encoding`, `SCM`, `client`, `serviceHost`, `serviceProject`, `serviceProjectParentUID`, `serviceProjectParentAlias`, `commits`, `account`, `password`, `encrypt`, `acl`, `synced`, `gitfoxID`, `lastSync`, `lastCommit`, `desc`, `extra`, `preMerge`, `job`, `fileServerUrl`, `fileServerAccount`, `fileServerPassword`, `deleted`, `openedBy`, `openBy`) VALUES (147, '326', '762,', 'r_002', '01000soft2510049_1', 'http://127.0.0.1:3000/git/01000soft2510049/01000soft2510049_1', '', 'utf-8', 'GitFox', 'git', '4', '11', '01000soft2510049', 'i_002', 0, '', '68f27a7f83022', 'plain', '{\"acl\":\"private\",\"groups\":[\"\"],\"users\":[\"\"]}', 1, '', NULL, '2025-10-18 01:18:55', NULL, '11', '0', 0, NULL, '', '', 0, 'admin', '');
INSERT INTO `zt_repo` (`id`, `product`, `projects`, `name`, `nameIdent`, `path`, `prefix`, `encoding`, `SCM`, `client`, `serviceHost`, `serviceProject`, `serviceProjectParentUID`, `serviceProjectParentAlias`, `commits`, `account`, `password`, `encrypt`, `acl`, `synced`, `gitfoxID`, `lastSync`, `lastCommit`, `desc`, `extra`, `preMerge`, `job`, `fileServerUrl`, `fileServerAccount`, `fileServerPassword`, `deleted`, `openedBy`, `openBy`) VALUES (148, '326', '762,', 'r_003', '01000soft2510049_1', 'http://127.0.0.1:3000/git/01000soft2510049/01000soft2510049_1', '', 'utf-8', 'GitFox', 'git', '4', '11', '01000soft2510049', 'i_002', 0, '', '68f27a7f83022', 'plain', '{\"acl\":\"private\",\"groups\":[\"\"],\"users\":[\"\"]}', 1, '', NULL, '2025-10-18 01:18:55', NULL, '11', '0', 0, NULL, '', '', 0, 'admin', '');
COMMIT;

SET FOREIGN_KEY_CHECKS = 1;
