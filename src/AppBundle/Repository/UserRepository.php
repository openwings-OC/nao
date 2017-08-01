<?php
/**
 * Created by PhpStorm.
 * User: JulienHalgand
 * Date: 10/07/2017
 * Time: 15:12
 */

namespace AppBundle\Repository;


class UserRepository extends \Doctrine\ORM\EntityRepository
{
    //Query pour l'autocompletion ou une partie du nom de l'oiseau
    public function findUserByLetter($letter){
        $user = $this->createQueryBuilder('b')
            ->where('b.username LIKE :letter')
            ->orWhere('b.email LIKE :letter')
            ->setParameter('letter', $letter.'%', false)
            ->getQuery()
            ->getResult();
        return $user;
    }
}