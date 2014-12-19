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
	 * @ORM\OneToMany(targetEntity="User\Entity\User", mappedBy="donVi")
	 */
	protected $nhanViens;

	public function __construct(){
        $this->nhanViens = new ArrayCollection();
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
}