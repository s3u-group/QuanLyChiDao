CREATE TABLE IF NOT EXISTS `users`(
  `user_id`         INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `username`        VARCHAR(255) DEFAULT NULL UNIQUE,
  `email`           VARCHAR(255) DEFAULT NULL UNIQUE,
  `display_name`    VARCHAR(50) DEFAULT NULL,
  `password`        VARCHAR(128) DEFAULT NULL,
  `state`           SMALLINT UNSIGNED NOT NULL DEFAULT '1',
  `dien_thoai`	    varchar(20) DEFAULT NULL UNIQUE,
  `dia_chi`		      varchar(255) DEFAULT NULL,
  `ho`              varchar(255) DEFAULT NULL,
  `ten`             varchar(255) DEFAULT NULL,
  `gioi_tinh`       tinyint(1) UNSIGNED,
  `thanh_pho_id`    bigint(20) unsigned,
  `quoc_gia_id`     bigint(20) unsigned,
  `don_vi_id`       int(11) unsigned,
  `ngay_tao`        datetime DEFAULT '0000-00-00 00:00:00',
  `dang_nhap_cuoi`  datetime DEFAULT '0000-00-00 00:00:00',
  `ngay_chinh_sua`  datetime DEFAULT '0000-00-00 00:00:00',
  KEY `thanh_pho_id` (`thanh_pho_id`),
  KEY `quoc_gia_id` (`quoc_gia_id`),
  KEY `don_vi_id` (`don_vi_id`)
) ENGINE=InnoDB CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `user_role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `roleId` varchar(255) NOT NULL,
  `is_default` tinyint(1) NOT NULL,
  `parent_id` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `user_role_linker` (
  `user_id` int(11) unsigned NOT NULL,
  `role_id` int(11) NOT NULL,
  PRIMARY KEY (`user_id`,`role_id`),
  KEY `role_id` (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `don_vi`(
  `id`      int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ten_don_vi`  varchar(255),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;