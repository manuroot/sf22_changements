<?php

// src/Tutorial/BlogBundle/Admin/PostAdmin.php

namespace Application\RelationsBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Knp\Menu\ItemInterface as MenuItemInterface;
use Application\RelationsBundle\Entity\Filetype;


    
class FiletypeAdmin extends Admin {

    /**
     * @param \Sonata\AdminBundle\Show\ShowMapper $showMapper
     *
     * @return void
     */
    protected function configureShowField(ShowMapper $showMapper) {
        $showMapper
                ->add('fileType')
                ->add('infos')
                ->add('details')
                ->add('folder');
                
    }

    /**
     * @param \Sonata\AdminBundle\Form\FormMapper $formMapper
     *
     * @return void
     */
    protected function configureFormFields(FormMapper $formMapper) {
        $formMapper
                ->with('General')
                 ->add('fileType')
                ->add('infos')
                ->add('details')
                ->add('folder')
              /*  ->setHelps(array(
                    'nomprojet' => 'Titre du Projet',
                    'keywords' => 'Set the keywords of a web page',
                ))*/
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
                ->addIdentifier('id')
                  ->add('fileType')
                ->add('folder')
               ->add('infos')
                //->add('title')
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
                  ->add('fileType')
                ->add('infos')
                ->add('details')
                ->add('folder')
              

        //  ->add('tags', null, array('field_options' => array('expanded' => true, 'multiple' => true)))
        ;
    }

    public function getEditTemplate()
{
    return 'RelationsBundle:Projet:base_edit.html.twig';
}
}
