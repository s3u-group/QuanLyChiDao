CREATE TABLE IF NOT EXISTS `cong_van`(
  `id`              bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `so_hieu`         varchar(255) DEFAULT NULL UNIQUE,
  `ten`             varchar(255) DEFAULT NULL,
  `loai_id`         bigint(20) unsigned,
  `linh_vuc_id`     bigint(20) unsigned,
  `trich_yeu`       mediumtext DEFAULT NULL,
  `noi_dung`        text DEFAULT NULL,
  `ngay_ban_hanh`   datetime DEFAULT '0000-00-00 00:00:00',
  `nguoi_ky_id`     int(11) unsigned,
  `ngay_hoan_thanh` datetime DEFAULT '0000-00-00 00:00:00',
  `ngay_hoan_thanh_thuc` datetime DEFAULT '0000-00-00 00:00:00',
  `ngay_tao`       	datetime DEFAULT '0000-00-00 00:00:00',
  `nguoi_tao_id`    int(11) unsigned,
  `ngay_sua`        datetime DEFAULT '0000-00-00 00:00:00',
  `trang_thai`		  mediumint unsigned DEFAULT '1',
  `cha_id`          bigint(20) unsigned,
  `discr`           varchar(20),
  PRIMARY KEY (`id`),
  KEY `nguoi_tao_id` (`nguoi_tao_id`),
  KEY `nguoi_ky_id` (`nguoi_ky_id`),
  KEY `loai_id` (`loai_id`),
  KEY `linh_vuc_id` (`linh_vuc_id`),
  KEY `cha_id` (`cha_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `don_vi`(
  `id`			int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ten_don_vi`	varchar(255),
  PRIMARY KEY (`id`)
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
  `vai_tro`			mediumint unsigned DEFAULT '5',
  `trang_thai`      mediumint unsigned DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `cong_van_id` (`cong_van_id`),
  KEY `nguoi_thuc_hien_id` (`nguoi_thuc_hien_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `theo_doi`(
  `id`			bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `ngay_bao_cao` datetime DEFAULT '0000-00-00 00:00:00',
  `nguoi_bao_cao_id` int(11) unsigned,
  `nguoi_tao_id` int(11) unsigned,
  `ngay_sua`        datetime DEFAULT '0000-00-00 00:00:00',
  `noi_dung` mediumtext,
  `cong_van_id`   bigint(20) unsigned,
  `trang_thai` mediumint unsigned,
  PRIMARY KEY (`id`),
  KEY `nguoi_bao_cao_id` (`nguoi_bao_cao_id`),
  KEY `nguoi_tao_id` (`nguoi_tao_id`),
  KEY `cong_van_id` (`cong_van_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `dinh_kem`(
  `id`			bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `url`			varchar(255),
  `doi_tuong_id` bigint(20) unsigned,
  `loai_doi_tuong` mediumint unsigned,
  PRIMARY KEY (`id`),
  KEY `doi_tuong_id` (`doi_tuong_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;