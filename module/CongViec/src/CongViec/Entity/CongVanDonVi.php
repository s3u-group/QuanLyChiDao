<?php
namespace CongViec\Entity;


 use Doctrine\ORM\Mapping as ORM;
/**
* @ORM\Entity
* @ORM\Table(name="cong_van_don_vi")
*/
class CongVanDonVi
{

	// có 1 khóa chính, có 2 khóa ngoại


	// 1
	// khóa chính
	/**
	* @ORM\Column(name="id",type="bigint",length=20)
	* @ORM\Id
	* @ORM\GeneratedValue
	*/
	private $id;

	
	// 2 
	// khóa ngoại
	/**
	* @ORM\ManyToOne(targetEntity="User\Entity\DonVi", cascade={"persist"})
	* @ORM\JoinColumn(name="don_vi_id", referencedColumnName="id")
	*/
	private $donVi;

	
	// 3
	// khóa ngoại
	/**
	* @ORM\ManyToOne(targetEntity="CongViec\Entity\CongVan", cascade={"persist"})
	* @ORM\JoinColumn(name="cong_van_id", referencedColumnName="id")
	*/

	private $congVan;

	
	// 1
	public function setId($id)
	{
		$this->$id=$id;		
	}

	public function getId()
	{
		return $this->id;
	}

	// 2
	public function setDonVi($donVi)
	{
		$this->donVi=$donVi;
	}
	public function getDonVi()
	{
		return $this->donVi;
	}

	// 3
	public function setCongVan($congVan)
	{
		$this->congVan=$congVan;
	}
	public function getCongVan()
	{
		return $this->congVan;
	}
	

}
?>