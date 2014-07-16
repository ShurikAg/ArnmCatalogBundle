<?php
namespace Arnm\CatalogBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;
/**
 * Template form use to manage Templates as well as gets embedded into page form
 *
 * @author Alex Agulyansky <alex@iibspro.com>
 */
class ItemType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder->add('title', 'text', array(
        'label' => 'catalog.item.form.title.label',
        'attr' => array(
            'data-toggle' => 'popover',
            'content' => 'catalog.item.form.title.help',
            'translation_domain' => 'catalog',
            'class' => 'form-control'
        ),
        'translation_domain' => 'catalog',
        'required' => false
    ));
    $builder->add('description', 'textarea', array(
        'label' => 'catalog.item.form.description.label',
        'attr' => array(
            'data-toggle' => 'popover',
            'content' => 'catalog.item.form.description.help',
            'translation_domain' => 'catalog',
            'class' => 'form-control'
        ),
        'translation_domain' => 'catalog',
        'required' => false
    ));
    $builder->add('active', 'checkbox', array(
        'label' => 'catalog.item.form.active.label',
        'attr' => array(
            'data-toggle' => 'popover',
            'content' => 'catalog.item.form.active.help',
            'translation_domain' => 'catalog',
            'class' => 'checkbox'
        ),
        'translation_domain' => 'catalog',
        'required' => false
    ));
    $builder->add('category', 'entity', array(
        'class' => 'Arnm\CatalogBundle\Entity\Category',
        'property' => 'name',
        'empty_value' => 'catalog.item.form.category.empty_value',
        'label' => 'catalog.item.form.category.label',
        'attr' => array(
            'data-toggle' => 'popover',
            'content' => 'catalog.item.form.category.help',
            'translation_domain' => 'catalog',
            'class' => 'form-control'
        ),
        'translation_domain' => 'catalog',
        'required' => false
    ));
    $builder->add('file', 'file', array(
        'label' => 'catalog.item.form.file.label',
        'attr' => array(
            'data-toggle' => 'popover',
            'content' => 'catalog.item.form.file.help',
            'translation_domain' => 'catalog',
            'class' => ''
        ),
        'translation_domain' => 'catalog',
        'required' => false
    ));
    $builder->add('collections', 'entity', array(
    	'class' => 'Arnm\CatalogBundle\Entity\Collection',
        'property' => 'name',
    	'multiple' => true,
       	'empty_value' => false,
        'label' => 'catalog.item.form.collections.label',
        'attr' => array(
            'data-toggle' => 'popover',
            'content' => 'catalog.item.form.collections.help',
            'translation_domain' => 'catalog',
            'class' => 'select2'
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
    return 'catalog_item';
  }

  /**
   * (non-PHPdoc)
   * @see Symfony\Component\Form.AbstractType::getDefaultOptions()
   */
  public function getDefaultOptions(array $options)
  {
    return array(
        'data_class' => 'Arnm\CatalogBundle\Entity\Item'
    );
  }
}
