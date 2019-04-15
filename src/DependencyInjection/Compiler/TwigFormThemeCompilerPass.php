<?php

namespace NS\ColorAdminBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class TwigFormThemeCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        if ($container->getParameter('color_admin.auto_form_theme') && $container->hasParameter('twig.form.resources')) {
            $resources = $container->getParameter('twig.form.resources');
            if (!in_array('@ColorAdmin/Form/fields.html.twig', $resources, true)) {
                $resources[] = '@ColorAdmin/Form/fields.html.twig';

                $container->setParameter('twig.form.resources', $resources);
            }
        }
    }
}
