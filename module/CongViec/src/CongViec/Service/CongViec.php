<?php
namespace CongViec\Service;

use Zend\ServiceManager\ServiceManagerAwareInterface;

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
		$query = $entityManager->createQuery('select bc from CongViec\Entity\TheoDoi bc where bc.congVan = ?1 orderBy bc.id DESC')
		$query->setParameter(1, $congViec->getId());
		return $query->getSingleResult();
	}
}