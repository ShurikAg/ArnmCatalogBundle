<?php
namespace Arnm\CatalogBundle\Model;

use Symfony\Component\Validator\Constraints as Assert;
/**
 * Data class for text widget form
 *
 * @author Alex Agulyansky <alex@iibspro.com>
 */
class CollectionsListWidget {
	/**
	 * Text value
	 *
	 * @var string
	 *
	 * @Assert\NotNull()
	 * @Assert\NotBlank()
	 */
	private $title;

	/**
	 * HTML element ID which is a target where to show the list of items
	 *
	 * @var string
	 *
	 * @Assert\NotNull()
	 * @Assert\NotBlank()
	 */
	private $targetElementId;

	/**
	 * List of collections
	 *
	 * @var array
	 */
	private $collections;

	public function __construct()
	{
	    $this->collections = new \Doctrine\Common\Collections\ArrayCollection();
	}
	/**
	 * Sets the value of title
	 *
	 * @param string $title
	 *
	 * @return CollectionShowcaseWidget
	 */
	public function setTitle($title) {
		$this->title = (string) $title;

		return $this;
	}

	/**
	 * Gets the value of title
	 *
	 * @return string
	 */
	public function getTitle() {
		return $this->title;
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
	 * Removes collection from the list
	 *
	 * @param \Arnm\CatalogBundle\Entity\Collection $collection
	 */
	public function removeCollection(\Arnm\CatalogBundle\Entity\Collection $collection)
	{
	   for($i = 0; $i < count($this->collections); $i++)
	   {
	       if($this->collections[i]->getId() == $collection->getId()){
	           unset($this->collections[i]);
	           return;
	       }
	   }
	}

	/**
	 * Get collections
	 *
	 * @return Doctrine\Common\Collections\ArrayCollection
	 */
	public function getCollections() {
		return $this->collections;
	}

	/**
	 * Sets target element ID
	 *
	 * @param string $id
	 *
	 * @return CollectionShowcaseWidget
	 */
	public function setTargetElementId($id)
	{
	    $this->targetElementId = (string) $id;

	    return $this;
	}

	/**
	 * Gets target element ID
	 *
	 * @return string
	 */
	public function getTargetElementId()
	{
	    return $this->targetElementId;
	}
}
