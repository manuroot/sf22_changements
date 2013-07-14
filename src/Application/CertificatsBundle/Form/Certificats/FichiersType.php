<?php

namespace Application\CertificatsBundle\Form\Certificats;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

use Application\CertificatsBundle\Form\EventListener\AddFichierFieldSubscriber;


class FichiersType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
         //  $builder->addEventSubscriber(new AddFichierFieldSubscriber());
   
        
           $builder
                 ->add('file')
               /*    ->add('name','text',array(
               'label' => 'Description du Fichier',
               'required'=>false,
               )
               )*/
                   ;             
        
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            //    'data_class' => 'Symfony\Component\HttpFoundation\File\File',
            'data_class' => 'Application\CertificatsBundle\Entity\CertificatsFiles',
                // 'cascade_validation' => true,
        ));
    }

    public function getName() {
        return 'fichier_certificats';
    }

}