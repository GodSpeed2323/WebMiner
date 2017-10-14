<?php

namespace Mediashare\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Top
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Mediashare\AppBundle\Entity\TopRepository")
 */
class Top
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
     * @ORM\Column(name="servername", type="string")
     */
    private $servername;

    /**
     * @var integer
     *
     * @ORM\Column(name="idconfig", type="integer")
     */
    private $idconfig;

    /**
     * @var integer
     *
     * @ORM\Column(name="iduser", type="integer")
     */
    private $iduser;

    /**
     * @var integer
     *
     * @ORM\Column(name="points", type="integer")
     */
    private $points;

    /**
     * @var integer
     *
     * @ORM\Column(name="ranked", type="integer")
     */
    private $ranked;

    /**
     * @var integer
     *
     * @ORM\Column(name="classement", type="integer")
     */
    private $classement;

    /**
     * @var boolean
     *
     * @ORM\Column(name="connected", type="boolean")
     */
    private $connected;

    /**
     * @var integer
     *
     * @ORM\Column(name="timer", type="integer")
     */
    private $timer;

    /**
     * @var boolean
     *
     * @ORM\Column(name="rankedupdated", type="boolean")
     */
    private $rankedupdated;

    /**
     * @var integer
     *
     * @ORM\Column(name="nextprogress", type="integer")
     */
    private $nextprogress;

    /**
     * @var float
     *
     * @ORM\Column(name="progress", type="float")
     */
    private $progress;

    /**
     * @var string
     *
     * @ORM\Column(name="ticketpass", type="string", length=255)
     */
    private $ticketpass;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=255)
     */
    private $username;


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
     * Set idconfig
     *
     * @param integer $idconfig
     * @return Top
     */
    public function setIdconfig($idconfig)
    {
        $this->idconfig = $idconfig;

        return $this;
    }

    /**
     * Get idconfig
     *
     * @return integer 
     */
    public function getIdconfig()
    {
        return $this->idconfig;
    }

    /**
     * Set iduser
     *
     * @param integer $iduser
     * @return Top
     */
    public function setIduser($iduser)
    {
        $this->iduser = $iduser;

        return $this;
    }

    /**
     * Get iduser
     *
     * @return integer 
     */
    public function getIduser()
    {
        return $this->iduser;
    }

    /**
     * Set points
     *
     * @param integer $points
     * @return Top
     */
    public function setPoints($points)
    {
        $this->points = $points;

        return $this;
    }

    /**
     * Get points
     *
     * @return integer 
     */
    public function getPoints()
    {
        return $this->points;
    }

    /**
     * Set ps
     *
     * @param integer $ps
     * @return Top
     */
    public function setPs($ps)
    {
        $this->ps = $ps;

        return $this;
    }

    /**
     * Get ps
     *
     * @return integer 
     */
    public function getPs()
    {
        return $this->ps;
    }

    /**
     * Set servername
     *
     * @param string $servername
     * @return Top
     */
    public function setServername($servername)
    {
        $this->servername = $servername;

        return $this;
    }

    /**
     * Get servername
     *
     * @return string 
     */
    public function getServername()
    {
        return $this->servername;
    }

    /**
     * Set classement
     *
     * @param integer $classement
     * @return Top
     */
    public function setClassement($classement)
    {
        $this->classement = $classement;

        return $this;
    }

    /**
     * Get classement
     *
     * @return integer 
     */
    public function getClassement()
    {
        return $this->classement;
    }

    /**
     * Set connected
     *
     * @param boolean $connected
     * @return Top
     */
    public function setConnected($connected)
    {
        $this->connected = $connected;

        return $this;
    }

    /**
     * Get connected
     *
     * @return boolean 
     */
    public function getConnected()
    {
        return $this->connected;
    }

    /**
     * Set timer
     *
     * @param integer $timer
     * @return Top
     */
    public function setTimer($timer)
    {
        $this->timer = $timer;

        return $this;
    }

    /**
     * Get timer
     *
     * @return integer 
     */
    public function getTimer()
    {
        return $this->timer;
    }

    /**
     * Set rankedupdated
     *
     * @param boolean $rankedupdated
     * @return Top
     */
    public function setRankedupdated($rankedupdated)
    {
        $this->rankedupdated = $rankedupdated;

        return $this;
    }

    /**
     * Get rankedupdated
     *
     * @return boolean 
     */
    public function getRankedupdated()
    {
        return $this->rankedupdated;
    }

    /**
     * Set nextprogress
     *
     * @param integer $nextprogress
     * @return Top
     */
    public function setNextprogress($nextprogress)
    {
        $this->nextprogress = $nextprogress;

        return $this;
    }

    /**
     * Get nextprogress
     *
     * @return integer 
     */
    public function getNextprogress()
    {
        return $this->nextprogress;
    }

    /**
     * Set progress
     *
     * @param float $progress
     * @return Top
     */
    public function setProgress($progress)
    {
        $this->progress = $progress;

        return $this;
    }

    /**
     * Get progress
     *
     * @return float 
     */
    public function getProgress()
    {
        return $this->progress;
    }

    /**
     * Set ticketpass
     *
     * @param string $ticketpass
     * @return Top
     */
    public function setTicketpass($ticketpass)
    {
        $this->ticketpass = $ticketpass;

        return $this;
    }

    /**
     * Get ticketpass
     *
     * @return string 
     */
    public function getTicketpass()
    {
        return $this->ticketpass;
    }

    /**
     * Set ranked
     *
     * @param integer $ranked
     * @return Top
     */
    public function setRanked($ranked)
    {
        $this->ranked = $ranked;

        return $this;
    }

    /**
     * Get ranked
     *
     * @return integer 
     */
    public function getRanked()
    {
        return $this->ranked;
    }

    /**
     * Set username
     *
     * @param string $username
     * @return Top
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string 
     */
    public function getUsername()
    {
        return $this->username;
    }
}
