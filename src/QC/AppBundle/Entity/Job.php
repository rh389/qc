<?php

namespace QC\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use QC\AppBundle\Entity\Customer;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Job
 *
 * @ORM\Table(name="Job")
 * @ORM\Entity(repositoryClass="QC\AppBundle\Entity\JobRepository")
 */
class Job
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
     * @var \DateTime $created
     *
     * Nullable to allow import of nulls from old database.
     *
     * @ORM\Column(nullable=true, type="datetime")
     */
    private $dateCreated;

    /**
     * @var \DateTime $created
     *
     * @ORM\Column(nullable=true, type="datetime")
     */
    private $dateRequired;

    /**
     * @var string
     *
     * @ORM\Column(nullable=true, type="string", length=255)
     */
    private $orderReference;

    /**
     * @var string
     *
     * @ORM\Column(nullable=true, type="string", length=255)
     */
    private $drawingNumber;

    /**
     * @var string
     *
     * @ORM\Column(nullable=true, type="text")
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(nullable=true, type="text")
     */
    private $note;

    /**
     * @var string
     *
     * @ORM\Column(nullable=true, type="string", length=255)
     */
    private $status;

    /**
     * @var string
     *
     * @ORM\Column(nullable=true, type="string", length=255)
     */
    private $createdBy;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    private $completed=false;

    /**
     * @var Customer
     *
     * @ORM\ManyToOne(targetEntity="Customer", inversedBy="jobs")
     * @ORM\JoinColumn(name="customerId", referencedColumnName="id")
     */
    private $customer;

    /**
     * @var JobItem[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="JobItem", mappedBy="job", cascade="persist")
     */
    private $items;

    public function __construct(){
        $this->dateCreated = new \DateTime();
        $this->items = new ArrayCollection();
    }

    /**
     * @param int $id
     * @return Job
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param boolean $completed
     * @return Job
     */
    public function setCompleted($completed=true)
    {
        $this->completed = $completed;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isCompleted()
    {
        return $this->completed;
    }

    /**
     * @param string $createdBy
     * @return Job
     */
    public function setCreatedBy($createdBy)
    {
        $this->createdBy = $createdBy;
        return $this;
    }

    /**
     * @return string
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * @param \DateTime $dateCreated
     * @return Job
     */
    public function setDateCreated($dateCreated)
    {
        $this->dateCreated = $dateCreated;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDateCreated()
    {
        return $this->dateCreated;
    }

    /**
     * @param \DateTime $dateRequired
     * @return Job
     */
    public function setDateRequired($dateRequired)
    {
        $this->dateRequired = $dateRequired;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDateRequired()
    {
        return $this->dateRequired;
    }

    /**
     * @param string $description
     * @return Job
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $drawingNumber
     * @return Job
     */
    public function setDrawingNumber($drawingNumber)
    {
        $this->drawingNumber = $drawingNumber;
        return $this;
    }

    /**
     * @return string
     */
    public function getDrawingNumber()
    {
        return $this->drawingNumber;
    }

    /**
     * @param string $note
     * @return Job
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
     * @param string $orderReference
     * @return Job
     */
    public function setOrderReference($orderReference)
    {
        $this->orderReference = $orderReference;
        return $this;
    }

    /**
     * @return string
     */
    public function getOrderReference()
    {
        return $this->orderReference;
    }

    /**
     * @param string $status
     * @return Job
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }



    /**
     * @param \QC\AppBundle\Entity\Customer $customer
     * @return Job
     */
    public function setCustomer($customer)
    {
        $this->customer = $customer;
        return $this;
    }

    /**
     * @return \QC\AppBundle\Entity\Customer
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * @param $items
     * @return Job
     */
    public function setItems($items)
    {
        foreach($items as $item){
            $this->addItem($item);
        }
        return $this;
    }

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection|JobItem[]
     */
    public function getItems()
    {
        return $this->items;
    }

    public function addItem(JobItem $item)
    {
            $this->items->add($item);
    }
}