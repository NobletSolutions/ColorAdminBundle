<?php

namespace NS\ColorAdminBundle\Form\Type;

use NS\ColorAdminBundle\Form\Mapper\DatetimeMapper;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DatetimepickerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date', DatepickerType::class, $options['datepicker_options'])
            ->add('time', TimepickerType::class, $options['timepicker_options'])
        ->setDataMapper(new DatetimeMapper());
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefined(['datepicker_options', 'timepicker_options']);
        $resolver->setDefaults(['datepicker_options'=>[], 'timepicker_options'=>[], 'compound'=>true]);
    }
}
