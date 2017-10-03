<?php

namespace Mediashare\UserBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Mediashare\GedBundle\Entity\Folder;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="Mediashare\UserBundle\Entity\UserRepository")
 * @ORM\HasLifecycleCallbacks
 * @UniqueEntity(fields="username", message="Ce nom d'utilisateur est déjà utilisé")
 * @UniqueEntity(fields="email", message="L'adresse mail est déjà utilisée")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @Assert\Regex(
     *     pattern="/[a-zA-Z0-9]/",
     *     match=true,
     *     message="Le nom d'utilisateur ne peut contenir que des majuscules/minuscules et des chiffres, sans espaces"
     * )
     */
    protected $username;

    /**
     * @ORM\Column(type="string", name="first_name", length=255, nullable=true)
     */
    protected $firstName;

    /**
     * @ORM\Column(type="boolean", name="updated", nullable=true)
     */
    protected $updated;

    /**
     * @ORM\Column(type="boolean", name="connected", nullable=true)
     */
    protected $connected;

    /**
     * @ORM\Column(type="integer", name="timer", nullable=true)
     */
    protected $timer;
    
    /**
     * @ORM\Column(type="integer", name="points", nullable=true)
     */
    protected $points;

    /**
     * @ORM\Column(type="integer", name="classement", nullable=true)
     */
    protected $classement;

    /**
     * @ORM\Column(type="string", name="publickey", length=255, nullable=true)
     */
    protected $publickey;
    /**
     * @ORM\Column(type="string", name="privatekey", length=255, nullable=true)
     */
    protected $privatekey;

    /**
     * @ORM\Column(type="string", name="last_name", length=255, nullable=true)
     */
    protected $lastName;

    /**
     * @ORM\Column(type="string", name="color", length=255, nullable=true)
     */
    protected $color;

    /**
     * @ORM\ManyToMany(targetEntity="Mediashare\GedBundle\Entity\Folder", mappedBy="users")
     * @ORM\OrderBy({"createDate" = "DESC"})
     **/
    private $folders;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="update_date", type="date")
     */
    private $updateDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="create_date", type="date")
     */
    private $createDate;

    public function __construct()
    {
        parent::__construct();
        $this->folders = new ArrayCollection();
        $this->enabled = 0;
    }

    public function getFirstName()
    {
        return $this->firstName;
    }

    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
        return $this;
    }

    public function getLastName()
    {
        return $this->lastName;
    }

    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
        return $this;
    }

    public function getColor()
    {
        return $this->color;
    }

    public function setColor($color)
    {
        $this->color = $color;
        return $this;
    }

    public function getFullname()
    {
        return $this->username . ' (' . $this->lastName . ' ' . $this->firstName . ')';
    }

    /**
     * Add Folder
     *
     * @param Folder $folder
     * @return User
     */
    public function addFolder(Folder $folder)
    {
        $this->folders[] = $folder;

        return $this;
    }

    public function removeFolder(Folder $folder)
    {
        $this->folders->removeElement($folder);
    }

    public function getFolders()
    {
        return $this->folders;
    }

    /**
     * Set updateDate
     *
     * @param \DateTime $updateDate
     * @return User
     */
    public function setUpdateDate($updateDate)
    {
        $this->updateDate = $updateDate;

        return $this;
    }

    /**
     * Get updateDate
     *
     * @return \DateTime
     */
    public function getUpdateDate()
    {
        return $this->updateDate;
    }

    /**
     * Set createDate
     *
     * @param \DateTime $createDate
     * @return User
     */
    public function setCreateDate($createDate)
    {
        $this->createDate = $createDate;

        return $this;
    }

    /**
     * Get createDate
     *
     * @return \DateTime
     */
    public function getCreateDate()
    {
        return $this->createDate;
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpdate()
    {
        if (null == $this->createDate) {
            $this->createDate = new \DateTime();
        }
        $this->updateDate = new \DateTime();
    }

    /**
     * Set points
     *
     * @param integer $points
     * @return User
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
     * Set classement
     *
     * @param integer $classement
     * @return User
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
     * Set updated
     *
     * @param boolean $updated
     * @return User
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * Get updated
     *
     * @return boolean 
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Set connected
     *
     * @param boolean $connected
     * @return User
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
     * @return User
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
     * Set publickey
     *
     * @param string $publickey
     * @return User
     */
    public function setPublickey($publickey)
    {
        $this->publickey = $publickey;

        return $this;
    }

    /**
     * Get publickey
     *
     * @return string 
     */
    public function getPublickey()
    {
        return $this->publickey;
    }

    /**
     * Set privatekey
     *
     * @param string $privatekey
     * @return User
     */
    public function setPrivatekey($privatekey)
    {
        $this->privatekey = $privatekey;

        return $this;
    }

    /**
     * Get privatekey
     *
     * @return string 
     */
    public function getPrivatekey()
    {
        return $this->privatekey;
    }
}
