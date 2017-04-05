<?php
/**
 * Created by PhpStorm.
 * User: gnat
 * Date: 20/12/16
 * Time: 4:56 PM
 */

namespace NS\ColorAdminBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class TwigFormThemeCompilerPass implements CompilerPassInterface
{
    /**
     * @inheritDoc
     */
    public function process(ContainerBuilder $container)
    {
        if ($container->getParameter('ns_color_admin.auto_form_theme') && $container->hasParameter('twig.form.resources')) {
            $resources = $container->getParameter('twig.form.resources');
            if (!in_array('NSAceBundle:Form:fields.html.twig', $resources) && !in_array('NSColorAdminBundle:Form:fields.html.twig', $resources)) {
                $resources[] = 'NSColorAdminBundle:Form:fields.html.twig';

                $container->setParameter('twig.form.resources', $resources);
            }
        }
    }
}
