<?php

namespace Application\RelationsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
//use Application\CertificatsBuundle\Form\CertificatsTagType;

class ProjetType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nomprojet', 'text',array('label' => 'Nom du Projet'))
            ->add('description', 'text',array('label' => 'Description'));
       
     /*   $builder->add('idapplis', 'collection', array(
             'type' => new ApplisType(),
              'allow_add' => true,
        'by_reference' => false,
             'prototype' => true,
                'prototype_name' => 'tag__name__',
                'options' => array(
                    // options on the rendered TagTypes
                ),
            ))
        ;*/
        
        
        
        /* checkbox*/
        /*
              $builder->add('idapplis','entity', array(
            'class' => 'Application\RelationsBundle\Entity\Applis',
                  'property' => 'nomapplis',
             'expanded' => true,
                'multiple'  => true
            ));  */
         /*     $builder->add('idapplis', 'genemu_jqueryselect2_entity', array(
            'class' => 'Application\RelationsBundle\Entity\Applis',
            'property' => 'nomapplis',
            'multiple' => true,
            'required' => true,
            'label' => 'Applications',
        ))
    ;*/
   /*   selct multiple*/  
        
        
              $builder->add('idapplis','entity', array(
            'class' => 'Application\RelationsBundle\Entity\Applis',
            'property' => 'nomapplis',
            'multiple' => true,
            'required' => true,
            'label' => 'Applications'
            ))
           ->add('picture', 'collection', array('type' => new DocumentsbbType,
        'allow_add' => true,
        'by_reference' => true,
        'allow_delete' => true,
        'prototype' => true,
                'prototype_name' => 'doc__name__',));
                      
           /*           ->add('picture', 'collection', array('type' => new DocumentsbbType,
        'allow_add' => true,
        'by_reference' => false,
        'allow_delete' => true,
        'prototype' => true));*/
              
                     // ->add('picture', 'file');
                        /*->add('picture', 'file', array(
'data_class' => 'Symfony\Component\HttpFoundation\File\File'
));*/
                      
        //  ->add('picture',new DocumentsbbType());
                   /* ->add('avatar', 'file', array(
'data_class' => 'Symfony\Component\HttpFoundation\File\File'
));*/
              /*    $builder->add('picture','entity', array(
            'class' => 'Application\RelationsBundle\Entity\Documentbb',
            'property' => 'name',
            'multiple' => true,
            'required' => true,
            'label' => 'Fichiers'
            )); */
                
             /*$builder->add('picture', 'sonata_type_model', array('context' =>
'user', 'provider' => 'sonata.media.provider.image'))
;*/
           /*  $builder->add('image', 'sonata_type_model_list', array('required' => false),
                    array('link_parameters'=>array('context'=>'default',
                   'provider'=>'sonata.media.provider.image')));*/
                
               //    $builder->add('idapplis', 'collection', array('type' => new ApplisType()));
                
                           // ->add('categories', new CategoryType())
      
          /*  $builder->add('idprojets','entity', array(
            'class' => 'Application\RelationsBundle\Entity\Filetype',
            'property' => 'FileType',
            'multiple' => false,
            'required' => true,
            'label' => 'Type'
            )); */   
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Application\RelationsBundle\Entity\Projet'
        ));
    }

    public function getName()
    {
        return 'Formulaire_Projet';
    }
}
