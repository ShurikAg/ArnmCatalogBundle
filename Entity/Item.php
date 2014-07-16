<?php
namespace Arnm\CatalogBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Arnm\CatalogBundle\Entity\Category;
/**
 * Arnm\CatalogBundle\Entity\Item
 *
 * @ORM\Table(name="catalog_item")
 * @ORM\Entity(repositoryClass="Arnm\CatalogBundle\Entity\ItemRepository")
 * 
 * @ORM\HasLifecycleCallbacks
 */
class Item {
	/**
	 * @var integer $id
	 *
	 * @ORM\Column(name="id", type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;

	/**
	 * @var string $title
	 *
	 * @ORM\Column(name="title", type="string", length=255)
	 * 
	 * @Assert\NotBlank()
	 * @Assert\Type(type="string", message="The value {{ value }} is not a valid {{ type }}.")
	 */
	private $title;

	/**
	 * @var string $description
	 *
	 * @ORM\Column(name="description", type="string", length=5000, nullable=true)
	 * 
	 * @Assert\Type(type="string", message="The value {{ value }} is not a valid {{ type }}.")
	 * @Assert\Length(
	 *      min = "2",
	 *      max = "5000",
	 *      minMessage = "Your first name must be at least {{ min }} characters length",
	 *      maxMessage = "Your first name cannot be longer than than {{ max }} characters length"
	 * )
	 */
	private $description;

	/**
	 * @var string $image
	 *
	 * @ORM\Column(name="image", type="string", length=255, nullable=true)
	 */
	private $image;

	/**
	 * @var boolean $active
	 *
	 * @ORM\Column(name="active", type="boolean")
	 */
	private $active;

	/**
	 * @var Category $category
	 * 
	 * @ORM\ManyToOne(targetEntity="Category", inversedBy="items")
	 * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
	 * 
	 * @Assert\NotBlank(message="The category must be selected.")
	 * @Assert\Type(type="object", message="The value {{ value }} is not a valid {{ type }}.")
	 */
	private $category;

	/**
	 * @ORM\ManyToMany(targetEntity="Collection", inversedBy="items")
	 * @ORM\JoinTable(name="item_collection")
	 */
	private $collections;

	public function __construct() {
		$this->users = new \Doctrine\Common\Collections\ArrayCollection();
		$this->collections = new \Doctrine\Common\Collections\ArrayCollection();
	}

	/**
	 * @Assert\Image()
	 */
	public $file;

	/**
	 * Get id
	 *
	 * @return integer 
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * Set title
	 *
	 * @param string $title
	 * @return Item
	 */
	public function setTitle($title) {
		$this->title = $title;
		return $this;
	}

	/**
	 * Get title
	 *
	 * @return string 
	 */
	public function getTitle() {
		return $this->title;
	}

	/**
	 * Set description
	 *
	 * @param string $description
	 * @return Item
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
	 * Set image
	 *
	 * @param string $image
	 * @return Item
	 */
	public function setImage($image) {
		$this->image = $image;
		return $this;
	}

	/**
	 * Get image
	 *
	 * @return string 
	 */
	public function getImage() {
		return $this->image;
	}

	/**
	 * Set active
	 *
	 * @param boolean $active
	 * @return Item
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
	 * Sets category for this item
	 * 
	 * @param Category $category
	 * 
	 * @return Item
	 */
	public function setCategory(Category $category) {
		$this->category = $category;

		return $this;
	}

	/**
	 * Gets category of this item
	 * 
	 * @return Category
	 */
	public function getCategory() {
		return $this->category;
	}

	/**
	 * Add collection
	 *
	 * @param \Arnm\CatalogBundle\Entity\Collection $collection
	 * 
	 * @return Item
	 */
	public function addCollection(\Arnm\CatalogBundle\Entity\Collection $collection) {
		$this->collections[] = $collection;

		return $this;
	}

	/**
	 * Get collections
	 *
	 * @return Doctrine\Common\Collections\ArrayCollection
	 */
	public function getCollections() {
		return $this->collections;
	}

	//////////////////////////////////
	// Methods that dealing with file upload funcitonality
	//////////////////////////////////
	/**
	 * @ORM\PrePersist()
	 * @ORM\PreUpdate()
	 */
	public function preUpload() {
		if (null !== $this->file) {
			//check if there is a file in place
			try {
				$this->removeUpload();
			} catch (\Exception $e) {
			}
			// do whatever you want to generate a unique name
			$this->image = uniqid() . '.' . $this->file->guessExtension();
		}
	}

	/**
	 * @ORM\PostPersist()
	 * @ORM\PostUpdate()
	 */
	public function upload() {
		if (null === $this->file) {
			return;
		}

		// if there is an error when moving the file, an exception will
		// be automatically thrown by move(). This will properly prevent
		// the entity from being persisted to the database on error
		$this->file->move($this->getUploadRootDir(), $this->getImage());

		unset($this->file);
	}

	/**
	 * @ORM\PostRemove()
	 */
	public function removeUpload() {
		if ($file = $this->getAbsolutePath()) {
			unlink($file);
		}
	}

	public function getAbsolutePath() {
		return null === $this->image ? null
				: $this->getUploadRootDir() . '/' . $this->getImage();
	}

	public function getWebPath() {
		return null === $this->image ? null
				: $this->getUploadDir() . '/' . $this->getImage();
	}

	protected function getUploadRootDir() {
		// the absolute directory path where uploaded documents should be saved
		return __DIR__ . '/../../../../web/' . $this->getUploadDir();
	}

	protected function getUploadDir() {
		// get rid of the __DIR__ so it doesn't screw when displaying uploaded doc/image in the view.
		return 'uploads/catalog';
	}
}
