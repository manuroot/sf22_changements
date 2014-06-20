<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * CertificatsCenter
 *
 * @ORM\Table(name="certificats_center")
 * @ORM\Entity
 */
class CertificatsCenter
{
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
     * @ORM\Column(name="file_name", type="string", length=50, nullable=false)
     */
    private $fileName;

    /**
     * @var string
     *
     * @ORM\Column(name="cn_name", type="string", length=50, nullable=false)
     */
    private $cnName;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="start_date", type="date", nullable=false)
     */
    private $startDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="end_time", type="date", nullable=false)
     */
    private $endTime;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="added_date", type="date", nullable=false)
     */
    private $addedDate;

    /**
     * @var string
     *
     * @ORM\Column(name="server_name", type="string", length=90, nullable=false)
     */
    private $serverName;

    /**
     * @var integer
     *
     * @ORM\Column(name="port", type="integer", nullable=false)
     */
    private $port;

    /**
     * @var string
     *
     * @ORM\Column(name="service_name", type="string", length=50, nullable=false)
     */
    private $serviceName;

    /**
     * @var string
     *
     * @ORM\Column(name="way", type="string", length=20, nullable=false)
     */
    private $way;

    /**
     * @var boolean
     *
     * @ORM\Column(name="status_file", type="boolean", nullable=false)
     */
    private $statusFile;

    /**
     * @var \Projet
     *
     * @ORM\ManyToOne(targetEntity="Projet")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="project", referencedColumnName="id")
     * })
     */
    private $project;

    /**
     * @var \Filetype
     *
     * @ORM\ManyToOne(targetEntity="Filetype")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="type_cert", referencedColumnName="id")
     * })
     */
    private $typeCert;


}
