<?php

namespace AppBundle\Service;

use Doctrine\ORM\EntityManager;

class observationCreation {

    private $em;

    public function __construct(EntityManager $em)
{
        $this->em = $em;
}


    public function uploadImage($observation, $dir){
        if($observation->getImage() === null){
            return;
        }
        $observation->getImage()->upload($observation->getCreatedAt(), $observation->getSpecy()->getCdNom(), $dir);
    }

    public function persistObservation($observation){
        $date = $observation->getCreatedAt();
        $observation->setUpdatedAt($date);
        $observation->setSpecy($observation->getSpecy());
        $this->em->persist($observation);
        $this->em->flush();
    }
}