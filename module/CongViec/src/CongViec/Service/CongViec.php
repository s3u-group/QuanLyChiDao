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
}