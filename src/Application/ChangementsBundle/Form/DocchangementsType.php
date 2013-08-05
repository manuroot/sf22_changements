<?php

namespace Application\ChangementsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;
use Application\ChangementsBundle\Form\EventListener\AddFichierFieldSubscriber;


class DocchangementsType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        
         /*if ($builder->getData()->getId()) { 
               $builder->add('file','file',array('label'=>'Fichier','required'=>false));
        }else {
          $builder->add('file','file',array('label'=>'Fichier (*)','required'=>true,));
        }*/
          $builder->addEventSubscriber(new AddFichierFieldSubscriber());
   
        $builder
              //  ->add('file',null,array('label' => 'MAJ Fichier'))
                     ->add('name',null,array('label' => 'Nom'))
             //    ->add('name','text',array('label' => 'Nom',required=>false,'mapped'=>false))
                ->add('idchangement', 'entity', array(
                    'class' => 'ApplicationChangementsBundle:Changements',
                    'query_builder' => function(EntityRepository $em) {
                        return $em->createQueryBuilder('u')
                                ->orderBy('u.nom', 'ASC');
                    },
                    'property' => 'nom',
                    'multiple' => true,
                    'required' => false,
                    'label' => 'Changements'
                ))
        //  ->add('name','text',array('label' => 'Description du Fichier'))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            //    'data_class' => 'Symfony\Component\HttpFoundation\File\File',
            'data_class' => 'Application\ChangementsBundle\Entity\Docchangements',
             'cascade_validation' => true,
        ));
    }

    public function getName() {
        return 'doc';
    }

}