<?php
namespace CongViec\Service;

use Zend\ServiceManager\ServiceManagerAwareInterface;
use Zend\ServiceManager\ServiceManager;

class CongViec implements ServiceManagerAwareInterface{

	protected $serviceManager;

    protected $entityManager;

	/**
     * Retrieve service manager instance
     *
     * @return ServiceManager
     */
    public function getServiceManager()
    {
        return $this->serviceManager;
    }

    /**
     * Set service manager instance
     *
     * @param ServiceManager $serviceManager
     * @return User
     */
    public function setServiceManager(ServiceManager $serviceManager)
    {
        $this->serviceManager = $serviceManager;
        return $this;
    }

    public function getEntityManager()
    {
    	$sm = $this->getServiceManager();
        if(!$this->entityManager)
        {
          $this->entityManager = $sm->get('Doctrine\ORM\EntityManager');
        }
        return $this->entityManager;
    }

	public function getBaoCaoCuoi($congViec){
		$entityManager = $this->getEntityManager();
		$query = $entityManager->createQuery('select bc from CongViec\Entity\TheoDoi bc where bc.congVan = ?1 order by bc.id DESC');
		$query->setMaxResults(1);
		$query->setParameter(1, $congViec->getId());
		return $query->getOneOrNullResult();
	}

    public function getNguoiDuocPhanCong($congViec){
        //danh sach nguoi thuc hien ngoai tru nguoi phan cong, co sap xep theo vai tro
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery('select p from CongViec\Entity\PhanCong p where p.congVan = ?1 and p.vaiTro != ?2 order by p.vaiTro');
        $query->setParameter(1, $congViec->getId());
        $query->setParameter(2, \CongViec\Entity\PhanCong::NGUOI_PHAN_CONG);
        $phanCongs = $query->getResult();
        return $phanCongs;
    }

    public function getPhuTrach($congViec){
        $entityManager = $this->getEntityManager();
        $sm = $this->getServiceManager();
        $userId = $sm->get('zfcuser_auth_service')->getIdentity()->getId();
        $query = $entityManager->createQuery('select p from CongViec\Entity\PhanCong p where p.congVan = ?1 and p.nguoiThucHien = ?2');
        $query->setParameter(1, $congViec->getId());
        $query->setParameter(2, $userId);
        return $query->getOneOrNullResult();
    }

    public function thongBaoCongViecThayDoi($congViec, $user){
      $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery('select p from CongViec\Entity\PhanCong p where p.congVan = ?1 and p.nguoiThucHien != ?2');
        $query->setParameter(1, $congViec->getId());
        $query->setParameter(2, $user->getId());
        $phanCongs = $query->getResult();
        foreach($phanCongs as $phanCong){
          if($phanCong->getTrangThai != \CongViec\Entity\PhanCong::CHUA_XEM){
            $phanCong->setTrangThai(\CongViec\Entity\PhanCong::BAO_CAO_MOI);
          }
          $entityManager->flush();
        }
    }

    public function congViecMois(){
      $entityManager = $this->getEntityManager();
      $sm = $this->getServiceManager();
      $userId = $sm->get('zfcuser_auth_service')->getIdentity()->getId();

      $qb = $entityManager->createQueryBuilder();
      $qb->select('cv')
          ->from('CongViec\Entity\CongViec', 'cv')
          ->join('cv.nguoiThucHiens', 'pc', 'with', 'pc.nguoiThucHien = ?1')
          ->leftJoin('cv.cha', 'c')
          ->leftJoin('c.nguoiKy', 'nk')
          ->where('cv.trangThai in (?2)')
          ->andWhere('pc.vaiTro != ?50')
          ->andWhere('pc.trangThai = ?5')
          ->setParameter(1, $userId)
          ->setParameter(2, array(\CongViec\Entity\CongViec::CHUA_XEM, \CongViec\Entity\CongViec::DANG_XU_LY))
          ->setParameter(50, \CongViec\Entity\PhanCong::NGUOI_PHAN_CONG)
          ->setParameter(5, \CongViec\Entity\PhanCong::CHUA_XEM)
          ;
      $query = $qb->getQuery();
      $congViecs = $query->getResult();
      return $congViecs;
    }

    public function baoCaoMois(){
      return array();
    }

    public function sapHetHans(){
      return array();
    }

    public function treHans(){
      return array();
    }
}