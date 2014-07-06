<?php

// src/Tutorial/BlogBundle/Admin/PostAdmin.php

namespace Application\RelationsBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Knp\Menu\ItemInterface as MenuItemInterface;
use Application\RelationsBundle\Entity\ChronoUser;

class ChronoUserAdmin extends Admin {

 /* public function getTemplate($name)
 
{

switch ($name) {
 
case 'edit':
 
return 'ApplicationRelationsBundle::admin-edit.html.twig';
 
break; 
 
default:
 
return parent::getTemplate($name);
 
break;
 
}
 
}*/
/* Show
*/
    /**
     * @param \Sonata\AdminBundle\Show\ShowMapper $showMapper
     *
     * @return void
     */
    protected function configureShowFields(ShowMapper $showMapper) {
        $showMapper
     //  ->with('Détail de l\'enregistrement')
        // ->add('id')
          
                ->add('nomUser')
                ->add('infos')
                ->add('telephone')    
                ->add('bureau')
                ->add('email')
                ->add('idGroup')
            //    ->add('description')
          //      ->end()
 ;
                  
    }

    /**
     * @param \Sonata\AdminBundle\Form\FormMapper $formMapper
     *
     * @return void
     */
    protected function configureFormFields(FormMapper $formMapper) {
        $formMapper
                ->with('General')
                      ->add('nomUser')
                ->add('infos')
                ->add('telephone')    
                ->add('bureau')
                ->add('email')
                ->add('idGroup')
                
                //page edit supplément
                ->setHelps(array(
                    'nomUser' => 'Nom du user',
                    'keywords' => 'Set the keywords of a web page',
                ))
             /*   ->add('image', 'sonata_type_model_list', array('required' => false),
                   array('link_parameters'=>array('context'=>'default',
                   'provider'=>'sonata.media.provider.image')))*/
                ->end()
        ;
        //->add('enabled', null, array('required' => false))
        ;
    }

    /**
     * @param \Sonata\AdminBundle\Datagrid\ListMapper $listMapper
     * @return void
     */
    protected function configureListFields(ListMapper $listMapper) {
        $listMapper
          ->add('id')
                        ->add('nomUser')
                ->add('infos')
                ->add('telephone')    
                ->add('bureau')
                ->add('email')
                ->add('idGroup')
              // ->addIdentifier('nomprojet')
              
                ->add('_action', 'actions', array(
                    'actions' => array(
                        'show' => array(),
                        'edit' => array(),
                        'delete' => array(),
                    )
                ))
        ;
    }

    /**
     * @param \Sonata\AdminBundle\Datagrid\DatagridMapper $datagridMapper
     *
     * @return void
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper) {
        $datagridMapper
                     ->add('nomUser')
                ->add('infos')
                ->add('telephone')    
                ->add('bureau')
                ->add('email')
                ->add('idGroup')
        //  ->add('tags', null, array('field_options' => array('expanded' => true, 'multiple' => true)))
        ;
    }
/*
public function getEditTemplate()
{
    return 'CertificatsBundle:CertificatsProjet:base_edit.html.twig';
}*/
}
