<?php
namespace Arnm\CatalogBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Arnm\CatalogBundle\Entity\Category;
use Arnm\CatalogBundle\Entity\Item;
/**
 * Arnm\CatalogBundle\Entity\Category
 *
 * @ORM\Table(name="category")
 * @ORM\Entity(repositoryClass="Arnm\CatalogBundle\Entity\CategoryRepository")
 */
class Category {
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
	 *
	 */
	private $name;

	/**
	 * @var string $description
	 *
	 * @ORM\Column(name="description", type="string", length=1000)
	 *
	 * @Assert\NotBlank()
	 * @Assert\Type(type="string", message="The value {{ value }} is not a valid {{ type }}.")
	 */
	private $description;

	/**
	 * @var boolean $active
	 *
	 * @ORM\Column(name="active", type="boolean")
	 */
	private $active;

	/**
	 * @var string $slug
	 *
	 * @ORM\Column(name="slug", type="string", length=255)
	 *
	 * @Assert\NotBlank()
	 * @Assert\Type(type="string", message="The value {{ value }} is not a valid {{ type }}.")
	 */
	private $slug;

	/**
	 * @var ArrayCollection $items
	 *
	 * @ORM\OneToMany(targetEntity="Item", mappedBy="category")
	 */
	private $items;

	/**
	 * Constructor
	 */
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
	 * @return Category
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
	 * @return Category
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
	 * Set active
	 *
	 * @param boolean $active
	 * @return Category
	 */
	public function setActive($active) {
		$this->active = $active;
		return $this;
	}

	/**
	 * Get active
	 *
	 * @return boolean
	 */
	public function getActive() {
		return $this->active;
	}

	/**
	 * Set slug
	 *
	 * @param string $slug
	 * @return Category
	 */
	public function setSlug($slug) {
		$this->slug = $slug;
		return $this;
	}

	/**
	 * Get slug
	 *
	 * @return string
	 */
	public function getSlug() {
		return $this->slug;
	}

	/**
	 * Add item
	 *
	 * @param \Arnm\CatalogBundle\Entity\Item $item
	 *
	 * @return Category
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
