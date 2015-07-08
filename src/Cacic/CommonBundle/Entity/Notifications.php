<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Notifications
 */
class Notifications
{
    /**
     * @var integer
     */
    private $idNotification;

    /**
     * @var string
     */
    private $subject;

    /**
     * @var string
     */
    private $from;

    /**
     * @var string
     */
    private $replyTo;

    /**
     * @var string
     */
    private $to;

    /**
     * @var string
     */
    private $body;

    /**
     * @var \DateTime
     */
    private $readDate;


    /**
     * Get idNotification
     *
     * @return integer 
     */
    public function getIdNotification()
    {
        return $this->idNotification;
    }

    /**
     * Set subject
     *
     * @param string $subject
     * @return Notifications
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * Get subject
     *
     * @return string 
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * Set from
     *
     * @param string $from
     * @return Notifications
     */
    public function setFrom($from)
    {
        $this->from = $from;

        return $this;
    }

    /**
     * Get from
     *
     * @return string 
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * Set replyTo
     *
     * @param string $replyTo
     * @return Notifications
     */
    public function setReplyTo($replyTo)
    {
        $this->replyTo = $replyTo;

        return $this;
    }

    /**
     * Get replyTo
     *
     * @return string 
     */
    public function getReplyTo()
    {
        return $this->replyTo;
    }

    /**
     * Set to
     *
     * @param string $to
     * @return Notifications
     */
    public function setTo($to)
    {
        $this->to = $to;

        return $this;
    }

    /**
     * Get to
     *
     * @return string 
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * Set body
     *
     * @param string $body
     * @return Notifications
     */
    public function setBody($body)
    {
        $this->body = $body;

        return $this;
    }

    /**
     * Get body
     *
     * @return string 
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Set readDate
     *
     * @param \DateTime $readDate
     * @return Notifications
     */
    public function setReadDate($readDate)
    {
        $this->readDate = $readDate;

        return $this;
    }

    /**
     * Get readDate
     *
     * @return \DateTime 
     */
    public function getReadDate()
    {
        return $this->readDate;
    }
}
