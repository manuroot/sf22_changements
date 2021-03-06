<?php

namespace Application\ChangementsBundle\Form;

/*
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;*/
use Craue\FormFlowBundle\Form\FormFlow;
use Craue\FormFlowBundle\Form\FormFlowInterface;
use Symfony\Component\Form\FormTypeInterface;
/*
use Craue\FormFlowBundle\Form\FormFlow;
use Craue\FormFlowBundle\Event\PostValidateEvent;
use Craue\FormFlowBundle\Event\PreBindEvent;
use Craue\FormFlowBundle\Form\FormFlowEvents;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;*/

//class ChangementsFlow extends FormFlow implements EventSubscriberInterface{
class ChangementsFlow extends FormFlow {
    
    
    /*protected $maxSteps = 6;*/
    protected $allowDynamicStepNavigation = true;


/**
     * @var FormTypeInterface
     */
    protected $formType;

    public function setFormType(FormTypeInterface $formType) {
        $this->formType = $formType;
    }

    public function getName() {
         return 'changements';
    }
protected function loadStepDescriptions() {
    return array(
        'Données Principales',
        'Demandeur et Utilisateurs',
        'Projet/Application(s)',
        'Environnement/Status',
          'Dates COMEP/VSR',
        'Confirmation'
     
       
    );
}

 protected function loadStepsConfig() {
       return array(
      1 =>  array(
            'label'=>'Données Principales', 
            'type' => $this->formType
               ),
      2 =>    array( 
               'label'=>'Demandeur et Utilisateurs', 
               'type' => $this->formType),
      3 =>     array(
               'label'=> 'Projet/Application(s)',
               'type' => $this->formType
               ),
      4 =>     array('label'=>'Environnement/Status', 'type' => $this->formType
               ),
      5 =>    array(
              'label'=>'Dates COMEP/VSR', 
              'type' => $this->formType
               ),
      6 =>  array(
            'label' => 'confirmation',
            'type' => $this->formType, 
          ),
        /*'confirmation',*/
    );
       
      
    }
public function getFormOptions($step, array $options = array()) {
        $options = parent::getFormOptions($step, $options);

        $options['flowStep'] = $step;

        return $options;
    }

/*
 public function setEventDispatcher(EventDispatcherInterface $dispatcher) {
        parent::setEventDispatcher($dispatcher);
        $dispatcher->addSubscriber($this);
    }

    public static function getSubscribedEvents() {
        return array(
            FormFlowEvents::PRE_BIND => 'onPreBind',
            FormFlowEvents::POST_BIND_REQUEST => 'onPostBindRequest',
            FormFlowEvents::POST_BIND_SAVED_DATA => 'onPostBindSavedData',
            FormFlowEvents::POST_VALIDATE => 'onPostValidate',
        );
    }

    public function onPreBind(PreBindEvent $event) {
        // ...
    }

    public function onPostBindRequest(PostBindRequestEvent $event) {
        // ...
    }

    public function onPostBindSavedData(PostBindSavedDataEvent $event) {
        // ...
    }

    public function onPostValidate(PostValidateEvent $event) {
        // ...
    }*/
}