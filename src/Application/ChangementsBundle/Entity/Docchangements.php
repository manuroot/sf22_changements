<?php

namespace Application\ChangementsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Application\ChangementsBundle\Entity\Changements;
/**
 * Projet
 *
 * @ORM\Table(name="changements_fichiers")
 * @ORM\Entity(repositoryClass="Application\ChangementsBundle\Entity\DocchangementsRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Docchangements {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     * @Assert\NotBlank
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $path;

      /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $OriginalFilename;
    
    
    /**
     *
     * @var ArrayCollection Projet $idchangements
     *
     * Inverse Side
     * 
     * @ORM\ManyToMany(targetEntity="Changements", mappedBy="picture",cascade={"persist"})
     */
    private $idchangement;

    /**
     * @Assert\File(maxSize="6000000")
     */
    private $file;
    //Assert\NotBlank

    /**
     * Date/Time of the update
     *
     * @var \Datetime
     * @ORM\Column(name="updated_at", type="datetime")
     */
    private $updatedAt;

    /**
     * Get id
     *
     * @return string 
     * fichier temporaire: ex: /tmp/php702KS7
     */
    
   // private $OriginalFilename;
    
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
          // a remettre apres
          //  $this->path=$this->generateNewFilename();
           $ext=$this->file->guessExtension();
           if (! isset($ext)){$ext="bin";}
            $this->path = sha1(uniqid(mt_rand(), true)) . '.' . $ext;
          $this->OriginalFilename=$this->getFile()->getClientOriginalName();
    
       //      echo "pathfilename=" .  $this->path . "<br>";exit(1);
         //   $this->path = sha1(uniqid(mt_rand(), true)) . '.' . $this->file->guessExtension();
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
   * Generates a non-random-filename
   *
   * @return string A non-random name to represent the current file
   */
  public function getFilename()
  {
      // nom du fichier
    $filename = $this->getFile()->getClientOriginalName();
    $ext=$this->getFile()->guessExtension();
  //  $ext = $this->getFile()->getExtension();
   // if ($ext !==null)
   
    
     echo "root dir=" . $this->getUploadRootDir() . "<br>";
     echo "file=$filename<br>";
     $name = substr($filename, 0, - strlen($ext));
    $i = 1;
  //  $this->getUploadRootDir(), $this->path;
    $fullpath=$this->getUploadRootDir();
    while(file_exists($fullpath . '/' .  $filename)) {
      $filename = $name . '-' . $i . $ext;
      $i++;
    }
     echo "ext=$ext<br>";
      echo "new name=$filename<br>";
     exit(1);
   
    
    return array($filename,$ext,);
  }
  
  
   public function generateNewFilename()
  {
      // nom du fichier
    $filename = $this->getFile()->getClientOriginalName();
    $ext=$this->getFile()->guessExtension();
  //  $ext = $this->getFile()->getExtension();
   // if ($ext !==null)
   
    
     echo "root dir=" . $this->getUploadRootDir() . "<br>";
     echo "file=$filename<br>";
     if (isset($ext) && strlen($ext)>0){
            $name = substr($filename, 0, - (strlen($ext)+1));
             $i = 1;
  //  $this->getUploadRootDir(), $this->path;
    $fullpath=$this->getUploadRootDir();
    while(file_exists($fullpath . '/' .  $filename)) {
      $filename = $name . '-' . $i . '.' . $ext;
      $i++;
    }
     }
     else 
           $name=$filename;
   
     echo "ext=$ext<br>name=$name<br>";
      echo "new name=$filename<br>";
     exit(1);
   
    
    return ($filename);
  }
  public function generatePathFileName($file)
{
  return $file->getOriginalName();
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
       // $this->name = $name;
         if (!isset($name)) {
            $this->name = "file";
        }
    //     $this->OriginalFilename=$this->getFile()->getClientOriginalName();
         $this->name=$name;
       
        //$this->file;}

        return $this;
    }

      /**
      *  Set name
     *
     * @param string $name
     * @return Document
     */
    public function setOriginalFilename() {
       // $this->name = $name;
       
         $this->OriginalFilename=$this->getFile()->getClientOriginalName();
       
        //$this->file;}

        return $this;
    }
    
     /**
     * Get name
     *
     * @return string 
     */
    public function getOriginalFilename() {
        return $this->OriginalFilename;
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
        $this->idchangement = new ArrayCollection();
        
    }

    /**
     * Add idchangement
     *
     * @param \Application\ChangementsBundle\Entity\Changements $idchangement
     * @return Docchangements
     */
    public function addIdchangement(Changements $idchangement) {
         if (!$this->idchangement->contains($idchangement)) {
        if (!$idchangement->getPicture()->contains($this)) {
           
                $idchangement->addPicture($this);  // Lie le Client au produit.
            }
            $this->idchangement->add($idchangement);
        }
        //$this->idchangement[] = $idchangement;

      //  return $this;
    }

    public function setIdchangement($items) {
         if ($items instanceof ArrayCollection || is_array($items)) {
            foreach ($items as $item) {
                $this->addIdchangement($item);
            }
        } elseif ($items instanceof Changements) {
            $this->addPicture($items);
        } else {
            throw new \Exception("$items must be an instance of Changements or ArrayCollection");
        }
    }
    /**
     * Remove idchangement
     *
     * @param \Application\ChangementsBundle\Entity\Changements $idchangement
     */
    
     public function removeIdchangement(Changements $idchangement) {
        if (!$this->idchangement->contains($idchangement)) {
            return;
        }
        $this->idchangement->removeElement($idchangement);
        $idchangement->removePicture($this);
    }
    
  

    /**
     * Get idchangement
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getIdchangement() {
        return $this->idchangement;
    }
 

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