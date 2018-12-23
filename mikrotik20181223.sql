/*
SQLyog Community Edition- MySQL GUI v5.29
Host - 5.5.5-10.1.33-MariaDB : Database - mikrotik_controller
*********************************************************************
Server version : 5.5.5-10.1.33-MariaDB
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

create database if not exists `mikrotik_controller`;

USE `mikrotik_controller`;

/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

/*Table structure for table `devices` */

DROP TABLE IF EXISTS `devices`;

CREATE TABLE `devices` (
  `device_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `device_name` varchar(128) NOT NULL,
  `device_type` varchar(128) DEFAULT NULL,
  `device_description` text,
  `device_ip` varchar(128) NOT NULL,
  `device_username` varchar(32) DEFAULT NULL,
  `device_password` varchar(32) DEFAULT NULL,
  `device_port_api` int(5) unsigned DEFAULT NULL,
  `region_domain_id` varchar(5) NOT NULL,
  `usermikrotik` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`device_ip`),
  UNIQUE KEY `device_id` (`device_id`),
  KEY `fkdevice_regiondomain` (`region_domain_id`),
  CONSTRAINT `fkdevice_regiondomain` FOREIGN KEY (`region_domain_id`) REFERENCES `region_domain` (`region_domain_id`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

/*Data for the table `devices` */

insert  into `devices`(`device_id`,`device_name`,`device_type`,`device_description`,`device_ip`,`device_username`,`device_password`,`device_port_api`,`region_domain_id`,`usermikrotik`) values (8,'EF Gedung Pemuda','RB951G-2HnD',NULL,'10.132.12.118','admin02','b1zn3t99',8778,'JKT',NULL),(5,'SMK Negeri 7 Jakarta','RB951G-2HnD',NULL,'10.132.128.24','admin02','b1zn3t99',8778,'JKT',NULL),(7,'SMP 31 Muhammadiyah Jakarta','RGB951-2HnD',NULL,'10.132.128.52','admin02','b1zn3t99',8778,'JKT',NULL),(6,'Kalbe Institute','RB951G-2HnD',NULL,'10.132.128.61','admin02','b1zn3t99',8778,'JKT',NULL),(9,'SMA Negeri 1 Jakarta','RB951G-2HnD',NULL,'10.132.128.71','admin02','b1zn3t99',8728,'JKP',NULL);

/*Table structure for table `region` */

DROP TABLE IF EXISTS `region`;

CREATE TABLE `region` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `region_id` varchar(5) NOT NULL,
  `region_name` varchar(64) NOT NULL,
  PRIMARY KEY (`region_id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `region` */

insert  into `region`(`id`,`region_id`,`region_name`) values (1,'DKI','DKI Jakarta');

/*Table structure for table `region_domain` */

DROP TABLE IF EXISTS `region_domain`;

CREATE TABLE `region_domain` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `region_domain_id` varchar(5) NOT NULL,
  `region_domain_name` varchar(64) NOT NULL,
  `region_id` varchar(5) NOT NULL,
  PRIMARY KEY (`region_domain_id`),
  UNIQUE KEY `id` (`id`),
  KEY `fk_regiondomain` (`region_id`),
  CONSTRAINT `fk_regiondomain` FOREIGN KEY (`region_id`) REFERENCES `region` (`region_id`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

/*Data for the table `region_domain` */

insert  into `region_domain`(`id`,`region_domain_id`,`region_domain_name`,`region_id`) values (3,'JKP','Jakarta Pusat','DKI'),(1,'JKT','Jakarta Timur','DKI');

/*Table structure for table `usermikrotik` */

DROP TABLE IF EXISTS `usermikrotik`;

CREATE TABLE `usermikrotik` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username_mikrotik` varchar(64) NOT NULL,
  `password_mikrotik` varchar(32) DEFAULT NULL,
  `port` int(10) unsigned DEFAULT '8728',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

/*Data for the table `usermikrotik` */

insert  into `usermikrotik`(`id`,`username_mikrotik`,`password_mikrotik`,`port`) values (3,'admin02','b1zn3t99',8728);

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `user_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(64) NOT NULL,
  `password` varchar(128) NOT NULL,
  `privilege` enum('full','write','read') DEFAULT NULL,
  `fullname` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`username`),
  UNIQUE KEY `user_id` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

/*Data for the table `users` */

insert  into `users`(`user_id`,`username`,`password`,`privilege`,`fullname`) values (1,'admin','$2y$10$vGuNwa5DRtpljEsaU423V.fwtL2A6WbrJuzGWei42E2aDFZ1CDobe','full','Administrator'),(12,'sonypiay','$2y$10$hJ1A/bEsJTg.02rrs2g7Te2rUmRjtnuSg9hJhdqMO3BII1Qnddhp6','full','Sony Darmawan');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
