<?php

namespace NS\ColorAdminBundle\Form\Extension;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Created by PhpStorm.
 * User: mark
 * Date: 12/10/16
 * Time: 11:30 AM
 */
class DetailsExtension extends AbstractTypeExtension
{
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['note'] = isset($options['note']) ? $options['note'] : false;
        $view->vars['wrapper_class'] = isset($options['wrapper_class']) ? $options['wrapper_class'] : false;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefined(['note', 'wrapper_class']);
    }

    public function getExtendedType()
    {
        return FormType::class;
    }
}