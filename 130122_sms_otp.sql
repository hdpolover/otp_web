/*
 SQLyog Ultimate v12.5.1 (64 bit)
 MySQL - 10.4.17-MariaDB : Database - sms_otp
 *********************************************************************
 */
/*!40101 SET NAMES utf8 */
;

/*!40101 SET SQL_MODE=''*/
;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */
;

/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */
;

/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */
;

/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */
;

CREATE DATABASE
/*!32312 IF NOT EXISTS*/
`sms_otp`
/*!40100 DEFAULT CHARACTER SET utf8mb4 */
;

USE `sms_otp`;

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
  `expired_otp` varchar(30) NOT NULL DEFAULT '0',
  `aktivasi` varchar(200) NOT NULL,
  `tgl_bergabung` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id_user`)
) ENGINE = InnoDB AUTO_INCREMENT = 12 DEFAULT CHARSET = utf8mb4;

/*Data for the table `tb_user` */
insert into
  `tb_user`(
    `id_user`,
    `nama`,
    `no_telp`,
    `email`,
    `password`,
    `status`,
    `otp`,
    `expired_otp`,
    `aktivasi`,
    `tgl_bergabung`
  )
values
  (
    10,
    'Jhon Doe',
    '085785111746',
    'developpertech@gmail.com',
    '$2y$10$y0tkwlvk/irfhXBODIYgwebgDFeyoFFmISRf23ht4Gca8Ggs3BAY6',
    1,
    '315c30725e8d7c92d4353633da5b43af3a2a25e8cd7b8a826a3632a8215e64cbb28a8e727742aa1e8f1087ed6bafce88228e95f0b13be482b3fd854ee9702cf0YKcVlKHU5/PXNTIrrvWPgjT03U0hKOgPW7huvpLtWUI=',
    '0',
    '4f30c5d7d00c73d41ef61c11ffcf549c04a47d13a041b2e88aeab19d2d646e0570e3c43d7b9ff802973ceec800d07317eda6651405e8ccf3e60b681c2f09dffaho9p6tAv6wDalKyV+LV712fPtm6AOt7SSw27uHQdJxM=',
    '2022-01-13 06:35:31'
  );

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */
;

/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */
;

/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */
;

/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */
;