<?php
namespace Taxonomy\Entity;

use Doctrine\ORM\Mapping as ORM;
use BaconStringUtils\Slugifier;

/**
* @ORM\Entity
* @ORM\Table(name="terms")
*/
class Term
{
	/**
	* @ORM\Column(name="term_id", type="integer")
	* @ORM\Id
	* @ORM\GeneratedValue
	*/
	private $id;

	/**
	 * @ORM\Column(length=200)
	 */
	private $name;

	/**
	 * @ORM\Column(length=200)
	 */
	private $slug;

	/**
	 * @ORM\Column(name="term_group", type="integer")
	 */
	private $termGroup = '';

	public function getId()
	{
		return $this->id;
	}

	public function setName($name)
	{
		$this->name = $name;
		return $this;
	}

	public function getName()
	{
		return $this->name;
	}

	public function setSlug($slug = null)
	{
		if(!$slug){
			$slugifier = new Slugifier();
			$this->slug = $slugifier->slugify($this->name);
		}
		else{
			$this->slug = $slug;			
		}
		return $this;
	}

	public function getSlug()
	{
		return $this->slug;
	}

	public function setTermGroup($termGroup)
	{
		$this->termGroup = $termGroup;
		return $this;
	}

	public function getTermGroup()
	{
		return $this->termGroup;
	}

}
?>