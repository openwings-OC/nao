<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Taxref;
use Doctrine\ORM\EntityRepository;


class TaxrefRepository extends EntityRepository {

    public function findAll(){

        $list = $this->createQueryBuilder('l')
            ->where('l.cdTaxsup > :taxsup')
            ->setParameter('taxsup', 0)
            ->orderBy('l.nomVern', 'ASC')
            ->getQuery()
            ->getResult();


        return $list;
    }

    public function findBirdByName($name){
        $bird = $this->createQueryBuilder('b')
            ->select('b')
            ->where('b.nomVern = :name')
            ->andwhere('b.cdTaxsup > :taxsup' )
            ->setParameter('name', $name)
            ->setParameter('taxsup', 0)
            ->getQuery()
            ->getSingleResult();

        return $bird;
    }

    public function findBirdByLetter($letter){
        $bird = $this->createQueryBuilder('b')
            ->where('b.nomVern LIKE :letter')
            ->andWhere('b.cdTaxsup > :taxsup')
            ->setParameter('letter', $letter.'%', false)
            ->setParameter('taxsup', 0)
            ->andWhere()
            ->getQuery()
            ->getResult();

        return $bird;
    }

}