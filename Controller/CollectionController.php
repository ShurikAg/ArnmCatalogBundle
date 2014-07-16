<?php
namespace Arnm\CatalogBundle\Controller;



use Arnm\CatalogBundle\Form\CollectionType;

use Arnm\CatalogBundle\Entity\Collection;

use Arnm\CoreBundle\Controllers\ArnmController;
/**
 * Controller responsible for management of categories
 *
 * @author Alex Agulyansky <alex@iibspro.com>
 */
class CollectionController extends ArnmController
{
    /**
     * Shows a list of categories
     *
     * @return Response
     */
    public function indexAction()
    {
        $entities = $this->getEntityManager()->getRepository('ArnmCatalogBundle:Collection')->findAll();

        return $this->render('ArnmCatalogBundle:Collection:index.html.twig', array(
            'entities' => $entities
        ));
    }

    /**
     * Shows and handles create new category form
     *
     * @return Response
     */
    public function newAction()
    {
        $collection = new Collection();
        $form = $this->createForm(new CollectionType(), $collection);

        if($this->getRequest()->getMethod() === 'POST') {
            $form->bind($this->getRequest());
            if($form->isValid()) {
                $em = $this->getEntityManager();
                $em->persist($collection);
                $em->flush();

                $this->getSession()->getFlashBag()->add('notice', $this->get('translator')->trans('catalog.collection.message.create.success', array(), 'catalog'));

                return $this->redirect($this->generateUrl('arnm_catalog_collections'));
            }
        }
        return $this->render('ArnmCatalogBundle:Collection:new.html.twig', array(
            'collection' => $collection,
            'form' => $form->createView()
        ));
    }

    /**
     * Shows and handles editing of existing category form
     *
     * @return Response
     */
    public function editAction($id)
    {
        $em = $this->getEntityManager();
        $collection = $em->getRepository('ArnmCatalogBundle:Collection')->findOneById($id);
        if(!($collection instanceof Collection)){
            throw new $this->createNotFoundException("Requested collection was not found!");
        }

        $form = $this->createForm(new CollectionType(), $collection);

        if($this->getRequest()->getMethod() === 'POST') {
            $form->bind($this->getRequest());
            if($form->isValid()) {
                $em->persist($collection);
                $em->flush();

                $this->getSession()->getFlashBag()->add('notice', $this->get('translator')->trans('catalog.collection.message.update.success', array(), 'catalog'));

                return $this->redirect($this->generateUrl('arnm_catalog_collection_edit', array('id' => $collection->getId())));
            }
        }
        return $this->render('ArnmCatalogBundle:Collection:edit.html.twig', array(
           	'collection' => $collection,
            'form' => $form->createView()
        ));
    }

    /**
     * Delete category
     *
     * @return Response
     */
    public function deleteAction($id)
    {
        $em = $this->getEntityManager();
        $collection = $em->getRepository('ArnmCatalogBundle:Collection')->findOneById($id);
        if(!($collection instanceof Collection)){
            throw new $this->createNotFoundException("Requested collection was not found!");
        }

        $em->remove($collection);
        $em->flush();

        $this->getSession()->getFlashBag()->add('notice', $this->get('translator')->trans('catalog.collection.message.delete.success', array(), 'catalog'));

        return $this->redirect($this->generateUrl('arnm_catalog_collections'));
    }
}
