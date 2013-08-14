<?php

namespace Application\RelationsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


/**
 * CertificatsProjet
 *
 * @ORM\Table(name="serveurs_categories")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 *
 * @UniqueEntity(fields="nom", message="Nom déja utilisé")
 * 
 * 
 * @ORM\Entity(repositoryClass="Application\RelationsBundle\Repository\ServeursCategoriesRepository")
 */
class ServeursCategories {

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
     * @ORM\Column(name="nom", type="string", length=40, nullable=false,unique=true)
     */
    private $nom;
 
    
    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=200, nullable=true)
     */
    private $description;

   
    
   
    
      public function __construct()
  {
 
    
  }
    
    public function __toString() {
        return $this->getNom();    // this will not look good if SonataAdminBundle uses this ;)
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
     * Set nom
     *
     * @param string $nom
     * @return Serveurs
     */
    public function setNom($nom)
    {
        $this->nom = $nom;
    
        return $this;
    }

    /**
     * Get nom
     *
     * @return string 
     */
    public function getNom()
    {
        return $this->nom;
    }

      /**
  
 

  
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

    }
