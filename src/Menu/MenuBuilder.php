<?php

namespace NS\ColorAdminBundle\Menu;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;

class MenuBuilder
{
    /** @var FactoryInterface */
    private $factory;

    /**
     * MenuBuilder constructor.
     * @param FactoryInterface $factory
     */
    public function __construct(FactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    /**
     * @param array $options
     * @return ItemInterface
     */
    public function createSidebarMenu(array $options)
    {
        $menu = $this->factory->createItem('root');

        $menu->addChild('Override Service')
                ->setAttribute('icon', 'laptop')
                ->setAttribute('badge', '10')
                ->setAttribute('badge-style', 'danger')
                ->addChild('Sub Menu')
                    ->addChild('Sub Menu 2', ['route'=>'homepage']);

        $menu->addChild('ns_color_admin.menu.sidebar', ['uri'=>'http://www.google.com']);

        $item = $menu->addChild('To change')
            ->setAttribute('icon', 'envelope')
            ->setAttribute('label', 'NEW')
            ->setAttribute('label-style', 'warning');
        $item->addChild('Sub Menu', ['uri'=>'http://www.google.com']);
        $menu->addChild('divider')
            ->setAttribute('divider', true);
        $item->addChild('Sub Menu 2', ['uri'=>'http://www.google.com']);
        $item->addChild('Sub Menu 3', ['uri'=>'http://www.google.com']);

        $menu->addChild('This menu', ['uri'=>'http://www.google.com'])
            ->setAttribute('icon', 'star')
            ->setAttribute('icon-style', 'primary');

        return $menu;
    }
}
