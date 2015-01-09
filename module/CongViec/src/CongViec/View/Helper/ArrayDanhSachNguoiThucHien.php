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
			
			if($object->getVaiTro()=='Chủ trì')
			{
				$ds[$object->getNguoiThucHien()->getId()]=$object->getNguoiThucHien()->getHo().' '.$object->getNguoiThucHien()->getTen();
			}

		}	
		return $ds;
	}	
}
?>