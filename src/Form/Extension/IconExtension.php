<?php

namespace NS\ColorAdminBundle\Form\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IconExtension extends AbstractTypeExtension
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefined(['icon_position']);
        $resolver->setDefaults(['icon' => false]);
        $resolver->setAllowedValues('icon_position', ['left', 'right']);
    }

    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        if ($options['icon'] !== false) {
            $view->vars['icon']          = $options['icon'];
            $view->vars['icon_position'] = $options['icon_position'] ?? false;
        }
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
