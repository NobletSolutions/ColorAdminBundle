<?php

namespace NS\ColorAdminBundle\Menu;

use Knp\Menu\FactoryInterface;

class MenuBuilder
{
    private $factory;

    public function __construct(FactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    public function createSidebarMenu(array $options)
    {
        $menu = $this->factory->createItem('root');

        $menu->addChild('Override Service')
                ->setAttribute('icon', 'laptop')
                ->setAttribute('badge', '10')
                ->setAttribute('badge-style', 'danger')
                ->addChild('Sub Menu')
                    ->addChild('Sub Menu 2', array('route'=>'homepage'));

        $menu->addChild('ns_color_admin.menu.sidebar', array('uri'=>'http://www.google.com'));

        $item = $menu->addChild('To change')
            ->setAttribute('icon', 'envelope')
            ->setAttribute('label', 'NEW')
            ->setAttribute('label-style', 'warning');
        $item->addChild('Sub Menu', array('uri'=>'http://www.google.com'));
        $menu->addChild('divider')
            ->setAttribute('divider', true);
        $item->addChild('Sub Menu 2', array('uri'=>'http://www.google.com'));
        $item->addChild('Sub Menu 3', array('uri'=>'http://www.google.com'));

        $menu->addChild('This menu', array('uri'=>'http://www.google.com'))
            ->setAttribute('icon', 'star')
            ->setAttribute('icon-style', 'primary');

        return $menu;
    }
}