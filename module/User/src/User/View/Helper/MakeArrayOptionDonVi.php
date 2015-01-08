<?php
namespace User\View\Helper;

use Zend\View\Helper\AbstractHelper;

class MakeArrayOptionDonVi extends AbstractHelper{
	public function __invoke($mangs){
		$dv=array();
		if(!$mangs)
		{
			return $dv;
		}
		foreach ($mangs as $mang) 
		{			
			$dv[$mang->getId()]=$mang->getTenDonVi();
		}	
		return $dv;
	}
}
?>