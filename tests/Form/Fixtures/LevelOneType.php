<?php declare(strict_types=1);

namespace NS\ColorAdminBundle\Tests\Form\Fixtures;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class LevelOneType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('field1', TextType::class)
            ->add('field2', LevelTwoConfigType::class, ['hidden' => ['parent' => 'field1', 'value' => 'one']]);
    }
}
