<?php
namespace CongViec\View\Helper;

use Zend\View\Helper\AbstractHelper;

class VaiTro extends AbstractHelper{
	public function __invoke(){ ?>
		<div class="right floated compact ui dropdown">
		  	<div class="text">Phối hợp</div>
		  	<i class="dropdown icon"></i>
		  	<div class="menu">
			    <div class="item" data-value="<?php echo \CongViec\Entity\PhanCong::PHOI_HOP; ?>">Phối hợp</div>
			    <div class="item" data-value="<?php echo \CongViec\Entity\PhanCong::CHU_TRI; ?>">Chủ trì</div>
			    <div class="item" data-value="<?php echo \CongViec\Entity\PhanCong::NGUOI_THEO_DOI; ?>">Theo dõi</div>
			<!--     <div class="item" data-value="<?php echo \CongViec\Entity\PhanCong::NGUOI_CAP_NHAT; ?>">Cập nhật</div> -->
		  	</div>
		</div>
	<?php }
}