<?php

namespace Application\ChangementsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


use Craue\FormFlowBundle\Form\FormFlow;
use Craue\FormFlowBundle\Event\PostValidateEvent;
use Craue\FormFlowBundle\Event\PreBindEvent;
use Craue\FormFlowBundle\Form\FormFlowEvents;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

//class ChangementsFlow extends FormFlow implements EventSubscriberInterface{
class ChangementsFlow extends FormFlow {
    protected $maxSteps = 6;
    protected $allowDynamicStepNavigation = true;



protected function loadStepDescriptions() {
    return array(
        'Données Principales',
        'Demandeur et Utilisateurs',
        'Projet/Application(s)',
        'Environnement/Status',
          'Dates COMEP/VSR',
        'Confirmation'
     
        /*'confirmation',*/
    );
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