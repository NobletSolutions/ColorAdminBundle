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
            'inverse_header'    => $this->template_config['header']['inverse'],
            'fixed_sidebar'     => $this->template_config['sidebar']['fixed'],
            'scrolling_sidebar' => $this->template_config['sidebar']['scrolling'],
            'grid_sidebar'      => $this->template_config['sidebar']['grid'],
            'gradient_sidebar'  => $this->template_config['sidebar']['gradient'],
            'use_pace'          => $this->template_config['use_pace']
        );
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('beginPanel', array($this, 'beginPanel'), array(
                    'pre_escape' => 'html',
                    'is_safe' => array('html'),
                    'needs_environment' => true
                )
            ),
            new \Twig_SimpleFunction('panelActions', array($this, 'panelActions'), array(
                    'pre_escape' => 'html',
                    'is_safe' => array('html'),
                    'needs_environment' => true
                )
            ),
            new \Twig_SimpleFunction('endPanel', array($this, 'endPanel'), array(
                    'pre_escape' => 'html',
                    'is_safe' => array('html'),
                    'needs_environment' => true
                )
            ),
        );
    }

    /**
     * @param \Twig_Environment $twig
     * @param $title
     * @param array $options
     * @param array $actions {{'title':string, 'icon':[repeat|minus|times|etc], 'click':javascript, 'uri':uri, 'style':[default|primary|warning|etc], 'link_new_window':false}, {'title':...}}
     * @return string
     */
    public function beginPanel(\Twig_Environment $twig, $title, $options = array(), $actions = array())
    {
        $default_options = array(
            'style'=>'inverse',//default, inverse, success, primary, warning, info, danger,
            'background' => 'default',
            'text' => 'default',
            'sortable' => false
        );

        $options = array_merge($default_options, $options);
        return $twig->render('NSColorAdminBundle::Twig/begin_panel.html.twig', array('title'=>$title, 'options'=>$options, 'actions'=>$actions));
    }

    /**
     * @param \Twig_Environment $twig
     * @param array $actions {{'title':string, 'icon':[repeat|minus|times|etc], 'click':javascript, 'uri':uri, 'style':[default|primary|warning|etc]}, {'title':...}}
     * @return string
     */
    public function panelActions(\Twig_Environment $twig, $actions = array())
    {
        return $twig->render('NSColorAdminBundle::Twig/panel_actions.html.twig', array('actions'=>$actions));
    }

    /**
     * @param \Twig_Environment $twig
     * @return string
     */
    public function endPanel(\Twig_Environment $twig)
    {
        return $twig->render('NSColorAdminBundle::Twig/end_panel.html.twig');
    }

    public function getName()
    {
        return 'nscoloradmin_template_extension';
    }
}