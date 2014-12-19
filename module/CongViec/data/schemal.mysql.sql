CREATE TABLE IF NOT EXISTS `cong_van`(
  `id`              bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `ten`             varchar(255) DEFAULT NULL,
  `loai_id`         bigint(20) unsigned,
  `trich_yeu`       mediumtext DEFAULT NULL,
  `noi_dung`        text DEFAULT NULL,
  `ngay_ban_hanh`   datetime DEFAULT '0000-00-00 00:00:00',
  `ngay_hoan_thanh` datetime DEFAULT '0000-00-00 00:00:00',
  `ngay_tao`       	datetime DEFAULT '0000-00-00 00:00:00',
  `nguoi_tao_id`    int(11) unsigned,
  `trang_thai`		  mediumint,
  `cha_id`          bigint(20) unsigned,
  PRIMARY KEY (`id`),
  KEY `nguoi_tao_id` (`nguoi_tao_id`),
  KEY `loai_id` (`loai_id`),
  KEY `cha_id` (`cha_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `cong_van_don_vi`(
  `id`			bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `don_vi_id`	int(11) unsigned, 
  `cong_van_id`	bigint(20) unsigned,
  PRIMARY KEY (`id`),
  KEY `don_vi_id` (`don_vi_id`),
  KEY `cong_van_id` (`cong_van_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `phan_cong`(
  `id` 				bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `cong_van_id`		bigint(20) unsigned,
  `nguoi_thuc_hien_id`	int(11) unsigned,
  `vai_tro`			mediumint unsigned,
  PRIMARY KEY (`id`),
  KEY `nguoi_thuc_hien_id` (`nguoi_thuc_hien_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `theo_doi`(
  `id`			bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `ngay_bao_cao` datetime DEFAULT '0000-00-00 00:00:00',
  `nguoi_bao_cao_id` int(11) unsigned,
  `noi_dung` mediumtext,
  PRIMARY KEY (`id`),
  KEY `nguoi_bao_cao_id` (`nguoi_bao_cao_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `dinh_kem`(
  `id`			bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `url`			varchar(255),
  `doi_tuong_id` bigint(20) unsigned,
  `loai_doi_tuong` mediumint unsigned,
  PRIMARY KEY (`id`),
  KEY `doi_tuong_id` (`doi_tuong_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;