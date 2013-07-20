<?php

namespace Application\RelationsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;
use Application\RelationsBundle\Form\EventListener\AddFichierFieldSubscriber;

class DocumentbbType extends AbstractType
{
    
    
      
              //  ->add('file',null,array('label' => 'MAJ Fichier'))
                //     ->add('name',null,array('label' => 'Nom'))
                
        
                
        public function buildForm(FormBuilderInterface $builder, array $options)
        {
          /*   if ($builder->getData()->getId()) { 
               $builder->add('file','file',array('label'=>'Fichier','required'=>false));
        }else {
          $builder->add('file','file',array('label'=>'Fichier (*)','required'=>true,));
        }*/
             $builder->addEventSubscriber(new AddFichierFieldSubscriber());
                $builder
                       //->add('file')
                        ->add('name',null,array('required'=>false))
                      //  ->add('fichier',new DocumentsType())
                           ->add('idprojet', 'entity', array(
                    'class' => 'ApplicationRelationsBundle:Projet',
                    'property' => 'nomprojet',
                    'multiple' => true,
                    'required' => false,
                    'label' => 'Projets'
                ))
                
                       //  ->add('idprojet',null,array('label'=>'Projets associés'))
                        ;
        }

        public function setDefaultOptions(OptionsResolverInterface $resolver)
        {
            $resolver->setDefaults(array(
            //    'data_class' => 'Symfony\Component\HttpFoundation\File\File',
                   'data_class' => 'Application\RelationsBundle\Entity\Documentbb',
                 'cascade_validation' => true,
            ));
        }

        public function getName()
        {
                return 'doc';
        }
}