<?php

namespace Application\RelationsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * Projet
 *
 * @ORM\Table(name="documents")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */

class Document
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    public $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    public $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    public $path;
    
    
    /**
     * @Assert\File(maxSize="6000000")
     */
    private $file;
    
 /**
     * Get file
     *
     * @return integer 
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
   * Generates a non-random-filename
   *
   * @return string A non-random name to represent the current file
   */
  public function generateFilename()
  {
    $filename = $this->getOriginalName();

    $ext = $this->getExtension($this->getOriginalExtension());
    $name = substr($this->getOriginalName(), 0, - strlen($ext));
    $i = 1;
    while(file_exists($this->getPath() . '/' .  $filename)) {
      $filename = $name . '-' . $i . $ext;
      $i++;
    }
    return $filename;
  }

/**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {
        if (null !== $this->file) {
            echo "fichier=" . $this->file . "<br>";
            exit(1);
            // faites ce que vous voulez pour générer un nom unique
          //   $this->path = sha1(uniqid(mt_rand(), true)).'.'.$this->file->getExtension();
            $this->path= $this->getOriginalName();
          //  $this->path = sha1(uniqid(mt_rand(), true)).'.'.$this->file->guessExtension();
        }
      /*  if($this->file !== null)
        {
            $this->url = $this->file->getClientOriginalName() . "_" . $this->id . "." . $this->file->guessExtension();
        }*/
    }
    
    
    /* public function keeppreUpload()
    {
        if (null !== $this->file) {
            // faites ce que vous voulez pour générer un nom unique
          //   $this->path = sha1(uniqid(mt_rand(), true)).'.'.$this->file->getExtension();
            $this->path = sha1(uniqid(mt_rand(), true)).'.'.$this->file->guessExtension();
        }
    
    }*/

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        if (null === $this->file) {
            return;
        }

          echo "fichier=" . $this->file . "<br>";
            exit(1);
        // s'il y a une erreur lors du déplacement du fichier, une exception
        // va automatiquement être lancée par la méthode move(). Cela va empêcher
        // proprement l'entité d'être persistée dans la base de données si
        // erreur il y a
        $this->file->move($this->getUploadRootDir(), $this->path);

        unset($this->file);
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
}