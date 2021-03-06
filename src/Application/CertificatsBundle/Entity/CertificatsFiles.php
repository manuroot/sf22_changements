<?php

namespace Application\CertificatsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use APY\DataGridBundle\Grid\Mapping as GRID;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Application\CertificatsBundle\Entity\CertificatsCenter;


/**
 * Projet

 * @ORM\Table(name="certificats_files")
 * @ORM\Entity(repositoryClass="Application\CertificatsBundle\Repository\CertificatsFilesRepository")
 * @GRID\Source(columns="id,md5,path,OriginalFilename,$certificats",groupBy={"id"}) 
  * @ORM\HasLifecycleCallbacks
 */

class CertificatsFiles {
//class Docchangements  extends DocchangementsBase {
// * @Orm\MappedSuperclass

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    // champs supplemanetaire de saisie
    protected $name;

    /**
     * champs supplemanetaire md5
     * 
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected $md5;

    /**
     * @Assert\File(maxSize="5M",
     *    notFoundMessage = "Le fichier n'a pas été trouvé sur le disque",
     *    uploadErrorMessage = "Erreur dans l'upload du fichier"
     * )
     */
    protected $file;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    // champs pour nom local aletatoire
    protected $path;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    // nom origine du fichier
    protected $OriginalFilename;

    /**
     * Date/Time of the update
     *
     * @var \Datetime
     * @ORM\Column(name="updated_at", type="datetime")
     */
    protected $updatedAt;

     /**
     * Date/Time of the update
     *
     * @var \Datetime
     * @ORM\Column(name="created_at", type="datetime")
     */
    protected $createdAt;
    
    
   /**
     * @ORM\OneToMany(targetEntity = "CertificatsCenter", mappedBy = "fichier")
     */
    protected $certificats;
    
    
    protected $temp;
    private $ok_extensions = array("crt", "der","pem", "cer", "p12", "pkcs12", "p7", "p7b");

   
    protected $disk_path = 'uploads/certificats';

    /**
     * Constructor
     */
    public function __construct() {
        //   parent::__construct();
      //  $this->idchangement = new ArrayCollection();
        $this->createdAt = new \DateTime();
    }

  
   
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

    /**
     * Set disk path
     * 
     */
    public function setDiskPath($disk_path = 'uploads/certificats') {
        $this->disk_path = $disk_path;
    }

    /**
     * Get disk path
     * 
     */
    public function getDiskPath() {
        return $this->disk_path;
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

    public function getUploadRootDir() {
        // le chemin absolu du répertoire où les documents uploadés doivent être sauvegardés
        return __DIR__ . '/../../../../web/' . $this->getUploadDir();
    }

    public function getUploadDir() {
        // on se débarrasse de « __DIR__ » afin de ne pas avoir de problème lorsqu'on affiche
        // le document/image dans la vue.
        return $this->disk_path;
    }

    
    public function getDownloadDir() {
        // on se débarrasse de « __DIR__ » afin de ne pas avoir de problème lorsqu'on affiche
        // le document/image dans la vue.
        return 'web/' . $this->disk_path . '/';
    }
    /**
     * Avant le persist et l'update (fichier deja uploadé)
     * 
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload() {
        $this->updatedAt = new \DateTime();
        // si upload de fichier (temp file)
        if (null !== $this->file) {

            // faites ce que vous voulez pour générer un nom unique
            // a remettre apres
            //  $this->path=$this->generateNewFilename();

            $ext = null;
            $this->OriginalFilename = $this->getFile()->getClientOriginalName();
            $fic = $this->OriginalFilename;
            $info = pathinfo($fic);
            if (isset($info)) {
                $ext = $info['extension'];
            }
            if (!isset($ext)) {
                $ext = $this->file->guessExtension();
            }
            if (!isset($ext)) {
                $ext = "bin";
            }
            $this->path = sha1(uniqid(mt_rand(), true)) . '.' . $ext;


            // recup nom origne

            if (!$this->name || $this->name == "")
                $this->name = $this->OriginalFilename;
            $this->md5 = md5_file($this->file);
            $this->updatedAt = new \DateTime();
            // echo "here";exit(1);
        }
        // check du md5
        if (!$this->md5 && (file_exists($this->getUploadDir() . '/' . $this->path))) {
            $this->md5 = md5_file($this->getUploadDir() . '/' . $this->path);
        }
        // check du nom
        if (!$this->name || $this->name == "TOTO") {
            $this->name = $this->OriginalFilename;
        }
        //    echo "here";exit(1);
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
      /*  echo "ext=$ext<br>";
        echo "new name=$filename<br>";
        exit(1);*/


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
/*
        echo "ext=$ext<br>name=$name<br>";
        echo "new name=$filename<br>";
        exit(1);
*/

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
            if (file_exists($file))
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
     * Set name
     *
     * @param string $name
     * @return Document
     */
    public function setName($name) {
        // $this->name = $name;
        if (isset($name) && $name != "")
            $this->name = $name;
        else
            $this->name = "TOTO";
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
     * Set CreatedAt
     *
     * @param \DateTime $CreatedAt
     * @return Docchangements
     */
    public function setCreatedAt($createdAt) {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get CreatedAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt() {
        return $this->createdAt;
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
    public function setCertificats(\Application\CertificatsBundle\Entity\CertificatsCenter $certificats = null) {
        $this->certificats = $certificats;

        return $this;
    }

    /**
     * Get certificats
     *
     * @return \Application\CertificatsBundle\Entity\CertificatsCenter 
     */
    public function getCertificats() {
        return $this->certificats;
    }

    
    /*
    private function getExtension(){
     
// nom origine du fichier uploadé
        $fic = $this->getFile()->getClientOriginalName();
        $info = pathinfo($fic);
        if (isset($info)) {
            $ext = $info['extension'];
        }
        if (!isset($ext)) {
            $ext = $this->file->guessExtension();
        }
        if (!isset($ext))
            $ext = "bin";
     return $ext;
    }
    public function isAuthorValid(ExecutionContextInterface $context) {
 
        $ok = $this->ok_extensions;
        // slmt si fichier uploadé
           if (null !== $this->file) {
     
      $ext=$this->getExtension();
      if (!in_array($ext, $ok)) {
             $message = "$ext non autorisée, Extensions autorisees: (crt,pem,key,txt,p12)";
            $context->addViolationAt('file', $message, array(), null);
        }
           }
    }

    public function getMapOriginalExtension() {
        $path_info = pathinfo($this->file->getClientOriginalName());
        return $path_info['extension'];
    }

    private function getSanitizedFilename(UploadedFile $file) {
        $whitelist = array('jpg jpeg gif png txt html doc xls pdf ppt pps odt ods odp');

// $original = $file->getFilename();
        $filename = $file->getClientOriginalName();

// Split the filename up by periods. The first part becomes the basename
// the last part the final extension.
        $filename_parts = explode('.', $filename);
        $new_filename = array_shift($filename_parts); // Remove file basename.
        $final_extension = array_pop($filename_parts); // Remove final extension.
// Loop through the middle parts of the name and add an underscore to the
// end of each section that could be a file extension but isn't in the list
// of allowed extensions.
        foreach ($filename_parts as $filename_part) {
            $new_filename .= '.' . $filename_part;
            if (!in_array($filename_part, $whitelist) && preg_match("/^[a-zA-Z]{2,5}\d?$/", $filename_part)) {
                $new_filename .= '_';
            }
        }
        $filename = $new_filename . '.' . $final_extension;

        if (preg_match('/\.(php|pl|py|cgi|asp|js)(\.|$)/i', $filename) && (substr($filename, -4) != '.txt')) {
            $filename .= '.txt';
        }

        return $this->checkFilename($filename);
    }

    private function checkFilename($basename) {
// Strip control characters (ASCII value < 32). Though these are allowed in
// some filesystems, not many applications handle them well.
        $basename = preg_replace('/[\x00-\x1F]/u', '_', $basename);
        $directory = $this->getUploadDir();

        if (substr(PHP_OS, 0, 3) == 'WIN') {
// These characters are not allowed in Windows filenames
            $basename = str_replace(array(':', '*', '?', '"', '<', '>', '|'), '_', $basename);
        }

// A URI or path may already have a trailing slash or look like "public://".
        if (substr($directory, -1) == '/') {
            $separator = '';
        } else {
            $separator = '/';
        }

        $destination = $directory . $separator . $basename;

        if (file_exists($destination)) {
// Destination file already exists, generate an alternative.
            $pos = strrpos($basename, '.');
            if ($pos !== FALSE) {
                $name = substr($basename, 0, $pos);
                $ext = substr($basename, $pos);
            } else {
                $name = $basename;
                $ext = '';
            }

            $counter = 0;
            do {
                $destination = $directory . $separator . $name . '_' . $counter++ . $ext;
            } while (file_exists($destination));
        }

        return $destination;
    }
}
*/

    /**
     * Add certificats
     *
     * @param \Application\CertificatsBundle\Entity\CertificatsCenter $certificats
     * @return CertificatsFiles
     */
    public function addCertificat(\Application\CertificatsBundle\Entity\CertificatsCenter $certificats)
    {
        $this->certificats[] = $certificats;
    
        return $this;
    }

    /**
     * Remove certificats
     *
     * @param \Application\CertificatsBundle\Entity\CertificatsCenter $certificats
     */
    public function removeCertificat(\Application\CertificatsBundle\Entity\CertificatsCenter $certificats)
    {
        $this->certificats->removeElement($certificats);
    }
}