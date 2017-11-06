/*
Navicat MySQL Data Transfer

Source Server         : EPG
Source Server Version : 50550
Source Host           : 192.168.1.148:3306
Source Database       : smart

Target Server Type    : MYSQL
Target Server Version : 50550
File Encoding         : 65001

Date: 2017-10-25 19:21:34
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for address
-- ----------------------------
DROP TABLE IF EXISTS `address`;
CREATE TABLE `address` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `address_address` varchar(255) DEFAULT NULL,
  `address_street` varchar(255) DEFAULT NULL,
  `address_cord_lat` varchar(10) DEFAULT NULL COMMENT 'X coordinates',
  `address_cord_lon` varchar(10) DEFAULT NULL COMMENT 'Y coordintes',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1001 DEFAULT CHARSET=utf8;
