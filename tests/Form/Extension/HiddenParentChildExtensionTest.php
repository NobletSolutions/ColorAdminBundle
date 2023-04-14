<?php declare(strict_types=1);

namespace Tests\NS\ColorAdminBundle\Form\Extension;

use NS\ColorAdminBundle\Form\Extension\HiddenParentChildExtension;
use NS\ColorAdminBundle\Tests\Form\Fixtures\DeeperHiddenPrototypeConfigType;
use NS\ColorAdminBundle\Tests\Form\Fixtures\DoubleCollectionDeeperPrototypeType;
use NS\ColorAdminBundle\Tests\Form\Fixtures\LevelOneType;
use NS\ColorAdminBundle\Tests\Form\Fixtures\UsingHiddenConfigType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;

class HiddenParentChildExtensionTest extends TypeTestCase
{
    public function testNoConfig(): void
    {
        $builder = $this->factory->createBuilder();
        $builder
            ->add('text', TextType::class)
            ->add('number', NumberType::class)
            ->add('textarea', TextareaType::class);

        $form = $builder->getForm();
        $view = $form->createView();
        $this->assertArrayNotHasKey('data-context-config', $view->vars['attr']);
    }

    public function testHasConfig(): void
    {
        $builder = $this->factory->createBuilder();
        $builder
            ->add('text', TextType::class)
            ->add('number', NumberType::class)
            ->add('textarea', TextareaType::class, ['hidden' => ['parent' => 'number', 'value' => 1]]);

        $form = $builder->getForm();
        $view = $form->createView();
        $this->assertArrayHasKey('data-context-config', $view->vars['attr']);
    }

    public function testHiddenIsEmpty(): void
    {
        $this->expectException(InvalidOptionsException::class);
        $builder = $this->factory->createBuilder();
        $builder
            ->add('text', TextType::class)
            ->add('number', NumberType::class)
            ->add('textarea', TextareaType::class, ['hidden' => []]);

        $builder->getForm()->createView();
    }

    public function testHiddenIsNotArray(): void
    {
        $this->expectException(InvalidOptionsException::class);
        $builder = $this->factory->createBuilder();
        $builder
            ->add('text', TextType::class)
            ->add('number', NumberType::class)
            ->add('textarea', TextareaType::class, ['hidden' => 'number']);

        $builder->getForm()->createView();
    }

    public function testHiddenHasNoValueForParent(): void
    {
        $this->expectException(InvalidOptionsException::class);
        $builder = $this->factory->createBuilder();
        $builder
            ->add('text', TextType::class)
            ->add('number', NumberType::class)
            ->add('textarea', TextareaType::class, ['hidden' => ['parent'=>'number','values'=>2]]);

        $builder->getForm()->createView();
    }

    public function testPrototypeOneLevelConfig(): void
    {
        $builder = $this->factory->createBuilder();
        $builder->add('stuff',CollectionType::class,[
            'allow_add'=>true,
            'prototype'=>true,
            'entry_type' => UsingHiddenConfigType::class
        ]);

        $form = $builder->getForm();
        $view = $form->createView();
        $this->assertArrayHasKey('data-context-prototypes', $view->vars['attr']);
        $this->assertEquals('{"form[stuff][__name__][text]":[{"display":"#form_stuff___name___text_something","values":"value"}],"form[stuff][__name__][number]":[{"display":["form[stuff][__name__][textarea]"],"values":[1]}]}', $view->vars['attr']['data-context-prototypes']);
    }

    public function testPrototypeDeeperLevelConfig(): void
    {
        $builder = $this->factory->createBuilder();
        $builder->add('deeper',DeeperHiddenPrototypeConfigType::class);

        $form = $builder->getForm();
        $view = $form->createView();
        $this->assertArrayHasKey('data-context-prototypes', $view->vars['attr']);
        $this->assertEquals('{"form[deeper][something][__name__][text]":[{"display":"#form_deeper_something___name___text_something","values":"value"}],"form[deeper][something][__name__][number]":[{"display":["form[deeper][something][__name__][textarea]"],"values":[1]}]}',$view->vars['attr']['data-context-prototypes']);
    }

    /**
     * @group multipleDeep
     */
    public function testMultipleDeepConfigs(): void
    {
        $builder = $this->factory->createBuilder();
        $builder->add('deeper',DoubleCollectionDeeperPrototypeType::class);

        $form = $builder->getForm();
        $form->setData(['deeper'=>[
            'somethingOne' => [
                ['text' => 'some text', 'number' => 1, 'textarea' => 'blah blah blah'],
                ['text' => 'some text', 'number' => 1, 'textarea' => 'blah blah blah'],
            ],
            'somethingTwo' => [
                ['text' => 'some text', 'number' => 1, 'textarea' => 'blah blah blah'],
                ['text' => 'some text', 'number' => 1, 'textarea' => 'blah blah blah'],
            ],
        ]]);
        $view = $form->createView();
        $expected = '{"form[deeper][somethingOne][0][text]":[{"display":"#form_deeper_somethingOne_0_text_something","values":"value"}],"form[deeper][somethingOne][0][number]":[{"display":["form[deeper][somethingOne][0][textarea]"],"values":[1]}],"form[deeper][somethingOne][1][text]":[{"display":"#form_deeper_somethingOne_1_text_something","values":"value"}],"form[deeper][somethingOne][1][number]":[{"display":["form[deeper][somethingOne][1][textarea]"],"values":[1]}],"form[deeper][somethingTwo][0][text]":[{"display":"#form_deeper_somethingTwo_0_text_something","values":"value"}],"form[deeper][somethingTwo][0][number]":[{"display":["form[deeper][somethingTwo][0][textarea]"],"values":[1]}],"form[deeper][somethingTwo][1][text]":[{"display":"#form_deeper_somethingTwo_1_text_something","values":"value"}],"form[deeper][somethingTwo][1][number]":[{"display":["form[deeper][somethingTwo][1][textarea]"],"values":[1]}]}';
        $this->assertEquals($expected,$view->vars['attr']['data-context-config']);
    }

    public function testChildrenAreHiddenAsWell(): void
    {
        $builder = $this->factory->createBuilder();
        $builder->add('deeper',LevelOneType::class);

        $form = $builder->getForm();
        $view = $form->createView();
        $expected = '{"form[deeper][field2][l2Field1]":[{"display":["form[deeper][field2][l2Field2]"],"values":["c2"]}],"form[deeper][field1]":[{"display":["form[deeper][field2]","form[deeper][field2][l2Field1]","form[deeper][field2][l2Field3]","form[deeper][field2][l2Field3][id]","form[deeper][field2][l2Field3][name]"],"values":["one"]}]}';
        $this->assertEquals($expected, $view->vars['attr']['data-context-config']);
    }

    protected function getExtensions(): array
    {
        $hiddenParent = new HiddenParentChildExtension();
        return [new PreloadedExtension([], [$hiddenParent->getExtendedType() => [$hiddenParent]])];
    }
}
