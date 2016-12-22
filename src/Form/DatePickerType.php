<?php

namespace NS\ColorAdminBundle\Form;

use NS\ColorAdminBundle\Service\DateFormatConverter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Description of DatePickerType
 *
 * @author gnat
 */
class DatePickerType extends AbstractType
{
    /**
     * @var DateFormatConverter
     */
    protected $converter;

    /**
     *
     * @param DateFormatConverter $converter
     */
    public function __construct(DateFormatConverter $converter = null)
    {
        $this->converter = ($converter) ?:new DateFormatConverter();
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'widget'   => 'single_text',
            'compound' => false,
            'inline' => false,
            'autoclose' => true,
            'format'   => $this->converter->getFormat(true),
        ]);
    }

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['attr']['data-provide']     = $options['inline'] ? 'datepicker-inline':'datepicker';

        $view->vars['attr']['data-date-format'] = strtolower($options['format']);
        $view->vars['attr']['placeholder']      = $options['format'];
        if($options['autoclose'])
        {
            $view->vars['attr']['data-date-autoclose'] = 'true';
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return DateType::class;
    }
}
