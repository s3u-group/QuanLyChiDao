<?php
/**
 * BjyAuthorize Module (https://github.com/bjyoungblood/BjyAuthorize)
 *
 * @link https://github.com/bjyoungblood/BjyAuthorize for the canonical source repository
 * @license http://framework.zend.com/license/new-bsd New BSD License
 */
namespace User\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * An example entity that represents a role.
 *
 * @ORM\Entity
 * @ORM\Table(name="user_role_linker")
 *
 * @author Tom Oram <tom@scl.co.uk>
 */
class UserRole
{
    /**
     * @var int
     * @ORM\Id
     * @ORM\Column(name="user_id",type="integer")     
     */
    protected $userId;

    /**
     * @var int
     * @ORM\Id
     * @ORM\Column(name="role_id",type="integer")     
     */
    protected $roleId;

    public function setUserId($userId)
    {
        $this->userId=$userId;
    }

    public function getUserId()
    {
        return $this->userId;
    }


    public function setRoleId($roleId)
    {
        $this->roleId=$roleId;
    }

    public function getRoleId()
    {
        return $this->roleId;
    }
}
