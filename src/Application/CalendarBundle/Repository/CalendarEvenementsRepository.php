<?php

namespace Application\CalendarBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * CalendarEvenementsRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CalendarEvenementsRepository extends EntityRepository {

    public function myFindAll($id_root = null) {

       
        $parameters = array();
        $values = array('a,b');
        $query = $this->createQueryBuilder('a')
                ->select($values)
                //   ->distinct('a.id')
                ->leftJoin('a.rootcalendar', 'b');
                //->getQuery()->getResult();

        echo "idroot=$id_root";
       // $id_root = null;
        if (isset($id_root)) {
            $query->where('a.id = :idRoot')
             ->setParameter('idRoot', $id_root);
           // $parameters['idRoot'] = $id_root;
                        
            //$query->setParameters($parameters);
        }
        $query=$query->getQuery()->getResult();

        //$this->query = $query;
        /*  if (!empty($criteria)) {
          $query = $this->getListBy($criteria);
          } */
       // $query->getQuery()->getResult();
       // $query->add('orderBy', 'a.id DESC');
        //$query->add('orderBy', "$sort $dir");

       
foreach ($query as $q) {
//echo "test";
             $id=$q->getId();
             echo "id=$id\n";
         // var_dump($q);
          //
          //
          } 
        //   ->getResult();
        return $query;
    }

}

