<?php

namespace Mediashare\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Config
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Mediashare\AppBundle\Entity\ConfigRepository")
* @UniqueEntity(
*     fields={"name"},
*     errorPath="name",
*     message="Le nom du Serveur est déjà utilisés."
* )
* @UniqueEntity(
*     fields={"publicKey"},
*     errorPath="publicKey",
*     message="La Clef Publique est déjà utilisés."
* )
* @UniqueEntity(
*     fields={"privateKey"},
*     errorPath="privateKey",
*     message="La Clef Privée est déjà utilisés."
* )
 */
class Config
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     */
    private $name;
    
    /**
     * @var string
     *
     * @ORM\Column(name="admin", type="string", length=255)
     */
    private $admin;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="idadmin", type="integer")
     */
    private $idadmin;

    /**
     * @var string
     *
     * @ORM\Column(name="link", type="string", length=500, nullable=true)
     */
    private $link;

    /**
     * @var string
     *
     * @ORM\Column(name="publicKey", type="string", length=255, unique=true)
     */
    private $publicKey;

    /**
     * @var string
     *
     * @ORM\Column(name="privateKey", type="string", length=255, unique=true)
     */
    private $privateKey;
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="online", type="boolean", nullable=true)
     */
    private $online;
    
    /**
     * @var string
     *
     * @ORM\Column(name="points_seconde", type="string", length=255, nullable=true)
     */
    private $pointsSeconde;

    /**
     * @var string
     *
     * @ORM\Column(name="points_total", type="string", length=255, nullable=true)
     */
    private $pointsTotal;

    /**
     * @var string
     *
     * @ORM\Column(name="nb_online", type="integer", nullable=true)
     */
    private $nbOnline;

    /**
     * @var string
     *
     * @ORM\Column(name="xmr_total", type="string", length=255, nullable=true)
     */
    private $xmrTotal;


    public function __toString()
    {
        return $this->name;
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
     * @return Config
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
     * Set publicKey
     *
     * @param string $publicKey
     * @return Config
     */
    public function setPublicKey($publicKey)
    {
        $this->publicKey = $publicKey;

        return $this;
    }

    /**
     * Get publicKey
     *
     * @return string 
     */
    public function getPublicKey()
    {
        return $this->publicKey;
    }

    /**
     * Set privateKey
     *
     * @param string $privateKey
     * @return Config
     */
    public function setPrivateKey($privateKey)
    {
        $this->privateKey = $privateKey;

        return $this;
    }

    /**
     * Get privateKey
     *
     * @return string 
     */
    public function getPrivateKey()
    {
        return $this->privateKey;
    }

    /**
     * Set admin
     *
     * @param string $admin
     * @return Config
     */
    public function setAdmin($admin)
    {
        $this->admin = $admin;

        return $this;
    }

    /**
     * Get admin
     *
     * @return string 
     */
    public function getAdmin()
    {
        return $this->admin;
    }

    /**
     * Set idadmin
     *
     * @param integer $idadmin
     * @return Config
     */
    public function setIdadmin($idadmin)
    {
        $this->idadmin = $idadmin;

        return $this;
    }

    /**
     * Get idadmin
     *
     * @return integer 
     */
    public function getIdadmin()
    {
        return $this->idadmin;
    }

    /**
     * Set online
     *
     * @param boolean $online
     * @return Config
     */
    public function setOnline($online)
    {
        $this->online = $online;

        return $this;
    }

    /**
     * Get online
     *
     * @return boolean 
     */
    public function getOnline()
    {
        return $this->online;
    }

    /**
     * Set pointsSeconde
     *
     * @param string $pointsSeconde
     * @return Config
     */
    public function setPointsSeconde($pointsSeconde)
    {
        $this->pointsSeconde = $pointsSeconde;

        return $this;
    }

    /**
     * Get pointsSeconde
     *
     * @return string 
     */
    public function getPointsSeconde()
    {
        return $this->pointsSeconde;
    }

    /**
     * Set pointsTotal
     *
     * @param string $pointsTotal
     * @return Config
     */
    public function setPointsTotal($pointsTotal)
    {
        $this->pointsTotal = $pointsTotal;

        return $this;
    }

    /**
     * Get pointsTotal
     *
     * @return string 
     */
    public function getPointsTotal()
    {
        return $this->pointsTotal;
    }

    /**
     * Set nbOnline
     *
     * @param integer $nbOnline
     * @return Config
     */
    public function setNbOnline($nbOnline)
    {
        $this->nbOnline = $nbOnline;

        return $this;
    }

    /**
     * Get nbOnline
     *
     * @return integer 
     */
    public function getNbOnline()
    {
        return $this->nbOnline;
    }

    /**
     * Set xmrTotal
     *
     * @param string $xmrTotal
     * @return Config
     */
    public function setXmrTotal($xmrTotal)
    {
        $this->xmrTotal = $xmrTotal;

        return $this;
    }

    /**
     * Get xmrTotal
     *
     * @return string 
     */
    public function getXmrTotal()
    {
        return $this->xmrTotal;
    }

    /**
     * Set link
     *
     * @param string $link
     * @return Config
     */
    public function setLink($link)
    {
        $this->link = $link;

        return $this;
    }

    /**
     * Get link
     *
     * @return string 
     */
    public function getLink()
    {
        return $this->link;
    }
}
