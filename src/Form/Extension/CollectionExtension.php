<?php

namespace NS\ColorAdminBundle\Form\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CollectionExtension extends AbstractTypeExtension
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefined(['add_button_label', 'add_button_icon']);
        $resolver->setDefaults([
            'add_button'       => true,
            'add_button_label' => 'Add',
            'add_button_icon'  => 'plus'
        ]);
    }

    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        $view->vars['add_button']       = $options['add_button'];
        $view->vars['add_button_label'] = $options['add_button_label'];
        $view->vars['add_button_icon']  = $options['add_button_icon'];
    }

    /**
     * @return string
     * Included for BC with SF3
     */
    public function getExtendedType(): string
    {
        return CollectionType::class;
    }

    public static function getExtendedTypes(): iterable
    {
        return [CollectionType::class];
    }
}
