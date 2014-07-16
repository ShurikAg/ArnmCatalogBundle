<?php
namespace Arnm\CatalogBundle\Controller\Widgets;

use Symfony\Component\Form\FormError;

use Symfony\Component\Validator\Constraints\Count;

use Doctrine\ORM\PersistentCollection;
use Arnm\CatalogBundle\Entity\Collection;
use Arnm\CatalogBundle\Form\ShowcaseContainerType;
use Arnm\CatalogBundle\Model\ShowcaseContainerWidget;
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
class ShowcaseContainerController extends ArnmWidgetController
{

    /**
     * {@inheritdoc}
     */
    public function renderAction(Widget $widget)
    {
        $elementIdParam = $widget->getParamByName('element_id');
        $collParam = $widget->getParamByName('default_collection');
        $defaultCollection = null;
        if ($collParam instanceof Param && $collParam->getValue() != '') {
            $defaultCollection = $this->getEntityManager()->getRepository('ArnmCatalogBundle:Collection')->find($collParam->getValue());
        }

        return $this->render('ArnmCatalogBundle:Widgets\ShowcaseContainer:render.html.twig', array(
            'elementId' => $elementIdParam->getValue(),
            'collection' => $defaultCollection
        ));
    }

    /**
     * {@inheritdoc}
     * @see Arnm\WidgetBundle\Controllers.ArnmWidgetController::getConfigFields()
     */
    public function getConfigFields()
    {
        return array(
            'element_id',
            'default_collection'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function editAction()
    {
        $container = new ShowcaseContainerWidget();
        $form = $this->createForm(new ShowcaseContainerType(), $container);

        return $this->render('ArnmCatalogBundle:Widgets\ShowcaseContainer:edit.html.twig', array(
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

        $container = new ShowcaseContainerWidget();
        $this->fillDataObject($widget, $container);
        $form = $this->createForm(new ShowcaseContainerType(), $container);

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

        $this->processSaveParam($widget, $container);

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
    protected function processSaveParam(Widget $widget, ShowcaseContainerWidget $container)
    {
        $em = $this->getEntityManager();
        //element ID
        $elementIdParam = $widget->getParamByName('element_id');
        if ($elementIdParam instanceof Param) {
            //update existing
            $elementIdParam->setValue((string) $container->getElementId());
        } else {
            //create new
            $elementIdParam = new Param();
            $elementIdParam->setName('element_id');
            $elementIdParam->setValue((string) $container->getElementId());
            $elementIdParam->setWidget($widget);
        }
        $em->persist($elementIdParam);
        //collections
        //get an array of collection ids
        $collection = $container->getDefaultCollection();
        $collId = ($collection instanceof Collection) ? $collection->getId() : '';
        $collParam = $widget->getParamByName('default_collection');
        if ($collParam instanceof Param) {
            //update existing
            $collParam->setValue($collId);
        } else {
            //create new
            $collParam = new Param();
            $collParam->setName('default_collection');
            $collParam->setValue($collId);
            $collParam->setWidget($widget);
        }
        $em->persist($collParam);

        $em->flush();
    }

    /**
     * Fill the object with a data from widget if available
     *
     * @param Widget $widget
     * @param ShowcaseContainerWidget $container
     */
    protected function fillDataObject(Widget $widget, ShowcaseContainerWidget $container)
    {
        $elementIdParam = $widget->getParamByName('element_id');
        if ($elementIdParam instanceof Param) {
            $container->setElementId($elementIdParam->getValue());
        }

        $collParam = $widget->getParamByName('default_collection');
        if ($collParam instanceof Param) {
            $collId = $collParam->getValue();

            if (!empty($collId)) {
                //gram the collections from DB
                $collection = $this->getEntityManager()->getRepository('ArnmCatalogBundle:Collection')->find($collId);
                $container->setDefaultCollection($collection);
            }
        }
    }
}
