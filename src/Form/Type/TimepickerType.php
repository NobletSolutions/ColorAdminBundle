<?php

namespace NS\ColorAdminBundle\Form\Type;

use NS\ColorAdminBundle\Form\Transformer\TimeTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TimepickerType extends AbstractType
{
    protected static $fields = ['template', 'maxHours', 'snapToStep', 'minuteStep', 'showSeconds', 'secondStep', 'showMeridian', 'showInputs', 'disableFocus', 'disableMousewheel', 'modalBackdrop', 'appendWidgetTo', 'explicitMode'];

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefined(TimepickerType::$fields);
        $resolver->setDefined('icons');
        $resolver->setAllowedValues('template', ['dropdown', 'modal', false]);
        $resolver->setAllowedValues('maxHours', range(1, 24));
        $resolver->setAllowedValues('snapToStep', [true, false]);
        $resolver->setAllowedValues('showSeconds', [true, false]);
        $resolver->setAllowedValues('showMeridian', [true, false]);
        $resolver->setAllowedValues('showInputs', [true, false]);
        $resolver->setAllowedValues('disableFocus', [true, false]);
        $resolver->setAllowedValues('disableMousewheel', [true, false]);
        $resolver->setAllowedValues('modalBackdrop', [true, false]);
        $resolver->setAllowedValues('explicitMode', [true, false]);
        $resolver->setDefault('template', 'dropdown');
        $resolver->setDefault('maxHours', 24);
        $resolver->setDefault('snapToStep', false);
        $resolver->setDefault('minuteStep', 1);
        $resolver->setDefault('secondStep', 1);
        $resolver->setDefault('showMeridian', false);
        $resolver->setDefault('showSeconds', false);
        $resolver->setDefault('showInputs', true);
        $resolver->setDefault('appendWidgetTo', 'body');
        $resolver->setDefault('explicitMode', true);
        $resolver->setDefault('disableMousewheel', false);
        $resolver->setDefault('modalBackdrop', false);
        $resolver->setDefault('disableFocus', false);
        $resolver->setDefault('html5', false);
        $resolver->setDefault('widget', 'single_text');
        $resolver->setDefault('icons', ['up' => 'fa fa-chevron-up', 'down' => 'fa fa-chevron-down']);
    }

    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        $view->vars['attr']['data-template'] = (string) $options['template'];
        $view->vars['attr']['data-max-hours'] = (string) $options['maxHours'];
        $view->vars['attr']['data-snap-top-step'] = (string) $options['snapToStep'];
        $view->vars['attr']['data-minute-step'] = (string) $options['minuteStep'];
        $view->vars['attr']['data-second-step'] = (string) $options['secondStep'];
        $view->vars['attr']['data-show-meridian'] = (string) $options['showMeridian'];
        $view->vars['attr']['data-show-inputs'] = (string) $options['showInputs'];
        $view->vars['attr']['data-disable-focus'] = (string) $options['disableFocus'];
        $view->vars['attr']['data-disable-mousewheel'] = (string) $options['disableMousewheel'];
        $view->vars['attr']['data-append-widget-to'] = (string) $options['appendWidgetTo'];
        $view->vars['attr']['data-modal-backdrop'] = (string) $options['modalBackdrop'];
        $view->vars['attr']['data-explicit-mode'] = (string) $options['explicitMode'];

        $view->vars['attr']['data-icons'] = json_encode($options['icons']);

        $view->vars['attr']['data-provide'] = 'timepicker';
    }

    public function getParent(): string
    {
        return TimeType::class;
    }
}
