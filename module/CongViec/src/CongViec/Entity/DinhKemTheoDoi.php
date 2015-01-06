<?php
namespace CongViec\Entity;

use Doctrine\ORM\Mapping as ORM;
use CongViec\Entity\DinhKem;

/**
 * @ORM\Entity
 * @ORM\AssociationOverrides({
 * @ORM\AssociationOverride(name="doiTuong"
 *			joinTable=@ORM\JoinTable(name="CongViec\Entity\TheoDoi"
 *          joinColumns=@ORM\JoinColumn(
 *              name="doi_tuong_id", referencedColumnName="id"
 *          )),
 *      )
 * })
 */
class DinhKemTheoDoi extends DinhKem{

}