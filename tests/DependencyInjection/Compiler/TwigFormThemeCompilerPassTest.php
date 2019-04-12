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
use Tests\NS\ColorAdminBundle\BaseTestCase;

class TwigFormThemeCompilerPassTest extends BaseTestCase
{
    public function test_has_no_setting()
    {
        $containerBuilder = $this->createMock(ContainerBuilder::class);
        $containerBuilder->method('getParameter')->will($this->returnCallback(function($arg){
            return $arg == 'color_admin.auto_form_theme';
        }));

        $containerBuilder->expects($this->once())->method('hasParameter')->with('twig.form.resources')->willReturn(false);
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
        $containerBuilder->method('getParameter')->will($this->returnCallback(function($arg) use($resources) {
            if($arg == 'color_admin.auto_form_theme') { return true; }
            if($arg == 'twig.form.resources') { return $resources; }
        }));

        $containerBuilder->expects($this->once())->method('hasParameter')->with('twig.form.resources')->willReturn(true);
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
        $containerBuilder->method('getParameter')->will($this->returnCallback(function($arg) use($resources) {
            if($arg == 'color_admin.auto_form_theme') { return true; }
            if($arg == 'twig.form.resources') { return $resources; }
        }));

        $containerBuilder->expects($this->once())->method('hasParameter')->with('twig.form.resources')->willReturn(true);
        $containerBuilder->expects($this->once())->method('setParameter')->with('twig.form.resources',array_merge($resources,['@ColorAdmin/Form/fields.html.twig']));

        $compiler = new TwigFormThemeCompilerPass();
        $compiler->process($containerBuilder);
    }

    public function test_configured_to_not_auto_configure()
    {
        $containerBuilder = $this->createMock(ContainerBuilder::class);
        $containerBuilder->method('getParameter')->will($this->returnCallback(function($arg) {
            if($arg == 'color_admin.auto_form_theme') { return false; }
        }));

        $containerBuilder->expects($this->never())->method('hasParameter')->with('twig.form.resources')->willReturn(true);
        $containerBuilder->expects($this->never())->method('setParameter');

        $compiler = new TwigFormThemeCompilerPass();
        $compiler->process($containerBuilder);
    }

    public function test_does_not_get_added_when_ace_bundle_included()
    {
        $resources = [
            'form_div_layout.html.twig',
            'SonataCoreBundle:Form:datepicker.html.twig',
            'NSAceBundle:Form:fields.html.twig',
        ];

        $containerBuilder = $this->createMock(ContainerBuilder::class);
        $containerBuilder->method('getParameter')->will($this->returnCallback(function($arg) use($resources) {
            if($arg == 'color_admin.auto_form_theme') { return true; }
            if($arg == 'twig.form.resources') { return $resources; }
        }));

        $containerBuilder->expects($this->once())->method('hasParameter')->with('twig.form.resources')->willReturn(true);
        $containerBuilder->expects($this->never())->method('setParameter');

        $compiler = new TwigFormThemeCompilerPass();
        $compiler->process($containerBuilder);
    }

}
