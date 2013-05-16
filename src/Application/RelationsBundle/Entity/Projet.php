<?php

namespace Application\RelationsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * CertificatsProjet
 *
 * @ORM\Table(name="certificats_projet")
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="Application\RelationsBundle\Entity\ProjetRepository")
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
     * @ORM\ManyToMany(targetEntity="Applis", inversedBy="idprojets",cascade={"persist"})
     * @ORM\JoinTable(name="projet_applis",
     *   joinColumns={
     *     @ORM\JoinColumn(name="certificatsprojet_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="certificatsapplis_id", referencedColumnName="id")
     *   }
     * )
     */
    private $idapplis;

    /**
     * @ORM\ManyToMany(targetEntity="Documentbb", inversedBy="idprojets",cascade={"persist"},orphanRemoval=true)
     * @ORM\JoinTable(name="projet_documents")
     */
    protected $picture;

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
        $this->projets = new \Doctrine\Common\Collections\ArrayCollection();
        $this->picture = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get idapplis
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getIdapplis() {
        return $this->idapplis;
    }
    public function __toString() {
        return $this->getNomprojet();    // this will not look good if SonataAdminBundle uses this ;)
    }

    /**
     * Add picture
     *
     * @param \Application\RelationsBundle\Entity\Document $picture
     * @return CertificatsProjet
     */
    public function addPicture(\Application\RelationsBundle\Entity\Documentbb $picture) {
        $this->picture[] = $picture;

        return $this;
    }

    /**
     * Remove picture
     *
     * @param \Application\RelationsBundle\Entity\Document $picture
     */
    public function removePicture(\Application\RelationsBundle\Entity\Documentbb $picture) {
        $this->picture->removeElement($picture);
    }

    /**
     * Get picture
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPicture() {
        return $this->picture;
    }

    /**
     * Add idapplis
     *
     * @param \Application\RelationsBundle\Entity\Applis $idapplis
     * @return CertificatsProjet
     */
    public function xaddIdappli(\Application\RelationsBundle\Entity\Applis $applis) {
 // Si l'objet fait déjà partie de la collection on ne l'ajoute pas
       if ($applis instanceof ArrayCollection || is_array($applis)) {
             if (! $this->idapplis->contains($applis)) {
                $this->idapplis->add($applis);
              //  $this->addIdappli($appli);
            }
        } elseif ($applis instanceof Applis) {
         /*   $this->addIdappli($applis);*/
              $this->idapplis->add($applis);
            
        }
         // work: mais pas en sens inverse   
      /*  $this->idapplis[] = $applis;*/
    
        return $this;
         
     

        
    }
    //}
    
    public function addIdappli(\Application\RelationsBundle\Entity\Applis $idapplis) {
 // Si l'objet fait déjà partie de la collection on ne l'ajoute pas
             $this->idapplis[] = $idapplis;
   
       /*  if (! $this->idapplis->contains($idapplis)) {
         
        $this->idapplis->add($idapplis);
*/
        
  //  }
    }


   
public function setIdapplis($applis)
    {
       /* if ($applis instanceof ArrayCollection || is_array($applis)) {
            foreach ($applis as $appli) {
                $this->addIdappli($appli);
            }
        } elseif (*/
            if ($applis instanceof Applis) {
            $this->addIdappli($applis);
        } 
        else {
            throw new \Exception("$applis must be an instance of Produit or ArrayCollection");
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

    
}