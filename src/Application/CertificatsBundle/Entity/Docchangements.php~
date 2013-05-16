<?php

namespace Application\CertificatsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * CertificatsProjet
 *
 * @ORM\Table(name="changements_fichiers")
 * @ORM\Entity(repositoryClass="Application\CertificatsBundle\Entity\DocchangementsRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Docchangements {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    public $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     * @Assert\NotBlank
     */
    public $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    public $path;

    /**
     * @ORM\ManyToMany(targetEntity="Changements", mappedBy="picture",cascade={"persist"})
     */
    private $idchangement;

    /**
     * @Assert\File(maxSize="6000000")
     */
    public $file;
    //Assert\NotBlank

    /**
     * Date/Time of the update
     *
     * @var \Datetime
     * @ORM\Column(name="updated_at", type="datetime")
     */
    private $updatedAt;

    
    
       public function __toString() {
        return $this->getName();    // this will not look good if SonataAdminBundle uses this ;)
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
          $this->updatedAt = new \DateTime();
        if (null !== $this->file) {
            
            // faites ce que vous voulez pour générer un nom unique
            $this->path = sha1(uniqid(mt_rand(), true)) . '.' . $this->file->guessExtension();
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

        unset($this->file);
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload() {
        if ($file = $this->getAbsolutePath()) {
            unlink($file);
        }
    }

    /**
 * @ORM\PostLoad()
 */
public function postLoad()
{
    $this->updatedAt = new \DateTime();
}


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
        $this->name = $name;
        if (!isset($name)) {
            $this->name = "toto";
        }
        //$this->file;}

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
        $this->idchangement = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add idchangement
     *
     * @param \Application\CertificatsBundle\Entity\Changements $idchangement
     * @return Docchangements
     */
    public function addIdchangement(\Application\CertificatsBundle\Entity\Changements $idchangement) {
        $this->idchangement[] = $idchangement;

        return $this;
    }

    /**
     * Remove idchangement
     *
     * @param \Application\CertificatsBundle\Entity\Changements $idchangement
     */
    public function removeIdchangement(\Application\CertificatsBundle\Entity\Changements $idchangement) {
        $this->idchangement->removeElement($idchangement);
    }

    /**
     * Get idchangement
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getIdchangement() {
        return $this->idchangement;
    }

    /*  public function generateFileNameFilename($file = null)
      {
      if (null === $file) {
      // use a random filename instead
      return null;
      }

      if (file_exists($file->getpath().$file->getOriginalName())) {
      return $this->appendToName($file);
      }

      return $file->getOriginalName();
      } */
    /*
      public function appendToName($file, $index = 0)
      {
      $newname = pathinfo($file->getOriginalName(), PATHINFO_FILENAME).$index.$file->getExtension();

      if (file_exists($file->getpath().$newname)) {
      return $this->appendToName($file, ++$index);
      } else {
      return $newname;
      }
      } */

    /* public function generatePathFileName($file)
      {
      return $file->getOriginalName();
      } */
    /**
      // @ORM\PreRemove()
     */
    /*  public function storeFilenameForRemove()
      {
      $this->filenameForRemove = $this->getAbsolutePath();
      } */
    /**
      // @ORM\PostRemove()
     */
    /* public function removeUpload()
      {
      if ($this->filenameForRemove) {
      unlink($this->filenameForRemove);
      }
      }
      }

      public function getAbsolutePath()
      {
      return null === $this->path ? null : $this->getUploadRootDir().'/'.$this->id.'.'.$this->path;
      } */

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return Docchangements
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    
        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime 
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }
}