<?php

namespace AppBundle\Entity;

use AppBundle\Validator\CheckImageSize;
use AppBundle\Validator\CheckImageType;
use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\Image;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use AppBundle\Validator\CheckImageTypeValidator;
use AppBundle\Validator\CheckImageSizeValidator;

/**
 * Observation
 *
 * @ORM\Table(name="observation")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ObservationRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Observation
{
    const STATUS_VALIDATE = 'validate';
    const STATUS_REVIEW = 'review';
    const STATUS_PENDING = 'pending';

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
     * @ORM\Column(name="createdAt", type="datetime")
     * @Assert\LessThanOrEqual("now", message="Vous ne pouvez pas choisir de date future")
     * @Assert\GreaterThanOrEqual("today - 1 year", message="Pour des raisons scientifiques, l'observation doit dater de moins d'un an")
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updatedAt", type="datetime")
     */
    private $updatedAt;

    /**
     * @var string
     *
     * @ORM\Column(name="state", type="string", length=255)
     */
    private $state;

    /**
     * @var int
     *
     * @ORM\Column(name="latitude", type="decimal", precision=12, scale=9)
     * @Assert\Range(min="41.59101", max="51.03457", minMessage="Votre observation doit de situer en France métropolitaine", maxMessage="Votre observation doit de situer en France métropolitaine")
     *
     */
    private $latitude;

    /**
     * @var int
     *
     * @ORM\Column(name="longitude", type="decimal", precision=12, scale=9)
     * @Assert\Range(min="-4.65", max="9.45", minMessage="Votre observation doit de situer en France métropolitaine", maxMessage="Votre observation doit de situer en France métropolitaine")
     *
     */
    private $longitude;

    /**
     *
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Image", cascade={"persist", "remove"})
     * @CheckImageType()
     * @CheckImageSize()
     *
     */
    private $image;

    /**
     * @var int
     * @ORM\ManyToOne(targetEntity="User", inversedBy="observations")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Taxref", inversedBy="observations", cascade="persist")
     * @ORM\JoinColumn(name="specy_id", referencedColumnName="CD_NOM")
     */
    private $specy;

    /**
     * @var string
     * @ORM\Column(name="comment", type="text")
     *
     * @Assert\Length(min= 10, max= 400, minMessage="Votre observation doit comporter au moins 10 caractères", maxMessage="Maximum 400 caractères")
     */
    private $comment;
    /**
     * @var string
     * @ORM\Column(name="observation_comment", type="text", nullable=true)
     */
    private $observationComment;

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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Observation
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return Observation
     */
    public function setUpdatedAt($updatedAt = null)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set state
     *
     * @param string $state
     *
     * @return Observation
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get state
     *
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set latitude
     *
     * @param integer $latitude
     *
     * @return Observation
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * Get latitude
     *
     * @return int
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Set longitude
     *
     * @param integer $longitude
     *
     * @return Observation
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * Get longitude
     *
     * @return int
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Set specy
     *
     * @param \AppBundle\Entity\Taxref $specy
     *
     * @return Observation
     */
    public function setSpecy(\AppBundle\Entity\Taxref $specy = null)
    {
        $this->specy = $specy;

        return $this;
    }

    /**
     * Get specy
     *
     * @return \AppBundle\Entity\Taxref
     */
    public function getSpecy()
    {
        return $this->specy;
    }

    /**
     * Set comment
     *
     * @param string $comment
     *
     * @return Observation
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get comment
     *
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }
    /**
     * Set comment
     *
     * @param string $comment
     *
     * @return Observation
     */
    public function setObservationComment($observationComment)
    {
        $this->observationComment = $observationComment;

        return $this;
    }

    /**
     * Get comment
     *
     * @return string
     */
    public function getObservationComment()
    {
        return $this->observationComment;
    }

    public function setImage(Image $image = null)
    {
        $this->image = $image;
        return $this;
    }


    public function getImage()
    {
        return $this->image;
    }

    public function setUser(User $user){
        $this->user = $user;
        return $this;
    }

    public function getUser(){
        return $this->user;
    }

}
