<?php

namespace NS\ColorAdminBundle\Twig;

use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;

class TemplateExtension extends AbstractExtension implements GlobalsInterface
{
    /** @var array */
    protected $template_config;

    public function __construct($template_config)
    {
        $this->template_config = $template_config;
    }

    public function getGlobals(): array
    {
        return [
            'color_admin' => [
                'theme' => $this->template_config['theme'],
                'theme_color' => $this->template_config['theme_color'],
                'fixed_header' => $this->template_config['header']['fixed'],
                'inverse_header' => $this->template_config['header']['inverse'],
                'fixed_sidebar' => $this->template_config['sidebar']['fixed'],
                'scrolling_sidebar' => $this->template_config['sidebar']['scrolling'],
                'grid_sidebar' => $this->template_config['sidebar']['grid'],
                'gradient_sidebar' => $this->template_config['sidebar']['gradient'],
                'use_pace' => $this->template_config['use_pace'],
                'draggable_panel' => $this->template_config['draggable_panel'],
                'pagination' => $this->template_config['pagination']
            ]
        ];
    }
}
