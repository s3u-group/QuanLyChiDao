<?php
namespace CongViec\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="phan_cong")
 */
class PhanCong
{
	
	const NGUOI_PHAN_CONG = 1;
	const NGUOI_CAP_NHAT = 2;
	const NGUOI_THEO_DOI = 3;
	const CHU_TRI = 4;
	const PHOI_HOP = 5;

	const CHUA_XEM = 1;
	const DA_XEM = 2;

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
	 * @ORM\ManyToOne(targetEntity="User\Entity\User", inversedBy="congViecs", cascade={"persist"})
	 * @ORM\JoinColumn(name="nguoi_thuc_hien_id", referencedColumnName="user_id")
	 */
	protected $nguoiThucHien;
 
	/**
	 * @ORM\Column(name="vai_tro",type="integer")
	 */
	protected $vaiTro = 5; //mac dinh la nguoi phoi hop

	/**
	 * @ORM\Column(name="trang_thai", type="integer")
	 */
	protected $trangThai = 1; //mac dinh la chua xem

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
		if($vaiTro == '')
			$this->vaiTro = null;
		else
			$this->vaiTro = $vaiTro;
		return $this;
	}

	public function getVaiTro(){
		return $this->vaiTro;
	}

	public function getVaiTroLabel(){
		switch ($this->vaiTro) {			
			case '1':
				return 'Phân công';
				break;
			case '2':
				return 'Cập nhật';
				break;
			case '3':
				return 'Theo dõi';
				break;
			case '4':
				return 'Chủ trì';
				break;
			
			case '5':
				return 'Phối hợp';
				break;
			default:
				return 'Chưa rõ';
				break;
		};
	}

	public function setTrangThai($trangThai){
		if($trangThai == '')
			$this->trangThai = null;
		else
			$this->trangThai = $trangThai;
		return $this;
	}

	public function getTrangThai(){
		return $this->trangThai;
	}

	public function chuaXem(){
		if($this->trangThai == self::CHUA_XEM) return 1;
		return 0;
	}

	public function getTrangThaiLabel(){
		switch ($this->trangThai) {			
			case '1':
				return 'Chưa xem';
				break;
			case '2':
				return 'Đã xem';
				break;
			default:
				return 'Chưa rõ';
				break;
		}
	}
}