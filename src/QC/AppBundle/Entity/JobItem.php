<?php

namespace QC\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use QC\AppBundle\Entity\Customer;

/**
 * Job
 *
 * @ORM\Table(name="JobItem")
 * @ORM\Entity
 */
class JobItem
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
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private $quantity;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @var Job
     *
     * @ORM\ManyToOne(targetEntity="Job", inversedBy="items")
     * @ORM\JoinColumn(name="jobId", referencedColumnName="id")
     */
    private $job;

    /**
     * @param string $description
     * @return JobItem
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
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $quantity
     * @return JobItem
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
        return $this;
    }

    /**
     * @return int
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @param \QC\AppBundle\Entity\Job $job
     * @return JobItem
     */
    public function setJob($job)
    {
        $this->job = $job;
        return $this;
    }

    /**
     * @return \QC\AppBundle\Entity\Job
     */
    public function getJob()
    {
        return $this->job;
    }
}