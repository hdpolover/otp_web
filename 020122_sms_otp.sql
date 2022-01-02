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

/*Table structure for table `tb_user` */

DROP TABLE IF EXISTS `tb_user`;

CREATE TABLE `tb_user` (
  `id_user` int(10) NOT NULL AUTO_INCREMENT,
  `nama` varchar(30) NOT NULL,
  `no_telp` varchar(20) NOT NULL,
  `email` varchar(30) NOT NULL,
  `password` varchar(200) NOT NULL,
  `otp` int(6) NOT NULL,
  `status` int(5) NOT NULL DEFAULT 0,
  `aktivasi` varchar(200) NOT NULL,
  `tgl_bergabung` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id_user`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4;

/*Data for the table `tb_user` */

insert  into `tb_user`(`id_user`,`nama`,`no_telp`,`email`,`password`,`otp`,`status`,`aktivasi`,`tgl_bergabung`) values 
(8,'Mahendra Dwi Purwanto','085785111746','mahendradwipurwanto@gmail.com','$2y$10$ov23EkkXE9YzKPiprwtqEeuV/EGp.UmboWd6sAmf/L2WjoJyqnmvS',0,1,'3763ca09aebb52c74c48cd48f7237eeabf931cc8634712c668f69f2d69d808f507d10f1b71f912c31ad3bc8269feb63c1e0dd450f118be78e61c08b3e9a2697dIrYVLl+a6DKFjSj5Jr3ouQaqSIhgJQHZHfK6JcY4wNw=','2022-01-02 15:33:13');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
