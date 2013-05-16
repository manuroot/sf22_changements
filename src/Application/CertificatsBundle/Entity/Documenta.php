<?php

namespace Application\CertificatsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

 
/**
 *
 * @ORM\Table(name="documentsa")
 * @ORM\HasLifecycleCallbacks
 * @ORM\Entity
 */
 
class Documenta
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
 
   
      /**
     * @var string $file
     * @Assert\File( maxSize = "1024k", mimeTypesMessage = "Please upload a valid file")
     * @ORM\Column(name="file", type="string", length=255)
     */
    private $file;
 
  
 
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
     * Set image
     *
     * @param string $image
     */
    public function setFile($file)
    {
        $this->file = $file;
    }
 
    /**
     * Get image
     *
     * @return string
     */
    public function getFile()
    {
        return $this->file;
    }
 
    public function getFullFilePath() {
        return null === $this->file ? null : $this->getUploadRootDir(). $this->file;
    }
 
    protected function getUploadRootDir() {
        // the absolute directory path where uploaded documents should be saved
        return $this->getTmpUploadRootDir().$this->getId()."/";
    }
 
    protected function getTmpUploadRootDir() {
        // the absolute directory path where uploaded documents should be saved
        return __DIR__ . '/../../../../web/upload/';
    }
 
    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function uploadFile() {
        // the file property can be empty if the field is not required
        if (null === $this->file) {
            return;
        }
        if(!$this->id){
            $this->file->move($this->getTmpUploadRootDir(), $this->file->getClientOriginalName());
        }else{
            $this->file->move($this->getUploadRootDir(), $this->file->getClientOriginalName());
        }
        $this->setImage($this->file->getClientOriginalName());
    }
     
    /**
     * @ORM\PostPersist()
     */
    public function moveFile()
    {
        if (null === $this->file) {
            return;
        }
        if(!is_dir($this->getUploadRootDir())){
            mkdir($this->getUploadRootDir());
        }
        copy($this->getTmpUploadRootDir().$this->file, $this->getFullImagePath());
        unlink($this->getTmpUploadRootDir().$this->file);
    }
 
    /**
     * @ORM\PreRemove()
     */
    public function removeFile()
    {
        unlink($this->getFullImagePath());
        rmdir($this->getUploadRootDir());
    }
}

