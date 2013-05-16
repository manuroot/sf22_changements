<?php

namespace Application\RelationsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Application\RelationsBundle\Form\CertificatsProjetType;

class ApplisType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nomapplis','text',array('label' => 'Application'))
                ->add('description','text',array('label' => 'Description'))
                
       //     ->add('idprojets')
        ;
        
     /*   $builder->add('idprojets', 'collection', array(
            'type' => new CertificatsProjetType(),
           'allow_add'    => true,
                 'allow_delete' => true,
                'by_reference' => false,
                  'required' => false
            
            ));*/
    /*       $builder->add('idprojets','entity', array(
            'class' => 'Application\RelationsBundle\Entity\CertificatsProjet',
            'property' => 'nomprojet',
            'multiple' => true,
            'required' => true,
            'label' => 'Nom des Projets'
            ));  */
    }
  
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Application\RelationsBundle\Entity\Applis'
        ));
    }

    public function getName()
    {
        return 'Formulaire_Application';
    }
}
