/*
SQLyog Ultimate v12.5.1 (64 bit)
MySQL - 10.4.17-MariaDB : Database - sms_otp
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`sms_otp` /*!40100 DEFAULT CHARACTER SET utf8mb4 */;

USE `sms_otp`;

/*Table structure for table `tb_login_ip` */

DROP TABLE IF EXISTS `tb_login_ip`;

CREATE TABLE `tb_login_ip` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `id_user` int(19) NOT NULL,
  `last_ip` varchar(30) NOT NULL,
  `device` text NOT NULL,
  `status` int(5) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;

/*Data for the table `tb_login_ip` */

insert  into `tb_login_ip`(`id`,`id_user`,`last_ip`,`device`,`status`) values 
(3,9,'::1','Windows 10 - Chrome 96.0.4664.110',1);

/*Table structure for table `tb_user` */

DROP TABLE IF EXISTS `tb_user`;

CREATE TABLE `tb_user` (
  `id_user` int(10) NOT NULL AUTO_INCREMENT,
  `nama` varchar(30) NOT NULL,
  `no_telp` varchar(20) NOT NULL,
  `email` varchar(30) NOT NULL,
  `password` varchar(200) NOT NULL,
  `status` int(5) NOT NULL DEFAULT 0,
  `otp` varchar(200) NOT NULL,
  `aktivasi` varchar(200) NOT NULL,
  `tgl_bergabung` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id_user`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4;

/*Data for the table `tb_user` */

insert  into `tb_user`(`id_user`,`nama`,`no_telp`,`email`,`password`,`status`,`otp`,`aktivasi`,`tgl_bergabung`) values 
(9,'Mahendra Dwi Purwanto','085785111746','mahendradwipurwanto@gmail.com','$2y$10$WA.9CvV348VMacA6uYsyiOxEX6JquIjw/OwT96DZMbYfKsEOaAcm2',1,'add010fc7316a85378f8919c8902f8299624d8155e457aad9ce4d3f3499a3280b69b342e8a04dd5695d0d373866788435c4db27e49e4d3d0bdcb27c8595adf71/xx/lL2R2hqZ5dX+sLuhw5tt0qcoqoEhiOLWiHTtRV4=','8e6cddbe9ad4bfb59c7072cfc67fb7cbcebfbea2a63b2582f2881f56d77514f7faf962c7e413dc04474f9079771a0ee9e473b2aff35949fe66fdbcba2a257b98i44VrTvxZCv44HkyfgAupJFYVIFF8G5D7eQ25zRJBec=','2022-01-02 20:48:31');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
