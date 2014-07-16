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
class ShowcaseContainerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('element_id', 'text', array(
            'label' => 'catalog.showcase_container.form.element_id.label',
            'attr' => array(
                'data-toggle' => 'popover',
                'content' => 'catalog.showcase_container.form.element_id.help',
                'class' => 'form-control',
                'ng-model' => 'wConfigForm.element_id',
                'translation_domain' => 'catalog'
            ),
            'translation_domain' => 'catalog',
            'required' => false
        ));
        $builder->add('default_collection', 'entity', array(
            'class' => 'Arnm\CatalogBundle\Entity\Collection',
            'property' => 'name',
            'label' => 'catalog.showcase_container.form.default_collection.label',
            'attr' => array(
                'data-toggle' => 'popover',
                'content' => 'catalog.showcase_container.form.default_collection.help',
                'class' => 'form-control',
                'ng-model' => 'wConfigForm.default_collection',
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
        return 'showcase_container';
    }

    /**
     * {@inheritdoc}
     * @see Symfony\Component\Form.AbstractType::setDefaultOptions()
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Arnm\CatalogBundle\Model\ShowcaseContainerWidget',
            'csrf_protection' => false
        ));
    }
}
