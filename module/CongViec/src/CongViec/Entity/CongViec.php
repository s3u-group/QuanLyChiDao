<?php
namespace CongViec\Entity;

use Doctrine\ORM\Mapping as ORM;
use CongViec\Entity\CongVan;

/**
 * @ORM\Entity
 */
class CongViec extends CongVan{
	public function getNguoiCapNhat(){
		$users = $this->getNguoiThucHiens();
		foreach($users as $user)
			if($user->getVaiTro() == \CongViec\Entity\PhanCong::NGUOI_CAP_NHAT)
				return $user->getNguoiThucHien();
	}

	public function getNguoiTheoDoi(){
		$users = $this->getNguoiThucHiens();
		foreach($users as $user)
			if($user->getVaiTro() == \CongViec\Entity\PhanCong::NGUOI_THEO_DOI)
				return $user->getNguoiThucHien();
	}

	public function getNguoiChuTri(){
		$users = $this->getNguoiThucHiens();
		foreach($users as $user)
			if($user->getVaiTro() == \CongViec\Entity\PhanCong::CHU_TRI)
				return $user->getNguoiThucHien();
	}

	public function getNguoiPhoiHop(){
		$users = $this->getNguoiThucHiens();
		$res = array();
		foreach($users as $user)
			if($user->getVaiTro() == \CongViec\Entity\PhanCong::PHOI_HOP)
				$res[] = $user->getNguoiThucHien();
		return $res;
	}
}