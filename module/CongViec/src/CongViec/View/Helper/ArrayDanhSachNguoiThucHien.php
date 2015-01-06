<?php
namespace CongViec\View\Helper;

use Zend\View\Helper\AbstractHelper;
class ArrayDanhSachNguoiThucHien extends AbstractHelper{
	public function __invoke($objects){
		$ds=array();

		if(!$objects)
		{
			return $ds;
		}

		foreach ($objects as $object) 
		{
			
			if($object->getVaiTro()=='Phân công'||$object->getVaiTro()=='Phối hợp')
			{
				$ds[$object->getNguoiThucHien()->getId()]=$object->getNguoiThucHien()->getUsername();
			}

		}	
		return $ds;
	}
}
?>