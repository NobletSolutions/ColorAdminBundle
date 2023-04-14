<?php declare(strict_types=1);

namespace NS\ColorAdminBundle\Tests\Form\Fixtures;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class LevelTwoConfigType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('l2Field1', ChoiceType::class,['choices' => ['c1'=>'c1','c2'=>'c2'],'placeholder'=>'place'])
            ->add('l2Field2', TextType::class, ['hidden'=>['parent'=>'l2Field1','value'=>'c2']])
            ->adD('l2Field3', EntityType::class);
    }
}
