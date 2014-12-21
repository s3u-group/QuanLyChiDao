<?php
namespace CongViec\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Datetime;

/**
 * @ORM\Entity
 * @ORM\Table(name="cong_van")
 */
class CongVan 
{
	// tre han : ngay hien tai > ngay hoan thanh & chua hoan thanh
	const CHUA_XEM = 1;
	const DANG_XU_LY = 5;
	const HOAN_THANH = 10;

	/**
	 * @ORM\Column(name="id", type="bigint", length=20)
	 * @ORM\Id
	 * @ORM\GeneratedValue
	 */
	protected $id;

	/**
	 * @ORM\Column
	 */
	protected $ten;

	/**
	 * @ORM\ManyToOne(targetEntity="Taxonomy\Entity\TermTaxonomy")
	 * @ORM\JoinColumn(name="loai_id", referencedColumnName="id")
	 */
	protected $loai;

	/**
	 * @ORM\Column(name="trich_yeu", type="text")	
	 */
	protected $trichYeu;

	/**
	 * @ORM\Column(name="noi_dung", type="text")	
	 */
	protected $noiDung;

	/**
	 * @ORM\Column(name="ngay_ban_hanh", type="datetime")	
	 */
	protected $ngayBanHanh;

	/**
	 * @ORM\Column(name="ngay_hoan_thanh", type="datetime")	
	 */
	protected $ngayHoanThanh;

	/**
	 * @ORM\Column(name="ngay_hoan_thanh_thuc", type="datetime")	
	 */
	protected $ngayHoanThanhThuc;

	/**
	 * @ORM\Column(name="ngay_tao",type="datetime")	
	 */
	protected $ngayTao;

	/**
	 * @ORM\ManyToOne(targetEntity="User\Entity\User")
	 * @ORM\JoinColumn(name="nguoi_tao_id", referencedColumnName="id")
	 */
	protected $nguoiTao;

	/**
	 * @ORM\Column(name="trang_thai", type="integer")	
	 */
	protected $trangThai;

	/**
	 * @ORM\ManyToOne(targetEntity="CongViec\Entity\CongVan")
	 * @ORM\JoinColumn(name="cha_id", referencedColumnName="id")
	 */
	protected $cha;

	/**
     * @ORM\OneToMany(targetEntity="CongViec\Entity\DinhKem", mappedBy="doiTuong")
     */
    protected $dinhKems;

    /**
     * @ORM\OneToMany(targetEntity="CongViec\Entity\PhanCong", mappedBy="congVan")
     */
    protected $nguoiThucHiens;

    /**
     * @ORM\ManyToMany(targetEntity="User\Entity\DonVi")
     * @ORM\JoinTable(name="cong_van_don_vi",
     *      joinColumns={@ORM\JoinColumn(name="cong_van_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="don_vi_id", referencedColumnName="id")}
     *      )
     */
    protected $donViTiepNhans;

    public function __construct()
    {
        $this->dinhKems = new ArrayCollection();
        $this->nguoiThucHiens = new ArrayCollection();
        $this->donViTiepNhans = new ArrayCollection();
    }

	public function getId(){
		return $this->id;
	}

	public function setTen($ten){
		$this->ten = $ten;
	}

	public function getTen(){
		return $this->ten;
	}

	public function setLoai($loai){
		$this->loai = $loai;
	}

	public function getLoai(){
		return $this->loai;
	}

	public function setTrichYeu($trichYeu){
		$this->trichYeu = $trichYeu;
	}

	public function getTrichYeu(){
		return $this->trichYeu;
	}

	public function setNoiDung($noiDung){
		$this->noiDung = $noiDung;
	}

	public function getNoiDung(){
		return $this->noiDung;
	}

	public function setNgayBanHanh($ngayBanHanh){
		$this->ngayBanHanh = $ngayBanHanh;
	}

	public function getNgayBanhanh(){
		return $this->ngayBanHanh;
	}

	public function setNgayHoanThanh($ngayHoanThanh){
		$this->ngayHoanThanh = $ngayHoanThanh;
	}

	public function getNgayHoanThanh(){
		return $this->ngayHoanThanh;
	}

	public function setNgayHoanThanhThuc($ngayHoanThanhThuc){
		$this->ngayHoanThanhThuc = $ngayHoanThanhThuc;
	}

	public function getNgayHoanThanhThuc(){
		return $this->ngayHoanThanhThuc;
	}

	public function setNgayTao($ngayTao){
		$this->ngayTao = $ngayTao;
	}

	public function getNgayTao(){
		return $this->ngayTao;
	}

	public function setNguoiTao($nguoiTao){
		$this->nguoiTao = $nguoiTao;
	}
	
	public function getNguoiTao(){
		return $this->nguoiTao;
	}

	public function setTrangThai($trangThai){
		$this->trangThai = $trangThai;
	}

	public function getTrangThai(){
		return $this->trangThai;
	}

	public function getTrangThaiNhom(){
		if(time() > $this->ngayHoanThanh->getTimestamp())
			return 'Trễ hạn';
		switch ($this->trangThai) {
			case '1':
				return 'Chưa xem';
				break;
			
			case '2':
				return 'Đang xử lý';
				break;

			case '3':
				return 'Hoàn thành';
				break;

			default:
				return 'Chưa rõ';
				break;
		}
	}

	public function setCha($cha){
		$this->cha = $cha;
	}

	public function getCha(){
		return $this->cha;
	}

    public function addDinhKems(Collection $dinhKems){
        foreach ($dinhKems as $dinhKem) {
            $this->dinhKems->add($dinhKem);
        }
    }

    public function removeDinhKems(Collection $dinhKems){
        foreach ($dinhKems as $dinhKem) {
            $this->dinhKems->removeElement($dinhKem);
        }
    }

    public function getDinhKems(){
        return $this->dinhKems->toArray();
    }

    public function addNguoiThucHiens($nguoiThucHiens){
    	foreach($nguoiThucHiens as $nguoiThucHien){
    		$nguoiThucHien->setCongVan($this);
    		$this->nguoiThucHiens->add($nguoiThucHien);
    	}
    }

    public function removeNguoiThucHiens($nguoiThucHiens){
    	foreach($nguoiThucHiens as $nguoiThucHien){
    		$nguoiThucHien->setCongVan(null);
    		$this->nguoiThucHiens->removeElement($nguoiThucHien);
    	}
    }

    public function getNguoiThucHiens(){
    	return $this->nguoiThucHiens->toArray();
    }

    public function addDonViTiepNhans($donViTiepNhans){
    	foreach ($donViTiepNhans as $donVi) {
    		$this->donViTiepNhans->add($donVi);
    	}
    }

    public function removeDonViTiepNhans($donViTiepNhans){
    	foreach($donViTiepNhans as $donVi){
    		$this->donViTiepNhans->removeElement($donVi);
    	}
    }

    public function getDonViTiepNhans(){
    	return $this->donViTiepNhans->toArray();
    }
}