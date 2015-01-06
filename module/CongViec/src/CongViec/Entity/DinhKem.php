<?php
namespace CongViec\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="dinh_kem")
 * @ORM\MappedSuperclass
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="loai_doi_tuong", type="integer")
 * @ORM\DiscriminatorMap({"1" = "DinhKem", "2" = "DinhKemTheoDoi"})
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
	 * @ORM\ManyToOne(targetEntity="CongViec\Entity\CongViec", inversedBy="dinhKems")
	 * @ORM\JoinColumn(name="doi_tuong_id", referencedColumnName="id")
	 */
	protected $doiTuong;

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
}