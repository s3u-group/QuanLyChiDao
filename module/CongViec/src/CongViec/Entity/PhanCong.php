<?php
namespace CongViec\Entity;


 use Doctrine\ORM\Mapping as ORM;
/**
* @ORM\Entity
* @ORM\Table(name="phan_cong")
*/
class PhanCong
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
	 * @ORM\Column(name="cong_van_id",type="bigint",length=20)
	 */
	private $congVan;

	
	// 3
	// khóa ngoại
	/**
	 * @ORM\Column(name="nguoi_thuc_hien_id",type="integer",length=11)
	 */
	private $nguoiThucHien;

	
	// 4 
	/**
	 * @ORM\Column(name="vai_tro",type="integer")
	 */
	private $vaiTro;

	
	
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
	public function setCongVan($congVan)
	{
		$this->congVan=$congVan;
	}
	public function getCongVan()
	{
		return $this->congVan;
	}

	// 3 
	public function setNguoiThucHien($nguoiThucHien)
	{
		$this->nguoiThucHien=$nguoiThucHien;
	}
	
	public function getNguoiThucHien()
	{
		return $this->nguoiThucHien;
	}

	// 4 
	public function setVaiTro($vaiTro)
	{
		$this->vaiTro=$vaiTro;
	}

	public function getVaiTro()
	{
		return $this->vaiTro;
	}
}
?>