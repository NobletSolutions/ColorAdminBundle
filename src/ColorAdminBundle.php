<?php

namespace NS\ColorAdminBundle;

use NS\ColorAdminBundle\DependencyInjection\Compiler\FlashBundleTemplateCompilerPass;
use NS\ColorAdminBundle\DependencyInjection\Compiler\KnpCompilerPass;
use NS\ColorAdminBundle\DependencyInjection\Compiler\TwigFormThemeCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class ColorAdminBundle extends Bundle
{
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);
        $container->addCompilerPass(new TwigFormThemeCompilerPass());
        $container->addCompilerPass(new KnpCompilerPass());
        $container->addCompilerPass(new FlashBundleTemplateCompilerPass());
    }
}
