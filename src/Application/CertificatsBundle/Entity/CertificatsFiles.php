<?php

namespace Application\CertificatsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Application\CertificatsBundle\Entity\CertificatsCenter;


/**
 * Certificats_Files
 *
 * @ORM\Table(name="certificats_files")
 * @ORM\Entity()
  * @ORM\HasLifecycleCallbacks
 */

class CertificatsFiles {
    

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

  
    /**
     * champs supplemanetaire md5
     * 
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $md5;

    /**
     * @Assert\File( maxSize="500k",
     *    notFoundMessage = "Le fichier n'a pas été trouvé sur le disque",
     *    uploadErrorMessage = "Erreur dans l'upload du fichier"
     * )
     */
    private $file;

      //   mimeTypes = {"application/x-x509-ca-cert", "application/x-pkcs12", "application/p12"},
     //     mimeTypesMessage = "Le fichier choisi ne correspond pas à un fichier valide",
     //     notFoundMessage = "Le fichier n'a pas été trouvé sur le disque",
     //    uploadErrorMessage = "Erreur dans l'upload du fichier"
   
  
    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    // champs pour nom local aletatoire
    private $path;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    // nom origine du fichier
    private $OriginalFilename;

  
    /**
     * @ORM\OneToOne(targetEntity = "CertificatsCenter", mappedBy = "fichier")
     */
    
    protected $certificats;
    
     /**
     * Date/Time of the update
     *
     * @var \Datetime
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;
  

    //Assert\NotBlank

    /**
     * Date/Time of the update
     *
     * @var \Datetime
     * @ORM\Column(name="updated_at", type="datetime",nullable=true)
     */
    private $updatedAt;
  
    private $temp;

     protected $disk_path='uploads/documents';
    //protected $disk_path='uploads/documents/certificats';
    /**
     * Get id
     *
     * @return UploadedFile
     * fichier temporaire: ex: /tmp/php702KS7
     */
    // private $OriginalFilename;

    public function getFile() {
        return $this->file;
    }

    /**
     * Set file
     *
     * 
     * @param UploadedFile $file
     * @return Document
     */
    public function setFile(UploadedFile $file = null) {
        $this->file = $file;
        // check if we have an old image path
        if (isset($this->path)) {
            // store the old name to delete after the update
            $this->temp = $this->path;
            $this->path = null;
        } else {
            $this->path = 'initial';
        }

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
        return $this->disk_path;
    }

    /**
      * @ORM\PrePersist()
     */
     public function preTestFic() {
        
          if (null !== $this->file) {
             $this->createdAt = new \DateTime();   
          }
        // si pa de fichier physique
        if (null == $this->path && null==$this->file) {
            return;
        }
     }
    /**
     * Avant le persist et l'update (fichier deja uploadé)
     * 
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload() {
        
        // si upload de fichier (temp file)
        if (null !== $this->file) {

           //  echo "here 111";exit(1);
            // faites ce que vous voulez pour générer un nom unique
            // a remettre apres
            //  $this->path=$this->generateNewFilename();
            $ext = $this->file->guessExtension();
            if (!isset($ext)) {
                $ext = "bin";
            }
            $this->path = sha1(uniqid(mt_rand(), true)) . '.' . $ext;
            // recup nom origne
            $this->OriginalFilename = $this->getFile()->getClientOriginalName();
             $this->md5 = md5_file($this->file);
             // date fichier uploadé
               $this->updatedAt = new \DateTime();
             //   $this->createdAt = new \DateTime();
        }
        // check du md5 si fichier existe deja
        if ($this->path){
            //   $this->updatedAt = new \DateTime();
      
          if (!$this->md5 && (file_exists($this->getUploadDir() . '/' . $this->path))){
             $this->md5 = md5_file($this->getUploadDir() . '/' . $this->path);
        }
        // check du nom
      /*   if (!$this->name || $this->name =="TOTO" ){
              $this->name = $this->OriginalFilename;
         }*/
        }
        if (!$this->createdAt){
             $this->createdAt = new \DateTime();
        }
         // echo "here 222";exit(1);
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

 /*   public function Ficinfos{
        if (isset($this->path))
     $file_basename = basename($file);
    $dir = dirname($file);
    $info = pathinfo($file);
    $prefixe_name = basename($file_basename, '.' . $info['extension']);
}*/
    /**
     * Generates a non-random-filename
     *
     * @return string A non-random name to represent the current file
     */
    public function getFilename() {
        // nom du fichier
        $filename = $this->getFile()->getClientOriginalName();
        $ext = $this->getFile()->guessExtension();
        //  $ext = $this->getFile()->getExtension();
        // if ($ext !==null)


        echo "root dir=" . $this->getUploadRootDir() . "<br>";
        echo "file=$filename<br>";
        $name = substr($filename, 0, - strlen($ext));
        $i = 1;
        //  $this->getUploadRootDir(), $this->path;
        $fullpath = $this->getUploadRootDir();
        while (file_exists($fullpath . '/' . $filename)) {
            $filename = $name . '-' . $i . $ext;
            $i++;
        }
        echo "ext=$ext<br>";
        echo "new name=$filename<br>";
        exit(1);


        return array($filename, $ext,);
    }

    public function generateNewFilename() {
        // nom du fichier
        $filename = $this->getFile()->getClientOriginalName();
        $ext = $this->getFile()->guessExtension();
        //  $ext = $this->getFile()->getExtension();
        // if ($ext !==null)


        echo "root dir=" . $this->getUploadRootDir() . "<br>";
        echo "file=$filename<br>";
        if (isset($ext) && strlen($ext) > 0) {
            $name = substr($filename, 0, - (strlen($ext) + 1));
            $i = 1;
            //  $this->getUploadRootDir(), $this->path;
            $fullpath = $this->getUploadRootDir();
            while (file_exists($fullpath . '/' . $filename)) {
                $filename = $name . '-' . $i . '.' . $ext;
                $i++;
            }
        }
        else
            $name = $filename;

      /*  echo "ext=$ext<br>name=$name<br>";
        echo "new name=$filename<br>";
        exit(1);*/


        return ($filename);
    }

    public function generatePathFileName($file) {
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
    public function postLoad() {
        //  nimporte koi!!
        //   $this->updatedAt = new \DateTime();
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
     *  Set name
     *
     * @param string $name
     * @return Document
     */
    public function setOriginalFilename() {
        // $this->name = $name;

        $this->OriginalFilename = $this->getFile()->getClientOriginalName();

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
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreateddAt() {
        return $this->createdAt;
    }
    
    
     /**
     * Set updatedAt
     *
     * @param \DateTime $createdAt
     * @return Docchangements
     */
    public function setCreatedAt($createdAt) {
       $this->createdAt = $createdAt;

        return $this;
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


    /**
     * Set certificats
     *
     * @param \Application\CertificatsBundle\Entity\CertificatsCenter $certificats
     * @return CertificatsFiles
     */
    public function setCertificats(\Application\CertificatsBundle\Entity\CertificatsCenter $certificats = null)
    {
        $this->certificats = $certificats;
    
        return $this;
    }

    /**
     * Get certificats
     *
     * @return \Application\CertificatsBundle\Entity\CertificatsCenter 
     */
    public function getCertificats()
    {
        return $this->certificats;
    }
}
/*application/pkcs8                   .p8  .key
application/pkcs10                  .p10 .csr
application/pkix-cert               .cer
application/pkix-crl                .crl
application/pkcs7-mime              .p7c

application/x-x509-ca-cert          .crt .der
application/x-x509-user-cert        .crt
application/x-pkcs7-crl             .crl

application/x-pem-file              .pem
application/x-pkcs12                .p12 .pfx

application/x-pkcs7-certificates    .p7b .spc
application/x-pkcs7-certreqresp     .p7r
 
 *  *   mimeTypes = {
     * "application/x-x509-user-cert",
     *   "application/x-pkcs12", 
     *   "application/vnd.openxmlformats-officedocument.wordprocessingml.document",
     *   "application/msword",
     *   "application/x-x509-ca-cert", "application/p12"},
     *    mimeTypesMessage = "Le fichier choisi ne correspond pas à un fichier valide (crt,pem,pkcs12)",* 
 */


/*echo 'info' . pathinfo($file, PATHINFO_EXTENSION);
    echo '<br>';
    $file_basename = basename($file);
    $dir = dirname($file);
    $info = pathinfo($file);
    $prefixe_name = basename($file_basename, '.' . $info['extension']);
 * 
 */