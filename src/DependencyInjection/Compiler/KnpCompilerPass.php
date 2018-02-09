<?php
/**
 * Created by PhpStorm.
 * User: gnat
 * Date: 22/12/16
 * Time: 10:54 AM
 */

namespace NS\ColorAdminBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class KnpCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if ($container->getParameter('ns_color_admin.use_knp_menu') && $container->hasParameter('knp_menu.renderer.twig.template') && in_array($container->getParameter('knp_menu.renderer.twig.template'),['KnpMenuBundle::menu.html.twig','@KnpMenu/menu.html.twig'])) {
            $container->setParameter('knp_menu.renderer.twig.template', 'NSColorAdminBundle:Menu:knp_menu.html.twig');
        }

        if ($container->hasParameter('knp_paginator.template.pagination') && in_array($container->getParameter('knp_paginator.template.pagination'),['KnpPaginatorBundle:Pagination:sliding.html.twig','@KnpPaginator/Pagination/sliding.html.twig'])) {
            $container->setParameter('knp_paginator.template.pagination', 'NSColorAdminBundle:Pagination:pagination.html.twig');
        }
    }
}
