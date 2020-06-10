<?php

namespace NS\ColorAdminBundle\Form\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DetailsExtension extends AbstractTypeExtension
{
    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        $view->vars['note']          = $options['note'];
        $view->vars['wrapper_class'] = $options['wrapper_class'];
        $view->vars['state']         = $options['state'] ?? false;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefined(['note', 'wrapper_class', 'state']);
        $resolver->setDefaults(['note' => false, 'wrapper_class' => false]);
        $resolver->setAllowedValues('state', ['valid', 'invalid']);
    }

    /**
     * @return string
     * Included for BC with SF3
     */
    public function getExtendedType(): string
    {
        return FormType::class;
    }

    public static function getExtendedTypes(): array
    {
        return [FormType::class];
    }
}
