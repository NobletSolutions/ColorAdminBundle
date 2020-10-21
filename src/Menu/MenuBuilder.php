<?php

namespace NS\ColorAdminBundle\Menu;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;

class MenuBuilder
{
    /** @var FactoryInterface */
    protected $factory;

    public function __construct(FactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    public function createSidebarMenu(array $options): ItemInterface
    {
        $menu = $this->factory->createItem('root');

        $menu->addChild('Override parameter')
                ->setAttribute('icon', 'laptop')
                ->setAttribute('badge', '10')
                ->setAttribute('badge-style', 'danger')
                ->addChild('ns.color_admin.menu_builder')
                    ->addChild('with your menu class to change this menu', ['uri'=>'http://www.google.com']);

        $menu->addChild('Menu Item', ['uri'=>'http://www.google.com']);

        $item = $menu->addChild('Menu Item 2')
            ->setAttribute('icon', 'envelope')
            ->setAttribute('label', 'NEW')
            ->setAttribute('label-style', 'warning');
        $item->addChild('Sub Menu', ['uri'=>'http://www.google.com']);
        $menu->addChild('divider')
            ->setAttribute('divider', true);
        $item->addChild('Sub Menu 2', ['uri'=>'http://www.google.com']);
        $item->addChild('Sub Menu 3', ['uri'=>'http://www.google.com']);

        $menu->addChild('Menu Item 3', ['uri'=>'http://www.google.com'])
            ->setAttribute('icon', 'star')
            ->setAttribute('icon-style', 'primary');

        return $menu;
    }
}
