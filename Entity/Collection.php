<?php
namespace Arnm\CatalogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * Arnm\CatalogBundle\Entity\Collection
 *
 * @ORM\Table(name="catalog_collection")
 * @ORM\Entity(repositoryClass="Arnm\CatalogBundle\Entity\CollectionRepository")
 */
class Collection {
	/**
	 * @var integer $id
	 *
	 * @ORM\Column(name="id", type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;

	/**
	 * @var string $name
	 *
	 * @ORM\Column(name="name", type="string", length=255)
	 * 
	 * @Assert\NotBlank()
	 * @Assert\Type(type="string", message="The value {{ value }} is not a valid {{ type }}.")
	 */
	private $name;

	/**
	 * @var string $description
	 *
	 * @ORM\Column(name="description", type="string", length=1000, nullable=true)
	 */
	private $description;

	/**
	 * @var \Doctrine\Common\Collections\ArrayCollection  
	 * 
	 * @ORM\ManyToMany(targetEntity="Item", mappedBy="collections")
	 */
	private $items;

	public function __construct() {
		$this->items = new \Doctrine\Common\Collections\ArrayCollection();
	}

	/**
	 * Get id
	 *
	 * @return integer 
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * Set name
	 *
	 * @param string $name
	 * @return Collection
	 */
	public function setName($name) {
		$this->name = $name;
		return $this;
	}

	/**
	 * Get name
	 *
	 * @return string 
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * Set description
	 *
	 * @param string $description
	 * @return Collection
	 */
	public function setDescription($description) {
		$this->description = $description;
		return $this;
	}

	/**
	 * Get description
	 *
	 * @return string 
	 */
	public function getDescription() {
		return $this->description;
	}

	/**
	 * Add item
	 *
	 * @param \Arnm\CatalogBundle\Entity\Item $item
	 * 
	 * @return Collection
	 */
	public function addItem(\Arnm\CatalogBundle\Entity\Item $item) {
		$this->items[] = $item;

		return $this;
	}

	/**
	 * Get items
	 *
	 * @return Doctrine\Common\Collections\ArrayCollection
	 */
	public function getItems() {
		return $this->items;
	}
}
