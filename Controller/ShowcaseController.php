<?php
namespace Arnm\CatalogBundle\Controller;

use Arnm\CoreBundle\Controllers\ArnmAjaxController;
use Doctrine\ORM\PersistentCollection;
use Symfony\Component\HttpFoundation\Response;
use Arnm\CatalogBundle\Entity\Collection;
/**
 * This controller responsible for collection showcase rendering
 *
 * @author Alex Agulyansky <alex@iibspro.com>
 */
class ShowcaseController extends ArnmAjaxController
{

    /**
     * This actiona is build for AJAX request handling of collection showcase
     *
     * @param int $id
     */
    public function showAction($id)
    {
        if (! $this->getRequest()->isXmlHttpRequest()) {
            //only ajax request is allowed for this action
            throw $this->createNotFoundException();
        }

        $collection = $this->getEntityManager()
            ->getRepository('ArnmCatalogBundle:Collection')
            ->find($id);

        $response = array(
            'status' => 'OK',
            'content' => $this->renderCollectionAction($collection)->getContent()
        );

        return $this->createResponse($response);
    }

    /**
     *
     * @param Collection $collection
     */
    public function renderCollectionAction(Collection $collection)
    {
        $items = null;
        if ($collection instanceof Collection) {
            $items = $collection->getItems();
            $items = $this->chunkCollection($items, 5, 3);
        }

        if (empty($items)) {
            return new Response("");
        }

        return $this->render('ArnmCatalogBundle:Showcase:renderCollection.html.twig',
        array(
            'collection' => $collection,
            'items' => $items
        ));
    }

    /**
     * Converts a collection of items into chunked array
     *
     * @param PersistentCollection $collection
     * @param int $size
     * @param int $maxChunks
     *
     * @return array
     */
    private function chunkCollection(PersistentCollection $collection, $size, $maxChunks = null)
    {
        $collectionArray = array();
        if (empty($collection)) {
            return $collectionArray;
        }

        $count = null;
        if (! is_null($maxChunks)) {
            $count = $size * $maxChunks;
        }

        //convert to array
        foreach ($collection as $key => $item) {
            $collectionArray[$key] = $item;
            if (! is_null($count)) {
                $count --;
                if ($count == 0) {
                    break;
                }
            }
        }

        if (count($collectionArray) <= $size) {
            return array(
                $collectionArray
            );
        }

        return array_chunk($collectionArray, $size);
    }
}