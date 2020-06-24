<?php

namespace NS\ColorAdminBundle\Form\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FileExtension extends AbstractTypeExtension
{
    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        $view->vars['picker_label'] = $options['picker_label'];
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefined(['picker_label']);
        $resolver->setDefault('picker_label', 'Choose...');
    }

    /**
     * @return string
     * Included for BC with SF3
     */
    public function getExtendedType(): string
    {
        return FileType::class;
    }

    public static function getExtendedTypes(): iterable
    {
        return [FileType::class];
    }
}
