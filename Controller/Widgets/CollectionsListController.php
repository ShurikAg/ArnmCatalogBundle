<?php
namespace Arnm\CatalogBundle\Controller\Widgets;

use Symfony\Component\Form\FormError;

use Arnm\CatalogBundle\Form\CollectionsListType;

use Arnm\CatalogBundle\Model\CollectionsListWidget;

use Arnm\WidgetBundle\Entity\Param;
use Arnm\WidgetBundle\Entity\Widget;
use Arnm\WidgetBundle\Manager\WidgetsManager;
use Arnm\WidgetBundle\Controllers\ArnmWidgetController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
/**
 * Controller of text widget
 *
 * @author Alex Agulyansky <alex@iibspro.com>
 */
class CollectionsListController extends ArnmWidgetController
{

    /**
     * {@inheritdoc}
     */
    public function renderAction(Widget $widget)
    {
        $titleParam = $widget->getParamByName('title');
        $collParam = $widget->getParamByName('collections');
        $targetIdParam = $widget->getParamByName('target_id');
        if ($collParam instanceof Param) {
            $collIdsStr = $collParam->getValue();
            $collIds = array();
            if (!empty($collIdsStr)) {
                $collIds = unserialize($collIdsStr);
            }
        }
        $qb = $this->getEntityManager()->createQueryBuilder();
        $collections = $qb->select('c')->from('Arnm\CatalogBundle\Entity\Collection', 'c')->add('where', $qb->expr()->in('c.id', $collIds))->getQuery()->execute();

        return $this->render('ArnmCatalogBundle:Widgets\CollectionsList:render.html.twig', array(
            'collections' => $collections,
            'title' => $titleParam->getValue(),
            'targetId' => $targetIdParam->getValue()
        ));
    }

    /**
     * {@inheritdoc}
     * @see Arnm\WidgetBundle\Controllers.ArnmWidgetController::getConfigFields()
     */
    public function getConfigFields()
    {
        return array(
            'title',
            'collections',
            'target_element_id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function editAction()
    {
        $collScase = new CollectionsListWidget();
        $form = $this->createForm(new CollectionsListType(), $collScase);

        return $this->render('ArnmCatalogBundle:Widgets\CollectionsList:edit.html.twig', array(
            'edit_form' => $form->createView()
        ));
    }

    /**
     * {@inheritdoc}
     *
     * @see Arnm\WidgetBundle\Controllers.ArnmWidgetController::updateAction()
     */
    public function updateAction($id, Request $request)
    {
        $widget = $this->getWidgetManager()->findWidgetById($id);
        if (!($widget instanceof Widget)) {
            throw $this->createNotFoundException("Widget with id: '" . $id . "' not found!");
        }

        $collScase = new CollectionsListWidget();
        $this->fillDataObject($widget, $collScase);
        $form = $this->createForm(new CollectionsListType(), $collScase);

        $data = $this->extractArrayFromRequest($request);

        $form->bind($data);
        if (!$form->isValid()) {
            $response = array(
                'error' => 'validation',
                'errors' => array()
            );
            $errors = $form->getErrors();
            foreach ($errors as $key => $error) {
                if ($error instanceof FormError) {
                    $response['errors'][$key] = $error->getMessage();
                }
            }

            return $this->createResponse($response);
        }

        $this->processSaveParam($widget, $collScase);

        return $this->createResponse(array(
            'OK'
        ));
    }

    /**
     * Creates new of updates existing param of the widget
     *
     * @param Widget $widget
     * @param CollectionsListWidget $collScase
     */
    protected function processSaveParam(Widget $widget, CollectionsListWidget $collScase)
    {
        $em = $this->getEntityManager();
        //title
        $titleParam = $widget->getParamByName('title');
        if ($titleParam instanceof Param) {
            //update existing
            $titleParam->setValue((string) $collScase->getTitle());
        } else {
            //create new
            $titleParam = new Param();
            $titleParam->setName('title');
            $titleParam->setValue((string) $collScase->getTitle());
            $titleParam->setWidget($widget);
        }
        $em->persist($titleParam);

        //collections
        //get an array of collection ids
        $collIds = array();
        foreach ($collScase->getCollections() as $collection) {
            $collIds[] = $collection->getId();
        }
        $collParam = $widget->getParamByName('collections');
        if ($collParam instanceof Param) {
            //update existing
            $collParam->setValue(serialize($collIds));
        } else {
            //create new
            $collParam = new Param();
            $collParam->setName('collections');
            $collParam->setValue(serialize($collIds));
            $collParam->setWidget($widget);
        }
        $em->persist($collParam);

        $targetIdParam = $widget->getParamByName('target_element_id');
        if ($targetIdParam instanceof Param) {
            //update existing
            $targetIdParam->setValue((string) $collScase->getTargetElementId());
        } else {
            //create new
            $targetIdParam = new Param();
            $targetIdParam->setName('target_element_id');
            $targetIdParam->setValue((string) $collScase->getTargetElementId());
            $targetIdParam->setWidget($widget);
        }
        $em->persist($targetIdParam);

        $em->flush();
    }

    /**
     * Fill the object with a data from widget if available
     *
     * @param Widget $widget
     * @param CollectionsListWidget $collScase
     */
    protected function fillDataObject(Widget $widget, CollectionsListWidget $collScase)
    {
        $titleParam = $widget->getParamByName('title');
        if ($titleParam instanceof Param) {
            $collScase->setTitle($titleParam->getValue());
        }
        $targetIdParam = $widget->getParamByName('target_element_id');
        if ($targetIdParam instanceof Param) {
            $collScase->setTargetElementId($targetIdParam->getValue());
        }

        $collParam = $widget->getParamByName('collections');
        if ($collParam instanceof Param) {
            $collIdsStr = $collParam->getValue();
            $collIds = array();
            if (!empty($collIdsStr)) {
                $collIds = unserialize($collIdsStr);
            }

            if (!empty($collIds)) {
                //gram the collections from DB
                $qb = $this->getEntityManager()->createQueryBuilder();
                $collections = $qb->select('c')->from('Arnm\CatalogBundle\Entity\Collection', 'c')->add('where', $qb->expr()->in('c.id', $collIds))->getQuery()->execute();
                foreach ($collections as $collection) {
                    $collScase->addCollection($collection);
                }
            }
        }
    }
}
