<?php

namespace NS\ColorAdminBundle\Form\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DateExtension extends AbstractTypeExtension
{
    public function finishView(FormView $view, FormInterface $form, array $options): void
    {
        if ($options['widget'] !== 'single_text') {
            $cclass = &$view->vars['attr']['class'];

            $cclass = $cclass ? $cclass . ' row pl-2 pr-2' : 'row pl-2 pr-2';

            foreach (['year', 'month', 'day'] as $field) {
                if ($form->has($field)) {
                    $class      = &$view->children[$field]->vars['attr']['class'];
                    $fieldClass = $options[$field . '_class'];
                    $class      = isset($class) ? $class . ' ' . $fieldClass : 'form-control mr-2 ' . $fieldClass;
                }
            }
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefined(['year_class', 'month_class', 'day_class']);
        $resolver->setDefaults(['year_class' => 'col-3', 'month_class' => 'col-3', 'day_class' => 'col-3']);
    }

    /**
     * @return string
     * Included for BC with SF3
     */
    public function getExtendedType(): string
    {
        return DateType::class;
    }

    public static function getExtendedTypes(): iterable
    {
        return [DateType::class];
    }
}
