<?php

namespace NS\ColorAdminBundle\Form\Type;

use NS\ColorAdminBundle\Service\DateFormatConverter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DatepickerType extends AbstractType
{
    /** @var DateFormatConverter */
    protected $converter;

    public function __construct(DateFormatConverter $converter = null)
    {
        $this->converter = ($converter) ?:new DateFormatConverter();
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        // For start_date, pass the date, or true to use today
        $resolver->setDefined(['start_date', 'today_highlight', 'autoclose']);
        $format = $this->converter->getFormat(true);
        $resolver->setDefaults([
            'widget'   => 'single_text',
            'compound' => false,
            'inline' => false,
            'format'   => $this->converter->getFormat(true),
            'today_highlight' => true,
            'autoclose' => true,
            'html5' => false
        ]);
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['type'] = 'test';
        $view->vars['start_date'] = isset($options['start_date']) ? $options['start_date'] : false;
        $view->vars['attr']['data-date-format'] = strtolower($options['format']);
        $view->vars['attr']['data-provide'] = 'datepicker';
        $view->vars['attr']['data-date-today-highlight'] = $options['today_highlight'];
        $view->vars['attr']['data-date-autoclose'] = $options['autoclose'];
    }

    public function getParent()
    {
        return DateType::class;
    }
}
