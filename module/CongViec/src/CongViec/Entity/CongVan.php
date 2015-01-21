<?php
namespace CongViec\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Datetime;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="cong_van")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({"cong-van" = "CongVan", "cong-viec" = "CongViec"})
 */
class CongVan 
{
	// tre han : hoan thanh tre han
	const DA_HUY = 0;
	const CHUA_XEM = 1;
	const DANG_XU_LY = 5;
	const HOAN_THANH = 10;
	const TRE_HAN = 15;

	/**
	 * @ORM\Column(name="id", type="bigint", length=20)
	 * @ORM\Id
	 * @ORM\GeneratedValue
	 */
	protected $id;

	/**
	 * @ORM\Column(name="so_hieu")	
	 */
	protected $soHieu;

	/**
	 * @ORM\Column
	 */
	protected $ten;

	/**
	 * @ORM\ManyToOne(targetEntity="Taxonomy\Entity\TermTaxonomy")
	 * @ORM\JoinColumn(name="loai_id", referencedColumnName="term_taxonomy_id")
	 */
	protected $loai;

	/**
	 * @ORM\ManyToOne(targetEntity="Taxonomy\Entity\TermTaxonomy")
	 * @ORM\JoinColumn(name="linh_vuc_id", referencedColumnName="term_taxonomy_id")
	 */
	protected $linhVuc;

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
	 * @ORM\ManyToOne(targetEntity="User\Entity\User")
	 * @ORM\JoinColumn(name="nguoi_ky_id", referencedColumnName="user_id")
	 */
	protected $nguoiKy;

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
	 * @ORM\JoinColumn(name="nguoi_tao_id", referencedColumnName="user_id")
	 */
	protected $nguoiTao;

	/**
	 * @ORM\Column(name="trang_thai", type="integer")	
	 */
	protected $trangThai = 1; // mac dinh chua xem

	/**
	 * @ORM\ManyToOne(targetEntity="CongViec\Entity\CongVan", cascade={"persist"})
	 * @ORM\JoinColumn(name="cha_id", referencedColumnName="id", nullable=true)
	 */
	protected $cha;

	/**
     * @ORM\OneToMany(targetEntity="CongViec\Entity\DinhKem", mappedBy="congVan")
     */
    protected $dinhKems;

    /**
     * @ORM\OneToMany(targetEntity="CongViec\Entity\PhanCong", mappedBy="congVan", cascade={"persist"})
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

    /**
     * @ORM\OneToMany(targetEntity="CongViec\Entity\CongViec", mappedBy="cha")
     */
    protected $congViecs;

    /**
     * @ORM\OneToMany(targetEntity="CongViec\Entity\TheoDoi", mappedBy="congVan")
     */
    protected $baoCaos;

    protected $quaHan = false;

    /**
	 * @ORM\PrePersist 
	 */
	public function onPrePersist(){
    	$this->ngayTao = new DateTime('now');

    	/*$sm = $this->getServiceManager(); //phai truyen duoc sm vao moi su dung duoc
		$auth = $sm->get('zfcuser_auth_service');
		if ($auth->hasIdentity()) {
		    $user = $auth->getIdentity();
		    $this->nguoiTao = $user;
		}*/
	}

	/**
	 * @ORM\PostLoad
	 */
	public function onPostLoad(){
		$date = new DateTime('now');
		if($this->ngayHoanThanh <= $date)
			$this->quaHan = true;
		else 
			$this->quaHan = false;
	}

    public function __construct()
    {
        $this->dinhKems = new ArrayCollection();
        $this->nguoiThucHiens = new ArrayCollection();
        $this->donViTiepNhans = new ArrayCollection();
        $this->congViecs = new ArrayCollection();
        $this->baoCaos = new ArrayCollection();
    }

	public function getId(){
		return $this->id;
	}

	public function setSoHieu($soHieu){
		$this->soHieu = $soHieu;
		return $this;
	}

	public function getSoHieu(){
		return $this->soHieu;
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

	public function getLoaiLabel(){
		if($loai = $this->getLoai())
			return $loai->getTermName();
	}

	public function setLinhVuc($linhVuc){
		$this->linhVuc = $linhVuc;
	}

	public function getLinhVuc(){
		return $this->linhVuc;
	}

	public function getLinhVucLabel(){
		if($linhVuc = $this->getLinhVuc())
			return $linhVuc->getTermName();
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

	public function getNgayBanHanh(){
		return $this->ngayBanHanh;
	}

	public function setNguoiKy($nguoiKy){
		$this->nguoiKy = $nguoiKy;
	}
	
	public function getNguoiKy(){
		return $this->nguoiKy;
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

        $ngayHoanThanh= $this->ngayHoanThanh->format('Y-m-d');
        $ngayHoanThanhThuc= $this->ngayHoanThanhThuc;
        $ngayHienTai=date('Y-m-d');
        //var_dump('id: '.$this->id.' ngayHienTai: '.$ngayHienTai.'  ngayHoanThanh: '.$ngayHoanThanh);
        // nếu chưa có ngày hoàn thành thực
        if($this->ngayHoanThanhThuc==null||$this->ngayHoanThanhThuc==''||$this->ngayHoanThanhThuc->getTimestamp()==0){
        	if(strtotime($ngayHienTai) > strtotime($ngayHoanThanh))
        	{
        		return 'Trễ hạn';
        	}
        	//var_dump((strtotime($ngayHienTai) - strtotime($ngayHoanThanh)));
        }
        /*if($this->trangThai==null&&$this->ngayHoanThanhThuc==null)
        {
        	if((strtotime($ngayHienTai) - strtotime($ngayHoanThanh))>0)
        	{
        		return 'Trễ hạn';
        	}
        	else
        	{
        		return 'Chưa xem';
        	}
        }*/
        /*if($this->trangThai==null&&$this->ngayHoanThanhThuc)
        {
        	$ngayHoanThanhThuc= $this->ngayHoanThanhThuc->format('Y-m-d');
        	if((strtotime($ngayHoanThanhThuc) - strtotime($ngayHoanThanh))>0)
        	{
        		return 'Trễ hạn';
        	}
        	else
        	{
        		return 'Hoàn thành';
        	}
        }*/
       /* if($this->ngayHoanThanhThuc)
        {
        	$ngayHoanThanhThuc= $this->ngayHoanThanhThuc->format('Y-m-d');
        	if((strtotime($ngayHoanThanhThuc) - strtotime($ngayHoanThanh))>0)
        	{
        		return 'Trễ hạn';
        	}
        	else
        	{
        		return 'Hoàn thành';
        	}
        }*/
		switch ($this->trangThai) {
			case '0':
				return 'Đã hủy';
				break;
				
			case '1':
				return 'Chưa xem';
				break;
			
			case '5':
				return 'Đang xử lý';
				break;

			case '10':
				return 'Hoàn thành';
				break;

			case '15':
				return 'Trễ hạn';
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

	public function getCongVan(){
		return $this->cha;
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

    public function addCongViecs($congViecs){
    	foreach($congViecs as $congViec){
    		$congViec->setCha($this);
    		$this->congViecs->add($congViec);
    	}
    }

    public function removeCongViecs($congViecs){
    	foreach($congViecs as $congViec){
    		$congViec->setCha(null);
    		$this->congViecs->remove($congViec);
    	}
    }

    public function getCongViecs(){
    	return $this->congViecs->toArray();
    }

    public function addBaoCaos($baoCaos){
    	foreach($baoCaos as $baoCao){
    		$baoCao->setCongVan($this);
    		$this->baoCaos->add($baoCao);
    	}
    }

    public function removeBaoCaos($baoCaos){
    	foreach($baoCaos as $baoCao){
    		$baoCao->setCongVan(null);
    		$this->baoCaos->remove($baoCao);
    	}
    }

    public function getBaoCaos(){
    	return $this->baoCaos->toArray();
    }

    public function isQuaHan(){
    	return $this->quaHan;
    }

    public function isHoanThanh(){
    	if($this->trangThai == self::HOAN_THANH) return 1;
    	if($this->trangThai == self::TRE_HAN) return 1;
    	return 0;
    }

    public function isChuaXem(){
    	if($this->trangThai == self::CHUA_XEM) return 1;
    	return 0;
    }

    public function isDaHuy(){
    	if($this->trangThai == self::DA_HUY) return 1;
    	return 0;
    }
}