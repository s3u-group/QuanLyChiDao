<?php
namespace User\Controller\Plugin;
use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use CongViec\Entity\CongViec;
 
class KiemTraQuyenCuaUser extends AbstractPlugin{
	private $entityManager; 
	private $serviceManager;
    
	public function getEntityManager()
    {       
        return $this->entityManager;
    }
	
	public function setEntityManager($entityManager)
	{
		$this->entityManager=$entityManager;
	}

	public function setServiceManager($serviceManager){
		$this->serviceManager=$serviceManager;
	}

	public function getServiceManager(){
		return $this->serviceManager;
	}

	/**
	 * 
	 */
	public function capNhatCongViec($id){
		$idUser=null;
		$sm=$this->getServiceManager();		
		$auth = $sm->get('zfcuser_auth_service');
		if ($auth->hasIdentity()) {
		    $idUser= $auth->getIdentity()->getId();
		}

		$entityManager=$this->getEntityManager();
		$congViec=$entityManager->getRepository('CongViec\Entity\CongViec')->find($id);
		if ($congViec&&$idUser) {
			if($congViec->getTrangThai()==CongViec::HOAN_THANH||$congViec->getTrangThai()==CongViec::TRE_HAN||$congViec->getTrangThai()==CongViec::DA_HUY)
			{
				$congViec=null;
				return $congViec;
			}
			if($congViec->getNguoiTao()->getId()==$idUser){
				return $congViec;
			}
			foreach ($congViec->getNguoiThucHiens() as $nguoiThucHien) {
				if($nguoiThucHien->getNguoiThucHien()->getId()==$idUser){
					if($nguoiThucHien->getVaiTro()=='Phân công'||
					   $nguoiThucHien->getVaiTro()=='Theo dõi'||
					   $nguoiThucHien->getVaiTro()=='Cập nhật'){
						return $congViec;
					}
				}
			}
		}
		$congViec=null;
		return $congViec;
	}


	public function isNguoiTao($id){
		$idUser=null;
		$sm=$this->getServiceManager();
		$entityManager=$this->getEntityManager();
		$auth = $sm->get('zfcuser_auth_service');
		if ($auth->hasIdentity()) {
		    $idUser= $auth->getIdentity()->getId();
		}
		$congViec=$entityManager->getRepository('CongViec\Entity\CongViec')->find($id);
		if($congViec&&$idUser){
			if($congViec->getNguoiTao()->getId()==$idUser){
				return true;
			}
		}
		return false;			
	}

	public function huyDinhKem($id){
		if($this->isNguoiTao($id)){
			$congViec=$entityManager->getRepository('CongViec\Entity\CongViec')->find($id);
			if($congViec->getTrangThai()==CongViec::CHUA_XEM||$congViec->getTrangThai()==CongViec::DANG_XU_LY)
			{				
				return true;
			}
			else{
				return false;
			}
		}
	}
}
?>