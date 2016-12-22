<?php

namespace NS\ColorAdminBundle\Twig;

class TemplateExtension extends \Twig_Extension implements \Twig_Extension_GlobalsInterface
{
    protected $template_config;

    public function __construct($template_config)
    {
        $this->template_config = $template_config;
    }

    public function getGlobals()
    {
        return [
            'theme'             => $this->template_config['theme'],
            'fixed_header'      => $this->template_config['header']['fixed'],
            'inverse_header'    => $this->template_config['header']['inverse'],
            'fixed_sidebar'     => $this->template_config['sidebar']['fixed'],
            'scrolling_sidebar' => $this->template_config['sidebar']['scrolling'],
            'grid_sidebar'      => $this->template_config['sidebar']['grid'],
            'gradient_sidebar'  => $this->template_config['sidebar']['gradient'],
            'use_pace'          => $this->template_config['use_pace'],
            'pagination'        => $this->template_config['pagination']
        ];
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('beginPanel', [$this, 'beginPanel'], [
                    'pre_escape' => 'html',
                    'is_safe' => ['html'],
                    'needs_environment' => true
                ]
            ),
            new \Twig_SimpleFunction('endPanel', [$this, 'endPanel'], [
                    'pre_escape' => 'html',
                    'is_safe' => ['html'],
                    'needs_environment' => true
                ]
            ),
            new \Twig_SimpleFunction('button', [$this, 'button'], [
                    'pre_escape' => 'html',
                    'is_safe' => ['html'],
                    'needs_environment' => true
                ]
            ),
            new \Twig_SimpleFunction('buttonDropdown', [$this, 'buttonDropdown'], [
                    'pre_escape' => 'html',
                    'is_safe' => ['html'],
                    'needs_environment' => true
                ]
            ),
            new \Twig_SimpleFunction('buttonDropdownItems', [$this, 'buttonDropdownItems'], [
                    'pre_escape' => 'html',
                    'is_safe' => ['html'],
                    'needs_environment' => true
                ]
            )
        ];
    }

    /**
     * @param \Twig_Environment $twig
     * @param $title
     * @param array $options
     * @param string
     * @return string
     */
    public function beginPanel(\Twig_Environment $twig, $title, $options = [], $actions = '')
    {
        $default_options = [
            'style'=>'inverse',//default, inverse, success, primary, warning, info, danger,
            'background' => 'default',
            'text' => 'default',
            'sortable' => false
        ];

        $options = array_merge($default_options, $options);
        return $twig->render('NSColorAdminBundle:Twig:begin_panel.html.twig', ['title'=>$title, 'options'=>$options, 'actions'=>$actions]);
    }

    /**
     * @param \Twig_Environment $twig
     * @return string
     */
    public function endPanel(\Twig_Environment $twig)
    {
        return $twig->render('NSColorAdminBundle:Twig:end_panel.html.twig');
    }

    /**
     * @param \Twig_Environment $twig
     * @param $text
     * @param $href
     * @param string $class
     * @return string
     * @internal param $style
     * @internal param $size
     */
    public function button(\Twig_Environment $twig, $text, $href, $class = 'btn-default')
    {
        return $twig->render('NSColorAdminBundle:Twig:button.html.twig', ['text'=>$text, 'href'=>$href, 'class'=>$class]);
    }

    /**
     * @param \Twig_Environment $twig
     * @param $text
     * @param $default
     * @param array $items
     * @param string $group_class
     * @param string $default_class
     * @return string
     * @internal param string $style
     * @internal param string $size
     */
    public function buttonDropdown(\Twig_Environment $twig, $text, $default, $items = [], $group_class = '', $default_class = 'btn-primary')
    {
        return $twig->render('NSColorAdminBundle:Twig:button_dropdown.html.twig', ['text'=>$text, 'default'=>$default, 'items'=>$items, 'group_class'=>$group_class, 'default_class'=>$default_class]);
    }

    /**
     * @param \Twig_Environment $twig
     * @param array $items
     * @return string
     */
    public function buttonDropdownItems(\Twig_Environment $twig, $items = [])
    {
        return $twig->render('NSColorAdminBundle:Twig:button_dropdown_items.html.twig', ['items'=>$items]);
    }

    public function getName()
    {
        return 'nscoloradmin_template_extension';
    }
}
