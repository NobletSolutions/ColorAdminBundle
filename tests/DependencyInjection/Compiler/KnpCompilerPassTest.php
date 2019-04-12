<?php
/**
 * Created by PhpStorm.
 * User: gnat
 * Date: 22/12/16
 * Time: 11:05 AM
 */

namespace Tests\NS\ColorAdminBundle\DependencyInjection\Compiler;

use NS\ColorAdminBundle\DependencyInjection\Compiler\KnpCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Tests\NS\ColorAdminBundle\BaseTestCase;

class KnpCompilerPassTest extends BaseTestCase
{
    public function test_dont_use_knp_menu()
    {
        $containerBuilder = $this->createMock(ContainerBuilder::class);
        $containerBuilder->expects($this->once())->method('getParameter')->with('color_admin.use_knp_menu')->willReturn(false);
        $hasMap = [
            ['knp_menu.renderer.twig.template', false],
            ['knp_paginator.template.pagination', false],
        ];

        $containerBuilder->method('hasParameter')->willReturnMap($hasMap);

        $compilerPass = new KnpCompilerPass();
        $compilerPass->process($containerBuilder);
    }

    public function test_has_no_knp_menu()
    {
        $containerBuilder = $this->createMock(ContainerBuilder::class);

        $containerBuilder->expects($this->once())->method('getParameter')->with('color_admin.use_knp_menu')->willReturn(true);
        $hasMap = [
            ['knp_menu.renderer.twig.template', false],
            ['knp_paginator.template.pagination', false],
        ];

        $containerBuilder->method('hasParameter')->willReturnMap($hasMap);
        $containerBuilder->expects($this->never())->method('setParameter')->with('knp_menu.renderer.twig.template', '@ColorAdmin/Menu/knp_menu.html.twig');

        $compilerPass = new KnpCompilerPass();
        $compilerPass->process($containerBuilder);
    }

    public function test_knp_has_non_default_parameter()
    {
        $containerBuilder = $this->createMock(ContainerBuilder::class);
        $map = [
            ['color_admin.use_knp_menu',true],
            ['knp_menu.renderer.twig.template','Non-default:Twig:knp_menu.html.twig']
        ];
        $containerBuilder->method('getParameter')->will($this->returnValueMap($map));

        $hasMap = [
            ['knp_menu.renderer.twig.template', true],
            ['knp_paginator.template.pagination', false],
        ];

        $containerBuilder->method('hasParameter')->willReturnMap($hasMap);
        $containerBuilder->expects($this->never())->method('setParameter')->with('knp_menu.renderer.twig.template', '@ColorAdmin/Menu/knp_menu.html.twig');

        $compilerPass = new KnpCompilerPass();
        $compilerPass->process($containerBuilder);
    }

    public function test_knp_set_twig_template()
    {
        $containerBuilder = $this->createMock(ContainerBuilder::class);
        $hasMap = [
            ['knp_menu.renderer.twig.template', true],
            ['knp_paginator.template.pagination', false],
        ];

        $containerBuilder->method('hasParameter')->willReturnMap($hasMap);

        $map = [
            ['color_admin.use_knp_menu',true],
            ['knp_menu.renderer.twig.template','KnpMenuBundle::menu.html.twig']
        ];
        $containerBuilder->method('getParameter')->will($this->returnValueMap($map));

        $containerBuilder->expects($this->once())->method('setParameter')->with('knp_menu.renderer.twig.template', '@ColorAdmin/Menu/knp_menu.html.twig');

        $compilerPass = new KnpCompilerPass();
        $compilerPass->process($containerBuilder);
    }

    public function test_has_no_knp_pager()
    {
        $containerBuilder = $this->createMock(ContainerBuilder::class);

        $hasMap = [
            ['knp_menu.renderer.twig.template', false],
            ['knp_paginator.template.pagination', false],
        ];

        $containerBuilder->method('hasParameter')->willReturnMap($hasMap);
        $containerBuilder->expects($this->once())->method('getParameter')->with('color_admin.use_knp_menu')->willReturn(false);

        $compilerPass = new KnpCompilerPass();
        $compilerPass->process($containerBuilder);
    }

    public function test_knp_pager_has_non_default_parameter()
    {
        $containerBuilder = $this->createMock(ContainerBuilder::class);
        $map = [
            ['color_admin.use_knp_menu', false],
            ['knp_paginator.template.pagination', 'Something:Non:default.html.twig'],
        ];
        $containerBuilder->method('getParameter')->will($this->returnValueMap($map));

        $hasMap = [
            ['knp_paginator.template.pagination', true],
        ];

        $containerBuilder->method('hasParameter')->willReturnMap($hasMap);
        $containerBuilder->expects($this->never())->method('setParameter')->with('knp_paginator.template.pagination', '@ColorAdmin/Pagination/pagination.html.twig');

        $compilerPass = new KnpCompilerPass();
        $compilerPass->process($containerBuilder);
    }

    public function test_knp_pager_set_twig_template()
    {
        $containerBuilder = $this->createMock(ContainerBuilder::class);
        $map = [
            ['color_admin.use_knp_menu', false],
            ['knp_paginator.template.pagination', 'KnpPaginatorBundle:Pagination:sliding.html.twig'],
        ];
        $containerBuilder->method('getParameter')->will($this->returnValueMap($map));

        $hasMap = [
            ['knp_paginator.template.pagination', true],
        ];

        $containerBuilder->method('hasParameter')->willReturnMap($hasMap);
        $containerBuilder->expects($this->once())->method('setParameter')->with('knp_paginator.template.pagination', '@ColorAdmin/Pagination/pagination.html.twig');

        $compilerPass = new KnpCompilerPass();
        $compilerPass->process($containerBuilder);
    }
}
