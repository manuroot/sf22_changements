<?php

namespace Application\RelationsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * CertificatsProjet
 *
 * @ORM\Table(name="projet_alldocuments")
 * @ORM\Entity(repositoryClass="Application\RelationsBundle\Repository\DocumentbbRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Documentbb {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * Nom  informations (textfield)
     * 
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * random nom sur disque
     * 
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $path;

    /**
     * lien vers projets
     *
     *  
     * @ORM\ManyToMany(targetEntity="Projet", mappedBy="picture")
     */
    private $idprojets;

      /**
     * Date/Time of the update
     *
     * @var \Datetime
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updatedAt;
    
    /**
     *
     * Champs file
     * 
     * @Assert\File(maxSize="6000000")
     */
    private $file;
    
     /**
     * champs supplemanetaire md5
     * 
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $md5;
    
    
    // nom origine du fichier
    private $OriginalFilename;

    /**
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
    
    public function setFile(UploadedFile $file = null) {
        $this->file = $file;
        // check if we have an old image path
        if (isset($this->path)) {
            // store the old name to delete after the update
            $this->temp = $this->path;
            $this->path = null;
        } 
        else {
            $this->path = 'initial';
        }

        return $this;
    }

    public function getAbsolutePath() {
        return null === $this->path ? null : $this->getUploadRootDir() . '/' . $this->path;
    }

    public function getWebPath() {
        return null === $this->path ? null : $this->getUploadDir() . '/' . $this->path;
    }

    protected function getUploadRootDir() {
        // le chemin absolu du répertoire où les documents uploadés doivent être sauvegardés
        return __DIR__ . '/../../../../web/' . $this->getUploadDir();
    }

    protected function getUploadDir() {
        // on se débarrasse de « __DIR__ » afin de ne pas avoir de problème lorsqu'on affiche
        // le document/image dans la vue.
        return 'uploads/documents';
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload() {
        
        // amodifier apres
          $this->updatedAt = new \DateTime();
    
        if (null !== $this->file) {
            // faites ce que vous voulez pour générer un nom unique
            // do whatever you want to generate a unique name
          
            //todo si guessextension null, recup extension a la mano !!
           /* $this->path = $filename . '.' . $this->file->guessExtension();
            $this->OriginalFilename = $this->getFile()->getClientOriginalName();
            if (!$this->name)
            $this->name = $this->OriginalFilename;*/
           //  $this->updatedAt = new \DateTime();
    
            //  $this->path[] = uniqid().'.'.$this->file->guessExtension();
            //  $this->path = sha1(uniqid(mt_rand(), true)).'.'.$this->file->guessExtension();
         $ext = $this->file->guessExtension();
            if (!isset($ext)) {
                $ext = "bin";
            }
            $this->path = sha1(uniqid(mt_rand(), true)) . '.' . $ext;
          
             $this->OriginalFilename = $this->getFile()->getClientOriginalName();
            if (!$this->name)
                $this->name = $this->OriginalFilename;
            $this->md5 = md5_file($this->file);
               $this->updatedAt = new \DateTime();
               
        }
        if (!$this->md5 && (file_exists($this->getUploadDir() . '/' . $this->path))){
             $this->md5 = md5_file($this->getUploadDir() . '/' . $this->path);
        }
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
     public function upload() {
        if (null === $this->file) {
            return;
        }

        // s'il y a une erreur lors du déplacement du fichier, une exception
        // va automatiquement être lancée par la méthode move(). Cela va empêcher
        // proprement l'entité d'être persistée dans la base de données si
        // erreur il y a
        $this->file->move($this->getUploadRootDir(), $this->path);

        // check if we have an old image
        if (isset($this->temp)) {
            // delete the old image
            unlink($this->getUploadRootDir() . '/' . $this->temp);
            // clear the temp image path
            $this->temp = null;
        }
       // unset($this->file);
        // clean up the file property as you won't need it anymore
        $this->file = null;
    }
    
   /* public function upload() {
        if (null === $this->file) {
            return;
        }

        // s'il y a une erreur lors du déplacement du fichier, une exception
        // va automatiquement être lancée par la méthode move(). Cela va empêcher
        // proprement l'entité d'être persistée dans la base de données si
        // erreur il y a
        $this->file->move($this->getUploadRootDir(), $this->path);

        unset($this->file);
    }*/
    
    

    /**
     * @ORM\PostRemove()
     */
    /*  public function removeUpload()
      {
      if ($file = $this->getAbsolutePath()) {
      unlink($file);
      }
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
     * Set name
     *
     * @param string $name
     * @return Document
     */
    public function setName($name) {

        if (isset($name))
            $this->name = $name;
         //$this->OriginalFilename;
        //  echo "origine=--" . $this->OriginalFilename . "--<br>";

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Set path
     *
     * @param string $path
     * @return Document
     */
    public function setPath($path) {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string 
     */
    public function getPath() {
        return $this->path;
    }

    /**
     * Constructor
     */
    public function __construct() {
        $this->idprojets = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add idprojets
     *
     * @param \Application\CertificatsBundle\Entity\CertificatsProjet $idprojets
     * @return Documentbb
     */
    public function addIdprojet(\Application\RelationsBundle\Entity\Projet $idprojets) {
        $this->idprojets[] = $idprojets;

        return $this;
    }

    /**
     * Remove idprojets
     *
     * @param \Application\RelationsBundle\Entity\Projet $idprojets
     */
    public function removeIdprojet(\Application\RelationsBundle\Entity\Projet $idprojets) {
        $this->idprojets->removeElement($idprojets);
    }

    /**
     * Get idprojets
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getIdprojets() {
        return $this->idprojets;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return Docchangements
     */
    public function setUpdatedAt($updatedAt) {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime 
     */
    public function getUpdatedAt() {
        return $this->updatedAt;
    }
      /**
     * Set md5
     *
     * @param string $md5
     * @return Docchangements
     */
    public function setMd5($md5) {
        $this->md5 = $md5;

        return $this;
    }

    /**
     * Get md5
     *
     * @return string 
     */
    public function getMd5() {
        return $this->md5;
    }
}