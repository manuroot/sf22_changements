<?php


namespace Application\CertificatsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

use Doctrine\ORM\EntityManager;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormError;
use Doctrine\ORM\QueryBuilder;
/*
use Application\ChangementsBundle\Repository\ChangementsRepository;
use Application\ChangementsBundle\Entity\Changements;
use Application\ChangementsBundle\Entity\ChangementsStatus;
use Application\RelationsBundle\Entity\Projet;
 * */
use Application\RelationsBundle\Repository\ProjetRepository;
use Application\CertificatsBundle\Repository\CertificatsCenterRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;


class CertificatsFilesFilterType extends AbstractType {

     private $_em;
     // datas pour $em
    private $_datas;

    public function __construct(EntityManager $entityManager,$datas=array()) {
        $this->_em = $entityManager;
      //   $this->_datas = $datas;
         //Array ( [nom] => [description] => [demandeur] => 
         //[dateDebut] => [dateDebut_max] => [dateFin] => [dateFin_max] => [ticketExt] => [ticketInt] => [idEnvironnement] => Array ( [0] => 1 )
        // $em = $this->getDoctrine()->getManager();
    }

    public function buildForm(FormBuilderInterface $builder, array $options) {
        
          // print_r($this->_datas);exit(1);
        $em = $this->_em;
        $choices_projets = $em->getRepository('ApplicationCertificatsBundle:CertificatsCenter')->getProjetsForRequeteBuilder();
        $choices_typecert = $em->getRepository('ApplicationCertificatsBundle:CertificatsCenter')->getTypeCertForRequeteBuilder();
       $builder
               
               /*  ->add('name', 'genemu_jqueryautocomplete_text', array(
                     'widget_addon' => array(
                        'icon' => 'pencil',
                        'type' => 'prepend'
                        ),
                  //  'route_name' => 'ajax_form_request_ticketint',
                    'configs' => array('minLength' => 2),
                    'data_class' => 'Application\CertificatsBundle\Entity\CertificatsFiles',
                      'property' => 'name',
                    'mapped' => false,'required'=>false,
              //      'data' => $this->getData()-> getTicketInt()
                ))*/
              
                ->add('name', 'genemu_jqueryautocomplete_text', array(
                    'label' => 'Nom',
                    'widget_addon' => array(
                        'icon' => 'pencil',
                        'type' => 'prepend'
                    ),
                     'configs' => array('minLength' => 2),
                    'mapped' => false, 'required' => false,
                    'route_name' => 'ajax_nom_certfiles',
                    'class' => 'Application\CertificatsFilesBundle\Entity\CertificatsFiles',
                ))

               ->add('project', 'choice', array(
                    'choices' => $choices_projets,
                    'required' => false,
                    'label' => 'Projets',
                    'expanded' => false,
                    'multiple' => true,
                    'mapped' => false,
                    'empty_value' => '--- Options ---', 
                ))
               
               ->add('typeCert', 'choice', array(
                    'choices' => $choices_typecert,
                    'required' => false,
                    'label' => 'Type',
                    'expanded' => false,
                    'multiple' => true,
                    'mapped' => false,
                    'empty_value' => '--- Options ---', 
                ))
               /*
                ->add('name','text',array( 
                    'widget_addon' => array(
                        'icon' => 'pencil',
                        'type' => 'prepend'
                        ),
                    'mapped'=>false,'required'=>false))*/
               
                  /*   ->add('path','text',array( 
                    'widget_addon' => array(
                        'icon' => 'pencil',
                        'type' => 'prepend'
                        ),
                    'mapped'=>false,'required'=>false))
                    */
                  ->add('md5','text',array( 
                    'widget_addon' => array(
                        'icon' => 'pencil',
                        'type' => 'prepend'
                        ),
                    'mapped'=>false,'required'=>false))
               
                 ->add('updatedAt','text',array( 
                      'attr' => array(
            'placeholder'=>'> updatedAt'),
                     'widget_addon' => array(
                        'icon' => 'time',
                        'type' => 'prepend'
                        ),
                     'mapped'=>false,'required'=>false
                     ))
                
                     ->add('updatedAt_max','text',array( 
                      'attr' => array(
            'placeholder'=>'< updatedAt'),
                     'widget_addon' => array(
                        'icon' => 'time',
                        'type' => 'prepend'
                        ),
                     'mapped'=>false,'required'=>false
                     ))
                
          /*->add('OriginalFilename', 'genemu_jqueryautocomplete_text', array(
                     'widget_addon' => array(
                        'icon' => 'pencil',
                        'type' => 'prepend'
                        ),
                  //  'route_name' => 'ajax_form_request_ticketint',
                    'configs' => array('minLength' => 2),
                    'data_class' => 'Application\CertificatsBundle\Entity\CertificatsFiles',
                      'property' => 'OriginalFilename',
                    'mapped' => false,'required'=>false,
              //      'data' => $this->getData()-> getTicketInt()
                ))*/
                ->add('OriginalFilename', 'genemu_jqueryautocomplete_text', array(
                    'label' => 'OriginalFilename',
                    'widget_addon' => array(
                        'icon' => 'pencil',
                        'type' => 'prepend'
                    ),
                     'configs' => array('minLength' => 2),
                    'mapped' => false, 'required' => false,
                    'route_name' => 'ajax_nom_certfiles',
                    'class' => 'Application\CertificatsFilesBundle\Entity\CertificatsFiles',
                ))
/*
               ->add('OriginalFilename', 'text', array(
                    'widget_addon' => array(
                        'icon' => 'pencil',
                        'type' => 'prepend'
                    ),
                    'mapped' => false, 'required' => false))
              */
                ->add('certificats','text',array( 
                    'label'=>'Nom du certificat',
                    'widget_addon' => array(
                        'icon' => 'pencil',
                        'type' => 'prepend'
                        ),
                    'mapped'=>false,'required'=>false))
               
                /* ->add('certificats', 'entity', array(
                      'class' => 'ApplicationCertificatsBundle:CertificatsCenter',
                     'query_builder' => function(EntityRepository $em) {
                        return $em->createQueryBuilder('u')
                                ->orderBy('u.fileName', 'ASC');
                    },
                    'property' => 'fileName',
                    'expanded' => false,
                    'multiple' => true,
                    'required' => false,
                    'label' => 'Certificats'
                ))*/
               ;
            
    }

    public function getName() {
        return 'certificatsfiles_searchfilter';
    }

    /* public function setDefaultOptions(OptionsResolverInterface $resolver) {
      $resolver->setDefaults(array(
      'csrf_protection' => false,
      'validation_groups' => array('filtering') // avoid NotBlank() constraint-related message
      ));
      } */
}

