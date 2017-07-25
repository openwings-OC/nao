<?php

namespace Tests\AppBundle\Repository;

use AppBundle\Entity\Taxref;
use AppBundle\Repository\TaxrefRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TaxrefRepositoryTest extends KernelTestCase {

    private $em;

    protected function setUp(){
        self::bootkernel();

        $this->em = static::$kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    /**
     * Récupérer tous les oiseaux
     */
    public function testGetAllBirds(){
        $repository = $this->em->getRepository('AppBundle:Taxref');

        $list = $repository->findAll();
        $number = 0;

        foreach ($list as $bird){
            $number++;
        }
        $this->assertEquals(2326, $number);

    }

    /**
     * Trouver un oiseau par son nom exact
     */
    /*public function testFindBirdByName() {
        $repository = $this->em->getRepository('AppBundle:Taxref');
        $name = 'Épervier bicolore';

        $bird = $repository->findBirdByName($name);

        //var_dump($bird->getNomVern());
        $number = 0;
            foreach ($bird as $epervier){
                $number++;
            }
        $this->assertEquals(1, $number);
    }

    /**
     * Récupérer en autocomplétion (1,2,3 lettres)
     */
   /* public function testFindBirdByLetter(){
        $repository = $this->em->getRepository('AppBundle:Taxref');
        $letter1 = 'E';

        $search1 = $repository->findBirdByLetter($letter1);

        $number1 = 0;
        foreach ($search1 as $bird){
            $number1++;
        }
        $this->assertEquals(2907, $number1);

    }*/
}