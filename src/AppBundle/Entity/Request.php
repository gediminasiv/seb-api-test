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
     * @var string
     *
     * @ORM\Column(name="ip_address", type="string")
     */
    private $ipAddress;

    /**
     * @var string
     *
     * @ORM\Column(name="from_page", type="string")
     */
    private $fromPage;

    /**
     * @var string
     *
     * @ORM\Column(name="user_agent", type="string")
     */
    private $userAgent;

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

    /**
     * Set ipAddress
     *
     * @param string $ipAddress
     *
     * @return Request
     */
    public function setIpAddress($ipAddress)
    {
        $this->ipAddress = $ipAddress;

        return $this;
    }

    /**
     * Get ipAddress
     *
     * @return string
     */
    public function getIpAddress()
    {
        return $this->ipAddress;
    }

    /**
     * Set fromPage
     *
     * @param string $fromPage
     *
     * @return Request
     */
    public function setFromPage($fromPage)
    {
        $this->fromPage = $fromPage;

        return $this;
    }

    /**
     * Get fromPage
     *
     * @return string
     */
    public function getFromPage()
    {
        return $this->fromPage;
    }

    /**
     * Set userAgent
     *
     * @param string $userAgent
     *
     * @return Request
     */
    public function setUserAgent($userAgent)
    {
        $this->userAgent = $userAgent;

        return $this;
    }

    /**
     * Get userAgent
     *
     * @return string
     */
    public function getUserAgent()
    {
        return $this->userAgent;
    }
}
