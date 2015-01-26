<?php
namespace User\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="don_vi")
 */
class DonVi
{
	/**
	 * @ORM\Column(name="id", type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue
	 */
	protected $id;

	/**
	 * @ORM\Column(name="ten_don_vi", type="text")
	 */
	protected $tenDonVi;

    /**
     * @ORM\Column(name="ten_viet_tat", type="text")
     */
    protected $tenVietTat;

    /**
     * @ORM\OneToOne(targetEntity="User\Entity\User")
     * @ORM\JoinColumn(name="thu_truong_id", referencedColumnName="user_id")
     */
    protected $thuTruong;

	/**
	 * @ORM\OneToMany(targetEntity="User\Entity\User", mappedBy="donVi")
	 */
	protected $nhanViens;	

    /**
     * @ORM\ManyToMany(targetEntity="CongViec\Entity\CongVan")
     * @ORM\JoinTable(name="cong_van_don_vi",
     *      joinColumns={@ORM\JoinColumn(name="don_vi_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="cong_van_id", referencedColumnName="id")}
     *      )
     */
    protected $congVanDens;

	public function __construct(){
        $this->nhanViens = new ArrayCollection();
        $this->congVanDens = new ArrayCollection();
    }

	public function getId(){
		return $this->id;
	}

	public function setTenDonVi($tenDonVi){
		$this->tenDonVi = $tenDonVi;
		return $this;
	}

	public function getTenDonVi(){
		return $this->tenDonVi;
	}

    public function setTenVietTat($tenVietTat){
        $this->tenVietTat = $tenVietTat;
        return $this;
    }

    public function getTenVietTat(){
        return $this->tenVietTat;
    }

    public function getTenVietTatHoacDayDu(){
        if($this->tenVietTat)
            return $this->tenVietTat;
        return $this->tenDonVi;
    }

    public function setThuTruong($thuTruong){
        $this->thuTruong = $thuTruong;
        return $this;
    }

    public function getThuTruong(){
        return $this->thuTruong;
    }

    public function getHoTenThuTruong(){
        if($thuTruong = $this->thuTruong){
            return $thuTruong->getHoTen();
        }
    }

	public function addNhanViens($nhanViens){
        foreach($nhanViens as $nhanVien){
            $nhanVien->setDonVi($this);
            $this->nhanViens->add($nhanVien);
        }
    }

    public function removeNhanViens($nhanViens){
        foreach($nhanViens as $nhanVien){
            $nhanVien->setDonVi(null);
            $this->nhanViens->removeElement($nhanVien);
        }
    }

    public function getNhanViens(){
        return $this->nhanViens->toArray();
    }

    public function addCongVanDens($congVanDens){
    	foreach($congVanDens as $congVan){
    		$this->congVanDens->add($congVan);
    	}
    }

    public function removeCongVanDens($congVanDens){
    	foreach($congVanDens as $congVan){
    		$this->congVanDens->removeElement($congVan);
    	}
    }

    public function getCongVanDens(){
    	return $this->congVanDens->toArray();
    }
}