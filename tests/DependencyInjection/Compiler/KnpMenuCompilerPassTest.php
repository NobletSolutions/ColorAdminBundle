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

    public function test_has_no_knp_menu()
    {
        $containerBuilder = $this->createMock(ContainerBuilder::class);

        $containerBuilder->expects($this->once())->method('getParameter')->with('ns_color_admin.use_knp_menu')->willReturn(true);
        $containerBuilder->expects($this->once())->method('hasParameter')->with('knp_menu.renderer.twig.template')->willReturn(false);
        $containerBuilder->expects($this->never())->method('setParameter')->with('knp_menu.renderer.twig.template', 'NSColorAdminBundle:Menu:knp_menu.html.twig');

        $compilerPass = new KnpMenuCompilerPass();
        $compilerPass->process($containerBuilder);
    }

    public function test_knp_has_non_default_parameter()
    {
        $containerBuilder = $this->createMock(ContainerBuilder::class);
        $map = [
            ['ns_color_admin.use_knp_menu',true],
            ['knp_menu.renderer.twig.template','Non-default:Twig:knp_menu.html.twig']
        ];
        $containerBuilder->method('getParameter')->will($this->returnValueMap($map));
        $containerBuilder->expects($this->once())->method('hasParameter')->with('knp_menu.renderer.twig.template')->willReturn(true);
        $containerBuilder->expects($this->never())->method('setParameter')->with('knp_menu.renderer.twig.template', 'NSColorAdminBundle:Menu:knp_menu.html.twig');

        $compilerPass = new KnpMenuCompilerPass();
        $compilerPass->process($containerBuilder);
    }

    public function test_knp_set_twig_template()
    {
        $containerBuilder = $this->createMock(ContainerBuilder::class);
        $map = [
            ['ns_color_admin.use_knp_menu',true],
            ['knp_menu.renderer.twig.template','KnpMenuBundle::menu.html.twig']
        ];
        $containerBuilder->method('getParameter')->will($this->returnValueMap($map));
        $containerBuilder->expects($this->once())->method('hasParameter')->with('knp_menu.renderer.twig.template')->willReturn(true);
        $containerBuilder->expects($this->once())->method('setParameter')->with('knp_menu.renderer.twig.template', 'NSColorAdminBundle:Menu:knp_menu.html.twig');

        $compilerPass = new KnpMenuCompilerPass();
        $compilerPass->process($containerBuilder);
    }
}
