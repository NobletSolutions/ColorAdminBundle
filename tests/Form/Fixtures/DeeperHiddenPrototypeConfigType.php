<?php declare(strict_types=1);

namespace NS\ColorAdminBundle\Tests\Form\Fixtures;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;

class DeeperHiddenPrototypeConfigType extends AbstractType
{
    /**
     * @inheritDoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('something',CollectionType::class,[
            'entry_type' => UsingHiddenConfigType::class,
            'allow_add' => true,
            'prototype' => true,
        ]);
    }
}
