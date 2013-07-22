<?php

namespace Application\ChangementsBundle\Entity;

use Doctrine\ORM\Query\ResultSetMapping;
use CalendR\Extension\Doctrine2\QueryHelper;
use Doctrine\ORM\EntityRepository;
use CalendR\Event\Provider\ProviderInterface;
use DoctrineExtensions\Query\Mysql\GroupConcat;
use Application\ChangementsBundle\Entity\Changements;

//use CalendR\Extension\Doctrine2\EventRepository as EventRepositoryTrait;
/**
 * NotesRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ChangementsRepository extends EntityRepository implements ProviderInterface {

    
    public function get_all_months() {
    return array(
          'Jan','Feb', 'Mar','Apr',
          'May','Jun','Jul','Aug',
          'Sep','Oct','Nov','Dec'
          );
}
            
//class ChangementsRepository extends EntityRepository{
    //use EventRepositoryTrait;
    public function myFindaAll() {
        return $this->createQueryBuilder('a')
                        ->leftJoin('a.idProjet', 'b')
                        ->leftJoin('a.demandeur', 'c')
                        ->leftJoin('a.idStatus', 'd')
                        ->leftJoin('a.picture', 'f')
                        ->orderBy('id')
                        ->getQuery();

        //->getResult();
    }
    
    /* edit et showxhtml
     * 
     */
    public function myFindaIdAll($id) {
        $parameters = array();
        $values = array('a,partial b.{id,nomprojet},partial c.{id,nomUser},partial d.{id,nom,description},f');
        
        $query = $this->createQueryBuilder('a')
                ->select($values)
                ->leftJoin('a.idProjet', 'b')
                ->leftJoin('a.demandeur', 'c')
                ->leftJoin('a.idStatus', 'd')
                ->leftJoin('a.picture', 'f')
                ->addSelect('g')
                //->addSelect('g')
                ->distinct('GroupConcat(g.nom) AS kak')
                ->leftJoin('a.idEnvironnement', 'g')
                ->leftJoin('a.comments', 'h')
                //->addSelect('e')
                ->addSelect('partial e.{id,nomUser}')
                ->distinct('GroupConcat(e.nomUser)')
                ->leftJoin('a.idusers', 'e');
        $query->add('orderBy', 'a.id DESC')
                ->andwhere('a.id = :myid');
        $query->setParameter('myid', $id);


        return $query->getQuery()->getSingleResult();
    }

    /*
     * WORKING::
     * 
     * SELECT DISTINCT c0_.id AS id0, c0_.nom AS nom1, c0_.date_debut AS date_debut2, c0_.date_fin AS date_fin3, c1_.nomprojet AS nomprojet4, c2_.nom_user AS nom_user5, GROUP_CONCAT( DISTINCT c3_.nom_user ) AS sclr6, GROUP_CONCAT( DISTINCT e4_.nom ) AS sclr7
      FROM changements c0_
      LEFT JOIN certificats_projet c1_ ON c0_.id_projet = c1_.id
      LEFT JOIN chrono_user c2_ ON c0_.demandeur = c2_.id
      INNER JOIN changements_users c5_ ON c0_.id = c5_.changements_id
      LEFT JOIN chrono_user c3_ ON c3_.id = c5_.chronouser_id
      LEFT JOIN changements_environnements c6_ ON c0_.id = c6_.changements_id
      LEFT JOIN environnement e4_ ON e4_.id = c6_.environnements_id
      GROUP BY c0_.id
      ORDER BY sclr6 ASC
      LIMIT 0 , 30
     */
    /* $queryBuilder = $this->createQueryBuilder('a')
      ->andWhere('a.enabled = TRUE');
      ->leftJoin('LbPlaneteBassBundle:Track', 't', Expr\Join::WITH, 't.categorie = :categorieTrack AND t.enabled = TRUE');
      ->leftJoin('LbPlaneteBassBundle:TrackAlbumReference', 't_ref', Expr\Join::WITH, 't_ref.track = t AND t_ref.album = a');
      ->where('t_ref.id IS NOT NULL');
      ->setParameter('categorieTrack', $categorieTrack);

      $queryBuilder->groupBy('a.title');
      $queryBuilder->orderBy('a.title', 'DESC'); */

   

    public function myFindAll($criteria = array()) {

        $parameters = array();
        $values = array('a,partial b.{id,nomprojet},partial c.{id,nomUser},partial d.{id,nom,description},f,partial h.{id}');
        $query = $this->createQueryBuilder('a')
                ->select($values)
                ->leftJoin('a.idProjet', 'b')
                ->leftJoin('a.demandeur', 'c')
                ->leftJoin('a.idStatus', 'd')
                ->leftJoin('a.picture', 'f')
                ->addSelect('g')
                //->addSelect('g')
                ->distinct('GroupConcat(g.nom) AS kak')
                ->leftJoin('a.idEnvironnement', 'g')
                ->leftJoin('a.comments', 'h')
                //->addSelect('e')
                ->addSelect('partial e.{id,nomUser}')
                ->distinct('GroupConcat(e.nomUser)')
                ->leftJoin('a.idusers', 'e');
        $query->add('orderBy', 'a.id DESC');
        return $query;
        //->getQuery();
    }

    public function myFindNewAll($criteria = array()) {

        //$qb->add('where', $qb->expr()->in('r.winner', $ids));
        //$ids=array('1','2');
        $ids = 'Prod';
        $parameters = array();
        $values = array('a,partial b.{id,nomprojet},partial c.{id,nomUser},partial d.{id,nom,description},f,partial h.{id}');
        $query = $this->createQueryBuilder('a')
                ->select($values)
                ->leftJoin('a.idProjet', 'b')
                ->leftJoin('a.demandeur', 'c')
                ->leftJoin('a.idStatus', 'd')
                ->leftJoin('a.picture', 'f')
                ->addSelect('g')
                //->addSelect('g')
                ->leftJoin('a.idEnvironnement', 'g')
                ->leftJoin('a.comments', 'h')
                //->addSelect('e')
                ->addSelect('partial e.{id,nomUser}')
                //->distinct('GroupConcat(e.nomUser)')
                ->leftJoin('a.idusers', 'e');
        //  $parameters['idEnv'] = (string) $ids;
        // $query->setParameters($parameters);
        //     $query->setParameter('ids', $ids);
        $query->add('orderBy', 'a.id DESC');
        return $query;
        //->getQuery();
    }

    public function findAjaxValue($criteria) {
       //   $query = $this->myFindNewAll();
          $parameters = array();
          $query = $this->createQueryBuilder('a');
              //  ->select($values)
      

      
          // Supprimer champs qui ne sont pas dans la classe
        foreach ($criteria as $field => $value) {
            if (!$this->getClassMetadata()->hasField($field)) {
                // Make sure we only use existing fields (avoid any injection)
                unset($criteria[$field]);
                //  continue;
            }
        }

        //les like
        $like_arrays = array('nom', 'description','ticketExt', 'ticketInt');
        foreach ($like_arrays as $val) {
            //  echo "val=$val<br>";
            if (isset($criteria[$val]) && !preg_match('/^\s*$/', $criteria[$val])) {

                //   if (isset($criteria[$val]) && ! preg_match('/[\s]+/',$criteria[$val])) {
                //     echo "critere=" . $criteria["$val"] . "<br>";
                $query->andWhere("a.$val LIKE :$val");

                $parameters[$val] = '%' . $criteria[$val] . '%';
            }
        }

        $query->setParameters($parameters);


        return $query;
    }
    
    
    // TODO REGEX
    public function getListBy($criteria) {

        $query = $this->myFindNewAll();
        $parameters = array();

        if (isset($criteria['idEnvironnement']) && $criteria['idEnvironnement'] != "") {
            //       var_dump($criteria['idEnvironnement']);exit(1);
            $query->andWhere('g.id IN (:idEnv)');
            $query->distinct('GroupConcat(g.nom) AS kak');
            $parameters['idEnv'] = $criteria['idEnvironnement'];
        }
        if (isset($criteria['demandeur']) && $criteria['demandeur'] != "") {
            //       var_dump($criteria['idEnvironnement']);exit(1);
            $query->andWhere('c.id = (:idUser)');
            $parameters['idUser'] = $criteria['demandeur'];
        }
        
        if (isset($criteria['idProjet']) && $criteria['idProjet'] != "") {
            //       var_dump($criteria['idEnvironnement']);exit(1);
            $query->andWhere('b.id IN (:idProjet)');
              $query->distinct('GroupConcat(d.nomprojet) AS projet');
            $parameters['idProjet'] = $criteria['idProjet'];
            
           
          
        }
      /*  if (isset($criteria['idStatus']) && $criteria['idStatus'] != "") {
            //       var_dump($criteria['idEnvironnement']);exit(1);
            $query->andWhere('d.id = (:idStatus)');
            $parameters['idStatus'] = $criteria['idStatus'];
        }*/
        
        if (isset($criteria['idStatus']) && $criteria['idStatus'] != "") {
            //       var_dump($criteria['idEnvironnement']);exit(1);
            $query->andWhere('d.id IN (:idStatus)');
            $query->distinct('GroupConcat(d.nom) AS status');
            $parameters['idStatus'] = $criteria['idStatus'];
        }
        
        
        if (isset($criteria['idusers']) && $criteria['idusers'] != "") {
            //       var_dump($criteria['idEnvironnement']);exit(1);
            $query->andWhere('e.id IN (:idUsers)');
            $query->distinct('GroupConcat(e.nomUser)');
            $parameters['idUsers'] = $criteria['idusers'];
        }


        if (isset($criteria['dateDebut']) && $criteria['dateDebut'] != "") {
            $query->andWhere('a.dateDebut > (:datedebut)');
            $parameters['datedebut'] = $criteria['dateDebut'];
        }
           if (isset($criteria['dateDebut_max']) && $criteria['dateDebut_max'] != "") {
            $query->andWhere('a.dateDebut < (:datedebut_max)');
            $parameters['datedebut_max'] = $criteria['dateDebut_max'];
        }
        
         if (isset($criteria['dateFin']) && $criteria['dateFin'] != "") {
            $query->andWhere('a.dateFin > (:dateFin)');
            $parameters['dateFin'] = $criteria['dateFin'];
        }
           if (isset($criteria['dateFin_max']) && $criteria['dateFin_max'] != "") {
            $query->andWhere('a.dateFin < (:dateFin_max)');
            $parameters['dateFin_max'] = $criteria['dateFin_max'];
        }
           if (isset($criteria['byid'])) {
             $query->andWhere('a.id = :myid');
            //   ->groupby('a.name');
            $parameters['myid'] = (string) $criteria['byid'];
          }
        
        // Supprimer champs qui ne sont pas dans la classe
        foreach ($criteria as $field => $value) {
            if (!$this->getClassMetadata()->hasField($field)) {
                // Make sure we only use existing fields (avoid any injection)
                unset($criteria[$field]);
                //  continue;
            }
        }

        //les like
        $like_arrays = array('nom', 'description','ticketExt', 'ticketInt');
        foreach ($like_arrays as $val) {
            //  echo "val=$val<br>";
            if (isset($criteria[$val]) && !preg_match('/^\s*$/', $criteria[$val])) {

                //   if (isset($criteria[$val]) && ! preg_match('/[\s]+/',$criteria[$val])) {
                //      echo "critere=" . $criteria["$val"] . "<br>";
                $query->andWhere("a.$val LIKE :$val");

                $parameters[$val] = '%' . $criteria[$val] . '%';
            }
        }

        $query->setParameters($parameters);


        return $query;
    }

    public function myFindsimpleAll($criteria = array()) {

        //$em = $this->getDoctrine()->getManager();
        $em = $this->getManager();
        //$values=array('a,partial b.{id,nomprojet},partial c.{id,nomUser},partial d.{id,nom,description},f,partial h.{id}');
        $values = 'a,partial b.{id,nomprojet},partial c.{id,nomUser},partial d.{id,nom,description},f,partial h.{id},';
        //   $values='a,partial b.{id,nomprojet},partial c.{id,nomUser},partial GroupConcat(e.nomUser),partial d.{id,nom,description},f,partial h.{id}';
        $query = $em->createQuery("
            SELECT $values FROM ApplicationChangementsBundle:Changements a 
            LEFT JOIN a.idProjet b
           LEFT JOIN a.demandeur c 
            LEFT JOIN a.idStatus d 
            LEFT JOIN a.idusers e 
             LEFT JOIN a.picture f
            LEFT JOIN a.idEnvironnement g 
           LEFT JOIN a.comments h
            GROUP BY a.id
           "
        );
        /* GroupConcat(e.nomUser) */
        return $query;
    }

    public function getEventsQueryBuilder(\DateTime $begin, \DateTime $end, array $options = array()) {
        $qb = $this->createQueryBuilder('e');

        return QueryHelper::addEventQuery($qb, 'e.dateDebut', 'e.dateFin', $begin, $end)
                        ->getQuery()
                        ->getResult();
        ;
    }

    public function getEvents(\DateTime $begin, \DateTime $end, array $options = array()) {
    //    echo "getEvents query";exit(1);
        return $this->getEventsQueryBuilder($begin, $end, $options);
    }

  
    public function sum_appli_year($year = null) {

            $current_date = new \DateTime();
          if (! isset($year)){
            $year= $current_date->format('Y');
        }
       $query = $this->createQueryBuilder('a')
                ->select('count(a.id) as nb,b.nomprojet,MONTH(a.dateDebut) as mois')
                ->leftJoin('a.idProjet', 'b')
                ->andWhere('a.dateDebut LIKE :date')
                ->groupby('b.nomprojet');
       
       // pkoi 2 ??
        $parameters['date'] = '%' . $year . '-%';
        //  echo "year=" . $parameters['date'] . "<br>";
        $query->setParameters($parameters);

        // Pour faire des % 
        $qa = $this->createQueryBuilder('a')->select('COUNT(a.id)')
                ->where('a.dateDebut LIKE :madate')
                ->setParameter('madate', '%' . $year . '-%')
                ->getQuery()
                ->getSingleScalarResult();
    $datas = array();
        foreach ($query->getQuery()->getScalarResult() as $valeur) {
      //     echo "qa=$qa " . $valeur['nomprojet'] . "=" . $valeur['nb'] . "<br>";
// echo $valeur['nomprojet'] . "--" . $valeur['mois'] . "<br>";
            array_push($datas, array($valeur['nomprojet'], round(($valeur['nb'] / $qa) * 100)));
        }
       //    exit(1);
        // print_r($datas);
        return $datas;
    }

    
    
    public function sum_demandeur_year($year = null) {

            $current_date = new \DateTime();
          if (! isset($year)){
            $year= $current_date->format('Y');
        }
       $query = $this->createQueryBuilder('a')
                ->select('count(a.id) as nb,b.nomUser,MONTH(a.dateDebut) as mois')
                ->leftJoin('a.demandeur', 'b')
                ->andWhere('a.dateDebut LIKE :date')
               ->groupby('b.nomUser');
        $parameters['date'] = '%' . $year . '-%';
        //  echo "year=" . $parameters['date'] . "<br>";
        $query->setParameters($parameters);

        $qa = $this->createQueryBuilder('a')->select('COUNT(a.id)')
                ->where('a.dateDebut LIKE :madate')
                ->setParameter('madate', '%' . $year . '-%')
                ->getQuery()
                ->getSingleScalarResult();
    $datas = array();
        foreach ($query->getQuery()->getScalarResult() as $valeur) {
            // echo $valeur['nomprojet'] . "--" . $valeur['mois'] . "<br>";
            array_push($datas, array($valeur['nomUser'], round(($valeur['nb'] / $qa) * 100)));
        }
        //   exit(1);
        // print_r($datas);
        return $datas;
    }
    public function getNbTopicParForums() {
        $qb = $this->createQueryBuilder('f')
                ->join('f.topics', 't')
                ->addSelect('COUNT(t)')
                ->groupBy('f.id');

        $entites = $qb->getQuery()
                ->getScalarResult();

        foreach ($entites as $valeur) {
            return $valeur;
        }
    }

    /*

      SELECT
      MONTH(c0_.date_debut) AS sclr0,
      c1_.nomprojet AS nomprojet1,
      count(c0_.id) AS sclr2
      FROM
      changements_main c0_
      LEFT JOIN certificats_projet c1_ ON c0_.id_projet = c1_.id
      WHERE
      c0_.date_debut LIKE '%2013%'
      GROUP BY
      sclr0,
      nomprojet1

      SELECT MONTH( c0_.date_debut ) AS mois, c1_.nomprojet AS nomprojet1, c0_.id_projet AS idprojet, count( c0_.id ) AS sclr2
      FROM changements_main c0_
      LEFT JOIN certificats_projet c1_ ON c0_.id_projet = c1_.id
      GROUP BY nomprojet1,mois
      ORDER BY mois
     */

    public function sum_allappli_bymonthyear($year = null) {
         $current_date = new \DateTime();
         if (! isset($year)){
            $year= $current_date->format('Y');
        }
        $query = $this->createQueryBuilder('a')
                //->select('MONTH(a.dateDebut) as mois,sum(b.nomprojet) as projet,count(a.id) as nb')
                ->select('MONTH(a.dateDebut) as mois,count(a.id) as nb')
                //->select('count(a.id) as nb,a.dateDebut,b.nomprojet,MONTH(a.dateDebut) as mois')
                ->leftJoin('a.idProjet', 'b')
                ->andWhere('a.dateDebut LIKE :date')
                ->groupby('mois');
        $parameters['date'] = '%' . $year . '-%';
        $query->setParameters($parameters);
        //return $query->getQuery();
         return $query->getQuery();
    }

    public function sum_appli_monthyear($year = null) {

          $current_date = new \DateTime();
          if (! isset($year)){
            $year= $current_date->format('Y');
        }

        $query = $this->createQueryBuilder('a')
                ->select('MONTH(a.dateDebut) as mois,b.nomprojet as projet,count(a.id) as nb')
                //->select('count(a.id) as nb,a.dateDebut,b.nomprojet,MONTH(a.dateDebut) as mois')
                ->leftJoin('a.idProjet', 'b')
                ->andWhere('a.dateDebut LIKE :date')
                ->groupby('mois,projet');



        $parameters['date'] = '%' . $year . '-%';
        //  echo "year=" . $parameters['date'] . "<br>";
        $query->setParameters($parameters);

        return $query->getQuery();
        //   ->getArrayResult();
        /* $qa = $this->createQueryBuilder('a')->select('COUNT(a.id)')
          ->where('a.dateDebut LIKE :madate')
          ->setParameter('madate', '%' . $year . '%')
          ->getQuery()
          ->getSingleScalarResult();

          // echo "xnb=$qa<br>";
          ///* $datas = array();
          foreach ($query->getScalarResult() as $valeur) {
          print_r($valeur);
          //echo $valeur['nomprojet'] . "--" . $valeur['mois'] . "<br>";
          array_push($datas, array($valeur['nomprojet'], round(($valeur['nb'] / $qa) * 100)));
          } */
        //   return($query->getQuery());
        /*  if (isset($year))
          $current_year = $year;
          else
          $current_year = date('Y');
          $cols = $this->t_cols;

          //$query = $em->createQuery('SELECT partial u.{id, username}, partial a.{id, name} FROM CmsUser u JOIN u.articles a');
          $select = $this->select()
          ->setIntegrityCheck(false)
          ->from($this->_name, array(
          'nb_demande' => new Zend_Db_Expr('COUNT(chrono_center.id)'),
          'mois' => new Zend_Db_Expr('MONTHNAME(demande)'),
          ))
          ->joinLeft($this->_join_name_proj, $this->_join_cols_proj, $this->_join_val_proj)
          ->where('YEAR(demande) = ?', $current_year)
          ->group(new Zend_Db_Expr('MONTH(demande)'))
          ->group('nom_projet');



          return($select);
         * 
         * 
         * 
         * 
         * 
         * 
          SELECT
          count(c0_.id) AS sclr0,
          c1_.nomprojet AS nomprojet1,
          MONTH(c0_.date_debut) AS sclr2
          FROM
          changements c0_
          LEFT JOIN certificats_projet c1_ ON c0_.id_projet = c1_.id
          WHERE
          c0_.date_debut LIKE '%2013%'
          GROUP BY
          sclr2
         * 
         */

        /* $em = $this->getDoctrine()->getManager(); */
        /* $em = $this->getManager();
          $query = $em->createQuery("
          SELECT COUNT(a.id), FROM ApplicationChangementsBundle:Changements a
          WHERE a.dateDebut LIKE ?1 GROUP BY a.name");
          $query->setParameter(1, '2013'); */
        //$users = $query->getResult(Query::HYDRATE_OBJECT);
    }

    /* public function createQueryBuilderForGetEvent(array $options)
      {
      // do what you want with the $option array
      return $this->createQueryBuilder('evt')
      ->setMaxResults(10)
      ;
      }


      public function getBeginFieldName()
      {
      return 'evt.beginDate';
      }


      public function getEndFieldName()
      {
      return 'evt.endDate';
      } */
}

