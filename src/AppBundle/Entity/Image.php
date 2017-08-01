<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use AppBundle\Validator\CheckImageTypeValidator;
use AppBundle\Validator\CheckImageSizeValidator;
use AppBundle\Validator\CheckImageSize;
use AppBundle\Validator\CheckImageType;


/**
 * Image
 *
 * @ORM\Table(name="image")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ImageRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Image
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
     * @ORM\Column(name="url", type="string", length=255)
     */
    private $url;


    /**
     * @var UploadedFile
     *
     */
    private $file;

    /**
     * @var string
     *
     * @ORM\Column(name="alt", type="string", length=255)
     */
    private $alt;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }


    public function setFile(File $file = null)
    {
        $this->file = $file;
        return $this;
    }


    public function getFile()
    {

        return $this->file;
    }

    /**
     * Set alt
     *
     * @param string $alt
     *
     * @return Image
     */
    public function setAlt($alt)
    {
        $this->alt = $alt;

        return $this;
    }

    /**
     * Get alt
     *
     * @return string
     */
    public function getAlt()
    {
        return $this->alt;
    }

    /**
     * Set url
     *
     * @param string $url
     *
     * @return Image
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    public function upload($date, $cdNom, $dir){
        if(null === $this->file){
            return;
        }
        $uniqid = uniqid();
        $date = $date->format('ym');
        $name = $date . $cdNom . $uniqid .".jpg";
        $this->file->move($dir, $name);

        $this->url = $name;
        $this->alt = $name;
    }

}
