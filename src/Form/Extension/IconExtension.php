<?php


namespace NS\ColorAdminBundle\Form\Extension;


use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IconExtension extends AbstractTypeExtension
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefined(['icon', 'icon_position']);
        $resolver->setDefaults(['icon'=>false]);
        $resolver->setAllowedValues('icon_position', ['left', 'right']);
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['icon'] = $options['icon'];
        $view->vars['icon_position'] = isset($options['icon_position']) ? $options['icon_position'] : false;
    }

    public function getExtendedType()
    {
        return TextType::class;
    }
}
