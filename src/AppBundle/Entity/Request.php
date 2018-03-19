<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Request
 *
 * @ORM\Table(name="request")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\RequestRepository")
 */
class Request
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="request_time", type="datetime")
     */
    private $requestTime;

    /**
     * @var json_array
     *
     * @ORM\Column(name="request_info", type="array")
     */
    private $requestInfo;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set requestTime
     *
     * @param \DateTime $requestTime
     *
     * @return Request
     */
    public function setRequestTime($requestTime)
    {
        $this->requestTime = $requestTime;

        return $this;
    }

    /**
     * Get requestTime
     *
     * @return \DateTime
     */
    public function getRequestTime()
    {
        return $this->requestTime;
    }

    /**
     * Set requestInfo
     *
     * @param \DateTime $requestInfo
     *
     * @return Request
     */
    public function setRequestInfo($requestInfo)
    {
        $this->requestInfo = $requestInfo;

        return $this;
    }

    /**
     * Get requestInfo
     *
     * @return \DateTime
     */
    public function getRequestInfo()
    {
        return $this->requestInfo;
    }
}

