<?php
namespace Application\CertificatsBundle\Menu;
use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAware; 

class MenuBuilder extends ContainerAware
{
    public function mainMenu(FactoryInterface $factory, array $options)
    {
      /*  $menu = $factory->createItem('root');
        $menu->setChildrenAttribute('class', 'navbar navbar-fixed-top navbar-inverse');
        $item=$menu->addChild('Projects', array('route' => 'projets'))
            ->setAttribute('icon', 'icon-list');
        $item->setCurrent(true);
         $menu->addChild('applications', array('route' => 'applications'))
            ->setAttribute('icon', 'icon-list');*/
      /*  $menu->addChild('Employees', array('route' => 'projets_show'))
            ->setAttribute('icon', 'icon-group');*/
     /*   return $menu;*/
        
   /*$image = "<img src='/path/to/image' />";
    $menu->addChild( $image , 
      array(
        'route' => 'url_route_name',
        'extras' => array(
          'safe_label' => true
        )
      )
    );
    */
$menu = $factory->createItem('root');
$menu->setChildrenAttribute('class', 'nav pull-right');
 
$menu->addChild('User')
->setAttribute('dropdown', true);
 
$menu['User']->addChild('Profile', array('uri' => '#'))
->setAttribute('divider_append', true);
$menu['User']->addChild('Logout', array('uri' => '#'));
 
$menu->addChild('Language')
->setAttribute('dropdown', true)
->setAttribute('divider_prepend', true);
 
$menu['Language']->addChild('Deutsch', array('uri' => '#'));
$menu['Language']->addChild('English', array('uri' => '#'));
 
return $menu;


    }
}