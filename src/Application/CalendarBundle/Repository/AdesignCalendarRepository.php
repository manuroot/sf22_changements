<?php

namespace Application\CalendarBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Application\CalendarBundle\Entity\EventEntity;
use Application\CalendarBundle\Event\CalendarEvent;

/**
 * AdesignCalendarRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class AdesignCalendarRepository extends EntityRepository {

    
     public function myFindaIdAll($id) {
 /*       $parameters = array();
        $values = array('a,partial b.{id,nomprojet},partial c.{id,nomUser},partial d.{id,nom,description},f,h');

        
    if (!$entity) {
                throw $this->createNotFoundException('Unable to find ChangementsContact entity.');
            }
            $ret['IsSuccess'] = true;
            $ret['Msg'] = 'update success';
            $data['id'] = $entity->getId();
            $entity->getBgColor(); //set the background color of the event's label
            $data['allDay'] = (boolean) $entity->getAllDay();
             $data['title'] = $entity->getTitle();
            $data['start'] = $entity->getstartDatetime()->format('Y-m-d H:i:s');
             $data['end']  = $entity->getendDatetime()->format('Y-m-d H:i:s');
          
            $data['className'] = $entity->getCssClass();
            $data['backgroundColor'] = $entity->getBgColor();
            $data['description'] = $entity->getDescription();
            $data['textColor'] = $entity->getFgColor(); //set the foregr
            $ret['data'] = $data;
 */
         
}
public function myFindNewEvent($timer=60,$id_root = null) {

       //$current = date('Y-m-d H:i:s', strtotime('now'));
   //  $current = date('Y-m-d H:i:s', strtotime('now'));

      $date_now = date("Y-m-d H:i:s", time());
    $date_last = date("Y-m-d H:i:s", time() - $timer);
  //  echo "date last=$date_last<br>\n";
  //  echo "date now=$date_now";
    $calendarEvent=new CalendarEvent(new \Datetime('now'),new \Datetime('now'),$id_root);
      //  $current = date('Y-m-d H:i:s', strtotime('now'));
      // $datetime_from = date("Y-m-d H:i",strtotime("-45 minutes",strtotime($thestime)));
      $parameters = array();
        $values = array('a,b');
        $qu = $this->createQueryBuilder('a')
                ->select($values)
                //   ->distinct('a.id')
             ->leftJoin('a.calendarid', 'b')
                 ->where('a.addedDate BETWEEN :startDate and :endDate')
                ->setParameter('startDate', $date_last)
                ->setParameter('endDate', $date_now);
         
        if (isset($id_root)) {
            $qu->andwhere('b.id = :idRoot')
                    ->setParameter('idRoot', $id_root);
        }
       
       $companyEvents = $qu->getQuery()->getScalarResult();
      
  //var_dump($companyEvents);
         foreach ($companyEvents as $v) {
            $eventEntity = new EventEntity($v['a_title'], $v['a_startDatetime'], $v['a_endDatetime'], $v['a_allDay']
            );
            $eventEntity->setId($v['a_id']);
            $eventEntity->setFgColor($v['a_fgColor']);
            $eventEntity->setBgColor($v['a_bgColor']);
            $eventEntity->setCssClass($v['a_cssClass']);
            $nbfic=(isset($v['num']) ? $v['num'] : "0");
             $eventEntity->setNbfiles($nbfic);
            $eventEntity->setDescription($v['a_description']); //set the foreground color of the event's label

            $calendarEvent->addEvent($eventEntity);
            ;
        }
//var_dump($calendarEvent);
        return $calendarEvent->getEvents();
        }
        


}

   