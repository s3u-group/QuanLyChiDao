<?php
namespace CongViec\Entity;


 use Doctrine\ORM\Mapping as ORM;
/**
* @ORM\Entity
* @ORM\Table(name="dinh_kem")
*/
class DinhKem
{

	// có 1 khóa chính, có 1 khóa ngoại


	// 1
	// khóa chính
	/**
	* @ORM\Column(name="id",type="bigint",length=20)
	* @ORM\Id
	* @ORM\GeneratedValue
	*/
	private $id;

	// 2 
	/**
	 * @ORM\Column
	 */
	private $url;

	
	// 3
	// khóa ngoại
	/**
	 * @ORM\Column(name="doi_tuong_id",type="bigint",length=20)
	 */
	private $doiTuong;

	// 4
	/**
	 * @ORM\Column(name="loai_doi_tuong", type="integer")
	 */
	private $loaiDoiTuong;


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
	public function setUrl($url)
	{
		$this->url=$url;
	}

	public function getUrl()
	{
		return $this->url;
	}

	// 3
	public function setDoiTuong($doiTuong)
	{
		$this->doiTuong=$doiTuong;
	}

	public function getDoiTuong()
	{
		return $this->doiTuong;
	}

	// 4 
	public function setLoaiDoiTuong($loaiDoiTuong)
	{
		$this->loaiDoiTuong=$loaiDoiTuong;
	}	
	public function getLoaiDoiTuong()
	{
		return $this->loaiDoiTuong;
	}
	

}
?>