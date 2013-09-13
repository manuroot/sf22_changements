<?php

namespace Application\ChangementsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use APY\DataGridBundle\Grid\Mapping as GRID;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\MinLength;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\Date;
use Application\RelationsBundle\Entity\Environnements;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use CalendR\Event\AbstractEvent;

//use Application\RelationsBundle\Entity\Document;
/**
 * Changements
 *
 * @ORM\Table(name="changements_main")
 * @ORM\HasLifecycleCallbacks
 * @ORM\Entity(repositoryClass="Application\ChangementsBundle\Repository\ChangementsRepository")
 * @GRID\Source(columns="id,nom,ticketExt,ticketInt,dateDebut,dateFin,idProjet.nomprojet,demandeur.nomUseridStatus.nom,idStatus.nom,idEnvironnement.nom")
 * @Vich\Uploadable
 */
//* @GRID\Source(columns="id,nom,dateDebut,dateFin,idProjet.nomprojet,demandeur.nomUser,idusers.nomUser:GroupConcat,idEnvironnement.nom:GroupConcat",groupBy={"id"})
// @GRID\Source(columns="id,nom,dateDebut,dateFin,idProjet.nomprojet,demandeur.nomUser,idEnvironnement.nom:concat_ws",groupBy={"id"})
// @GRID\Source(columns="id,nom,dateDebut,dateFin,idProjet.nomprojet,demandeur.nomUser,idusers.nomUser:GroupConcat,idEnvironnement.nom:GroupConcat",groupBy={"id"})
 //* @GRID\Source(columns="id,nom,ticketExt,ticketInt,dateDebut,dateFin,idProjet.nomprojet,demandeur.nomUseridStatus.nom,idStatus.nom,idEnvironnement.nom:GroupConcat",groupBy={"id"})

class Changements extends AbstractEvent {

//class Changements


    protected $begin;
    protected $end;
    protected $uid;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @GRID\Column(title="id", size="10", type="text",filterable="false")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=50, nullable=false)
     * @ORM\OrderBy({"nom" = "ASC"})
     * @Assert\Length(
     *      min = "5",
     *      max = "40",
     *      minMessage = "Your name must be at least {{ limit }} characters length |
     *  Au minimum {{ limit }} caracteres",
     *      maxMessage = "Your first name cannot be longer than than {{ limit }} characters length |
     *  Au maximum {{ limit }} caracteres"
     * )
     *
     * @GRID\Column(field="nom", title="Nom",size="80")
     */
    private $nom;

     /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_demande", type="date", nullable=false)
     */
    private $dateDemande;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_debut", type="datetime", nullable=false)
     * @GRID\Column(title="Début", size="30",format="Y-m-d",type="datetime")
     */
    private $dateDebut;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_fin", type="datetime", nullable=true)
     * @GRID\Column(title="Fin", size="30",format="Y-m-d",type="datetime")
     * 
     */
    private $dateFin;
    // @GRID\Column(title="Fin", size="40",format="Y-m-d h:i",type="datetime")
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_comep", type="datetime", nullable=true)
     * @GRID\Column(title="COMEP", size="50",format="Y-m-d",type="datetime")
     */
    private $dateComep;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_vsr", type="date", nullable=true)
     * @GRID\Column(title="VSR", size="50",format="Y-m-d",type="datetime")
     */
    private $dateVsr;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

        /**
     * @var \Projet
     *
     * @ORM\ManyToOne(targetEntity="Application\ChangementsBundle\Entity\KindChangements")
     * @ORM\OrderBy({"nom" = "ASC"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_kind", referencedColumnName="id",nullable=true)
     * })
     */
    private $idKind;
    
    
    /**
     * @var \Projet
     *
     * @ORM\ManyToOne(targetEntity="Application\RelationsBundle\Entity\Projet",inversedBy="idchangement")
     * @ORM\OrderBy({"nomprojet" = "ASC"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_projet", referencedColumnName="id",nullable=false)
     * })
     * @GRID\Column(field="idProjet.nomprojet", title="Projet",size="20",filter="select",selectFrom="query")

     */
    private $idProjet;
//selectMulti="false",
    /**
     * @var string
     * 
     * @ORM\ManyToMany(targetEntity="Docchangements", inversedBy="idchangement",cascade={"persist"})
     * @ORM\JoinTable(name="changements_documents",
     * joinColumns={@ORM\JoinColumn(name="changements_id", referencedColumnName="id")},
     *   inverseJoinColumns={@ORM\JoinColumn(name="docchangements_id", referencedColumnName="id")}
     * )
     */
    protected $picture;

    /**
     * @var string
     * 
     * coté proprietaire (inversedBy)
     * 
     * @ORM\ManyToMany(targetEntity="Application\RelationsBundle\Entity\Environnements",inversedBy="idchangements",cascade={"persist"})
     * @ORM\OrderBy({"nom" = "ASC"})
     * @ORM\JoinTable(name="changements_environnements")
     * @GRID\Column(field="idEnvironnement.nom", filterable=true,size="30",title="Env", filter="select",selectFrom="query")
     */
    private $idEnvironnement;
    
    //values={"type1"="Production"})
    //* @GRID\Column(field="idEnvironnement.nom:GroupConcat", filterable=true,size="100",title="Env", filter="select",selectFrom="query")
    // * @ORM\ManyToMany(targetEntity="Application\RelationsBundle\Entity\Environnements",inversedBy="idchangements",cascade={"persist"})
    //* @GRID\Column(type="extended_text", field="idEnvironnement.nom:AtGroupConcat", title="Categories", filter="select", selectMulti="true", selectFrom="values")
    //  * @GRID\Column(type="extended_text", field="idEnvironnement.nom:AtGroupConcat", filterable=true,size="20",title="Env", filter="select",selectMulti="false",selectFrom="values")
    //title="Categories", filter="select", selectMulti="true", selectFrom="values")
    //last:* @GRID\Column(field="idEnvironnement.nom:GroupConcat", filterable=true,size="20",title="Env", filter="select",selectFrom="query")
    // @GRID\Column(title="Env", field="idEnvironnement", size="30", visible=true, sortable=true, filtrable="true")
    // GRID\Column(title="Environnements", field="idEnvironnement.nom:concat_ws",  visible=true, sortable=true, filter="select",selectFrom="query")

    /**
     * coté proprietaire (inversedBy)
     * 
     *  @ORM\ManyToMany(targetEntity="Application\RelationsBundle\Entity\Applis", inversedBy="idprojets",cascade={"persist"})
     * @ORM\OrderBy({"nomapplis" = "ASC"})
     * @ORM\JoinTable(name="changements_applis")
     */
    private $idapplis;

      /**
     * coté proprietaire (inversedBy)
     * 
     * @ORM\ManyToMany(targetEntity="Application\Sonata\UserBundle\Entity\User",cascade={"persist"})
      * @ORM\JoinTable(name="changements_favoris",
     * joinColumns={@ORM\JoinColumn(name="changements_id", referencedColumnName="id")},
     *   inverseJoinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")}
     * )
     * @GRID\Column(title="Favoris",field="idusers.nomUser:GroupConcat", size="20",title="Users", filter="select")
     */
    private $idfavoris;
    
    
    /**
     * coté proprietaire (inversedBy)
     * 
     * @ORM\ManyToMany(targetEntity="Application\RelationsBundle\Entity\ChronoUser", inversedBy="idchangement",cascade={"persist"})
      * @ORM\OrderBy({"nomUser" = "ASC"})
      * @ORM\JoinTable(name="changements_users")
     * @GRID\Column(title="Users",field="idusers.nomUser:GroupConcat", size="20",title="Users", filter="select")
     */
    private $idusers;

    //  * @GRID\Column(field="idusers.nomUser:GroupConcat", size="20",title="Users", filter="select")
// @GRID\Column(title="Users", field="idusers.nomUser:GroupConcat", size="20", visible=true, sortable=true, filtrable="true")

    /**
     * @var \ChronoUser
     *
     * @ORM\ManyToOne(targetEntity="Application\RelationsBundle\Entity\ChronoUser")
     * @ORM\JoinColumn(name="demandeur", referencedColumnName="id")
     * @GRID\Column(field="demandeur.nomUser", title="Demandeur",size="20",filter="select",selectFrom="query")
     */
    private $demandeur;

    /**
     * 
     * mapped:ok, coté proprietaire ??
     * @var \ChangementStatus
     *
     * @ORM\ManyToOne(targetEntity="ChangementsStatus")
     * @ORM\OrderBy({"nom" = "ASC"})
     * @ORM\JoinColumn(name="id_status", referencedColumnName="id",nullable=false)
     * @GRID\Column(field="idStatus.nom", title="Status",size="10",filter="select",selectFrom="query")
     */
    private $idStatus;

    //  * @ORM\ManyToOne(targetEntity="ChangementsStatus", inversedBy="idchangements")
    
    /**
     * not proprietaire side (mappedby)
     * 
     * @ORM\OneToMany(targetEntity="ChangementsComments", mappedBy="changement",cascade={"persist"})
     */
    private $comments;

    /**
     * @ORM\Column(type="string", length=12, name="ticket_ext", nullable=true)
     * @GRID\Column(type="text",field="ticketExt", title="Ticket_Ext",size="12")
     *
     * @Assert\Regex(
     *     pattern="/([1-9]\-[0-9]{5,10}|[0-9]{5,10})/",
     *     match=true,
     *     message="patterns autorisées ex: 1-12345678 ou 1234567"
     * )
     * @var string $ticket_ext
     */
    private $ticketExt;
// pattern="/(^[1-9]-[0-9]+$|[0-9]{5,10})/",

    /**
     * @ORM\Column(type="integer", length=5, name="ticket_int", nullable=true)
     * @GRID\Column(type="text",field="ticketInt", title="Ticket_Int",size="10")
     *  * @Assert\Regex(
     * pattern="/^[0-9]{5,6}/",
     *     match=true,
     *     message="patterns autorisées ex: 12345 (5 a 10 car.)"
     * )
     * @var integer $ticket_int
     */
    private $ticketInt;

    /* //**
      //* @Assert\File(
     *     maxSize="1M",
     *     mimeTypes={"image/png", "image/jpeg", "image/pjpeg"}
     * )
     * @Vich\UploadableField(mapping="user_avatar", fileNameProperty="avatar")
     * @ORM\Column(type="string", length=255)
     *
     * @var File $avatar
     */
    
     /**
     * @var boolean
     *
     * @ORM\Column(name="astreinte", type="boolean", nullable=true)
     */
    private $astreinte;
    
      
//protected $avatar;


    /*  public function getUid()
      {
      return $this->uid;
      }

      public function getBegin()
      {
      return $this->begin;
      }

      public function getEnd()
      {
      return $this->end;
      } */

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set nom
     *
     * @param string $nom
     * @return Changements
     */
    public function setNom($nom) {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string 
     */
    public function getNom() {
        return $this->nom;
    }

    /**
     * Set dateDebut
     *
     * @param \DateTime $dateDebut
     * @return Changements
     */
    public function setDateDebut($dateDebut) {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    /**
     * Get dateDebut
     *
     * @return \DateTime 
     */
    public function getDateDebut() {
        return $this->dateDebut;
    }

    /**
     * Set dateFin
     *
     * @param \DateTime $dateFin
     * @return Changements
     */
    public function setDateFin($dateFin) {
        $this->dateFin = $dateFin;

        return $this;
    }

    /**
     * Get dateFin
     *
     * @return \DateTime 
     */
    public function getDateFin() {
        return $this->dateFin;
    }

    /**
     * Set dateComep
     *
     * @param \DateTime $dateComep
     * @return Changements
     */
    public function setDateComep($dateComep) {
        $this->dateComep = $dateComep;

        return $this;
    }

    /**
     * Get dateComep
     *
     * @return \DateTime 
     */
    public function getDateComep() {
        return $this->dateComep;
    }

    /**
     * Set dateVsr
     *
     * @param \DateTime $dateVsr
     * @return Changements
     */
    public function setDateVsr($dateVsr) {
        $this->dateVsr = $dateVsr;

        return $this;
    }

    /**
     * Get dateVsr
     *
     * @return \DateTime 
     */
    public function getDateVsr() {
        return $this->dateVsr;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Changements
     */
    public function setDescription($description) {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * Set demandeur
     *
     * @param \Application\ChangementsBundle\Entity\ChronoUser $demandeur
     * @return Changements
     */
    public function setDemandeur(\Application\RelationsBundle\Entity\ChronoUser $demandeur = null) {
        $this->demandeur = $demandeur;

        return $this;
    }

    /**
     * Get idProject
     *
     * @return \Application\RelationsBundle\Entity\ChronoUser 
     */
    public function getDemandeur() {
        return $this->demandeur;
    }

    /**
     * Set idProject
     *
     * @param \Application\RelationsBundle\Entity\Projet $idProjet
     * @return Changements
     */
    public function setIdProjet(\Application\RelationsBundle\Entity\Projet $idProjet = null) {
        $this->idProjet = $idProjet;

        return $this;
    }

    /**
     * Get idProject
     *
     * @return \Application\RelationsBundle\Entity\Projet 
     */
    public function getIdProjet() {
        return $this->idProjet;
    }

    /**
     * Set idProject
     *
     * @param \Application\ChangementsBundle\Entity\ChangementsStatut $idStatus
     * @return Changements
     */
    public function setIdStatus(\Application\ChangementsBundle\Entity\ChangementsStatus $idStatus = null) {
        $this->idStatus = $idStatus;

        return $this;
    }

    /**
     * Get idProject
     *
     * @return \Application\ChangementsBundle\Entity\ChangementsStatus 
     */
    public function getIdStatus() {
        return $this->idStatus;
    }

    /**
     * Constructor
     */
    public function __construct() {
        // ????????
        $this->idusers = new ArrayCollection();
        $this->idapplis = new ArrayCollection();
        $this->picture = new ArrayCollection();
        $this->idEnvironnement = new ArrayCollection();
        $this->idfavoris = new ArrayCollection();
      $this->dateDemande = new \DateTime('now');
         $this->astreinte = false;
        //   $this->idapplis = new \Doctrine\Common\Collections\ArrayCollection();
        /*         $this->uid = $uid;
          $this->begin = clone $start;
          $this->end = clone $end; */
    }

    /**
     * Add idusers
     *
     * @param \Application\RelationsBundle\Entity\ChronoUser $idusers
     * @return Changements
     */
    public function addIduser(\Application\RelationsBundle\Entity\ChronoUser $idusers) {
        $this->idusers[] = $idusers;

        return $this;
    }

    /**
     * Remove idusers
     *
     * @param \Application\RelationsBundle\Entity\ChronoUser $idusers
     */
    public function removeIduser(\Application\RelationsBundle\Entity\ChronoUser $idusers) {
        $this->idusers->removeElement($idusers);
    }

    /**
     * Get idusers
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getIdusers() {
        return $this->idusers;
    }

    public function __toString() {
        return $this->getNom();    // this will not look good if SonataAdminBundle uses this ;)
    }

    /**
     * Add idapplis
     *
     * @param \Application\RelationsBundle\Entity\Applis $idapplis
     * @return Changements
     */
    public function addIdappli(\Application\RelationsBundle\Entity\Applis $idapplis) {
        $this->idapplis[] = $idapplis;

        return $this;
    }

    /**
     * Remove idapplis
     *
     * @param \Application\RelationsBundle\Entity\Applis $idapplis
     */
    public function removeIdappli(\Application\RelationsBundle\Entity\Applis $idapplis) {
        $this->idapplis->removeElement($idapplis);
    }

    /**
     * Get idapplis
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getIdapplis() {
        return $this->idapplis;
    }

    /**
     * Set fic
     *
     * @param string $fic
     */
    public function setFic($fic) {
        $this->fic = $fic;
    }

    /**
     * Add idEnvironnement
     *
     * @param \Application\RelationsBundle\Entity\Environnements $idEnvironnement
     * @return Changements
     */
    public function addIdEnvironnement(\Application\RelationsBundle\Entity\Environnements $idEnvironnement) {
        $this->idEnvironnement[] = $idEnvironnement;

        return $this;
    }

    /**
     * Remove idEnvironnement
     *
     * @param \Application\RelationsBundle\Entity\Environnements $idEnvironnement
     */
    public function removeIdEnvironnement(\Application\RelationsBundle\Entity\Environnements $idEnvironnement) {
        $this->idEnvironnement->removeElement($idEnvironnement);
    }

    /**
     * Get idEnvironnement
     *
     * @return \Doctrine\Common\Collections\Collection 

     */
    public function getIdEnvironnement() {
        return $this->idEnvironnement;
    }

    /**
     * Get picture
     *
     * @return Docchangements 
     */
    public function getPicture() {
        return $this->picture;
    }

    /**
     * Add picture
     *
     * @param Docchangements $picture
     * @return Changements
     */
    public function addPicture(Docchangements $picture) {
        // Si l'objet fait déjà partie de la collection on ne l'ajoute pas
        if (!$this->picture->contains($picture)) {
            $this->picture->add($picture);
        }
    }

    /**
     * Set picture
     *
     * @param Docchangements $picture
     * @return Changements
     */
    // public function setPicture(Docchangements $picture = null)
    public function setPicture($items) {
        if ($items instanceof ArrayCollection || is_array($items)) {
            foreach ($items as $item) {
                $this->addPicture($item);
            }
        } elseif ($items instanceof Docchangements) {
            $this->addPicture($item);
        } else {
            throw new \Exception("$items must be an instance of Applus or ArrayCollection");
        }
    }

    /**
     * Remove picture
     *
     * @param \Application\RelationsBundle\Entity\Document $picture
     */

    /**
     * Remove idapplis
     *
     * @param Docchangements $picture
     */
    public function removePicture(Docchangements $picture) {
        if (!$this->picture->contains($picture)) {
            return;
        }
        $this->picture->removeElement($picture);

        $picture->removeIdchangement($this);
        //removeIdchangement(\Application\ChangementsBundle\Entity\Changements $idchangement) {
    }

    /* public function removePicture(Docchangements $picture)
      {
      $this->picture->removeElement($picture);
      }
     */

    public function getConfirmation() {

        return "Nom:" . $this->getNom() . "<br>" . $this->getIdapplis();
    }

    public function getUid() {
        return $this->id;
    }

    public function getBegin() {
        return $this->dateDebut;
    }

    public function getEnd() {
        if (!isset($this->dateFin))
              return $this->dateDebut;
            else
        return $this->dateFin;
    }

    /**
     * Remove comments
     *
     * @param \Application\ChangementsBundle\Entity\ChangementsComments $comments
     */
    public function removeComment(\Application\ChangementsBundle\Entity\ChangementsComments $comments) {
        $this->comments->removeElement($comments);
    }

    /**
     * Add comments
     *
     * @param \Application\ChangementsBundle\Entity\ChangementsComments $comments
     * @return Eproduit
     */
    public function addComment(\Application\ChangementsBundle\Entity\ChangementsComments $comments) {
        $this->comments[] = $comments;

        return $this;
    }

    /**
     * Get comments
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getComments() {
        return $this->comments;
    }

    /**
     * Set ticketExt
     *
     * @param integer $ticketExt
     * @return Changements
     */
    public function setTicketExt($ticketExt) {
        $this->ticketExt = $ticketExt;

        return $this;
    }

    /**
     * Get ticketExt
     *
     * @return integer 
     */
    public function getTicketExt() {
        return $this->ticketExt;
    }

    /**
     * Set ticketInt
     *
     * @param integer $ticketInt
     * @return Changements
     */
    public function setTicketInt($ticketInt) {
        $this->ticketInt = $ticketInt;

        return $this;
    }

    /**
     * Get ticketInt
     *
     * @return Changements 
     */
    public function getTicketInt() {
        return $this->ticketInt;
    }

       /**
     * Set statusFile
     *
     * @param boolean $astreinte
      * @return integer 
     */
    public function setAstreinte($astreinte)
    {
        $this->astreinte = $astreinte;
    
        return $this;
    }

    /**
     * Get statusFile
     *
     * @return boolean 
     */
    public function getAstreinte()
    {
        return $this->astreinte;
    }
    

    /**
     * Set idKind
     *
     * @param \Application\ChangementsBundle\Entity\KindChangements $idKind
     * @return Changements
     */
    public function setIdKind(\Application\ChangementsBundle\Entity\KindChangements $idKind)
    {
        $this->idKind = $idKind;
    
        return $this;
    }

    /**
     * Get idKind
     *
     * @return \Application\ChangementsBundle\Entity\KindChangements 
     */
    public function getIdKind()
    {
        return $this->idKind;
    }

    /**
     * Set dateDemande
     *
     * @param \DateTime $dateDemande
     * @return Changements
     */
    public function setDateDemande($dateDemande)
    {
        $this->dateDemande = $dateDemande;
    
        return $this;
    }

    /**
     * Get dateDemande
     *
     * @return \DateTime 
     */
    public function getDateDemande()
    {
        return $this->dateDemande;
    }
    
    
    
     /**
     * Add idusers
     *
     * @param \Application\RelationsBundle\Entity\ChronoUser $idusers
     * @return Changements
     */
    public function addIfavoris(\Application\Sonata\UserBundle\Entity\User $idfavoris) {
         if (!$this->idfavoris->contains($idfavoris)) {
         
        $this->idfavoris->add($idfavoris);

        
    }
       /* $this->idfavoris[] = $idfavoris;

        return $this;*/
    }

    /**
     * Remove idusers
     *
     * @param \Application\RelationsBundle\Entity\ChronoUser $idfavoris
     */
    public function removeIdfavoris(\Application\Sonata\UserBundle\Entity\User $idfavoris) {
        if (!$this->idfavoris->contains($idfavoris)) {
            return;
        }
        $this->idfavoris->removeElement($idfavoris);
        
    
    }

    /**
     * Get idusers
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getIdfavoris() {
        return $this->idfavoris;
    }
   
     public function checkIdfavoris(\Application\Sonata\UserBundle\Entity\User $idfavoris) {
         if (!$this->idfavoris->contains($idfavoris)) 
            return false;
         else 
             return true;
    }

 public function setIdfavoris($users)
    {
        if ($users instanceof ArrayCollection || is_array($users)) {
            foreach ($users as $item) {
                $this->addIdfavoris($item);
            }
        } elseif ($users instanceof \Application\Sonata\UserBundle\Entity\User) {
            $this->addIdfavoris($users);
        } else {
            throw new \Exception("$users must be an instance of User or ArrayCollection");
        }
    }
   
}