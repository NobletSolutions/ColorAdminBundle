<?php
/**
 * Created by PhpStorm.
 * User: gnat
 * Date: 22/12/16
 * Time: 11:50 AM
 */

namespace Tests\NS\ColorAdminBundle\DependencyInjection\Compiler;

use NS\ColorAdminBundle\DependencyInjection\Compiler\TwigFormThemeCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class TwigFormThemeCompilerPassTest extends \PHPUnit_Framework_TestCase
{
    public function test_has_no_setting()
    {
        $containerBuilder = $this->createMock(ContainerBuilder::class);
        $containerBuilder->expects($this->once())->method('hasParameter')->with('twig.form.resources')->willReturn(false);
        $containerBuilder->expects($this->never())->method('getParameter')->with('twig.form.resources')->willReturn(false);
        $containerBuilder->expects($this->never())->method('setParameter');

        $compiler = new TwigFormThemeCompilerPass();
        $compiler->process($containerBuilder);
    }

    public function test_already_configured()
    {
        $resources = [
            'form_div_layout.html.twig',
            'SonataCoreBundle:Form:datepicker.html.twig',
            'NSColorAdminBundle:Form:fields.html.twig'
        ];
        $containerBuilder = $this->createMock(ContainerBuilder::class);
        $containerBuilder->expects($this->once())->method('hasParameter')->with('twig.form.resources')->willReturn(true);
        $containerBuilder->expects($this->once())->method('getParameter')->with('twig.form.resources')->willReturn($resources);
        $containerBuilder->expects($this->never())->method('setParameter');

        $compiler = new TwigFormThemeCompilerPass();
        $compiler->process($containerBuilder);
    }

    public function test_not_configured_gets_added()
    {
        $resources = [
            'form_div_layout.html.twig',
            'SonataCoreBundle:Form:datepicker.html.twig',
        ];
        $containerBuilder = $this->createMock(ContainerBuilder::class);
        $containerBuilder->expects($this->once())->method('hasParameter')->with('twig.form.resources')->willReturn(true);
        $containerBuilder->expects($this->once())->method('getParameter')->with('twig.form.resources')->willReturn($resources);
        $containerBuilder->expects($this->once())->method('setParameter')->with('twig.form.resources',array_merge($resources,['NSColorAdminBundle:Form:fields.html.twig']));

        $compiler = new TwigFormThemeCompilerPass();
        $compiler->process($containerBuilder);
    }
}
