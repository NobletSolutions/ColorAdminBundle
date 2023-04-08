<?php

namespace NS\ColorAdminBundle\Form\Type;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;

trait Select2Input
{
    protected RouterInterface $router;

    /**
     * @required
     */
    public function setRouter(RouterInterface $router): void
    {
        $this->router = $router;
    }

    protected array $params = [
        'url',
        'method',
        'allowClear',
        'closeOnSelect',
        'debug',
        'maximumInputLength',
        'maximumSelectionLength',
        'minimumInputLength',
        'minimumResultsForSearch',
        'initCallback',
        'ajaxDelay',
        'tags',
        'escapeAllMarkup',
        'append',
        'placeholder',
    ];

    protected function getOptions(FormEvent $event): array
    {
        $choices = [];
        $form    = $event->getForm();
        $data    = $event->getData();
        $options = $form->getConfig()->getOptions();

        if (is_array($data)) {
            foreach ($data as $choice) {
                $choices[$choice] = $choice;
            }
        } else {
            $choices[$data] = $data;
        }

        $options['choices'] = $choices;

        return $options;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefined(array_merge($this->params, ['transformer', 'route', 'routeParams', 'config', 'class', 'language'/*, 'property', 'transformer'*/]));
        $resolver->setDefault('minimumInputLength', 2);
        $resolver->setDefault('escapeAllMarkup', true);
        $resolver->setAllowedTypes('allowClear', ['boolean']);
        $resolver->setAllowedTypes('closeOnSelect', ['boolean']);
        $resolver->setAllowedTypes('debug', ['boolean']);
        $resolver->setAllowedTypes('maximumInputLength', ['integer']);
        $resolver->setAllowedTypes('maximumSelectionLength', ['integer']);
        $resolver->setAllowedTypes('minimumInputLength', ['integer']);
        $resolver->setAllowedTypes('minimumResultsForSearch', ['integer']);
        $resolver->setAllowedTypes('language', ['array']);
    }

    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        if (isset($options['route'])) {
            $url            = $this->router->generate($options['route'], $options['routeParams'] ?? []);
            $options['url'] = $url;
        }

        foreach ($this->params as $param) {
            if (isset($options[$param]) && $options[$param]) {
                $pname                             = implode('-', preg_split('/(?=[A-Z])/', $param, -1, PREG_SPLIT_NO_EMPTY));
                $view->vars['attr']["data-$pname"] = is_array($options[$param]) ? json_encode($options[$param]) : $options[$param];
            }
        }

        if (isset($options['config']) && $options['config']) {
            foreach ($options['config'] as $key => $param) {
                $pname                             = implode('-', preg_split('/(?=[A-Z])/', $key, -1, PREG_SPLIT_NO_EMPTY));
                $view->vars['attr']["data-$pname"] = $param;
            }
        }

        if (isset($options['url']) && !$view->vars['value']) {
            $view->vars['choices'] = [];//Don't pre-populate the dropdown if we're loading results via ajax, but leave the existing value if it was added by the presetdata event
        }

        if (isset($options['language']) && !empty($options['language'])) {
            $view->vars['attr']['data-language-config'] = json_encode($options['language']);
        }

        $view->vars['attr']['class'] = isset($view->vars['attr']['class']) ? $view->vars['attr']['class'] . ' nsSelect2' : 'nsSelect2';
    }
}
