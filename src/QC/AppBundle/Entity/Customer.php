<?php

namespace QC\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Customer
 *
 * @ORM\Table(name="Customer")
 * @ORM\Entity(repositoryClass="QC\AppBundle\Entity\CustomerRepository")
 */
class Customer
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
     * @ORM\Column(nullable=true, name="shortCode", type="string", length=255)
     */
    private $shortCode;

    /**
     * @var string
     *
     * @ORM\Column(nullable=true, name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(nullable=true, name="address1", type="string", length=255)
     */
    private $address1;

    /**
     * @var string
     *
     * @ORM\Column(nullable=true, name="address2", type="string", length=255)
     */
    private $address2;

    /**
     * @var string
     *
     * @ORM\Column(nullable=true, name="address3", type="string", length=255)
     */
    private $address3;

    /**
     * @var string
     *
     * @ORM\Column(nullable=true, name="address4", type="string", length=255)
     */
    private $address4;

    /**
     * @var string
     *
     * @ORM\Column(nullable=true, name="postcode", type="string", length=255)
     */
    private $postcode;

    /**
     * @var string
     *
     * @ORM\Column(nullable=true, name="note", type="text")
     */
    private $note;

    /**
     * @var CustomerContact[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="CustomerContact", mappedBy="customer")
     */
    private $contacts;

    /**
     * @var Job[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Job", mappedBy="customer")
     */
    private $jobs;

    /**
     * @var bool
     *
     * @ORM\Column(name="archived", type="boolean")
     */
    private $archived=false;


    public function __construct(){
        $this->contacts = new ArrayCollection();
        $this->jobs = new ArrayCollection();
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
     * Set short code
     *
     * @param string $shortCode
     * @return Customer
     */
    public function setShortCode($shortCode)
    {
        $this->shortCode = $shortCode;
        return $this;
    }

    /**
     * @return string
     */
    public function getShortCode()
    {
        return $this->shortCode;
    }


    /**
     * Set name
     *
     * @param string $name
     * @return Customer
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
     * @param string $address1
     * @return Customer
     */
    public function setAddress1($address1)
    {
        $this->address1 = $address1;
        return $this;
    }

    /**
     * @return string
     */
    public function getAddress1()
    {
        return $this->address1;
    }

    /**
     * @param string $address2
     * @return Customer
     */
    public function setAddress2($address2)
    {
        $this->address2 = $address2;
        return $this;
    }

    /**
     * @return string
     */
    public function getAddress2()
    {
        return $this->address2;
    }

    /**
     * @param string $address3
     * @return Customer
     */
    public function setAddress3($address3)
    {
        $this->address3 = $address3;
        return $this;
    }

    /**
     * @return string
     */
    public function getAddress3()
    {
        return $this->address3;
    }

    /**
     * @param string $address4
     * @return Customer
     */
    public function setAddress4($address4)
    {
        $this->address4 = $address4;
        return $this;
    }

    /**
     * @return string
     */
    public function getAddress4()
    {
        return $this->address4;
    }

    /**
     * @param string $note
     * @return Customer
     */
    public function setNote($note)
    {
        $this->note = $note;
        return $this;
    }

    /**
     * @return string
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * @param string $postcode
     * @return Customer
     */
    public function setPostcode($postcode)
    {
        $this->postcode = $postcode;
        return $this;
    }

    /**
     * @return string
     */
    public function getPostcode()
    {
        return $this->postcode;
    }

    /**
     * @param boolean $archived
     * @return Customer
     */
    public function setArchived($archived=true)
    {
        $this->archived = $archived;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isArchived()
    {
        return $this->archived;
    }

    /**
     * @param $contacts
     * @return Customer
     */
    public function setContacts($contacts)
    {
        $this->contacts = $contacts;
        return $this;
    }

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection|CustomerContact[]
     */
    public function getContacts()
    {
        return $this->contacts;
    }

    /**
     * @param $jobs
     * @return Customer
     */
    public function setJobs($jobs)
    {
        $this->jobs = $jobs;
        return $this;
    }

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection|Job[]
     */
    public function getJobs()
    {
        return $this->jobs;
    }

    /**
     * @return Job
     */
    public function getMostRecentJob(){
        /** @var $mostRecentJob Job */
        $mostRecentJob = null;
        foreach($this->getJobs() as $job){
            if(!$mostRecentJob || ($job->getId() > $mostRecentJob->getId())){
                $mostRecentJob = $job;
            }
        }
        return $mostRecentJob;
    }
}

