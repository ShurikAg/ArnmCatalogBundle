<?php
namespace Arnm\CatalogBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;
/**
 * Template form use to manage Templates as well as gets embedded into page form
 *
 * @author Alex Agulyansky <alex@iibspro.com>
 */
class CategoryType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder->add('name', 'text', array(
        'label' => 'catalog.category.form.name.label',
        'attr' => array(
            'data-toggle' => 'popover',
            'content' => 'catalog.category.form.name.help',
            'translation_domain' => 'catalog',
            'class' => 'form-control'
        ),
        'translation_domain' => 'catalog',
        'required' => false
    ));
    $builder->add('description', 'textarea', array(
        'label' => 'catalog.category.form.description.label',
        'attr' => array(
            'data-toggle' => 'popover',
            'content' => 'catalog.category.form.description.help',
    		'translation_domain' => 'catalog',
            'class' => 'form-control'
        ),
        'translation_domain' => 'catalog',
        'required' => false
    ));
    $builder->add('slug', 'text', array(
        'label' => 'catalog.category.form.slug.label',
        'attr' => array(
            'data-toggle' => 'popover',
            'content' => 'catalog.category.form.slug.help',
            'translation_domain' => 'catalog',
            'class' => 'form-control'
        ),
        'translation_domain' => 'catalog',
        'required' => false
    ));
    $builder->add('active', 'checkbox', array(
        'label' => 'catalog.category.form.active.label',
        'attr' => array(
            'data-toggle' => 'popover',
            'content' => 'catalog.category.form.active.help',
            'translation_domain' => 'catalog',
            'class' => 'checkbox'
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
    return 'category';
  }

  /**
   * (non-PHPdoc)
   * @see Symfony\Component\Form.AbstractType::getDefaultOptions()
   */
  public function getDefaultOptions(array $options)
  {
    return array(
        'data_class' => 'Arnm\CatalogBundle\Entity\Category'
    );
  }
}
