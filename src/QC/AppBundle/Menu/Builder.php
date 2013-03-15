<?php
namespace QC\AppBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAware;

class Builder extends ContainerAware
{
    public function mainMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root');

        $menu->addChild('Home', array('uri' => '/'));
        $child = $menu->addChild('Customers');
        $child->addChild('List customers', array('route'=>'customers'));
        $child = $menu->addChild('Jobs');
        $child->addChild('List jobs', array('route'=>'jobs'));
        // ... add more children

        return $menu;
    }
}