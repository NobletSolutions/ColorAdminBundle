<?php

namespace NS\ColorAdminBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Description of KnobType
 *
 * @author mark
 */
class EntitySelectPickerType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefined(['style', 'search', 'simple']);
    }

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.UnusedLocalVariable)
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['attr']['data-style'] = isset($options['style']) ? $options['style'] : 'btn-white';
        $view->vars['attr']['data-live-search'] = isset($options['search']) ? $options['search'] : 'true';
        $view->vars['attr']['class'] = (isset($view->vars['attr']['class']) ? $view->vars['attr']['class']:'').(isset($options['simple']) ? 'selectpicker':'selectpicker form-control');
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return EntityType::class;
    }
}
