<?php
namespace Arnm\CatalogBundle\Controller;


use Arnm\CatalogBundle\Form\ItemType;

use Arnm\CatalogBundle\Entity\Item;

use Arnm\CoreBundle\Controllers\ArnmController;
/**
 * Controller responsible for management of catalog items
 *
 * @author Alex Agulyansky <alex@iibspro.com>
 */
class ItemController extends ArnmController
{
    /**
     * Shows a list of categories
     *
     * @return Response
     */
    public function indexAction()
    {
        $entities = $this->getEntityManager()->getRepository('ArnmCatalogBundle:Item')->findAll();

        return $this->render('ArnmCatalogBundle:Item:index.html.twig', array(
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
        $item = new Item();
        $form = $this->createForm(new ItemType(), $item);

        if($this->getRequest()->getMethod() === 'POST') {
            $form->bind($this->getRequest());
            if($form->isValid()) {
                $em = $this->getEntityManager();
                $em->persist($item);
                $em->flush();

                $this->getSession()->getFlashBag()->add('notice', $this->get('translator')->trans('catalog.item.message.add.success', array(), 'catalog'));

                return $this->redirect($this->generateUrl('arnm_catalog_items'));
            }
        }
        return $this->render('ArnmCatalogBundle:Item:new.html.twig', array(
            'item' => $item,
            'form' => $form->createView()
        ));
    }

    /**
     * Shows and handles editing of existing item form
     *
     * @return Response
     */
    public function editAction($id)
    {
        $em = $this->getEntityManager();
        $item = $em->getRepository('ArnmCatalogBundle:Item')->findOneById($id);
        if(!($item instanceof Item)){
            throw new $this->createNotFoundException("Requested category was not found!");
        }

        $form = $this->createForm(new ItemType(), $item);

        if($this->getRequest()->getMethod() === 'POST') {
            $form->bind($this->getRequest());
            if($form->isValid()) {
                $em->persist($item);
                $em->flush();

                $this->getSession()->getFlashBag()->add('notice', $this->get('translator')->trans('catalog.item.message.update.success', array(), 'catalog'));

                return $this->redirect($this->generateUrl('arnm_catalog_item_edit', array('id' => $item->getId())));
            }
        }
        return $this->render('ArnmCatalogBundle:Item:edit.html.twig', array(
            'item' => $item,
            'form' => $form->createView()
        ));
    }

    /**
     * Delete item
     *
     * @return Response
     */
    public function deleteAction($id)
    {
        $em = $this->getEntityManager();
        $item = $em->getRepository('ArnmCatalogBundle:Item')->findOneById($id);
        if(!($item instanceof Item)){
            throw new $this->createNotFoundException("Requested item was not found!");
        }

        $em->remove($item);
        $em->flush();

        $this->getSession()->getFlashBag()->add('notice', $this->get('translator')->trans('catalog.item.message.delete.success', array(), 'catalog'));

        return $this->redirect($this->generateUrl('arnm_catalog_items'));
    }
}
