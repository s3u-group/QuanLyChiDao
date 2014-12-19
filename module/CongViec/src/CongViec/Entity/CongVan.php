<?php
namespace CongViec\Entity;



 use Doctrine\ORM\Mapping as ORM;
/**
* @ORM\Entity
* @ORM\Table(name="cong_van")
*/
class CongVan 
{
	// có 1 khóa chính, 2 khóa ngoại

	// 1
	// khóa chính
	/**
	* @ORM\Column(name="id",type="bigint",length=20)
	* @ORM\Id
	* @ORM\GeneratedValue
	*/
	private $id;


	//2
	/**
	 * @ORM\Column
	 */
	private $ten;



	//3
	// khóa ngoại
	/**
	* @ORM\Column(name="loai_id",type="bigint",length=20)	
	*/
	private $loai;


	// 4
	/**
	* @ORM\Column(name="trich_yeu",type="text")	
	*/
	private $trichYeu;


	// 5
	/**
	* @ORM\Column(name="noi_dung",type="text")	
	*/
	private $noiDung;


	//6
	/**
	* @ORM\Column(name="ngay_ban_hanh",type="datetime")	
	*/
	private $ngayBanHanh;


	// 7
	/**
	* @ORM\Column(name="ngay_hoan_thanh",type="datetime")	
	*/
	private $ngayHoanThanh;


	// 8
	/**
	* @ORM\Column(name="ngay_tao",type="datetime")	
	*/
	private $ngayTao;


	// 9
	/**
	* @ORM\Column(name="nguoi_tao_id",type="integer",length=11)	
	*/
	private $nguoiTao;


	// 10
	/**
	* @ORM\Column(name="trang_thai",type="integer")	
	*/
	private $trangThai;


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
	public function setTen($ten)
	{
		$this->ten=$ten;
	}

	public function getTen()
	{
		return $this->ten;
	}

	// 3
	public function setLoai($loai)
	{
		$this->loai=$loai;
	}
	public function getLoai()
	{
		return $this->loai;
	}

	// 4
	public function setTrichYeu($trichYeu)
	{
		$this->trichYeu=$trichYeu;
	}

	public function getTrichYeu()
	{
		return $this->trichYeu;
	}


	// 5
	public function setNoiDung($noiDung)
	{
		$this->noiDung=$noiDung;
	}
	public function getNoiDung()
	{
		return $this->noiDung;
	}

	// 6 
	public function setNgayBanHanh($ngayBanHanh)
	{
		$this->ngayBanHanh=$ngayBanHanh;
	}

	public function getNgayBanhanh()
	{
		return $this->ngayBanHanh;
	}

	// 7 
	public function setNgayHoanThanh($ngayHoanThanh)
	{
		$this->ngayHoanThanh=$ngayHoanThanh;
	}
	public function getNgayHoanThanh()
	{
		return $this->ngayHoanThanh;
	}

	// 8 
	public function setNgayTao($ngayTao)
	{
		$this->ngayTao=$ngayTao;
	}

	public function getNgayTao()
	{
		return $this->ngayTao;
	}

	// 9
	public function setNguoiTao($nguoiTao)
	{
		$this->nguoiTao=$nguoiTao;
	}
	public function getNguoiTao()
	{
		return $this->nguoiTao;
	}

	// 10
	public function setTrangThai($trangThai)
	{
		$this->trangThai=$trangThai;
	}
	public function getTrangThai()
	{
		return $this->trangThai;
	}

}
?>