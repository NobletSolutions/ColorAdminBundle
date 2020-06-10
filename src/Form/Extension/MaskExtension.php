<?php

namespace NS\ColorAdminBundle\Form\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MaskExtension extends AbstractTypeExtension
{
    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        $view->vars['attr']['data-mask']             = $options['mask'];
        $view->vars['attr']['data-mask-placeholder'] = $options['mask_placeholder'];
        $view->vars['attr']['data-mask-definitions'] = json_encode($options['mask_definitions']);

        if (!$view->vars['note']) {
            $view->vars['note'] = $options['mask'];
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefined(['mask', 'mask_placeholder', 'mask_definitions']);
        $resolver->setDefaults(['mask' => false, 'mask_placeholder' => false, 'mask_definitions' => false]);
        /*
         * mask_definitions allows you to override the default definitions with your own.  For example:
         * 'defintions' => array(
         *      'h' => '[A-Fa-f0-9]' //With this definition, "h" in mask will allow only hex characters: "99-hh-9999" = number number hex hex number number number number
         *      '~' => '[+-]' //"~" char represents a plus or minus sign: Temp: "~99" = +15, -22
         * )
         *
         * Default definitions:
         * "9": "[0-9]",
         *  a: "[A-Za-z]",
         *  "*": "[A-Za-z0-9]"
         *
         * Default placeholder: "_"
         */
    }

    /**
     * @return string
     * Included for BC with SF3
     */
    public function getExtendedType(): string
    {
        return TextType::class;
    }

    public static function getExtendedTypes(): array
    {
        return [TextType::class];
    }
}
