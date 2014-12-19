<?php
/**
 * BjyAuthorize Module (https://github.com/bjyoungblood/BjyAuthorize)
 *
 * @link https://github.com/bjyoungblood/BjyAuthorize for the canonical source repository
 * @license http://framework.zend.com/license/new-bsd New BSD License
 */
 
namespace User\Entity;

use BjyAuthorize\Provider\Role\ProviderInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use ZfcUser\Entity\UserInterface;
use DateTime;

/**
 * An example of how to implement a role aware user entity.
 *
 * @ORM\Entity
 * @ORM\Table(name="users")
 *
 * @author Tom Oram <tom@scl.co.uk>
 */
class User implements UserInterface, ProviderInterface
{
    /**
     * @var int
     * @ORM\Id
     * @ORM\Column(name="user_id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     * @ORM\Column(type="string", length=255, unique=true, nullable=true)
     */
    protected $username;

    /**
     * @var string
     * @ORM\Column(type="string", unique=true,  length=255)
     */
    protected $email;

    /**
     * @var string
     * @ORM\Column(name="display_name", type="string", length=50, nullable=true)
     */
    protected $displayName;

    /**
     * @var string
     * @ORM\Column(type="string", length=128)
     */
    protected $password;

    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    protected $state = 1;

    /**
     * @var \Doctrine\Common\Collections\Collection
     * @ORM\ManyToMany(targetEntity="User\Entity\Role")
     * @ORM\JoinTable(name="user_role_linker",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="user_id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="role_id", referencedColumnName="id")}
     * )
     */
    protected $roles;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $ho;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $ten;

    /**
     * @ORM\Column(name="gioi_tinh", type="integer")
     */
    protected $gioiTinh;

    /**
     * @ORM\ManyToOne(targetEntity="Taxonomy\Entity\TermTaxonomy")
     * @ORM\JoinColumn(name="thanh_pho_id", referencedColumnName="term_taxonomy_id")
     */
    protected $thanhPho;

    /**
     * @ORM\ManyToOne(targetEntity="Taxonomy\Entity\TermTaxonomy")
     * @ORM\JoinColumn(name="quoc_gia_id", referencedColumnName="term_taxonomy_id")
     */
    protected $quocGia;

    /**
     * @var string
     * @ORM\Column(name="dien_thoai", type="string", unique=true,  length=20, nullable=true)
     */
    protected $dienThoai;

    /**
     * @var string
     * @ORM\Column(name="dia_chi", type="string", length=255, nullable=true)
     */
    protected $diaChi;

    /**
     * @ORM\Column(name="ngay_tao", type="datetime")
     */
    private $ngayTao;

    /**
     * @ORM\Column(name="dang_nhap_cuoi", type="datetime")
     */
    private $dangNhapCuoi;

    /**
     * @ORM\Column(name="ngay_chinh_sua", type="datetime")
     */
    private $ngayChinhSua;

    /**
     * @ORM\ManyToOne(targetEntity="User\Entity\DonVi")
     * @ORM\JoinColumn(name="don_vi_id", referencedColumnName="id")
     */
    protected $donVi;

    /**
     * Initialies the roles variable.
     */
    public function __construct()
    {
        $this->roles = new ArrayCollection();
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set id.
     *
     * @param int $id
     *
     * @return void
     */
    public function setId($id)
    {
        $this->id = (int) $id;
    }

    /**
     * Get username.
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set username.
     *
     * @param string $username
     *
     * @return void
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * Get email.
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set email.
     *
     * @param string $email
     *
     * @return void
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * Get displayName.
     *
     * @return string
     */
    public function getDisplayName()
    {
        return $this->displayName;
    }

    /**
     * Set displayName.
     *
     * @param string $displayName
     *
     * @return void
     */
    public function setDisplayName($displayName)
    {
        $this->displayName = $displayName;
    }

    /**
     * Get password.
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set password.
     *
     * @param string $password
     *
     * @return void
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * Get state.
     *
     * @return int
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set state.
     *
     * @param int $state
     *
     * @return void
     */
    public function setState($state)
    {
        $this->state = $state;
    }

    /**
     * Get role.
     *
     * @return array
     */
    public function getRoles()
    {
        return $this->roles->getValues();
    }

    /**
     * Add a role to the user.
     *
     * @param Role $role
     *
     * @return void
     */
    public function addRole($role)
    {
        $this->roles[] = $role;
    }

    /**
     * @param string $dienThoai
     * @return void
     */
    public function setDienThoai($dienThoai){
        $this->dienThoai = $dienThoai;
        return $this;
    }

    /**
     * @return string
     */
    public function getDienThoai(){
        return $this->dienThoai;
    }

    /**
     * @param string $diaChi
     * @return void
     */
    public function setDiaChi($diaChi){
        $this->diaChi = $diaChi;
        return $this;
    }

    /**
     * @return string
     */
    public function getDiaChi(){
        return $this->diaChi;
    }

    public function setHo($ho){
        $this->ho = $ho;
        return $this;
    }

    public function getHo(){
        return $this->ho;
    }

    public function setTen($ten){
        $this->ten = $ten;
        return $this;
    }

    public function getTen(){
        return $this->ten;
    }

    public function getHoTen(){
        if($this->getHo())
            return $this->getHo() . ' ' . $this->getTen();
        return $this->getTen();
    }

    public function setGioiTinh($gioiTinh){
        $this->gioiTinh = $gioiTinh;
        return $this;
    }

    public function getGioiTinh(){
        switch($this->gioiTinh){
            case '1':
                return 'Nam';
                break;
            case '2':
                return 'Nữ';
                break;
            default:
                return '--';
        }
    }

    public function setThanhPho($thanhPho){
        $this->thanhPho = $thanhPho;
        return $this;
    }

    public function getThanhPho(){
        return $this->thanhPho;
    }

    public function setQuocGia($quocGia){
        $this->quocGia = $quocGia;
        return $this;
    }

    public function getQuocGia(){
        return $this->quocGia;
    }

    public function setNgayTao($ngayTao){
        $this->ngayTao = $ngayTao;
        return $this;
    }

    public function getNgayTao(){
        return $this->ngayTao;
    }

    public function setDangNhapCuoi($dangNhapCuoi){
        $this->dangNhapCuoi = $dangNhapCuoi;
        return $this;
    }

    public function getDangNhapCuoi(){
        return $this->dangNhapCuoi;
    }

    public function setNgayChinhSua($ngayChinhSua){
        $this->ngayChinhSua = $ngayChinhSua;
        return $this;
    }

    public function getNgayChinhSua(){
        return $this->ngayChinhSua;
    }

    public function setDonVi($donVi){
        $this->donVi = $donVi;
        return $this;
    }

    public function getDonVi(){
        return $this->donVi;
    }
}
