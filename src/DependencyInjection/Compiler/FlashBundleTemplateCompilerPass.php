<?php
/**
 * Created by PhpStorm.
 * User: gnat
 * Date: 05/01/17
 * Time: 4:21 PM
 */

namespace NS\ColorAdminBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class FlashBundleTemplateCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if ($container->hasParameter('ns_flash.template') && $container->getParameter('ns_flash.template') == 'NSFlashBundle:Messages:index.html.twig') {
            $container->setParameter('ns_flash.template', '@ColorAdmin/Flash/flash_messages.html.twig');
        }
    }
}
