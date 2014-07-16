<?php
namespace Arnm\CatalogBundle\Controller;

use Arnm\CatalogBundle\Form\CategoryType;

use Arnm\CatalogBundle\Entity\Category;

use Arnm\CoreBundle\Controllers\ArnmController;
/**
 * Controller responsible for management of categories
 *
 * @author Alex Agulyansky <alex@iibspro.com>
 */
class CategoryController extends ArnmController
{
    /**
     * Shows a list of categories
     *
     * @return Response
     */
    public function indexAction()
    {
        $entities = $this->getEntityManager()->getRepository('ArnmCatalogBundle:Category')->findAll();

        return $this->render('ArnmCatalogBundle:Category:index.html.twig', array(
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
        $category = new Category();
        $form = $this->createForm(new CategoryType(), $category);

        if($this->getRequest()->getMethod() === 'POST') {
            $form->bind($this->getRequest());
            if($form->isValid()) {
                $em = $this->getEntityManager();
                $em->persist($category);
                $em->flush();

                $this->getSession()->getFlashBag()->add('notice', $this->get('translator')->trans('catalog.category.message.create.success', array(), 'catalog'));

                return $this->redirect($this->generateUrl('arnm_catalog_categories'));
            }
        }
        return $this->render('ArnmCatalogBundle:Category:new.html.twig', array(
            'category' => $category,
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
        $category = $em->getRepository('ArnmCatalogBundle:Category')->findOneById($id);
        if(!($category instanceof Category)){
            throw new $this->createNotFoundException("Requested category was not found!");
        }

        $form = $this->createForm(new CategoryType(), $category);

        if($this->getRequest()->getMethod() === 'POST') {
            $form->bind($this->getRequest());
            if($form->isValid()) {
                $em->persist($category);
                $em->flush();

                $this->getSession()->getFlashBag()->add('notice', $this->get('translator')->trans('catalog.category.message.update.success', array(), 'catalog'));

                return $this->redirect($this->generateUrl('arnm_catalog_category_edit', array('id' => $category->getId())));
            }
        }
        return $this->render('ArnmCatalogBundle:Category:edit.html.twig', array(
            'category' => $category,
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
        $category = $em->getRepository('ArnmCatalogBundle:Category')->findOneById($id);
        if(!($category instanceof Category)){
            throw new $this->createNotFoundException("Requested category was not found!");
        }

        $em->remove($category);
        $em->flush();

        $this->getSession()->getFlashBag()->add('notice', $this->get('translator')->trans('catalog.category.message.delete.success', array(), 'catalog'));

        return $this->redirect($this->generateUrl('arnm_catalog_categories'));
    }
}
