<?php

namespace Application\CertificatsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use APY\DataGridBundle\Grid\Mapping as GRID;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\MinLength;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Application\RelationsBundle\Entity\Projet;
use Application\RelationsBundle\Entity\Applis;
use Application\RelationsBundle\Entity\FileType;

/**
 * CertificatsCenter
 *
 * @ORM\Table(name="certificats_center")
 * @ORM\Entity(repositoryClass="Application\CertificatsBundle\Entity\CertificatsCenterRepository")
 * @ORM\HasLifecycleCallbacks()
* @GRID\Source(columns="id,fileName,cnName,endTime,serverName,serviceName,project.nomprojet,typeCert.fileType,idapplis.nomapplis:AtGroupConcat",groupBy={"id"}) 
*/
//* @GRID\Source(columns="id,fileName,cnName,endTime,serverName,serviceName,project.nomprojet,typeCert.fileType,idapplis.nomapplis:GroupConcat:distinct",groupBy={"id"}) 
class CertificatsCenter
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @GRID\Column(title="id", size="20", type="text",filter="false")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="file_name", type="string", length=50, nullable=false)
     * @GRID\Column(title="Nom", size="100", type="text")
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

     /**
     * @ORM\ManyToOne(targetEntity="Application\Sonata\MediaBundle\Entity\Media")
     */
    protected $picture;
    
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
      * @var string
     * @ORM\ManyToMany(targetEntity="\Application\RelationsBundle\Entity\Applis", inversedBy="idprojets",cascade={"persist"})
     * @ORM\OrderBy({"nomapplis" = "ASC"})
     * @ORM\JoinTable(name="certificats_xapplis")
     * @Grid\Column(type="extended_text",field="idapplis.nomapplis:AtGroupConcat", title="Cities",filter="select", selectMulti="true", selectFrom="values")
     */
    private $idapplis;
//@Grid\Column(field="tags.name:count:distinct", title="Tags") 
    
   public function __construct()
  {
    $this->addedDate = new \DateTime('now');
    $this->updatedDate = new \DateTime('now');
    $this->statusFile = true;
    
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
        $metadata->addPropertyConstraint('fileName', new NotBlank());
        $metadata->addPropertyConstraint('fileName', new MinLength(5));
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
     * Set picture
     *
     * @param \Application\Sonata\MediaBundle\Entity\Media $picture
     * @return CertificatsCenter
     */
    public function setPicture(\Application\Sonata\MediaBundle\Entity\Media $picture = null)
    {
        $this->picture = $picture;
    
        return $this;
    }

    /**
     * Get picture
     *
     * @return \Application\Sonata\MediaBundle\Entity\Media 
     */
    public function getPicture()
    {
        return $this->picture;
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
}
