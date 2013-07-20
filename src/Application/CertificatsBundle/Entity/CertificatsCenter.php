<?php

namespace Application\CertificatsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use APY\DataGridBundle\Grid\Mapping as GRID;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Mapping\ClassMetadata;
/*
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\Date;*/
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Application\RelationsBundle\Entity\Projet;
use Application\RelationsBundle\Entity\Applis;
use Application\RelationsBundle\Entity\FileType;
use Symfony\Component\HttpFoundation\File\File;
use Application\CertificatsBundle\Entity\CertificatsFiles;
/**
 * CertificatsCenter
 *
 * @ORM\Table(name="certificats_center")
 * @ORM\Entity(repositoryClass="Application\CertificatsBundle\Repository\CertificatsCenterRepository")
 * @ORM\HasLifecycleCallbacks()
 * @GRID\Source(columns="id,fileName,cnName,endTime,serverName,serviceName,project.nomprojet,idEnvironnement.nom,typeCert.fileType",groupBy={"id"}) 
 */


// * keep:
//  * @GRID\Source(columns="id,fileName,cnName,endTime,serverName,serviceName,project.nomprojet,idEnvironnement.nom,typeCert.fileType,idapplis.nomapplis:GroupConcat",groupBy={"id"}) 
// @GRID\Source(groupBy={"id"}) 
// //* @GRID\Source(columns="id,fileName,cnName,endTime,serverName,serviceName,project.nomprojet,typeCert.fileType,idapplis.nomapplis:GroupConcat",groupBy={"id"}) 
//* @GRID\Source(columns="id,fileName,cnName,endTime,serverName,serviceName,project.nomprojet,typeCert.fileType,idapplis.nomapplis:GroupConcat",groupBy={"id"}) 
class CertificatsCenter
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @GRID\Column(title="id", size="20", type="text",filter="false",filterable=false)
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="file_name", type="string", length=50, nullable=false)
     * @Assert\Length(
     *      min = "5",
     *      max = "50",
     *      minMessage = "Your name must be at least {{ limit }} characters length |
     *  Au minimum {{ limit }} caracteres",
     *      maxMessage = "filenamee cannot be longer than than {{ limit }} characters length |
     *  fichier: au maximum {{ limit }} caracteres"
     * )
     * @GRID\Column(title="Nom", size="50", type="text")
     */
    private $fileName;

    /**
     * @var string
     *
     * @ORM\Column(name="cn_name", type="string", length=50, nullable=false)
     * @GRID\Column(title="CN", size="100", type="text")
     */
    private $cnName;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="start_date", type="date", nullable=false)
     * @GRID\Column(title="Début", size="30",format="Y-m-d",type="datetime")
     */
    private $startDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="end_time", type="date", nullable=false)
     * @GRID\Column(title="Fin", size="50",format="Y-m-d",type="datetime")
     */
 
    
    // @GRID\Column(title="Fin Validité", size="50", type="datetime",filter="select",selectFrom="query")
    private $endTime;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="added_date", type="date", nullable=false)
     */
    private $addedDate;

      /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_date", type="date", nullable=false)
     */
    private $updatedDate;

    
    /**
     * @var string
     *
     * @ORM\Column(name="server_name", type="string", length=90, nullable=false)
     * @GRID\Column(title="Serveur", size="50", type="text")
     */
    private $serverName;

    /**
     * @var integer
     *
     * @ORM\Column(name="port", type="integer", nullable=false)
     */
    private $port;

    /**
     * @var string
     *
     * @ORM\Column(name="service_name", type="string", length=50, nullable=false)
     * @GRID\Column(title="Service", size="50", type="text")
     */
    private $serviceName;

    /**
     * @var string
     *
     * @ORM\Column(name="way", type="string", length=20, nullable=false)
     */
    private $way;

    /**
     * @var boolean
     *
     * @ORM\Column(name="status_file", type="boolean", nullable=true)
     */
    private $statusFile;

    
      /**
     * @var boolean
     *
     * @ORM\Column(name="warning_file", type="boolean", nullable=true)
     */
    private $warningFile;
    
    
  /**
     * @var \Projet
     *
     * @ORM\ManyToOne(targetEntity="\Application\RelationsBundle\Entity\Projet")
     * @ORM\OrderBy({"nomprojet" = "ASC"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="project", referencedColumnName="id")
     * })
    
     * @GRID\Column(field="project.nomprojet", title="Projet",size="20",filter="select",selectFrom="query")
    */
    private $project;

// @ORM\OneToOne(targetEntity="\Application\CertificatsBundle\Entity\CertificatsFiles",mappedBy="certificats",cascade={"persist","remove"}), 
    
     /**
      * 
     * @ORM\OneToOne(targetEntity="\Application\CertificatsBundle\Entity\CertificatsFiles", inversedBy="certificats",cascade={"persist","remove"}), 
      * 
     * @ORM\JoinColumn(name = "fichier", nullable = true, referencedColumnName = "id",onDelete="set null")
     */
    protected $fichier;
    
   


     /**
     * @var string
     * 
     * @ORM\ManyToOne(targetEntity="Application\RelationsBundle\Entity\Environnements")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idEnvironnement", referencedColumnName="id")
     * })
     * @GRID\Column(field="idEnvironnement.nom", filterable=false,size="20",title="Env", filter="select",selectFrom="query")
     */
    private $idEnvironnement;
    
    
    
    
    //* @OrderBy({"name" = "ASC"})
    /**
     * @var \Filetype
     *
     * @ORM\ManyToOne(targetEntity="\Application\RelationsBundle\Entity\Filetype")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="type_cert", referencedColumnName="id")
     * })
     * 
     * @GRID\Column(field="typeCert.fileType", size="20",title="Type",filter="select",selectFrom="query")
     */
    private $typeCert;

     /**
     * @var Doctrine\Common\Collections\ArrayCollection
     * @ORM\ManyToMany(targetEntity="\Application\RelationsBundle\Entity\Applis", inversedBy="idprojets",cascade={"persist"})
     * @ORM\OrderBy({"nomapplis" = "ASC"})
     * @ORM\JoinTable(name="certificats_xapplis")
    
     *
     */
    private $idapplis;
    // agarder
    //      *  @Grid\Column(type="text",title="Applis2",field="idapplis.nomapplis:GroupConcat",operators={"like"}, operatorsVisible=false, filterable=false,operatorsVisible=true,selectFrom="query")
      //    @Grid\Column(type="text",title="Applis1",size="30",field="idapplis.nomapplis:GroupConcat",  selectExpanded=true , operatorsVisible=true,selectFrom="query")
   //  *  @Grid\Column(type="array",title="Applis2",field="idapplis.nomapplis", operatorsVisible=true, filterable=true,selectFrom="query")
    //    
    //    *  @Grid\Column(type="text",title="Applis2",size="30",field="idapplis.nomapplis:GroupConcat",joinType="inner",operators={"eq","neq","nlike","lt","like"}, operatorsVisible=true,selectFrom="query",groupBy={"id"})
  //  @Grid\Column(type="text",title="Applis1",size="30",field="idapplis.nomapplis",joinType="inner",selectFrom="values",groupBy={"id"})
    // @Grid\Column(type="extended_text",field="idapplis.nomapplis:GroupConcat", title="Applis",filter="select", selectMulti="false", )
//@Grid\Column(field="tags.name:count:distinct", title="Tags") 
    // * @GRID\Column(field="category.name", title="Category Name", joinType="inner")
    // @GRID\Column(field="category.name", title="Category Name")
     // @GRID\Column(field="category.firstChild.name", title="Category first child")
     // @GRID\Column(field="category.tags", type="array", title="Category tags")
    
    
      /**
     * @var \ChronoUser
     *
     * @ORM\ManyToOne(targetEntity="Application\RelationsBundle\Entity\ChronoUser")
     * @ORM\OrderBy({"nomUser" = "ASC"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="demandeur", referencedColumnName="id")
     * })
     * @GRID\Column(field="demandeur.nomUser", title="Demandeur",size="20",filter="select",selectFrom="query")
    */
    private $demandeur;

      /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=200, nullable=true)
     */
    private $description;
    
     /**
     * Set description
     *
     * @param string $description
     * @return Serveurs
     */
    public function setDescription($description)
    {
        $this->description = $description;
    
        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

 
     public function __toString() {
        return $this->getFileName();    
    }
    
    
   public function __construct()
  {
    $this->addedDate = new \DateTime('now');
    $this->updatedDate = new \DateTime('now');
    $this->statusFile = true;
    $this->warningFile = false;
    
  }
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set fileName
     *
     * @param string $fileName
     * @return CertificatsCenter
     */
    public function setFileName($fileName)
    {
        $this->fileName = $fileName;
    
        return $this;
    }

    /**
     * Get fileName
     *
     * @return string 
     */
    public function getFileName()
    {
        return $this->fileName;
    }

    /**
     * Set cnName
     *
     * @param string $cnName
     * @return CertificatsCenter
     */
    public function setCnName($cnName)
    {
        $this->cnName = $cnName;
    
        return $this;
    }

    /**
     * Get cnName
     *
     * @return string 
     */
    public function getCnName()
    {
        return $this->cnName;
    }

    /**
     * Set startDate
     *
     * @param \DateTime $startDate
     * @return CertificatsCenter
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;
    
        return $this;
    }

    /**
     * Get startDate
     *
     * @return \DateTime 
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * Set endTime
     *
     * @param \DateTime $endTime
     * 
     * @return CertificatsCenter
     */
    
    // public function setEndTime($endTime)
      // @param string $endTime
     public function setEndTime($endTime)
    {
        $this->endTime = $endTime;
    
        return $this;
    }

    /**
     * Get endTime
     *
     * @return dateTime 
     */
    public function getEndTime()
    {
        // @return \DateTime 
        return $this->endTime;
    }

    /**
     * Set addedDate
     *
     * @param \DateTime $addedDate
     * @return CertificatsCenter
     */
    public function setAddedDate($addedDate)
    {
        $this->addedDate = $addedDate;
    
        return $this;
    }

    /**
     * Get addedDate
     *
     * @return \DateTime 
     */
    public function getAddedDate()
    {
        return $this->addedDate;
    }

    /**
     * Set addedDate
     *
     * @param \DateTime $addedDate
     * @return CertificatsCenter
     */
    public function setUpdatedDate($updatedDate)
    {
        $this->updatedDate = $updatedDate;
    
        return $this;
    }

    /**
     * Get addedDate
     *
     * @return \DateTime 
     */
    public function getUpdatedDate()
    {
        return $this->updatedDate;
    }
    /**
     * Set serverName
     *
     * @param string $serverName
     * @return CertificatsCenter
     */
    public function setServerName($serverName)
    {
        $this->serverName = $serverName;
    
        return $this;
    }

    /**
     * Get serverName
     *
     * @return string 
     */
    public function getServerName()
    {
        return $this->serverName;
    }

    /**
     * Set port
     *
     * @param integer $port
     * @return CertificatsCenter
     */
    public function setPort($port)
    {
        $this->port = $port;
    
        return $this;
    }

    /**
     * Get port
     *
     * @return integer 
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * Set serviceName
     *
     * @param string $serviceName
     * @return CertificatsCenter
     */
    public function setServiceName($serviceName)
    {
        $this->serviceName = $serviceName;
    
        return $this;
    }

    /**
     * Get serviceName
     *
     * @return string 
     */
    public function getServiceName()
    {
        return $this->serviceName;
    }

    /**
     * Set way
     *
     * @param string $way
     * @return CertificatsCenter
     */
    public function setWay($way)
    {
        $this->way = $way;
    
        return $this;
    }

    /**
     * Get way
     *
     * @return string 
     */
    public function getWay()
    {
        return $this->way;
    }

    /**
     * Set statusFile
     *
     * @param boolean $statusFile
     * @return CertificatsCenter
     */
    public function setStatusFile($statusFile)
    {
        $this->statusFile = $statusFile;
    
        return $this;
    }

    /**
     * Get statusFile
     *
     * @return boolean 
     */
    public function getStatusFile()
    {
        return $this->statusFile;
    }
    
    
   /**
    *  Set statusFile
     *
     * @param boolean $statusFile
     * @return CertificatsCenter
     */
    public function setWarningFile($warningFile)
    {
        $this->warningFile = $warningFile;
    
        return $this;
    }

    /**
     * Get warningFile
     *
     * @return boolean 
     */
    public function getWarningFile()
    {
        return $this->warningFile;
    }
    /**
     * Set typeCert
     *
     * @param \Application\RelationsBundle\Entity\Filetype $typeCert
     * @return CertificatsCenter
     */
    public function setTypeCert(\Application\RelationsBundle\Entity\Filetype $typeCert = null)
    {
        $this->typeCert = $typeCert;
    
        return $this;
    }

    /**
     * Get typeCert
     *
     * @return \Application\RelationsBundle\Entity\Filetype 
     */
    public function getTypeCert()
    {
        return $this->typeCert;
    }

    /**
     * Set project
     *
     * @param \Application\RelationsBundle\Entity\Projet $project
     * @return CertificatsCenter
     */
    public function setProject(\Application\RelationsBundle\Entity\Projet $project = null)
    {
        $this->project = $project;
    
        return $this;
    }

    /**
     * Get project
     *
     * @return \Application\RelationsBundle\Entity\Projet 
     */
    public function getProject()
    {
        return $this->project;
    }
    
    
    
 public static function loadValidatorMetadata(ClassMetadata $metadata) {
        //nom
      //  $metadata->addPropertyConstraint('fileName', new NotBlank());
     //   $metadata->addPropertyConstraint('fileName', new MinLength(5));
      //   $metadata->addPropertyConstraint('endTime', new Assert\Date());
       /* $metadata->addConstraint(new UniqueEntity(array(
            "fields" => "fileName", 
            "message" => "Ce nom existe deja")
        ));*/
        //date de fin
        /*$metadata->addPropertyConstraint('endTime', new NotBlank());
        $metadata->addPropertyConstraint('endTime', new Date());*/
        //sexe    
 
      /*  $metadata->addPropertyConstraint('sexe', new Choice(array(
                    'choices' => array('M', 'F'),
            'message'=>'test'
                                 )));*/
    }
    

    /**
     * Add idapplis
     *
     * @param \Application\RelationsBundle\Entity\Applis $idapplis
     * @return CertificatsCenter
     */
    public function addIdappli(\Application\RelationsBundle\Entity\Applis $idapplis)
    {
        $this->idapplis[] = $idapplis;
    
        return $this;
    }

    /**
     * Remove idapplis
     *
     * @param \Application\RelationsBundle\Entity\Applis $idapplis
     */
    public function removeIdappli(\Application\RelationsBundle\Entity\Applis $idapplis)
    {
        $this->idapplis->removeElement($idapplis);
    }

    /**
     * Get idapplis
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getIdapplis()
    {
        return $this->idapplis;
    }

   
    /**
     * @ORM\PreUpdate
     */
    public function setUpdatedAtValue() {
       
        $this->setUpdatedDate(new \DateTime());
     
    }

    public function prePersist() {
          $this->setAddedDate(new \DateTime);
        $this->setUpdatedDate(new \DateTime);
        /* if (null == $this->getGlobalnote()){


          } */
    }
    
     /**
     * Set demandeur
     *
     * @param \Application\ChangementsBundle\Entity\ChronoUser $demandeur
     * @return Changements
     */
    public function setDemandeur(\Application\RelationsBundle\Entity\ChronoUser $demandeur = null)
    {
        $this->demandeur = $demandeur;
    
        return $this;
    }

    /**
     * Get idProject
     *
     * @return \Application\RelationsBundle\Entity\ChronoUser 
     */
    public function getDemandeur()
    {
        return $this->demandeur;
    }
    /*
     * SELECT 
  c0_.id AS id0, 
  c0_.nom AS nom1, 
  c0_.date_debut AS date_debut2, 
  c0_.date_fin AS date_fin3, 
  c1_.nomprojet AS nomprojet4, 
  c2_.nom_user AS nom_user5, 
  GROUP_CONCAT(distinct e3_.nom) AS sclr6 
FROM 
  changements_main c0_ 
  LEFT JOIN certificats_projet c1_ ON c0_.id_projet = c1_.id 
  LEFT JOIN chrono_user c2_ ON c0_.demandeur = c2_.id 
  LEFT JOIN changements_environnements c4_ ON c0_.id = c4_.changements_id 
  LEFT JOIN environnement e3_ ON e3_.id = c4_.environnements_id 
GROUP BY 
  c0_.id 
HAVING sclr6 LIKE '%prod%'
ORDER BY 
  c0_.id DESC 
     */

    
  
     
   
    /**
     * Remove fichier
     *
     * @param 
     */
     /**
     * Remove fichier
     *
     * @param Docchangements $picture
     */
    public function removeFichier(\Application\CertificatsBundle\Entity\CertificatsFiles $fichier){
        if (!$this->fichier->contains($fichier)) {
            return;
        }
        $this->fichier->removeElement($fichier);

       // $fichier->removeIdchangement($this);
      
        }
        
    /*public function removePicture(Docchangements $picture)
    {
        $this->picture->removeElement($picture);
    }
    */
    
     /**
     * Add fichier
     *
     * @param \Application\CertificatsBundle\Entity\CertificatsFiles $fichier
     * @return CertificatsFiles
     */
    public function addFichier(CertificatsFiles $fichier=null) {
        $this->fichier = $fichier;

        return $this;
    }
    /**
     * Set fichier
     *
     * @param \Application\CertificatsBundle\Entity\CertificatsFiles $fichier
     * @return CertificatsCenter
     */
    public function setFichier(CertificatsFiles $fichier = null)
    {
          /* if ($item instanceof CertificatsFiles) {
            $this->addFichier($item);
        } else {
            throw new \Exception("$items must be an instance of Applus or ArrayCollection");
        }*/
        $this->fichier = $fichier;
    
        return $this;
    }

    /**
     * Get fichier
     *
     * @return \Application\CertificatsBundle\Entity\CertificatsFiles 
     */
    public function getFichier()
    {
        return $this->fichier;
    }

 
    
     /**
     * Set environnement
     *
     * @param \Application\RelationsBundle\Entity\Environnements $idEnvironnement
     * @return CertificatsCenter
     */
    
    public function setIdEnvironnement(\Application\RelationsBundle\Entity\Environnements $idEnvironnement = null)
    {
        $this->idEnvironnement = $idEnvironnement;
    
        return $this;
    }

    /**
     * Get environnement
     *
     * @return \Application\RelationsBundle\Entity\Environnements 
     */
    public function getIdEnvironnement()
    {
        return $this->idEnvironnement;
    }
}