<?php
namespace Arnm\CatalogBundle\Form;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;
/**
 * Template form use to manage Templates as well as gets embedded into page form
 *
 * @author Alex Agulyansky <alex@iibspro.com>
 */
class CollectionsListType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', 'text', array(
            'label' => 'catalog.collection_showcase.form.title.label',
            'attr' => array(
                'data-toggle' => 'popover',
                'content' => 'catalog.collection_showcase.form.title.help',
                'class' => 'form-control',
                'ng-model' => 'wConfigForm.title',
                'translation_domain' => 'catalog'
            ),
            'translation_domain' => 'catalog',
            'required' => false
        ));
        $builder->add('collections', 'entity', array(
            'class' => 'Arnm\CatalogBundle\Entity\Collection',
            'property' => 'name',
            'multiple' => true,
            'empty_value' => false,
            'label' => 'catalog.collection_showcase.form.collections.label',
            'attr' => array(
                'data-toggle' => 'popover',
                'content' => 'catalog.collection_showcase.form.collections.help',
                'class' => 'form-control',
                'ng-model' => 'wConfigForm.collections',
                'translation_domain' => 'catalog'
            ),
            'translation_domain' => 'catalog',
            'required' => false
        ));
        $builder->add('target_element_id', 'text', array(
            'label' => 'catalog.collection_showcase.form.target_element_id.label',
            'attr' => array(
                'data-toggle' => 'popover',
                'content' => 'catalog.collection_showcase.form.target_element_id.help',
                'class' => 'form-control',
                'ng-model' => 'wConfigForm.target_element_id',
                'translation_domain' => 'catalog'
            ),
            'translation_domain' => 'catalog',
            'required' => false
        ));
    }

    /**
     * (non-PHPdoc)
     * @see Symfony\Component\Form.FormTypeInterface::getName()
     */
    public function getName()
    {
        return 'collections_list';
    }

    /**
     * {@inheritdoc}
     * @see Symfony\Component\Form.AbstractType::setDefaultOptions()
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Arnm\CatalogBundle\Model\CollectionsListWidget',
            'csrf_protection' => false
        ));
    }

}
