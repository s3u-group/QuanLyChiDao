<?php
namespace Taxonomy\Entity;
use Datetime;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
/**
* @ORM\Entity
* @ORM\Table(name="term_taxonomy")
* @ORM\HasLifecycleCallbacks
*/
class TermTaxonomy
{
	/**
	* @ORM\Column(name="term_taxonomy_id", type="integer")
	* @ORM\Id
	* @ORM\GeneratedValue
	*/
	private $id;

	/**
	* @ORM\ManyToOne(targetEntity="Taxonomy\Entity\Term", cascade={"persist"})
	* @ORM\JoinColumn(name="term_id", referencedColumnName="term_id")
	*/
	private $term;

	/**
	 * @ORM\Column(length=200)
	 */
	private $taxonomy;

	/**
	 * @ORM\Column(type="text")
	 */
	private $description = '';

	/**
	* @ORM\ManyToOne(targetEntity="Taxonomy\Entity\TermTaxonomy")
	* @ORM\JoinColumn(name="parent", referencedColumnName="term_taxonomy_id")
	*/
	private $parent;

	/**
	* @ORM\Column(type="integer")
	*/
	private $count = '';

	/**
	 * @ORM\ManyToOne(targetEntity="User\Entity\User")
	 * @ORM\JoinColumn(name="create_user", referencedColumnName="user_id")
	 */
	protected $createUser;

	/**
     * @ORM\Column(name="create_date", type="datetime")
     */
	protected $createDate;

	/**
	 * @ORM\ManyToOne(targetEntity="User\Entity\User")
	 * @ORM\JoinColumn(name="modify_user", referencedColumnName="user_id")
	 */
	protected $modifyUser;

	/**
     * @ORM\Column(name="modify_date", type="datetime")
     */
	protected $modifyDate;

	/**
     * @ORM\ManyToMany(targetEntity="User\Entity\User")
     * @ORM\JoinTable(name="user_group",
     *      joinColumns={@ORM\JoinColumn(name="group_id", referencedColumnName="term_taxonomy_id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="user_id")}
     * )
     */
    protected $users;

	private $level;

	/**
	 * @ORM\PrePersist 
	 */
	public function onPrePersist(){
    	$this->createDate = new DateTime('now');
	}

	/**
	 * @ORM\preUpdate 
	 */
	public function onPreUpdate(){
    	$this->modifyDate = new DateTime('now');
	}

	public function __construct()
    {
        $this->users = new ArrayCollection();
    }

	public function getId()
	{
		return $this->id;
	}

	public function setTerm($term)
	{
		$this->term = $term;
		return $this;
	}

	public function getTerm()
	{
		return $this->term;
	}

	public function getTermName(){
		if($term = $this->getTerm())
			return $term->getName();
	}

	public function setTaxonomy($taxonomy)
	{
		$this->taxonomy = $taxonomy;
		return $this;
	}

	public function getTaxonomy()
	{
		return $this->taxonomy;
	}

	public function setDescription($description)
	{
		$this->description = $description;
		return $this;
	}

	public function getDescription()
	{
		return $this->description;
	}

	public function setParent($parent)
	{
		$this->parent = $parent;
		return $this;
	}

	public function getParent()
	{
		return $this->parent;
	}

	public function setCount($count)
	{
		$this->count = $count;
		return $this;
	}

	public function getCount()
	{
		return $this->count;
	}

	public function setLevel($level){
		$this->level = $level;
		return $this;
	}

	public function getLevel(){
		return $this->level;
	}

	public function setCreateUser($createUser){
		$this->createUser = $createUser;
		return $this;
	}

	public function getCreateUser(){
		return $this->createUser;
	}

	public function setCreateDate($createDate){
		$this->createDate = $createDate;
		return $this;
	}

	public function getCreateDate(){
		return $this->createDate;
	}

	public function setModifyUser($modifyUser){
		$this->modifyUser = $modifyUser;
		return $this;
	}

	public function getModifyUser(){
		return $this->modifyUser;
	}

	public function setModifyDate($modifyDate){
		$this->modifyDate = $modifyDate;
		return $this;
	}

	public function getModifyDate(){
		return $this->modifyDate;
	}

	public function getNameCreateUser(){
		$user = $this->getCreateUser();
		if($user)
			return $user->getHoTen();
	}

	public function getNameModifyUser(){
		$user = $this->getModifyUser();
		if($user)
			return $user->getHoTen();
	}

	public function getCreateDateFull(){
		$date = $this->getCreateDate();
		if($date)
			return $date->format('d/m/Y H:i:s');
	}

	public function getModifyDateFull(){
		$date = $this->getModifyDate();
		if($date)
			return $date->format('d/m/Y H:i:s');
	}

	public function addUsers($users){
		foreach($users as $user){
			$this->users->add($user);
		}
	}

	public function removeUsers($users){
		foreach($users as $user){
			$this->users->removeElement($user);
		}
	}

	public function getUsers(){
		return $this->users->toArray();
	}
}
?>