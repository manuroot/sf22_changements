<?php

// src/Tutorial/BlogBundle/Admin/PostAdmin.php

namespace Application\RelationsBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Knp\Menu\ItemInterface as MenuItemInterface;
use Application\RelationsBundle\Entity\CertificatsProjet;

class ProjetAdmin extends Admin {

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
    /**
     * @param \Sonata\AdminBundle\Show\ShowMapper $showMapper
     *
     * @return void
     */
    protected function configureShowField(ShowMapper $showMapper) {
        $showMapper
       ->with('Détail de l\'enregistrement')
         ->add('id')
                ->add('nomprojet')
                ->end()
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
              //    ->add('id')
                ->add('nomprojet')
                //page edit supplément
                ->setHelps(array(
                    'nomprojet' => 'Titre du Projet',
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
                   ->add('nomprojet')
             //   ->addIdentifier('nomprojet')
              
                ->add('_action', 'actions', array(
                    'actions' => array(
                        'view' => array(),
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
                ->add('nomprojet');

        //  ->add('tags', null, array('field_options' => array('expanded' => true, 'multiple' => true)))
        ;
    }
/*
public function getEditTemplate()
{
    return 'CertificatsBundle:CertificatsProjet:base_edit.html.twig';
}*/
}
