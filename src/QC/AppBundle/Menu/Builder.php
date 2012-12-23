<?php
namespace QC\AppBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAware;

class Builder extends ContainerAware
{
    public function mainMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root');

        $menu->addChild('Home', array('uri' => 'something'));
        $child = $menu->addChild('About Me', array(
            'uri' => '/something'
        ));
        $child->addChild('Home2', array('uri'=>'/'));
        // ... add more children

        return $menu;
    }
}