<?php
namespace CongViec\View\Helper;

use Zend\View\Helper\AbstractHelper;
use CongViec\Entity\CongViec;
class GetVaiTroOfUser extends AbstractHelper{
	private $sm;

	

	public function setServiceManager($sm){
		$this->sm=$sm;
	}
	public function getServiceManager(){
		return $this->sm;
	}

	/**
	 * @var tham số truyền vào là một công việc không phải danh sách công việc)
	 * kết quả trả về là tru nếu công việc đó do user đang đăng nhập tạo
	 * hoặc kết quả trả về là false nếu user đang đăng nhập không phải người tạo
	 */
	public function isNguoiTao($congViec){
		$idUser=null;
		$sm=$this->getServiceManager();
		$auth = $sm->get('zfcuser_auth_service');
		if ($auth->hasIdentity()) {
		    $idUser= $auth->getIdentity()->getId();
		}
		if($congViec&&$idUser){
			if($congViec->getNguoiTao()->getId()==$idUser){
				return true;
			}
		}
		return false;			
	}

	public function enableButtonUpdate($congViec){
		if($this->isNguoiTao($congViec)){
			if($congViec->getTrangThai()==CongViec::CHUA_XEM||$congViec->getTrangThai()==CongViec::DANG_XU_LY)
			{				
				return true;
			}
			else{
				return false;
			}
		}
	}

	public function capNhatCongViec($congViec){
		$idUser=null;
		$sm=$this->getServiceManager();		
		$auth = $sm->get('zfcuser_auth_service');
		if ($auth->hasIdentity()) {
		    $idUser= $auth->getIdentity()->getId();
		}
		
		if ($congViec&&$idUser) {
			if($congViec->getTrangThai()==CongViec::HOAN_THANH||$congViec->getTrangThai()==CongViec::TRE_HAN||$congViec->getTrangThai()==CongViec::DA_HUY)
			{				
				return false;
			}
			if($congViec->getNguoiTao()->getId()==$idUser){
				return true;
			}
			foreach ($congViec->getNguoiThucHiens() as $nguoiThucHien) {
				if($nguoiThucHien->getNguoiThucHien()->getId()==$idUser){
					if($nguoiThucHien->getVaiTro()=='Phân công'||
					   $nguoiThucHien->getVaiTro()=='Theo dõi'||
					   $nguoiThucHien->getVaiTro()=='Cập nhật'){
						return true;
					}
				}
			}
		}
		return false;
	}
}
?>