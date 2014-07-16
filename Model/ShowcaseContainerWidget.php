<?php
namespace Arnm\CatalogBundle\Model;

use Arnm\CatalogBundle\Entity\Collection;

use Symfony\Component\Validator\Constraints as Assert;
/**
 * Data class for text widget form
 *
 * @author Alex Agulyansky <alex@iibspro.com>
 */
class ShowcaseContainerWidget {
	
	/**
	 * HTML element ID which is a target where to show the list of items in
	 * 
	 * @var string
	 * 
	 * @Assert\NotNull()
	 * @Assert\NotBlank()
	 * @Assert\Type(type="string", message="The value {{ value }} is not a valid {{ type }}.")
	 */
	private $elementId;

	/**
	 * A default collection to be shown
	 * 
	 * @var \Arnm\CatalogBundle\Entity\Collection
	 */
	private $defaultCollection;

	/**
	 * Sets default collection
	 *
	 * @param \Arnm\CatalogBundle\Entity\Collection $collection
	 * 
	 * @return ShowcaseContainerWidget
	 */
	public function setDefaultCollection(Collection $collection) {
		$this->defaultCollection = $collection;

		return $this;
	}

	/**
	 * Get collections
	 *
	 * @return Doctrine\Common\Collections\ArrayCollection
	 */
	public function getDefaultCollection() {
		return $this->defaultCollection;
	}
	
	/**
	 * Sets element ID
	 * 
	 * @param string $id
	 * 
	 * @return CollectionShowcaseWidget
	 */
	public function setElementId($id)
	{
	    $this->elementId = (string) $id;
	    
	    return $this;
	}
	
	/**
	 * Gets element ID
	 * 
	 * @return string
	 */
	public function getElementId()
	{
	    return $this->elementId;
	}
}
