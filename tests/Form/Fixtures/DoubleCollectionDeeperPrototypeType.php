<?php declare(strict_types=1);

namespace NS\ColorAdminBundle\Tests\Form\Fixtures;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;

class DoubleCollectionDeeperPrototypeType extends AbstractType
{
    /**
     * @inheritDoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('somethingOne',CollectionType::class,[
                'entry_type' => UsingHiddenConfigType::class,
                'allow_add' => true,
                'prototype' => true,
            ])
            ->add('somethingTwo',CollectionType::class,[
                'entry_type' => UsingHiddenConfigType::class,
                'allow_add' => true,
                'prototype' => true,
            ]);
    }

}
