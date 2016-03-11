<?php

namespace NS\ColorAdminBundle\Twig;

class TemplateExtension extends \Twig_Extension
{
    protected $template_config;

    public function __construct($template_config)
    {
        $this->template_config = $template_config;
    }

    public function getGlobals()
    {
        return array(
            'theme'             => $this->template_config['theme'],
            'fixed_header'      => $this->template_config['header']['fixed'],
            'inverse_header'      => $this->template_config['header']['inverse'],
            'fixed_sidebar'     => $this->template_config['sidebar']['fixed'],
            'scrolling_sidebar' => $this->template_config['sidebar']['scrolling'],
            'grid_sidebar' => $this->template_config['sidebar']['grid'],
            'gradient_sidebar' => $this->template_config['sidebar']['gradient'],
            'use_pace'          => $this->template_config['use_pace']
        );
    }

    public function getName()
    {
        return 'nscoloradmin_template_extension';
    }
}