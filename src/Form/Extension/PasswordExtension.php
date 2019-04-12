<?php
/**
 * Created by PhpStorm.
 * User: mark
 * Date: 26/03/19
 * Time: 11:59 AM
 */

namespace NS\ColorAdminBundle\Form\Extension;


use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PasswordExtension extends AbstractTypeExtension
{
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['toggle'] = isset($options['toggle']) ? $options['toggle'] : false;
        $view->vars['placement'] = isset($options['placement']) ? $options['placement'] : false;
        $view->vars['indicator'] = isset($options['indicator']) ? $options['indicator'] : false;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefined(['toggle', 'placement', 'indicator']);
        $resolver->setAllowedValues('toggle', [true, false]);
        $resolver->setAllowedValues('placement', ['before', 'after']);
        $resolver->setAllowedValues('indicator', [true, false]);
        $resolver->setDefaults(['placement'=>'before']);
    }

    public function getExtendedType()
    {
        return PasswordType::class;
    }
}
