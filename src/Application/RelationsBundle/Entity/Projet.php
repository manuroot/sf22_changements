<?php

namespace Application\RelationsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Application\RelationsBundle\Entity\Applis;
use Application\RelationsBundle\Entity\Documentbb;
/**
 * CertificatsProjet
 *
 * @ORM\Table(name="projet_main")
 * @ORM\Entity(repositoryClass="Application\RelationsBundle\Repository\ProjetRepository")
 */
class Projet {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nomprojet", type="string", length=40, nullable=false)
     */
    private $nomprojet;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=200, nullable=true)
     */
    private $description;

    /**
     * @var ArrayCollection Applis $idapplis
     * Owning Side
     * 
     * @ORM\ManyToMany(targetEntity="Applis", inversedBy="idprojets",cascade={"persist"})
     * @ORM\JoinTable(name="projet_applis",
     *   joinColumns={@ORM\JoinColumn(name="certificatsprojet_id", referencedColumnName="id")},
     *   inverseJoinColumns={@ORM\JoinColumn(name="certificatsapplis_id", referencedColumnName="id")}
     * )
     */
    private $idapplis;

       
     /**
     * Owner side
     * 
     * @ORM\ManyToMany(targetEntity="Documentbb", inversedBy="idprojet",cascade={"persist"})
     * @ORM\JoinTable(name="projet_documents",
     * joinColumns={@ORM\JoinColumn(name="projet_id", referencedColumnName="id")},
     *   inverseJoinColumns={@ORM\JoinColumn(name="documentbb_id", referencedColumnName="id")}
     * )
     */
     protected $picture;

    
      /**
       *  @ORM\OneToMany(targetEntity="Application\ChangementsBundle\Entity\Changements",mappedBy="idProjet",cascade={"persist"})
       */
    protected $idchangement;
                
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set nomProjet
     *
     * @param string $nomProjet
     * @return CertificatsProjet
     */
    public function setNomprojet($nomprojet) {
        $this->nomprojet = $nomprojet;

        return $this;
    }

    /**
     * Get nomprojet
     *
     * @return string 
     */
    public function getNomprojet() {
        return $this->nomprojet;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return CertificatsProjet
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

    public function __construct() {
        $this->idapplis = new ArrayCollection();
        $this->picture = new ArrayCollection();
    }

    
    public function __toString() {
        return $this->getNomprojet();    // this will not look good if SonataAdminBundle uses this ;)
    }

    
    
    
    /**
     * Get picture
     *
     * @return Docchangements 
     */
    public function getPicture()
    {
        return $this->picture;
    }

   
   
    /**
     * Add picture
     *
     * @param Docchangements $picture
     * @return Changements
     */
      public function addPicture(Documentbb $picture)
    {
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
     public function setPicture($items)
    {
        if ($items instanceof ArrayCollection || is_array($items)) {
            foreach ($items as $item) {
                $this->addPicture($item);
            }
        } elseif ($items instanceof Documentbb) {
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
    public function removePicture(Documentbb $picture){
        if (!$this->picture->contains($picture)) {
            return;
        }
        $this->picture->removeElement($picture);
        $picture->removeIdprojet($this);
       //removeIdchangement(\Application\ChangementsBundle\Entity\Changements $idchangement) {
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
     * Add Applis
     *
     * @param Applis $idapplis
     */
    public function addIdappli(Applis $idapplis) {
 // Si l'objet fait déjà partie de la collection on ne l'ajoute pas
        //     $this->idapplis[] = $idapplis;
   
         if (!$this->idapplis->contains($idapplis)) {
         
        $this->idapplis->add($idapplis);

        
    }
    }

 public function setIdapplis($items)
    {
        if ($items instanceof ArrayCollection || is_array($items)) {
            foreach ($items as $item) {
                $this->addIdappli($item);
            }
        } elseif ($items instanceof Applis) {
            $this->addIdappli($items);
        } else {
            throw new \Exception("$items must be an instance of Applus or ArrayCollection");
        }
    }
   
    /**
     * Remove idapplis
     *
     * @param \Application\RelationsBundle\Entity\Applis $idapplis
     */
    public function removeIdappli(\Application\RelationsBundle\Entity\Applis $idapplis) {
        if (!$this->idapplis->contains($idapplis)) {
            return;
        }
        $this->idapplis->removeElement($idapplis);

        $idapplis->removeIdprojet($this);
       
        }

    

    /**
     * Add idchangement
     *
     * @param \Application\ChangementsBundle\Entity\Changements $idchangement
     * @return Projet
     */
    public function addIdchangement(\Application\ChangementsBundle\Entity\Changements $idchangement)
    {
        $this->idchangement[] = $idchangement;
    
        return $this;
    }

    /**
     * Remove idchangement
     *
     * @param \Application\ChangementsBundle\Entity\Changements $idchangement
     */
    public function removeIdchangement(\Application\ChangementsBundle\Entity\Changements $idchangement)
    {
        $this->idchangement->removeElement($idchangement);
    }

    /**
     * Get idchangement
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getIdchangement()
    {
        return $this->idchangement;
    }
}