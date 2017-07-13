<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Observation;
use AppBundle\Entity\Taxref;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\User;

class loadObservations implements FixtureInterface
{
public function load(ObjectManager $manager)
{
$observation = new Observation();
$observation->setCreatedAt(new \DateTime());
$observation->setUpdatedAt(new \DateTime());
$observation->setLatitude(43.489433);
$observation->setLongitude(3.669961);
$observation->setState('publish');
$specie = $manager->getRepository('AppBundle:Taxref')->findSpecyByBirdId(441604);
$observation->setSpecie($specie);
//$observation->setSpecie($manager->getRepository('AppBundle:Taxref')->findSpecyByBirdId(441604));

$manager->persist($observation);
$manager->flush();
}
}

