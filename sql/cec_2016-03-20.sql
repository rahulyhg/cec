# ************************************************************
# Sequel Pro SQL dump
# Version 4096
#
# http://www.sequelpro.com/
# http://code.google.com/p/sequel-pro/
#
# Host: 127.0.0.1 (MySQL 5.7.11)
# Database: cec
# Generation Time: 2016-03-20 09:27:04 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table cec_article
# ------------------------------------------------------------

DROP TABLE IF EXISTS `cec_article`;

CREATE TABLE `cec_article` (
  `c_id` int(11) DEFAULT '0',
  `u_id` int(11) DEFAULT '0',
  `a_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `a_title` varchar(255) DEFAULT NULL,
  `a_content` text,
  `a_image` varchar(255) DEFAULT NULL,
  `a_status` tinyint(2) DEFAULT '0',
  `a_display_order` int(11) DEFAULT '0',
  `a_display_to_home` tinyint(2) DEFAULT '0' COMMENT 'co hien thi ngoai trang home hay ko',
  `a_type` tinyint(2) DEFAULT '0' COMMENT 'Hoat Dong / Normal / Page',
  `a_seo_description` text,
  `a_seo_keyword` text,
  `a_datecreated` int(10) DEFAULT '0',
  `a_datemodified` int(10) DEFAULT '0',
  PRIMARY KEY (`a_id`),
  KEY `c_id` (`c_id`),
  KEY `a_status` (`a_status`),
  KEY `a_display_order` (`a_display_order`),
  KEY `a_display_to_home` (`a_display_to_home`),
  KEY `a_type` (`a_type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Dump of table cec_category
# ------------------------------------------------------------

DROP TABLE IF EXISTS `cec_category`;

CREATE TABLE `cec_category` (
  `c_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `c_name` varchar(255) DEFAULT '0',
  `c_root` int(11) DEFAULT '0',
  `c_lft` int(11) DEFAULT '0',
  `c_rgt` int(11) DEFAULT '0',
  `c_level` int(11) DEFAULT '0',
  `c_status` int(11) DEFAULT '0',
  `c_count` int(11) DEFAULT '0',
  `c_seo_description` text,
  `c_seo_keyword` text,
  `c_datecreated` int(11) DEFAULT '0',
  `c_datemodified` int(11) DEFAULT '0',
  PRIMARY KEY (`c_id`),
  KEY `c_status` (`c_status`),
  KEY `c_lft` (`c_lft`),
  KEY `c_rgt` (`c_rgt`),
  KEY `c_root` (`c_root`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Dump of table cec_contact
# ------------------------------------------------------------

DROP TABLE IF EXISTS `cec_contact`;

CREATE TABLE `cec_contact` (
  `co_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `co_company` varchar(255) DEFAULT NULL,
  `co_fullname` varchar(255) DEFAULT NULL,
  `co_address` varchar(255) DEFAULT NULL,
  `co_phone` varchar(255) DEFAULT '',
  `co_fax` int(11) DEFAULT NULL,
  `co_email` varchar(255) DEFAULT NULL,
  `co_content` text,
  `co_datecreated` int(10) DEFAULT '0',
  `co_datemodified` int(10) DEFAULT '0',
  PRIMARY KEY (`co_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Dump of table cec_image
# ------------------------------------------------------------

DROP TABLE IF EXISTS `cec_image`;

CREATE TABLE `cec_image` (
  `a_id` int(11) DEFAULT '0',
  `p_id` int(11) DEFAULT '0',
  `i_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `i_name` varchar(255) DEFAULT NULL COMMENT 'caption of the image',
  `i_basename` varchar(255) DEFAULT NULL,
  `i_extension` varchar(10) DEFAULT NULL,
  `i_filename` varchar(255) DEFAULT NULL,
  `i_path` varchar(255) DEFAULT NULL,
  `i_status` tinyint(2) DEFAULT '0',
  `i_size` int(11) DEFAULT '0',
  `i_datecreated` int(10) DEFAULT '0',
  `i_datemodified` int(10) DEFAULT '0',
  PRIMARY KEY (`i_id`),
  KEY `a_id` (`a_id`),
  KEY `p_id` (`p_id`),
  KEY `i_status` (`i_status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Dump of table cec_product
# ------------------------------------------------------------

DROP TABLE IF EXISTS `cec_product`;

CREATE TABLE `cec_product` (
  `u_id` int(11) DEFAULT '0',
  `pc_id` int(11) DEFAULT '0',
  `p_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `p_name` varchar(255) DEFAULT NULL,
  `p_image` varchar(255) DEFAULT NULL,
  `p_status` tinyint(2) DEFAULT '0',
  `p_display_order` int(11) DEFAULT '0',
  `p_seo_description` text,
  `p_seo_keyword` text,
  `p_datecreated` int(10) DEFAULT '0',
  `p_datemodified` int(10) DEFAULT '0',
  PRIMARY KEY (`p_id`),
  KEY `pc_id` (`pc_id`),
  KEY `p_status` (`p_status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Dump of table cec_product_category
# ------------------------------------------------------------

DROP TABLE IF EXISTS `cec_product_category`;

CREATE TABLE `cec_product_category` (
  `pc_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `pc_name` varchar(255) DEFAULT NULL,
  `pc_root` int(11) DEFAULT '0',
  `pc_lft` int(11) DEFAULT '0',
  `pc_rgt` int(11) DEFAULT '0',
  `pc_level` int(11) DEFAULT '0',
  `pc_status` int(11) DEFAULT '0',
  `pc_count` int(11) DEFAULT '0',
  `pc_seo_description` text,
  `pc_seo_keyword` text,
  `pc_datecreated` int(11) DEFAULT '0',
  `pc_datemodified` int(11) DEFAULT '0',
  PRIMARY KEY (`pc_id`),
  KEY `pc_root` (`pc_root`),
  KEY `pc_lft` (`pc_lft`),
  KEY `pc_rgt` (`pc_rgt`),
  KEY `pc_status` (`pc_status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Dump of table cec_slug
# ------------------------------------------------------------

DROP TABLE IF EXISTS `cec_slug`;

CREATE TABLE `cec_slug` (
  `u_id` int(11) DEFAULT '0',
  `s_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `s_slug` varchar(128) DEFAULT NULL,
  `s_hash` varchar(32) DEFAULT NULL,
  `s_objectid` int(11) DEFAULT '0',
  `s_model` varchar(255) NOT NULL DEFAULT '',
  `s_status` smallint(2) DEFAULT '0',
  `s_datecreated` int(10) DEFAULT '0',
  `s_datemodified` int(10) DEFAULT '0',
  PRIMARY KEY (`s_id`),
  KEY `u_id` (`u_id`),
  KEY `s_slug` (`s_slug`),
  KEY `s_hash` (`s_hash`),
  KEY `s_status` (`s_status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Dump of table cec_user
# ------------------------------------------------------------

DROP TABLE IF EXISTS `cec_user`;

CREATE TABLE `cec_user` (
  `u_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `u_name` varchar(255) DEFAULT NULL,
  `u_email` varchar(100) DEFAULT NULL,
  `u_password` varchar(100) NOT NULL,
  `u_role` int(4) DEFAULT '0',
  `u_avatar` varchar(155) DEFAULT '',
  `u_status` int(2) DEFAULT '0',
  `u_datecreated` int(10) DEFAULT '0',
  `u_datemodified` int(10) DEFAULT '0',
  PRIMARY KEY (`u_id`),
  KEY `u_email` (`u_email`),
  KEY `u_password` (`u_password`),
  KEY `u_status` (`u_status`),
  KEY `u_role` (`u_role`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

LOCK TABLES `cec_user` WRITE;
/*!40000 ALTER TABLE `cec_user` DISABLE KEYS */;

INSERT INTO `cec_user` (`u_id`, `u_name`, `u_email`, `u_password`, `u_role`, `u_avatar`, `u_status`, `u_datecreated`, `u_datemodified`)
VALUES
	(1,'Administrator','admin@fly.com','$2a$10$FM2SzKio5sTsV9dbJQ9qGeJ..0pt1VxgMWnCorP8q4gCBLq3uh0o6',5,'/uploads/avatar/2016/03/12745522-1168033236555228-5650083758396135947-n-1457686175.jpg',1,0,1458463831);

/*!40000 ALTER TABLE `cec_user` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
