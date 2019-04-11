<?php
/**
 * Created by PhpStorm.
 * User: mark
 * Date: 22/03/19
 * Time: 3:17 PM
 */

namespace NS\ColorAdminBundle\Form\Type;


use NS\ColorAdminBundle\Service\DateFormatConverter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DatepickerType extends AbstractType
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

    public function configureOptions(OptionsResolver $resolver)
    {
        // For start_date, pass the date, or true to use today
        $resolver->setDefined(['start_date', 'date_format']);
        $resolver->setDefaults([
            'format'   => $this->converter->getFormat(true)
        ]);
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['start_date'] = isset($options['start_date']) ? $options['start_date'] : false;
        $view->vars['date_format'] = isset($options['date_format']) ? strtolower($options['date_format']) : 'yyyy-mm-dd';
    }

    public function getParent()
    {
        return TextType::class;
    }
}
