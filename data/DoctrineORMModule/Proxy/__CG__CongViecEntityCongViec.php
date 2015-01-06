<?php

namespace DoctrineORMModule\Proxy\__CG__\CongViec\Entity;

/**
 * DO NOT EDIT THIS FILE - IT WAS CREATED BY DOCTRINE'S PROXY GENERATOR
 */
class CongViec extends \CongViec\Entity\CongViec implements \Doctrine\ORM\Proxy\Proxy
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
            return array('__isInitialized__', 'id', 'soHieu', 'ten', 'loai', 'linhVuc', 'trichYeu', 'noiDung', 'ngayBanHanh', 'nguoiKy', 'ngayHoanThanh', 'ngayHoanThanhThuc', 'ngayTao', 'nguoiTao', 'trangThai', 'cha', 'dinhKems', 'nguoiThucHiens', 'donViTiepNhans', 'congViecs');
        }

        return array('__isInitialized__', 'id', 'soHieu', 'ten', 'loai', 'linhVuc', 'trichYeu', 'noiDung', 'ngayBanHanh', 'nguoiKy', 'ngayHoanThanh', 'ngayHoanThanhThuc', 'ngayTao', 'nguoiTao', 'trangThai', 'cha', 'dinhKems', 'nguoiThucHiens', 'donViTiepNhans', 'congViecs');
    }

    /**
     * 
     */
    public function __wakeup()
    {
        if ( ! $this->__isInitialized__) {
            $this->__initializer__ = function (CongViec $proxy) {
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

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getId', array());

        return parent::getId();
    }

    /**
     * {@inheritDoc}
     */
    public function setSoHieu($soHieu)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setSoHieu', array($soHieu));

        return parent::setSoHieu($soHieu);
    }

    /**
     * {@inheritDoc}
     */
    public function getSoHieu()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getSoHieu', array());

        return parent::getSoHieu();
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
    public function setLoai($loai)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setLoai', array($loai));

        return parent::setLoai($loai);
    }

    /**
     * {@inheritDoc}
     */
    public function getLoai()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getLoai', array());

        return parent::getLoai();
    }

    /**
     * {@inheritDoc}
     */
    public function setLinhVuc($linhVuc)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setLinhVuc', array($linhVuc));

        return parent::setLinhVuc($linhVuc);
    }

    /**
     * {@inheritDoc}
     */
    public function getLinhVuc()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getLinhVuc', array());

        return parent::getLinhVuc();
    }

    /**
     * {@inheritDoc}
     */
    public function setTrichYeu($trichYeu)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setTrichYeu', array($trichYeu));

        return parent::setTrichYeu($trichYeu);
    }

    /**
     * {@inheritDoc}
     */
    public function getTrichYeu()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getTrichYeu', array());

        return parent::getTrichYeu();
    }

    /**
     * {@inheritDoc}
     */
    public function setNoiDung($noiDung)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setNoiDung', array($noiDung));

        return parent::setNoiDung($noiDung);
    }

    /**
     * {@inheritDoc}
     */
    public function getNoiDung()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getNoiDung', array());

        return parent::getNoiDung();
    }

    /**
     * {@inheritDoc}
     */
    public function setNgayBanHanh($ngayBanHanh)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setNgayBanHanh', array($ngayBanHanh));

        return parent::setNgayBanHanh($ngayBanHanh);
    }

    /**
     * {@inheritDoc}
     */
    public function getNgayBanHanh()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getNgayBanHanh', array());

        return parent::getNgayBanHanh();
    }

    /**
     * {@inheritDoc}
     */
    public function setNguoiKy($nguoiKy)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setNguoiKy', array($nguoiKy));

        return parent::setNguoiKy($nguoiKy);
    }

    /**
     * {@inheritDoc}
     */
    public function getNguoiKy()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getNguoiKy', array());

        return parent::getNguoiKy();
    }

    /**
     * {@inheritDoc}
     */
    public function setNgayHoanThanh($ngayHoanThanh)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setNgayHoanThanh', array($ngayHoanThanh));

        return parent::setNgayHoanThanh($ngayHoanThanh);
    }

    /**
     * {@inheritDoc}
     */
    public function getNgayHoanThanh()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getNgayHoanThanh', array());

        return parent::getNgayHoanThanh();
    }

    /**
     * {@inheritDoc}
     */
    public function setNgayHoanThanhThuc($ngayHoanThanhThuc)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setNgayHoanThanhThuc', array($ngayHoanThanhThuc));

        return parent::setNgayHoanThanhThuc($ngayHoanThanhThuc);
    }

    /**
     * {@inheritDoc}
     */
    public function getNgayHoanThanhThuc()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getNgayHoanThanhThuc', array());

        return parent::getNgayHoanThanhThuc();
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
    public function setNguoiTao($nguoiTao)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setNguoiTao', array($nguoiTao));

        return parent::setNguoiTao($nguoiTao);
    }

    /**
     * {@inheritDoc}
     */
    public function getNguoiTao()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getNguoiTao', array());

        return parent::getNguoiTao();
    }

    /**
     * {@inheritDoc}
     */
    public function setTrangThai($trangThai)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setTrangThai', array($trangThai));

        return parent::setTrangThai($trangThai);
    }

    /**
     * {@inheritDoc}
     */
    public function getTrangThai()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getTrangThai', array());

        return parent::getTrangThai();
    }

    /**
     * {@inheritDoc}
     */
    public function getTrangThaiNhom()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getTrangThaiNhom', array());

        return parent::getTrangThaiNhom();
    }

    /**
     * {@inheritDoc}
     */
    public function setCha($cha)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setCha', array($cha));

        return parent::setCha($cha);
    }

    /**
     * {@inheritDoc}
     */
    public function getCha()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getCha', array());

        return parent::getCha();
    }

    /**
     * {@inheritDoc}
     */
    public function addDinhKems($dinhKems)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'addDinhKems', array($dinhKems));

        return parent::addDinhKems($dinhKems);
    }

    /**
     * {@inheritDoc}
     */
    public function removeDinhKems($dinhKems)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'removeDinhKems', array($dinhKems));

        return parent::removeDinhKems($dinhKems);
    }

    /**
     * {@inheritDoc}
     */
    public function getDinhKems()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getDinhKems', array());

        return parent::getDinhKems();
    }

    /**
     * {@inheritDoc}
     */
    public function addNguoiThucHiens($nguoiThucHiens)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'addNguoiThucHiens', array($nguoiThucHiens));

        return parent::addNguoiThucHiens($nguoiThucHiens);
    }

    /**
     * {@inheritDoc}
     */
    public function removeNguoiThucHiens($nguoiThucHiens)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'removeNguoiThucHiens', array($nguoiThucHiens));

        return parent::removeNguoiThucHiens($nguoiThucHiens);
    }

    /**
     * {@inheritDoc}
     */
    public function getNguoiThucHiens()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getNguoiThucHiens', array());

        return parent::getNguoiThucHiens();
    }

    /**
     * {@inheritDoc}
     */
    public function addDonViTiepNhans($donViTiepNhans)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'addDonViTiepNhans', array($donViTiepNhans));

        return parent::addDonViTiepNhans($donViTiepNhans);
    }

    /**
     * {@inheritDoc}
     */
    public function removeDonViTiepNhans($donViTiepNhans)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'removeDonViTiepNhans', array($donViTiepNhans));

        return parent::removeDonViTiepNhans($donViTiepNhans);
    }

    /**
     * {@inheritDoc}
     */
    public function getDonViTiepNhans()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getDonViTiepNhans', array());

        return parent::getDonViTiepNhans();
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

}
