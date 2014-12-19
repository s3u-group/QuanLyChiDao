<?php
namespace CongViec\Entity;


 use Doctrine\ORM\Mapping as ORM;
 use Doctrine\Common\Collections\ArrayCollection;
/**
* @ORM\Entity
* @ORM\Table(name="theo_doi")
*/
class TheoDoi
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


	//2
	/**
	* @ORM\Column(name="ngay_bao_cao",type="datetime")	
	*/
	private $ngayBaoCao;


	// 3
	// khóa ngoại
	/**
	* @ORM\ManyToOne(targetEntity="User\Entity\User", cascade={"persist"})
	* @ORM\JoinColumn(name="nguoi_bao_cao_id", referencedColumnName="id")
	*/
	private $nguoiBaoCao;

	// 4
	/**
	* @ORM\Column(name="noi_dung",type="text")	
	*/
	private $noiDung;

	// 5
	// khóa ngoại
	/**
	* @ORM\ManyToOne(targetEntity="CongViec\Entity\CongVan", cascade={"persist"})
	* @ORM\JoinColumn(name="cong_van_id", referencedColumnName="id")
	*/
	private $congVan;



	/**
     * @OneToMany(targetEntity="CongViec\Entity\DinhKem", mappedBy="doiTuong")
     **/
    private $dinhKems;

    public function __construct()
    {
        $this->dinhKems = new ArrayCollection();
    }

    public function addDinhKems(Collection $dinhKems)
    {
        foreach ($dinhKems as $dinhKem) {
            $this->dinhKems->add($dinhKem);
        }
    }

    public function removeDinhKems(Collection $dinhKems)
    {
        foreach ($dinhKems as $dinhKem) {
            $this->dinhKems->removeElement($dinhKem);
        }
    }

    public function getDinhKems()
    {
        return $this->dinhKems;
    }



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
	public function setNgayBaoCao($ngayBaoCao)
	{
		$this->ngayBaoCao=$ngayBaoCao;
	}
	
	public function getNgayBaoCao()
	{
		return $this->ngayBaoCao;
	}

	// 3
	public function setNguoiBaoCao($nguoiBaoCao)
	{
		$this->nguoiBaoCao=$nguoiBaoCao;
	}

	public function getNguoiBaoCao()
	{
		return $this->nguoiBaoCao;
	}

	// 4 
	public function setNoiDung($noiDung)
	{
		$this->noiDung=$noiDung;
	}
	public function getNoiDung()
	{
		return $this->noiDung;
	}

	// 5
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