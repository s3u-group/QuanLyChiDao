<?php
namespace CongViec\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="dinh_kem")
 */
class DinhKem
{
	/**
	 * @ORM\Column(name="id",type="bigint",length=20)
	 * @ORM\Id
	 * @ORM\GeneratedValue
	 */
	protected $id;

	/**
	 * @ORM\Column
	 */
	protected $url;

	/**
	 * @ORM\Column(name="doi_tuong_id",type="bigint",length=20)
	 */
	protected $doiTuong;

	const CONG_VAN=1;
	const THEO_DOI=2;

	/**
	 * @ORM\Column(name="loai_doi_tuong", type="integer")
	 */
	protected $loaiDoiTuong;

	public function getId(){
		return $this->id;
	}

	public function setUrl($url){
		$this->url = $url;
	}

	public function getUrl(){
		return $this->url;
	}

	public function setDoiTuong($doiTuong){
		$this->doiTuong = $doiTuong;
	}

	public function getDoiTuong(){
		return $this->doiTuong;
	}

	public function setLoaiDoiTuong($loaiDoiTuong){
		$this->loaiDoiTuong = $loaiDoiTuong;
	}

	public function getLoaiDoiTuong(){
		return $this->loaiDoiTuong;
	}
}