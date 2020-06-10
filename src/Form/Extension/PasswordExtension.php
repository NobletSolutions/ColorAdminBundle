<?php

namespace NS\ColorAdminBundle\Form\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PasswordExtension extends AbstractTypeExtension
{
    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        $view->vars['toggle']    = $options['toggle'] ?? false;
        $view->vars['placement'] = $options['placement'] ?? false;
        $view->vars['indicator'] = $options['indicator'] ?? false;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefined(['toggle', 'placement', 'indicator']);
        $resolver->setAllowedValues('toggle', [true, false]);
        $resolver->setAllowedValues('placement', ['before', 'after']);
        $resolver->setAllowedValues('indicator', [true, false]);
        $resolver->setDefaults(['placement' => 'before']);
    }

    /**
     * @return string
     * Included for BC with SF3
     */
    public function getExtendedType(): string
    {
        return PasswordType::class;
    }

    public static function getExtendedTypes(): iterable
    {
        return [PasswordType::class];
    }
}
