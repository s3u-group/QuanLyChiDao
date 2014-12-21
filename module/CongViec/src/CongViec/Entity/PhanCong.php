<?php
namespace CongViec\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="phan_cong")
 */
class PhanCong
{
	const CHU_TRI = 1;
	const PHOI_HOP = 2;

	/**
	 * @ORM\Column(name="id",type="bigint",length=20)
	 * @ORM\Id
	 * @ORM\GeneratedValue
	 */
	protected $id;

	/**
	 * @ORM\ManyToOne(targetEntity="CongViec\Entity\CongVan", inversedBy="nguoiThucHiens")
	 * @ORM\JoinColumn(name="cong_van_id", referencedColumnName="id")
	 */
	protected $congVan;

	/**
	 * @ORM\ManyToOne(targetEntity="User\Entity\User", cascade={"persist"})
	 * @ORM\JoinColumn(name="nguoi_thuc_hien_id", referencedColumnName="id")
	 */
	protected $nguoiThucHien;
 
	/**
	 * @ORM\Column(name="vai_tro",type="integer")
	 */
	protected $vaiTro = 2; //mac dinh la nguoi phoi hop

	public function getId(){
		return $this->id;
	}

	public function setCongVan($congVan){
		$this->congVan = $congVan;
	}

	public function getCongVan(){
		return $this->congVan;
	}

	public function setNguoiThucHien($nguoiThucHien){
		$this->nguoiThucHien = $nguoiThucHien;
	}
	
	public function getNguoiThucHien(){
		return $this->nguoiThucHien;
	}

	public function setVaiTro($vaiTro){
		$this->vaiTro = $vaiTro;
	}

	public function getVaiTro(){
		switch ($this->vaiTro) {
			case '1':
				return 'Chủ trì';
				break;
			
			case '2':
				return 'Phối hợp';
				break;
			
			default:
				return 'Chưa rõ';
				break;
		};
	}
}