<?php

namespace Application\ChangementsBundle\Repository;

use Doctrine\ORM\EntityRepository;

//use CalendR\Extension\Doctrine2\EventRepository as EventRepositoryTrait;
/**
 * NotesRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ChangementsStatusRepository extends EntityRepository {

    public function GetNomStatus() {

        $result = array();
        $query = $this->createQueryBuilder('a')
                ->select('partial a.{id,nom}');
        $arr = $query->getQuery()->getArrayResult();
        foreach ($arr as $k => $v) {
            array_push($result, $v['nom']);
        }

        ///  print_r($result);exit(1);
        return $result;
    }

}
