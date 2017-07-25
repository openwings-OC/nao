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
$observation->setLatitude(48.878429);
$observation->setLongitude(2.322183);
$observation->setState('publish');
$observation->setImage('http://www.cocheurs.fr/img/especes/3531.JPG');
$observation->setAuthor('Aglantine');
$observation->setComment('Le Plongeon à bec blanc est le plus grand de la famille des Gaviidés. Son corps est parfaitement adapté à la vie aquatique de cette espèce avec les pattes attachées loin en arrière de l’abdomen. Cette particularité morphologique est idéale pour nager mais pas pour marcher sur le sol. Il ne s’envole que depuis la surface de l’eau. ');
$specie = $manager->getRepository('AppBundle:Taxref')->findSpecyByBirdId(953);
$observation->setSpecy($specie);


$manager->persist($observation);
$manager->flush();
}
}

