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
  `role_name` varchar(255) NOT NULL,
  `is_default` tinyint(1) NOT NULL,
  `parent_id` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


INSERT INTO `user_role` (`id`, `roleId`, `role_name`, `is_default`, `parent_id`) VALUES
(1, 'khach', 'KhÃ¡ch', 0, NULL),
(2, 'cong-viec-can-xu-ly', 'CÃ´ng viá»‡c cáº§n xá»­ lÃ½', 0, NULL),
(3, 'giao-viec-moi', 'Giao viá»‡c má»›i', 0, NULL),
(4, 'theo-doi-viec-da-giao', 'Theo dÃµi viá»‡c Ä‘Ã£ giao', 0, NULL),
(5, 'bao-cao-nghiem-thu', 'BÃ¡o cÃ¡o nghiá»‡m thu', 0, NULL),
(6, 'nhat-ky-cong-viec', 'Nháº­t kÃ½ cÃ´ng viá»‡c', 0, NULL),
(7, 'tao-tai-khoan', 'Táº¡o tÃ i khoáº£n', 0, NULL),
(8, 'danh-sach-nhan-vien', 'Danh sÃ¡ch nhÃ¢n viÃªn', 0, NULL),
(9, 'tao-don-vi', 'Táº¡o Ä‘Æ¡n vá»‹', 0, NULL),
(10, 'danh-muc-don-vi', 'Danh má»¥c Ä‘Æ¡n vá»‹', 0, NULL),
(11, 'phan-quyen', 'PhÃ¢n quyá»n', 0, NULL),
(12, 'ho-so-ca-nhan', 'Há»“ sÆ¡ cÃ¡ nhÃ¢n', 0, NULL),
(13, 'doi-mat-khau', 'Äá»•i máº­t kháº©u', 0, NULL),
(14, 'thong-tin-phan-mem', 'ThÃ´ng tin pháº§n má»n', 0, NULL),
(15, 'admin', 'Admin', 0, NULL);


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