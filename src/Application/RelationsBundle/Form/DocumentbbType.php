<?php

namespace Application\RelationsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class DocumentbbType extends AbstractType
{
        public function buildForm(FormBuilderInterface $builder, array $options)
        {
             if ($builder->getData()->getId()) { 
               $builder->add('file','file',array('label'=>'Fichier','required'=>false));
        }else {
          $builder->add('file','file',array('label'=>'Fichier (*)','required'=>true,));
        }
                $builder
                       //->add('file')
                        ->add('name',null,array('required'=>false))
                      //  ->add('fichier',new DocumentsType())
                         ->add('idprojets',null,array('label'=>'Projets associés'))
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