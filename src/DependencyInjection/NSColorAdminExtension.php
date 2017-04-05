<?php

namespace NS\ColorAdminBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * @link http://symfony.com/doc/current/cookbook/bundles/extension.html
 */
class NSColorAdminExtension extends Extension implements PrependExtensionInterface
{
    public function prepend(ContainerBuilder $container)
    {
        $bundles = $container->getParameter('kernel.bundles');

        if(isset($bundles['KnpPaginatorBundle']))
        {
            $curr_configs = $container->getExtensionConfig($this->getAlias());
            $curr_config = $this->processConfiguration(new Configuration(), $curr_configs);

            $config = ['template'=> ['pagination'=>$curr_config['templates']['pagination']['template']]];

            $container->prependExtensionConfig('knp_paginator', $config);
        }

        $container->prependExtensionConfig('liip_imagine', [
            'filter_sets' => [
                'admin_thumbnail' => [
                    'quality' => 50,
                    'filters' => [
                        'thumbnail' => [
                            'size' => [150, 150],
                            'mode' => 'inset'
                        ]
                    ]
                ],
            ]
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('ns_color_admin.templates', $config['templates']);
        $container->setParameter('ns_color_admin.use_knp_menu', $config['use_knp_menu']);
        $container->setParameter('ns_color_admin.auto_form_theme', $config['auto_form_theme']);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');
    }
}
