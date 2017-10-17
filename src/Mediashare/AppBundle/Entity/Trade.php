<?php

namespace Mediashare\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Trade
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Mediashare\AppBundle\Entity\TradeRepository")
 */
class Trade
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
     * @var integer
     *
     * @ORM\Column(name="iduser", type="integer")
     */
    private $iduser;

    /**
     * @var integer
     *
     * @ORM\Column(name="idconfig", type="integer")
     */
    private $idconfig;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=255)
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="servername", type="string", length=255)
     */
    private $servername;

    /**
     * @var string
     *
     * @ORM\Column(name="ticketpass", type="string", length=255)
     */
    private $ticketpass;

    /**
     * @var integer
     *
     * @ORM\Column(name="amout", type="integer")
     */
    private $amout;


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
     * Set iduser
     *
     * @param integer $iduser
     * @return Trade
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
     * Set idconfig
     *
     * @param integer $idconfig
     * @return Trade
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
     * Set username
     *
     * @param string $username
     * @return Trade
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

    /**
     * Set servername
     *
     * @param string $servername
     * @return Trade
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
     * Set amout
     *
     * @param integer $amout
     * @return Trade
     */
    public function setAmout($amout)
    {
        $this->amout = $amout;

        return $this;
    }

    /**
     * Get amout
     *
     * @return integer 
     */
    public function getAmout()
    {
        return $this->amout;
    }

    /**
     * Set ticketpass
     *
     * @param string $ticketpass
     * @return Trade
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
}
