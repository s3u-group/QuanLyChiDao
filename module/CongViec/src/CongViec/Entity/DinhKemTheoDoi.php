<?php
namespace CongViec\Entity;

use Doctrine\ORM\Mapping as ORM;
use CongViec\Entity\DinhKem;

/**
 * @ORM\Entity
 */
class DinhKemTheoDoi extends DinhKem{
	/**
	 * @ORM\ManyToOne(targetEntity="CongViec\Entity\TheoDoi")
	 * @ORM\JoinColumn(name="doi_tuong_id", referencedColumnName="id", nullable=true)
	 */
	protected $theoDoi;

	public function setTheoDoi($theoDoi){
		$this->theoDoi = $theoDoi;
		return $this;
	}

	public function getTheoDoi(){
		return $theoDoi;
	}
}