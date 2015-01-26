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
  `chuc_vu_id`      bigint(20) unsigned,
  `thanh_pho_id`    bigint(20) unsigned,
  `quoc_gia_id`     bigint(20) unsigned,
  `don_vi_id`       int(11) unsigned,
  `ngay_tao`        datetime DEFAULT '0000-00-00 00:00:00',
  `dang_nhap_cuoi`  datetime DEFAULT '0000-00-00 00:00:00',
  `ngay_chinh_sua`  datetime DEFAULT '0000-00-00 00:00:00',
  KEY `thanh_pho_id` (`thanh_pho_id`),
  KEY `quoc_gia_id` (`quoc_gia_id`),
  KEY `chuc_vu_id` (`chuc_vu_id`),
  KEY `don_vi_id` (`don_vi_id`)
) ENGINE=InnoDB CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `user_role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `roleId` varchar(255) NOT NULL,
  `role_name` varchar(255) NOT NULL,
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
  `ten_viet_tat`  varchar(255),
  `thu_truong_id` int(11) unsigned,
  PRIMARY KEY (`id`),
  KEY `thu_truong_id` (`thu_truong_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `user_group`(
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `group_id` bigint(20) unsigned,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `group_id` (`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `user_role` (`id`, `roleId`, `role_name`, `is_default`, `parent_id`) VALUES
(1, 'khach', 'KhÃ¡ch', 0, NULL),
(2, 'nguoi-dung', 'NgÆ°á»i dÃ¹ng', 0, '1'),
(3, 'xu-ly-cong-viec', 'Xá»­ lÃ½ cÃ´ng viá»‡c', 0, '2'),
(4, 'giao-viec', 'Giao cÃ´ng viá»‡c', 0, '3'),
(5, 'ket-xuat', 'Káº¿t xuáº¥t bÃ¡o cÃ¡o', 0, '2'),
(6, 'quan-tri', 'Quáº£n trá»‹ há»‡ thá»‘ng', 0, '2');

INSERT INTO `users` (`user_id`,`username`, `password`, `state`) VALUES (1, 'admin', '$2y$14$GcPpzRHXOs4KHhKldLWtE.NG8Ryk8rvsq2/tlQWHF1v1oucfOodPO', 0);

INSERT INTO `user_role_linker` (`user_id`, `role_id`) VALUES (1,6);