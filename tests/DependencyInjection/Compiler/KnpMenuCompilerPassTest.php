<?php
/**
 * Created by PhpStorm.
 * User: gnat
 * Date: 22/12/16
 * Time: 11:05 AM
 */

namespace Tests\NS\ColorAdminBundle\DependencyInjection\Compiler;

use NS\ColorAdminBundle\DependencyInjection\Compiler\KnpMenuCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class KnpMenuCompilerPassTest extends \PHPUnit_Framework_TestCase
{
    public function test_dont_use_knp_menu()
    {
        $containerBuilder = $this->createMock(ContainerBuilder::class);
        $containerBuilder->expects($this->once())->method('getParameter')->with('ns_color_admin.use_knp_menu')->willReturn(false);
        $containerBuilder->expects($this->never())->method('setParameter')->with('knp_menu.renderer.twig.template', 'NSColorAdminBundle:Menu:knp_menu.html.twig');

        $compilerPass = new KnpMenuCompilerPass();
        $compilerPass->process($containerBuilder);
    }
}
