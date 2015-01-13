<?php

namespace DoctrineORMModule\Proxy\__CG__\User\Entity;

/**
 * DO NOT EDIT THIS FILE - IT WAS CREATED BY DOCTRINE'S PROXY GENERATOR
 */
class User extends \User\Entity\User implements \Doctrine\ORM\Proxy\Proxy
{
    /**
     * @var \Closure the callback responsible for loading properties in the proxy object. This callback is called with
     *      three parameters, being respectively the proxy object to be initialized, the method that triggered the
     *      initialization process and an array of ordered parameters that were passed to that method.
     *
     * @see \Doctrine\Common\Persistence\Proxy::__setInitializer
     */
    public $__initializer__;

    /**
     * @var \Closure the callback responsible of loading properties that need to be copied in the cloned object
     *
     * @see \Doctrine\Common\Persistence\Proxy::__setCloner
     */
    public $__cloner__;

    /**
     * @var boolean flag indicating if this object was already initialized
     *
     * @see \Doctrine\Common\Persistence\Proxy::__isInitialized
     */
    public $__isInitialized__ = false;

    /**
     * @var array properties to be lazy loaded, with keys being the property
     *            names and values being their default values
     *
     * @see \Doctrine\Common\Persistence\Proxy::__getLazyProperties
     */
    public static $lazyPropertiesDefaults = array();



    /**
     * @param \Closure $initializer
     * @param \Closure $cloner
     */
    public function __construct($initializer = null, $cloner = null)
    {

        $this->__initializer__ = $initializer;
        $this->__cloner__      = $cloner;
    }







    /**
     * 
     * @return array
     */
    public function __sleep()
    {
        if ($this->__isInitialized__) {
            return array('__isInitialized__', 'id', 'username', 'email', 'displayName', 'password', 'state', 'roles', 'ho', 'ten', 'gioiTinh', 'thanhPho', 'quocGia', 'dienThoai', 'diaChi', '' . "\0" . 'User\\Entity\\User' . "\0" . 'ngayTao', '' . "\0" . 'User\\Entity\\User' . "\0" . 'dangNhapCuoi', '' . "\0" . 'User\\Entity\\User' . "\0" . 'ngayChinhSua', 'donVi', 'congViecs');
        }

        return array('__isInitialized__', 'id', 'username', 'email', 'displayName', 'password', 'state', 'roles', 'ho', 'ten', 'gioiTinh', 'thanhPho', 'quocGia', 'dienThoai', 'diaChi', '' . "\0" . 'User\\Entity\\User' . "\0" . 'ngayTao', '' . "\0" . 'User\\Entity\\User' . "\0" . 'dangNhapCuoi', '' . "\0" . 'User\\Entity\\User' . "\0" . 'ngayChinhSua', 'donVi', 'congViecs');
    }

    /**
     * 
     */
    public function __wakeup()
    {
        if ( ! $this->__isInitialized__) {
            $this->__initializer__ = function (User $proxy) {
                $proxy->__setInitializer(null);
                $proxy->__setCloner(null);

                $existingProperties = get_object_vars($proxy);

                foreach ($proxy->__getLazyProperties() as $property => $defaultValue) {
                    if ( ! array_key_exists($property, $existingProperties)) {
                        $proxy->$property = $defaultValue;
                    }
                }
            };

        }
    }

    /**
     * 
     */
    public function __clone()
    {
        $this->__cloner__ && $this->__cloner__->__invoke($this, '__clone', array());
    }

    /**
     * Forces initialization of the proxy
     */
    public function __load()
    {
        $this->__initializer__ && $this->__initializer__->__invoke($this, '__load', array());
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __isInitialized()
    {
        return $this->__isInitialized__;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __setInitialized($initialized)
    {
        $this->__isInitialized__ = $initialized;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __setInitializer(\Closure $initializer = null)
    {
        $this->__initializer__ = $initializer;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __getInitializer()
    {
        return $this->__initializer__;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __setCloner(\Closure $cloner = null)
    {
        $this->__cloner__ = $cloner;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific cloning logic
     */
    public function __getCloner()
    {
        return $this->__cloner__;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     * @static
     */
    public function __getLazyProperties()
    {
        return self::$lazyPropertiesDefaults;
    }

    
    /**
     * {@inheritDoc}
     */
    public function getId()
    {
        if ($this->__isInitialized__ === false) {
            return (int)  parent::getId();
        }


        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getId', array());

        return parent::getId();
    }

    /**
     * {@inheritDoc}
     */
    public function setId($id)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setId', array($id));

        return parent::setId($id);
    }

    /**
     * {@inheritDoc}
     */
    public function getUsername()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getUsername', array());

        return parent::getUsername();
    }

    /**
     * {@inheritDoc}
     */
    public function setUsername($username)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setUsername', array($username));

        return parent::setUsername($username);
    }

    /**
     * {@inheritDoc}
     */
    public function getEmail()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getEmail', array());

        return parent::getEmail();
    }

    /**
     * {@inheritDoc}
     */
    public function setEmail($email)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setEmail', array($email));

        return parent::setEmail($email);
    }

    /**
     * {@inheritDoc}
     */
    public function getDisplayName()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getDisplayName', array());

        return parent::getDisplayName();
    }

    /**
     * {@inheritDoc}
     */
    public function setDisplayName($displayName)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setDisplayName', array($displayName));

        return parent::setDisplayName($displayName);
    }

    /**
     * {@inheritDoc}
     */
    public function getPassword()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getPassword', array());

        return parent::getPassword();
    }

    /**
     * {@inheritDoc}
     */
    public function setPassword($password)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setPassword', array($password));

        return parent::setPassword($password);
    }

    /**
     * {@inheritDoc}
     */
    public function getState()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getState', array());

        return parent::getState();
    }

    /**
     * {@inheritDoc}
     */
    public function setState($state)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setState', array($state));

        return parent::setState($state);
    }

    /**
     * {@inheritDoc}
     */
    public function getRoles()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getRoles', array());

        return parent::getRoles();
    }

    /**
     * {@inheritDoc}
     */
    public function addRole($role)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'addRole', array($role));

        return parent::addRole($role);
    }

    /**
     * {@inheritDoc}
     */
    public function removeRole($roles)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'removeRole', array($roles));

        return parent::removeRole($roles);
    }

    /**
     * {@inheritDoc}
     */
    public function setDienThoai($dienThoai)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setDienThoai', array($dienThoai));

        return parent::setDienThoai($dienThoai);
    }

    /**
     * {@inheritDoc}
     */
    public function getDienThoai()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getDienThoai', array());

        return parent::getDienThoai();
    }

    /**
     * {@inheritDoc}
     */
    public function setDiaChi($diaChi)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setDiaChi', array($diaChi));

        return parent::setDiaChi($diaChi);
    }

    /**
     * {@inheritDoc}
     */
    public function getDiaChi()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getDiaChi', array());

        return parent::getDiaChi();
    }

    /**
     * {@inheritDoc}
     */
    public function setHo($ho)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setHo', array($ho));

        return parent::setHo($ho);
    }

    /**
     * {@inheritDoc}
     */
    public function getHo()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getHo', array());

        return parent::getHo();
    }

    /**
     * {@inheritDoc}
     */
    public function setTen($ten)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setTen', array($ten));

        return parent::setTen($ten);
    }

    /**
     * {@inheritDoc}
     */
    public function getTen()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getTen', array());

        return parent::getTen();
    }

    /**
     * {@inheritDoc}
     */
    public function getHoTen()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getHoTen', array());

        return parent::getHoTen();
    }

    /**
     * {@inheritDoc}
     */
    public function setGioiTinh($gioiTinh)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setGioiTinh', array($gioiTinh));

        return parent::setGioiTinh($gioiTinh);
    }

    /**
     * {@inheritDoc}
     */
    public function getGioiTinh()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getGioiTinh', array());

        return parent::getGioiTinh();
    }

    /**
     * {@inheritDoc}
     */
    public function setThanhPho($thanhPho)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setThanhPho', array($thanhPho));

        return parent::setThanhPho($thanhPho);
    }

    /**
     * {@inheritDoc}
     */
    public function getThanhPho()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getThanhPho', array());

        return parent::getThanhPho();
    }

    /**
     * {@inheritDoc}
     */
    public function setQuocGia($quocGia)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setQuocGia', array($quocGia));

        return parent::setQuocGia($quocGia);
    }

    /**
     * {@inheritDoc}
     */
    public function getQuocGia()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getQuocGia', array());

        return parent::getQuocGia();
    }

    /**
     * {@inheritDoc}
     */
    public function setNgayTao($ngayTao)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setNgayTao', array($ngayTao));

        return parent::setNgayTao($ngayTao);
    }

    /**
     * {@inheritDoc}
     */
    public function getNgayTao()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getNgayTao', array());

        return parent::getNgayTao();
    }

    /**
     * {@inheritDoc}
     */
    public function setDangNhapCuoi($dangNhapCuoi)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setDangNhapCuoi', array($dangNhapCuoi));

        return parent::setDangNhapCuoi($dangNhapCuoi);
    }

    /**
     * {@inheritDoc}
     */
    public function getDangNhapCuoi()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getDangNhapCuoi', array());

        return parent::getDangNhapCuoi();
    }

    /**
     * {@inheritDoc}
     */
    public function setNgayChinhSua($ngayChinhSua)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setNgayChinhSua', array($ngayChinhSua));

        return parent::setNgayChinhSua($ngayChinhSua);
    }

    /**
     * {@inheritDoc}
     */
    public function getNgayChinhSua()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getNgayChinhSua', array());

        return parent::getNgayChinhSua();
    }

    /**
     * {@inheritDoc}
     */
    public function setDonVi($donVi)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setDonVi', array($donVi));

        return parent::setDonVi($donVi);
    }

    /**
     * {@inheritDoc}
     */
    public function getDonVi()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getDonVi', array());

        return parent::getDonVi();
    }

    /**
     * {@inheritDoc}
     */
    public function addCongViecs($congViecs)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'addCongViecs', array($congViecs));

        return parent::addCongViecs($congViecs);
    }

    /**
     * {@inheritDoc}
     */
    public function removeCongViecs($congViecs)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'removeCongViecs', array($congViecs));

        return parent::removeCongViecs($congViecs);
    }

    /**
     * {@inheritDoc}
     */
    public function getCongViecs()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getCongViecs', array());

        return parent::getCongViecs();
    }

    /**
     * {@inheritDoc}
     */
    public function getNhiemVu($congViec)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getNhiemVu', array($congViec));

        return parent::getNhiemVu($congViec);
    }

}
