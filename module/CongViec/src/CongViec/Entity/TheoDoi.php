<?php
namespace CongViec\Entity;


use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Datetime;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="theo_doi")
 */
class TheoDoi
{
	/**
	 * @ORM\Column(name="id", type="bigint", length=20)
	 * @ORM\Id
	 * @ORM\GeneratedValue
	 */
	protected $id;

	/**
	 * @ORM\Column(name="ngay_bao_cao", type="datetime")	
	 */
	protected $ngayBaoCao;

	/**
	 * @ORM\Column(name="ngay_sua", type="datetime")
	 */
	protected $ngaySua;

	/**
	 * @ORM\ManyToOne(targetEntity="User\Entity\User")
	 * @ORM\JoinColumn(name="nguoi_bao_cao_id", referencedColumnName="user_id")
	 */
	protected $nguoiBaoCao;

	/**
	 * @ORM\ManyToOne(targetEntity="User\Entity\User")
	 * @ORM\JoinColumn(name="nguoi_tao_id", referencedColumnName="user_id")
	 */
	protected $nguoiTao;

	/**
	 * @ORM\Column(name="noi_dung", type="text")	
	 */
	protected $noiDung;

	/**
	 * @ORM\ManyToOne(targetEntity="CongViec\Entity\CongVan")
	 * @ORM\JoinColumn(name="cong_van_id", referencedColumnName="id")
	 */
	protected $congVan;

	/**
     * @ORM\OneToMany(targetEntity="CongViec\Entity\DinhKemTheoDoi", mappedBy="theoDoi")
     */
    protected $dinhKems;

    const DA_HUY=0;
    const DANG_HOAT_DONG=1;

    /**
	 * @ORM\Column(name="trang_thai", type="integer")	
	 */
	protected $trangThai=1;

	/**
	 * @ORM\PrePersist 
	 */
	public function onPrePersist(){
    	$this->ngayBaoCao = new DateTime('now');
	}

	/**
	 * @ORM\preUpdate  
	 */
	public function onPreUpdate(){
    	$this->ngaySua = new DateTime('now');
	}

    public function __construct(){
        $this->dinhKems = new ArrayCollection();
    }

	public function getId(){
		return $this->id;
	}

	public function setNgayBaoCao($ngayBaoCao){
		$this->ngayBaoCao = $ngayBaoCao;
	}
	
	public function getNgayBaoCao(){
		return $this->ngayBaoCao;
	}

	public function setNgaySua($ngaySua){
		$this->ngaySua = $ngaySua;
		return $this;
	}

	public function getNgaySua(){
		return $this->ngaySua;
	}

	public function setNguoiBaoCao($nguoiBaoCao){
		$this->nguoiBaoCao = $nguoiBaoCao;
	}

	public function getNguoiBaoCao(){
		return $this->nguoiBaoCao;
	}

	public function setNoiDung($noiDung){
		$this->noiDung = $noiDung;
	}

	public function getNoiDung(){
		return $this->noiDung;
	}

	public function setCongVan($congVan){
		$this->congVan = $congVan;
	}

	public function getCongVan(){
		return $this->congVan;
	}

	public function getCongViec(){
		return $this->congVan;
	}

    public function addDinhKems($dinhKems){
        foreach ($dinhKems as $dinhKem) {
            $this->dinhKems->add($dinhKem);
        }
    }

    public function removeDinhKems($dinhKems){
        foreach ($dinhKems as $dinhKem) {
            $this->dinhKems->removeElement($dinhKem);
        }
    }

    public function getDinhKems(){
        return $this->dinhKems->toArray();
    }

    public function setTrangThai($trangThai)
    {
    	$this->trangThai=$trangThai;
    }

    public function getTrangThai()
    {
    	return $this->trangThai;
    }

    public function setNguoiTao($nguoiTao){
    	$this->nguoiTao=$nguoiTao;
    }
    public function getNguoiTao(){
    	return $this->nguoiTao;
    }

    public function getTenNguoiTao(){
    	if($nguoiTao = $this->getNguoiTao())
    		return $nguoiTao->getHoTen();
    }

    public function isDaSua(){
    	if($this->ngaySua->getTimestamp())
    		return 1;
    	return 0;
    }

}