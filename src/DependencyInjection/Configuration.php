<?php

namespace NS\ColorAdminBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/configuration.html}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('ns_color_admin');

        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.

        $rootNode->children()
            ->scalarNode('use_knp_menu')->defaultFalse()->end()
            ->arrayNode('templates')
                ->addDefaultsIfNotSet()
                ->children()
                    ->enumNode('theme')
                        ->values(array('default', 'red', 'blue', 'purple', 'orange', 'black'))
                        ->defaultValue('default')
                        ->end()
                    ->arrayNode('pagination')
                        ->addDefaultsIfNotSet()
                        ->children()
                            ->scalarNode('template')->defaultValue('NSColorAdminBundle:Pagination:pagination.html.twig')->end()
                            ->scalarNode('wrapper_class')->defaultValue('pagination-md')->end()
                            ->booleanNode('labels')->defaultFalse()->end()
                        ->end()
                    ->end()
                    ->booleanNode('use_pace')->defaultFalse()->end()
                    ->arrayNode('header')
                        ->addDefaultsIfNotSet()
                        ->children()
                            ->booleanNode('fixed')->defaultTrue()->end()
                            ->booleanNode('inverse')->defaultFalse()->end()
                        ->end()
                    ->end()
                    ->arrayNode('sidebar')
                        ->addDefaultsIfNotSet()
                        ->children()
                            ->booleanNode('fixed')->defaultTrue()->end()
                            ->booleanNode('scrolling')->defaultTrue()->end()
                            ->booleanNode('grid')->defaultFalse()->end()
                            ->booleanNode('gradient')->defaultFalse()->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ->end();

        return $treeBuilder;
    }
}