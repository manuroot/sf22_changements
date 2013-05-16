<?php

namespace Application\RelationsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * CertificatsProjet
 *
 * @ORM\Table(name="documentsbb")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */

class Documentbb
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $path;
    
    /**
    * @ORM\ManyToMany(targetEntity="Projet", mappedBy="picture")
    */
    private $idprojets;
    
    /**
     * @Assert\File(maxSize="6000000")
     */
    private $file;
    
     /**
     * Get id
     *
     * @return string 
     */
    public function getFile() {
        return $this->file;
    }
 /**
     * Set file
     *
     * @param string $file
     * @return Document
     */
    public function setFile($file)
    {
        $this->file = $file;
    
        return $this;
    }


    public function getAbsolutePath()
    {
        return null === $this->path ? null : $this->getUploadRootDir().'/'.$this->path;
    }

    public function getWebPath()
    {
        return null === $this->path ? null : $this->getUploadDir().'/'.$this->path;
    }

    protected function getUploadRootDir()
    {
        // le chemin absolu du répertoire où les documents uploadés doivent être sauvegardés
        return __DIR__.'/../../../../web/'.$this->getUploadDir();
    }

    protected function getUploadDir()
    {
        // on se débarrasse de « __DIR__ » afin de ne pas avoir de problème lorsqu'on affiche
        // le document/image dans la vue.
        return 'uploads/documents';
    }
 


/**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {
        if (null !== $this->file) {
            // faites ce que vous voulez pour générer un nom unique
            // do whatever you want to generate a unique name
            $filename = sha1(uniqid(mt_rand(), true));
           $this->path = $filename.'.'.$this->file->guessExtension();
           //  $this->path[] = uniqid().'.'.$this->file->guessExtension();
          //  $this->path = sha1(uniqid(mt_rand(), true)).'.'.$this->file->guessExtension();
        }
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        if (null === $this->file) {
            return;
        }

        // s'il y a une erreur lors du déplacement du fichier, une exception
        // va automatiquement être lancée par la méthode move(). Cela va empêcher
        // proprement l'entité d'être persistée dans la base de données si
        // erreur il y a
        $this->file->move($this->getUploadRootDir(), $this->path);

        unset($this->file);
    }

  /**
  * @ORM\PostRemove()
  */
  /*  public function removeUpload()
    {
        if ($file = $this->getAbsolutePath()) {
            unlink($file);
        }
    }*/

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
     * Set name
     *
     * @param string $name
     * @return Document
     */
    public function setName($name)
    {
        $this->name = $name;
    
        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set path
     *
     * @param string $path
     * @return Document
     */
    public function setPath($path)
    {
        $this->path = $path;
    
        return $this;
    }

    /**
     * Get path
     *
     * @return string 
     */
    public function getPath()
    {
        return $this->path;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->idprojets = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add idprojets
     *
     * @param \Application\CertificatsBundle\Entity\CertificatsProjet $idprojets
     * @return Documentbb
     */
    public function addIdprojet(\Application\RelationsBundle\Entity\Projet $idprojets)
    {
        $this->idprojets[] = $idprojets;
    
        return $this;
    }

    /**
     * Remove idprojets
     *
     * @param \Application\RelationsBundle\Entity\Projet $idprojets
     */
    public function removeIdprojet(\Application\RelationsBundle\Entity\Projet $idprojets)
    {
        $this->idprojets->removeElement($idprojets);
    }

    /**
     * Get idprojets
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getIdprojets()
    {
        return $this->idprojets;
    }
}