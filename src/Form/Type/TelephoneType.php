<?php
/**
 * Created by PhpStorm.
 * User: mark
 * Date: 22/03/19
 * Time: 2:22 PM
 */

namespace NS\ColorAdminBundle\Form\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TelephoneType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefined(['type', 'icon']);
        $resolver->setDefaults(['type'=>'tel', 'icon'=>'phone']);
    }

    public function getParent()
    {
        return TextType::class;
    }
}
