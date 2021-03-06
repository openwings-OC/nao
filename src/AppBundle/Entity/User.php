<?php
/**
 * Created by PhpStorm.
 * User: JulienHalgand
 * Date: 10/07/2017
 * Time: 14:33
 */

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\Boolean;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 * @ORM\HasLifecycleCallbacks()
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
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var string
     *
     * @ORM\Column(name="firstname", type="string", length=50)
     * @Assert\Length(min = 2,  max = 50, minMessage="Votre prénom doit contenir au moins 2 caractères", maxMessage="Votre prénom peut contenir 50 caractères maximum")
     * @Assert\Regex(pattern="/\d/", match=false, message="Votre prénom ne peut pas contenir de chiffres")
     * @Assert\NotBlank(message="Ce champs est vide")
     */
    private $firstname;

    /**
     * @var string
     *
     * @ORM\Column(name="lastname", type="string", length=50)
     * @Assert\Length(min = 2,  max = 50, minMessage="Votre nom doit contenir au moins 2 caractères", maxMessage="Votre nom peut contenir 50 caractères maximum")
     * @Assert\Regex(pattern="/\d/", match=false, message="Votre nom ne peut pas contenir de chiffres")
     * @Assert\NotBlank(message="Ce champs est vide")
     */
    private $lastname;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="Observation", mappedBy="user", cascade="persist")
     * @Assert\All({
     *   @Assert\Type(type="AppBundle\Entity\Observation")
     * })
     * @Assert\Valid
     */
    private $observations;


    /**
     * @Assert\Length(min = 7,  max = 50, minMessage="Votre email doit contenir au moins 6 caractères", maxMessage="Votre email peut contenir 50 caractères maximum")
     * @Assert\NotBlank(message="Ce champs est vide")
     * @Assert\Email(
     *     message = "Cette email '{{ value }}' n'est pas valide",
     *     checkMX = true
     * )
     */
    protected $email;


    /**
     * @Assert\Length(min = 8,  max = 20, minMessage="Votre mot de passe doit contenir au moins 8 caractères", maxMessage="Votre mot de passe peut contenir 20 caractères maximum")
     * @Assert\Regex(pattern="/^\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])\S*$/", match=true, message="Votre mot de passe doit contenir des chiffres, des lettres et au moins une majuscule")

     * @Assert\NotBlank(message="Ce champs est vide")
     */
    protected $plainPassword;

    /**
     * @Assert\NotBlank(message="Ce champs est vide",groups={"editUser"})
     */
    protected $roles;

    protected $enabled;
    /**
     * User constructor.
     */

    public function __construct(){
        parent::__construct();
        $this->createdAt = new \DateTime();
        $this->observations = new ArrayCollection();
        $this->roles = array('ROLE_USER');
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Bill
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
     * @return mixed
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * @param mixed $firstname
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
    }

    /**
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * @param string $lastname
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
    }

    /**
     * @return ArrayCollection
     */
    public function getObservations()
    {
        return $this->observations;
    }

    /**
     * @param ArrayCollection $observations
     */
    public function setObservations($observations)
    {
        $this->observations = $observations;
    }

    public function getRole(){
        if(in_array('ROLE_SUPER_ADMIN', $this->getRoles())||in_array('ROLE_ADMIN', $this->getRoles())){
            return 'ROLE_ADMIN';
        }elseif (in_array('ROLE_NATURALISTE', $this->getRoles())){
            return 'ROLE_NATURALISTE';
        }else{
            return 'ROLE_USER';
        }
    }

    public function setRole($role){
        $this->roles = array($role);
    }

    public function getEnabled(){
       return $this->enabled;
    }

    public function setEnabled($enabled){
        $this->enabled = $enabled;
        return $this;
    }
}