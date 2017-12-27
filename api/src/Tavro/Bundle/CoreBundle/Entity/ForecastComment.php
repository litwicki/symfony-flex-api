<?php

namespace Tavro\Bundle\CoreBundle\Entity;

use Doctrine\ORM\Mapping AS ORM;

use JMS\Serializer\Annotation\Accessor;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\VirtualProperty;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\MaxDepth;
use Doctrine\ORM\Mapping\Table;

use Tavro\Bundle\CoreBundle\Model\Entity\Entity;
use Tavro\Bundle\CoreBundle\Model\EntityInterface\ForecastEntityInterface;

/**
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="Tavro\Bundle\CoreBundle\Repository\Entity\ForecastCommentRepository")
 * @ORM\Table(name="tavro_forecast_comment", indexes={@ORM\Index(name="NODE_COMMENT", columns={"comment_id","forecast_id"})})
 * @ExclusionPolicy("all")
 */
class ForecastComment extends Entity implements ForecastEntityInterface
{
    /**
     * @ORM\ManyToOne(targetEntity="Tavro\Bundle\CoreBundle\Entity\Comment", inversedBy="forecast_comments")
     * @ORM\JoinColumn(name="comment_id", referencedColumnName="id", nullable=FALSE)
     * @Groups({"api", "detail"})
     */
    protected $comment;

    /**
     * @ORM\ManyToOne(targetEntity="Tavro\Bundle\CoreBundle\Entity\Forecast", inversedBy="forecast_comments")
     * @ORM\JoinColumn(name="forecast_id", referencedColumnName="id", nullable=FALSE)
     */
    protected $forecast;

    public function __construct()
    {
        parent::__construct();
        $this->create_date = new \DateTime();
    }

    /**
     * Set comment
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\Comment $comment
     * @return ForecastComment
     */
    public function setComment(\Tavro\Bundle\CoreBundle\Entity\Comment $comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get comment
     *
     * @return \Tavro\Bundle\CoreBundle\Entity\Comment
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set Forecast
     *
     * @param \Tavro\Bundle\CoreBundle\Entity\Forecast $forecast
     * @return ForecastComment
     */
    public function setForecast(\Tavro\Bundle\CoreBundle\Entity\Forecast $forecast)
    {
        $this->forecast = $forecast;

        return $this;
    }

    /**
     * Get Forecast
     *
     * @return \Tavro\Bundle\CoreBundle\Entity\Forecast
     */
    public function getForecast()
    {
        return $this->forecast;
    }


    /**
     * Set body
     *
     * @param string $body
     *
     * @return ForecastComment
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
}
