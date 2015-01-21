<?php
namespace CongViec\Service;

use Zend\ServiceManager\ServiceManagerAwareInterface;
use Zend\ServiceManager\ServiceManager;
use Datetime;

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
      //tra ve phan cong do minh dam nhan
        $entityManager = $this->getEntityManager();
        $sm = $this->getServiceManager();
        $userId = $sm->get('zfcuser_auth_service')->getIdentity()->getId();
        $query = $entityManager->createQuery('select p from CongViec\Entity\PhanCong p where p.congVan = ?1 and p.nguoiThucHien = ?2');
        $query->setParameter(1, $congViec->getId());
        $query->setParameter(2, $userId);
        return $query->getOneOrNullResult();
    }

    public function daGiao($congViec){
      $phuTrach = $this->getPhuTrach($congViec);
      if(!$phuTrach) return 0;
      if($phuTrach->getVaiTro() == \CongViec\Entity\PhanCong::NGUOI_PHAN_CONG)
        return 1;
      return 0;
    }

    public function thongBaoCongViecThayDoi($congViec){
      $entityManager = $this->getEntityManager();
      $sm = $this->getServiceManager();
      $userId = $sm->get('zfcuser_auth_service')->getIdentity()->getId();
      $query = $entityManager->createQuery('select p from CongViec\Entity\PhanCong p where p.congVan = ?1 and p.nguoiThucHien != ?2');
      $query->setParameter(1, $congViec->getId());
      $query->setParameter(2, $userId);
      $phanCongs = $query->getResult();
      foreach($phanCongs as $phanCong){
        if($phanCong->getTrangThai != \CongViec\Entity\PhanCong::CHUA_XEM){
          $phanCong->setTrangThai(\CongViec\Entity\PhanCong::BAO_CAO_MOI);
        }
        $entityManager->flush();
      }
    }

    public function moCongViec($congViec){
        $entityManager = $this->getEntityManager();
        $sm = $this->getServiceManager();
        $userId = $sm->get('zfcuser_auth_service')->getIdentity()->getId();
        $query = $entityManager->createQuery('select p from CongViec\Entity\PhanCong p where p.congVan = ?1 and p.nguoiThucHien = ?2');
        $query->setParameter(1, $congViec->getId());
        $query->setParameter(2, $userId);
        $phanCong = $query->getOneOrNullResult();
        $phanCong->setTrangThai(\CongViec\Entity\PhanCong::DA_XEM);
        $entityManager->flush();
    }

    public function congViecMois(){
      $entityManager = $this->getEntityManager();
      $sm = $this->getServiceManager();
      $userId = $sm->get('zfcuser_auth_service')->getIdentity()->getId();

      $qb = $entityManager->createQueryBuilder();
      $qb->select('cv')
          ->from('CongViec\Entity\CongViec', 'cv')
          ->join('cv.nguoiThucHiens', 'pc', 'with', 'pc.nguoiThucHien = ?1')
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
      $entityManager = $this->getEntityManager();
      $sm = $this->getServiceManager();
      $userId = $sm->get('zfcuser_auth_service')->getIdentity()->getId();

      $qb = $entityManager->createQueryBuilder();
      $qb->select('cv')
          ->from('CongViec\Entity\CongViec', 'cv')
          ->join('cv.nguoiThucHiens', 'pc', 'with', 'pc.nguoiThucHien = ?1')
          ->where('cv.trangThai in (?2)')
          ->andWhere('pc.trangThai = ?5')
          ->setParameter(1, $userId)
          ->setParameter(2, array(\CongViec\Entity\CongViec::CHUA_XEM, \CongViec\Entity\CongViec::DANG_XU_LY))
          ->setParameter(5, \CongViec\Entity\PhanCong::BAO_CAO_MOI)
          ;
      $query = $qb->getQuery();
      $congViecs = $query->getResult();
      return $congViecs;
    }

    public function sapHetHanTheoDois(){
      $entityManager = $this->getEntityManager();
      $sm = $this->getServiceManager();
      $userId = $sm->get('zfcuser_auth_service')->getIdentity()->getId();

      $qb = $entityManager->createQueryBuilder();
      $qb->select('cv')
          ->from('CongViec\Entity\CongViec', 'cv')
          ->join('cv.nguoiThucHiens', 'pc', 'with', 'pc.nguoiThucHien = ?1')
          ->where('cv.trangThai in (?2)')
          ->andWhere('cv.ngayHoanThanh > ?4')
          ->andWhere('cv.ngayHoanThanh <= ?5')
          ->andWhere('pc.vaiTro in (?6)')
          ->setParameter(1, $userId)
          ->setParameter(2, array(\CongViec\Entity\CongViec::CHUA_XEM, \CongViec\Entity\CongViec::DANG_XU_LY))
          ->setParameter(4, new Datetime('now'))
          ->setParameter(5, new Datetime('+ 5 days'))
          ->setParameter(6, array(\CongViec\Entity\PhanCong::NGUOI_PHAN_CONG, \CongViec\Entity\PhanCong::NGUOI_CAP_NHAT, \CongViec\Entity\PhanCong::NGUOI_THEO_DOI))
          ;
      $query = $qb->getQuery();
      $congViecs = $query->getResult();
      return $congViecs;
    }

    public function treHanTheoDois(){
      $entityManager = $this->getEntityManager();
      $sm = $this->getServiceManager();
      $userId = $sm->get('zfcuser_auth_service')->getIdentity()->getId();

      $qb = $entityManager->createQueryBuilder();
      $qb->select('cv')
          ->from('CongViec\Entity\CongViec', 'cv')
          ->join('cv.nguoiThucHiens', 'pc', 'with', 'pc.nguoiThucHien = ?1')
          ->where('cv.trangThai in (?2)')
          ->andWhere('cv.ngayHoanThanh <= ?7')
          ->andWhere('pc.vaiTro in (?6)')
          ->setParameter(1, $userId)
          ->setParameter(2, array(\CongViec\Entity\CongViec::CHUA_XEM, \CongViec\Entity\CongViec::DANG_XU_LY))
          ->setParameter(7, new DateTime('now'))
          ->setParameter(6, array(\CongViec\Entity\PhanCong::NGUOI_PHAN_CONG, \CongViec\Entity\PhanCong::NGUOI_CAP_NHAT, \CongViec\Entity\PhanCong::NGUOI_THEO_DOI))
          ;
      $query = $qb->getQuery();
      $congViecs = $query->getResult();
      return $congViecs;
    }

    public function sapHetHanThucHiens(){
      $entityManager = $this->getEntityManager();
      $sm = $this->getServiceManager();
      $userId = $sm->get('zfcuser_auth_service')->getIdentity()->getId();

      $qb = $entityManager->createQueryBuilder();
      $qb->select('cv')
          ->from('CongViec\Entity\CongViec', 'cv')
          ->join('cv.nguoiThucHiens', 'pc', 'with', 'pc.nguoiThucHien = ?1')
          ->where('cv.trangThai in (?2)')
          ->andWhere('cv.ngayHoanThanh > ?4')
          ->andWhere('cv.ngayHoanThanh <= ?5')
          ->andWhere('pc.vaiTro in (?6)')
          ->setParameter(1, $userId)
          ->setParameter(2, array(\CongViec\Entity\CongViec::CHUA_XEM, \CongViec\Entity\CongViec::DANG_XU_LY))
          ->setParameter(4, new Datetime('now'))
          ->setParameter(5, new Datetime('+ 5 days'))
          ->setParameter(6, array(\CongViec\Entity\PhanCong::CHU_TRI, \CongViec\Entity\PhanCong::PHOI_HOP))
          ;
      $query = $qb->getQuery();
      $congViecs = $query->getResult();
      return $congViecs;
    }

    public function treHanThucHiens(){
      $entityManager = $this->getEntityManager();
      $sm = $this->getServiceManager();
      $userId = $sm->get('zfcuser_auth_service')->getIdentity()->getId();

      $qb = $entityManager->createQueryBuilder();
      $qb->select('cv')
          ->from('CongViec\Entity\CongViec', 'cv')
          ->join('cv.nguoiThucHiens', 'pc', 'with', 'pc.nguoiThucHien = ?1')
          ->where('cv.trangThai in (?2)')
          ->andWhere('cv.ngayHoanThanh <= ?7')
          ->andWhere('pc.vaiTro in (?6)')
          ->setParameter(1, $userId)
          ->setParameter(2, array(\CongViec\Entity\CongViec::CHUA_XEM, \CongViec\Entity\CongViec::DANG_XU_LY))
          ->setParameter(7, new DateTime('now'))
          ->setParameter(6, array(\CongViec\Entity\PhanCong::CHU_TRI, \CongViec\Entity\PhanCong::PHOI_HOP))
          ;
      $query = $qb->getQuery();
      $congViecs = $query->getResult();
      return $congViecs;
    }

    public function duocPhanCong($congViec){
      if(!$congViec instanceof \CongViec\Entity\CongViec) return 0;
      $entityManager = $this->getEntityManager();
      $sm = $this->getServiceManager();
      $userId = $sm->get('zfcuser_auth_service')->getIdentity()->getId();
      $query = $entityManager->createQuery('select p from CongViec\Entity\PhanCong p where p.congVan = ?1 and p.nguoiThucHien = ?2');
      $query->setParameter(1, $congViec->getId());
      $query->setParameter(2, $userId);
      $phanCong = $query->getOneOrNullResult();
      if($phanCong) return 1;
      return 0;
    }
}