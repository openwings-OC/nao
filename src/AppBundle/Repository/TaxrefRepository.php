<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Taxref;
use Doctrine\ORM\EntityRepository;


class TaxrefRepository extends EntityRepository {

    //Selectionne tous les oiseaux de la bdd
    public function findAll(){

        $list = $this->createQueryBuilder('l')
            ->where('l.cdTaxsup > :taxsup')
            ->setParameter('taxsup', 0)
            ->orderBy('l.nomVern', 'ASC')
            ->getQuery()
            ->getResult();


        return $list;
    }

    //Recherche l'oiseau ave cle nom complet
    public function findBirdByName($name){
        $bird = $this->createQueryBuilder('b')
            ->select('b')
            ->where('b.nomVern = :name')
            //->orWhere('b.nomValide = :name')
            ->andwhere('b.cdTaxsup > :taxsup' )
            ->setParameter('name', $name)
            ->setParameter('taxsup', 0)
            ->getQuery()
            ->getSingleResult();

        return $bird;
    }

    //Query pour l'autocompletion ou une partie du nom de l'oiseau
    public function findBirdByLetter($letter){
        $bird = $this->createQueryBuilder('b')
            ->where('b.nomVern LIKE :letter')
            ->orWhere('b.nomValide LIKE :letter')
            ->andWhere('b.cdTaxsup > :taxsup')
            ->setParameter('letter', $letter.'%', false)
            ->setParameter('taxsup', 0)
            ->andWhere()
            ->getQuery()
            ->getResult();

        return $bird;
    }

}