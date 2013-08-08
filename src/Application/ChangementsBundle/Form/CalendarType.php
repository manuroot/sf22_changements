<?php

namespace Application\ChangementsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManager;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormError;
use Doctrine\ORM\QueryBuilder;
use Application\ChangementsBundle\Entity\ChangementsRepository;
use Application\ChangementsBundle\Entity\Changements;
use Application\ChangementsBundle\Entity\ChangementsStatus;
use Application\RelationsBundle\Entity\Projet;
use Application\RelationsBundle\Repository\ProjetRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;


class CalendarType extends AbstractType {

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

        $em = $this->_em;
        $choices_years = $em->getRepository('ApplicationChangementsBundle:Changements')-> GetYears();
          
        $builder->setAttribute('show_legend', false); // no legend for main form
       /*
     
    }
        */
       
        $min_year=Date('Y')-3;
        $max_year=Date('Y')+3;
$builder
   ->add('publishedAt', 'date', array(
                            'widget' => 'choice',
                            'format' => 'yyyy-MMMM-dd',
                            'pattern' => '{{ year }}-{{ month }}-{{ day }}',
                            'years' => range($min_year,$max_year),
        'years' => $choices_years,
                            'label' => false,
                            'input' => 'string',
       'mapped'=>false
                  
                        ))
            ;
         
    }

    public function getName() {
        return 'changements_calendar_form';
    }

}
