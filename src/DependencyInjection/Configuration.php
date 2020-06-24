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
        /**
         * Instantiating a new TreeBuilder without a constructor arg is deprecated in SF4 and removed in SF5
         */
        if(method_exists(TreeBuilder::class, '__construct'))
        {
            $treeBuilder = new TreeBuilder('color_admin');
            $rootNode = $treeBuilder->getRootNode();
        }
        /**
         * Included for backward-compatibility with SF3
         */
        else
        {
            $treeBuilder = new TreeBuilder();
            $rootNode = $treeBuilder->root('color_admin');
        }

        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.

        $rootNode->children()
            ->booleanNode('use_knp_menu')->defaultTrue()->end()
            ->booleanNode('auto_form_theme')->defaultTrue()->end()
            ->arrayNode('templates')
                ->addDefaultsIfNotSet()
                ->children()
                    ->enumNode('theme')
                    ->values(['apple', 'default', 'facebook', 'material', 'transparent'])
                    ->defaultValue('default')
                    ->end()
                    ->enumNode('theme_color')
                        ->values(['aqua', 'black', 'blue', 'default', 'green', 'indigo', 'lime', 'orange', 'pink', 'purple', 'red', 'yellow'])
                        ->defaultValue('default')
                        ->end()
                    ->arrayNode('pagination')
                        ->addDefaultsIfNotSet()
                        ->children()
                            ->scalarNode('template')->defaultValue('@ColorAdmin/Pagination/pagination.html.twig')->end()
                            ->scalarNode('wrapper_class')->defaultValue('pagination-md')->end()
                            ->booleanNode('labels')->defaultFalse()->end()
                        ->end()
                    ->end()
                    ->booleanNode('use_pace')->defaultFalse()->end()
                    ->booleanNode('draggable_panel')->defaultFalse()->end()
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
