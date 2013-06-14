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

//class ChangementsRepository extends EntityRepository{
    //use EventRepositoryTrait;
    public function myFindaAll() {
        return $this->createQueryBuilder('a')
                        ->orderBy('id')
                        ->getQuery();

        //->getResult();
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

    public function myFindbAll() {
        //$fields = array('d.id', 'd.name', 'o.id');
        //->select($fields)
        //  $fields = array('a', 'b.id','b.nomprojet','c','d','f','h');
//$fields = 'partial d.{id, name}, partial o.{id}';  //if you want to get entity object
        /* $query = $em->createQuery('SELECT DISTINCT c0_.id AS id0, c0_.nom AS nom1, c0_.date_debut AS date_debut2, c0_.date_fin AS date_fin3, c1_.nomprojet AS nomprojet4, c2_.nom_user AS nom_user5, GROUP_CONCAT( DISTINCT c3_.nom_user ) AS sclr6, GROUP_CONCAT( DISTINCT e4_.nom ) AS sclr7
          FROM changements c0_
          LEFT JOIN certificats_projet c1_ ON c0_.id_projet = c1_.id
          LEFT JOIN chrono_user c2_ ON c0_.demandeur = c2_.id
          INNER JOIN changements_users c5_ ON c0_.id = c5_.changements_id
          LEFT JOIN chrono_user c3_ ON c3_.id = c5_.chronouser_id
          LEFT JOIN changements_environnements c6_ ON c0_.id = c6_.changements_id
          LEFT JOIN environnement e4_ ON e4_.id = c6_.environnements_id
          GROUP BY c0_.id
          ORDER BY `c1_`.`nomprojet` ASC');

          $em = $this->getDoctrine()->getManager();
          $qb = $em->createQueryBuilder();
          $result = $qb->select('n.user1lname')->from('MySite\MyBundle\Entity\User', 'n')
          ->where($qb->expr()->like('n.user1lname', $qb->expr()->literal($searchTerm.'%')))
          ->getQuery()
          ->getResult();
         */
        return $this->createQueryBuilder('a')
                        //  ->select($fields)
                        //GROUP_CONCAT( DISTINCT c3_.nom_user ) 
                        ->select(array('a,b,c,d,f,h'))
                        ->leftJoin('a.idProjet', 'b')
                        ->leftJoin('a.demandeur', 'c')
                        ->leftJoin('a.idStatus', 'd')
                        ->leftJoin('a.idusers', 'e')
                        ->leftJoin('a.picture', 'f')
                        ->leftJoin('a.idEnvironnement', 'g')
                        ->leftJoin('a.comments', 'h')
                        ->groupby('a.nom')
                        ->add('orderBy', 'a.id DESC');
        // ->getQuery();
        //   ->leftJoin('a.demandeur', 'c')
        //  ->getQuery();
    }

    public function getMyPager(array $criteria, $ret = 'getquery') {

        $parameters = array();
        $query = $this->createQueryBuilder('a')
                ->select('a,b,c,d,e,f')
                ->add('orderBy', 'a.id DESC')
                ->leftJoin('a.proprietaire', 'b')
                //  ->leftJoin($join, $alias, $conditionType)
                ->leftJoin('a.categorie', 'c')
                ->leftJoin('a.idStatus', 'd')
                ->leftJoin('a.globalnote', 'e')
                ->leftJoin('a.imageMedia', 'f')
        ;
        if (isset($criteria['author'])) {
            //  print_r($criteria);exit(1);
            $query->andwhere('a.proprietaire = :proprietaire');
            $parameters['proprietaire'] = $criteria['author'];
        }


        if (isset($criteria['non-author'])) {
            //  print_r($criteria);exit(1);
            $query->andWhere('a.proprietaire <> :user_id');
            $parameters['user_id'] = $criteria['non-author'];
        }



        if (isset($criteria['alltags'])) {
            $query->addSelect('t');
            $query->leftJoin('a.tags', 't');
        }
        if (isset($criteria['year'])) {
            // echo "year=" . $criteria['year'] . "<br>";exit(1);
            $query->andWhere('a.createdAt LIKE :year');
            $parameters['year'] = '%' . $criteria['year'] . '%';
        }
        if (isset($criteria['date'])) {
            // echo "year=" . $criteria['year'] . "<br>";exit(1);
            $query->andWhere('a.createdAt LIKE :date');
            $parameters['date'] = '%' . $criteria['date'] . '%';
        }
        if (isset($criteria['tag'])) {
            $query->addSelect('t');
            $query->leftJoin('a.tags', 't');
            $query->andWhere('t.id = :tag');
            //   ->groupby('a.name');
            $parameters['tag'] = (string) $criteria['tag'];
            //       $parameters['tag'] = 'tag1';
        }
        $query->setParameters($parameters);
        // ??
        $query->groupby('a.name');
        //>getQuery();
        //  print_r($query->getQuery());
        //  exit(1);
        if ($ret == 'query')
            return $query;
        else
            return $query->getQuery();
        //return $query->getQuery()->getResult();
    }
public function myFindtstAll($criteria=array()) {
         $parameters = array();
         $query = $this->createQueryBuilder('a')
                ->select(array('a,b,c,d,f,h'))
                         ->leftJoin('a.idProjet', 'b')
                        ->leftJoin('a.demandeur', 'c')
                        ->leftJoin('a.idStatus', 'd')
                      ->leftJoin('a.idusers', 'e')
                        ->leftJoin('a.picture', 'f')
                ->leftJoin('a.idEnvironnement','g')
                  ->leftJoin('a.comments','h');
       $query->groupby('a.nom')
               ->add('orderBy', 'a.id DESC');
    
       return $query;
        
    }
    
     public function myFindnnAll($criteria = array()) {

       $parameters = array();
       $values=array('a,partial b.{id,nomprojet},partial c.{id,nomUser},partial d.{id,nom,description},f,partial h.{id}');
       $query = $this->createQueryBuilder('a')
                ->select($values)
                ->leftJoin('a.idProjet', 'b')
                ->leftJoin('a.demandeur', 'c')
                ->leftJoin('a.idStatus', 'd')
                  ->leftJoin('a.picture', 'f')
               
                ->addSelect('g')
                ->distinct('GroupConcat(nom)')
                ->leftJoin('a.idEnvironnement', 'g')
                 ->leftJoin('a.comments', 'h')
                ->addSelect('e')
          
              ->distinct('GroupConcat(nomUser)')
                ->leftJoin('a.idusers', 'e');
                  $query->add('orderBy', 'a.id DESC');
        return $query;
        //->getQuery();
    }

     public function myFindAll($criteria = array()) {

           $parameters = array();
        $values=array('a,partial b.{id,nomprojet},partial c.{id,nomUser},partial d.{id,nom,description},f,partial h.{id}');
        $query = $this->createQueryBuilder('a')
                    ->select($values)
                 ->leftJoin('a.idProjet', 'b')
                ->leftJoin('a.demandeur', 'c')
                ->leftJoin('a.idStatus', 'd')
                  ->leftJoin('a.picture', 'f')
                
                ->addSelect('g')
                //->addSelect('g')
                ->distinct('GroupConcat(g.nom)')
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

     public function myFind11All($criteria = array()) {

            $parameters = array();
        $values=array('a,partial b.{id,nomprojet},partial c.{id,nomUser},partial d.{id,nom,description},f,partial h.{id}');
       $query = $this->createQueryBuilder('a')
                ->select($values)
                ->leftJoin('a.idProjet', 'b')
                ->leftJoin('a.demandeur', 'c')
                ->leftJoin('a.idStatus', 'd')
                 ->leftJoin('a.picture', 'f')
                 ->addSelect('g')
                ->distinct('GroupConcat(nom)')
                ->leftJoin('a.idEnvironnement', 'g')
                  ->leftJoin('a.comments', 'h')
                ->addSelect('e')
                  ->distinct('GroupConcat(nomUser)')
                ->leftJoin('a.idusers', 'e')
       
               ;
                  $query->add('orderBy', 'a.id DESC');
        return $query;
  
    }

      public function myFindsimpleAll($criteria = array()) {

        //$em = $this->getDoctrine()->getManager();
      $em = $this->getManager();
        //$values=array('a,partial b.{id,nomprojet},partial c.{id,nomUser},partial d.{id,nom,description},f,partial h.{id}');
       $values='a,partial b.{id,nomprojet},partial c.{id,nomUser},partial d.{id,nom,description},f,partial h.{id},';
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
        /* GroupConcat(e.nomUser)*/
     return $query;
      }
      
    public function myFindoldAll($criteria = array()) {

         $parameters = array();
   //           $values=array('a,partial b.{id,nomprojet},partial c.{id,nomUser},partial d.{id,nom,description},f,partial h.{id}');
   
        $values=array('a,partial b.{id,nomprojet},partial c.{id,nomUser},partial d.{id,nom,description},f,partial h.{id}');
         //$values=array('a,partial b.{id,nomprojet},partial c.{id,nomUser},partial d.{id,nom,description},f,partial h.{id},GroupConcat(i.nomUser)');
            
       $query = $this->createQueryBuilder('a')
                   ->select($values)
                ->leftJoin('a.idProjet', 'b')
                ->leftJoin('a.demandeur', 'c')
                ->leftJoin('a.idStatus', 'd')
                 ->leftJoin('a.picture', 'f')
                ->addSelect('g')
                ->distinct('GroupConcat(nom)')
                ->leftJoin('a.idEnvironnement', 'g')
               ->leftJoin('a.comments', 'h')
                ->addSelect('e')
     
              ->distinct('GroupConcat(nomUser)')
                ->leftJoin('a.idusers', 'e')
               
              //  ->addselect('GroupConcat(e.nomUser)');;
        //   $query->setParameters($parameters);
        // ??
               ;
      // $query->addSelect('distinct i.nomUser');
      //  $query->groupby('a.id');
               $query->add('orderBy', 'a.id DESC');
        return $query;
        //->getQuery();
    }

    /* $em = $this->getManager();
      $query = $em->createQuery('SELECT DISTINCT c0_.id AS id0, c0_.nom AS nom1,
     * c0_.date_debut AS date_debut2, c0_.date_fin AS date_fin3, c1_.nomprojet AS nomprojet4,
     * c2_.nom_user AS nom_user5, GROUP_CONCAT( DISTINCT c3_.nom_user ) AS sclr6, GROUP_CONCAT( DISTINCT e4_.nom ) AS sclr7
      FROM changements c0_
      LEFT JOIN certificats_projet c1_ ON c0_.id_projet = c1_.id
      LEFT JOIN chrono_user c2_ ON c0_.demandeur = c2_.id
      INNER JOIN changements_users c5_ ON c0_.id = c5_.changements_id
      LEFT JOIN chrono_user c3_ ON c3_.id = c5_.chronouser_id
      LEFT JOIN changements_environnements c6_ ON c0_.id = c6_.changements_id
      LEFT JOIN environnement e4_ ON e4_.id = c6_.environnements_id
      GROUP BY c0_.id
      ORDER BY `c1_`.`nomprojet` ASC');
      return $query->getResult(); */

    //$fields = array('d.id', 'd.name', 'o.id');
    //->select($fields)
    //  $fields = array('a', 'b.id','b.nomprojet','c','d','f','h');
//$fields = 'partial d.{id, name}, partial o.{id}';  //if you want to get entity object
    /*
     * 
     * ->where('det.id IN (:miarray)')
      ->setParameter('miarray', array('143','144'))
     */
    /*   $parameters = array();
      $query = $this->createQueryBuilder('a')
      //return $this->createQueryBuilder('a')
      //  ->select($fields)
      ->select(array('a,b,c,d,f,g,h'))
      //  ->select(array('a,b,c,d,e,f,g,h'))
      ->leftJoin('a.idProjet', 'b')
      ->leftJoin('a.demandeur', 'c')
      ->leftJoin('a.idStatus', 'd')
      // ->addSelect(GroupConcat(e.nomUser, ' / '))
      ->leftJoin('a.idusers', 'e')
      ->leftJoin('a.picture', 'f')
      ->leftJoin('a.idEnvironnement','g')
      // ->where('g.id = :changement_id')
      // ->setParameter('changement_id', 3)
      ->leftJoin('a.comments','h');
      //   $query->setParameters($parameters);
      // ??
      $query->groupby('a.id')
      ->add('orderBy', 'a.id DESC');

      return $query;

     */

    /*
      $em = $this->getManager();

      $query = "SELECT DISTINCT
      a.id AS id0,
      a.nom AS nom1,
      a.date_debut AS date_debut2,
      a.date_fin AS date_fin3,
      c1_.nomprojet AS nomprojet4,
      c2_.nom_user AS nom_user5,
      GROUP_CONCAT(distinct c3_.nom_user) AS sclr6,
      GROUP_CONCAT(distinct e4_.nom) AS sclr7
      FROM
      changements a
      LEFT JOIN certificats_projet c1_ ON a.id_projet = c1_.id
      LEFT JOIN chrono_user c2_ ON a.demandeur = c2_.id
      LEFT JOIN changements_users c5_ ON a.id = c5_.changements_id
      LEFT JOIN chrono_user c3_ ON c3_.id = c5_.chronouser_id
      LEFT JOIN changements_environnements c6_ ON a.id = c6_.changements_id
      LEFT JOIN environnement e4_ ON e4_.id = c6_.environnements_id
      GROUP BY
      a.id
      ORDER BY
      sclr6 ASC";
      $rsm = new ResultSetMapping;

      //$rsm->addFieldResult('t', 'id', 'id');

      $qns = $this->_em->createNativeQuery($query, $rsm);

      $qns->getResult();
      /*
     * SELECT DISTINCT a,b,c,d,GroupConcat( DISTINCT e.nomUser ),f,h FROM Application\ChangementsBundle\Entity\Changements a 
      LEFT JOIN a.idProjet b LEFT JOIN a.demandeur c LEFT JOIN a.idStatus d
      LEFT JOIN a.idusers e LEFT JOIN a.picture f
      LEFT JOIN a.idEnvironnement g
      LEFT JOIN a.comments h GROUP BY a.nom ORDER BY a.id DESC');
     */
    /*
      $query = $em->createQuery('SELECT DISTINCT a,b,c,d,f,h FROM Application\ChangementsBundle\Entity\Changements a
      LEFT JOIN a.idProjet b LEFT JOIN a.demandeur c LEFT JOIN a.idStatus d LEFT JOIN a.idusers e LEFT JOIN a.picture f LEFT JOIN a.idEnvironnement g LEFT JOIN a.comments h GROUP BY a.nom ORDER BY a.id DESC');
     */
    // return $qns;
    //->getQuery();
    // ->groupby('a.nom')
    // ->getQuery();
    //   ->leftJoin('a.demandeur', 'c')
    //  ->getQuery();
    //  }
    public function getEventsQueryBuilder(\DateTime $begin, \DateTime $end, array $options = array()) {
        $qb = $this->createQueryBuilder('e');

        return QueryHelper::addEventQuery($qb, 'e.dateDebut', 'e.dateFin', $begin, $end)
                        ->getQuery()
                        ->getResult();
        ;
    }

    public function getEvents(\DateTime $begin, \DateTime $end, array $options = array()) {
        return $this->getEventsQueryBuilder($begin, $end, $options);
    }

    public function sum_appli_year($year = null) {

        if (isset($year))
            $current_year = $year;
        else
            $current_year = date('Y');

      /*  $parameters = array();
        $query = $this->createQueryBuilder('a')
                ->select('count(a.id) as nb,b.nomprojet')
                ->leftJoin('a.idProjet', 'b')
                ->andWhere('a.dateDebut LIKE :date')
                ->groupby('b.nomprojet');
        $parameters['date'] = '%' . $year . '%';
      //  echo "year=" . $parameters['date'] . "<br>";
        $query->setParameters($parameters);*/
        
         $query = $this->createQueryBuilder('a')
                ->select('count(a.id) as nb,b.nomprojet,MONTH(a.dateDebut) as mois')
                ->leftJoin('a.idProjet', 'b')
                ->andWhere('a.dateDebut LIKE :date')
                ->groupby('b.nomprojet');
        $parameters['date'] = '%' . $year . '%';
      //  echo "year=" . $parameters['date'] . "<br>";
        $query->setParameters($parameters);
        
        $qa = $this->createQueryBuilder('a')->select('COUNT(a.id)')
                ->where('a.dateDebut LIKE :madate')
                ->setParameter('madate', '%' . $year . '%')
                ->getQuery()
                ->getSingleScalarResult();

       // echo "xnb=$qa<br>";
        //exit(1);
        $datas = array();
        foreach ($query->getQuery()->getScalarResult() as $valeur) {
           // echo $valeur['nomprojet'] . "--" . $valeur['mois'] . "<br>";
            array_push($datas, array($valeur['nomprojet'], round(($valeur['nb'] / $qa) * 100)));
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

    public function sum_appli_monthyear($year = null) {

        
         $query = $this->createQueryBuilder('a')
                   ->select('MONTH(a.dateDebut) as mois,b.nomprojet,count(a.id) as nb')
                //->select('count(a.id) as nb,a.dateDebut,b.nomprojet,MONTH(a.dateDebut) as mois')
                ->leftJoin('a.idProjet', 'b')
                ->andWhere('a.dateDebut LIKE :date')
                  ->groupby('mois');
                 // ->groupby('b.nomprojet');
               
               
        $parameters['date'] = '%' . $year . '%';
      //  echo "year=" . $parameters['date'] . "<br>";
        $query->setParameters($parameters);
       
        $query->getQuery();
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
        }*/
        return($query->getQuery());
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
      
       /* $em = $this->getDoctrine()->getManager();*/
       /* $em = $this->getManager();
        $query = $em->createQuery("
            SELECT COUNT(a.id), FROM ApplicationChangementsBundle:Changements a 
            WHERE a.dateDebut LIKE ?1 GROUP BY a.name");
        $query->setParameter(1, '2013');*/
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

