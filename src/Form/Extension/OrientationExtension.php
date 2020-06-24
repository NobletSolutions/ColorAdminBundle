<?php

namespace NS\ColorAdminBundle\Form\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrientationExtension extends AbstractTypeExtension
{
    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        $view->vars['vertical'] = $options['vertical'];
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefined(['vertical']);
        $resolver->setDefaults(['vertical' => false]);
        $resolver->setAllowedValues('vertical', [true, false]);
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
