<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\ProgressBar;

use AppBundle\Entity\Taxref;

class ImportCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        // Name and description for app/console command
        $this
            ->setName('import:csv')
            ->setDescription('Import users from CSV file');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Showing when the script is launched
        $now = new \DateTime();
        $output->writeln('<comment>Start : ' . $now->format('d-m-Y G:i:s') . ' ---</comment>');

        // Importing CSV on DB via Doctrine ORM
        $this->import($input, $output);

        // Showing when the script is over
        $now = new \DateTime();
        $output->writeln('<comment>End : ' . $now->format('d-m-Y G:i:s') . ' ---</comment>');
    }

    protected function import(InputInterface $input, OutputInterface $output)
    {
        // Getting php array of data from CSV
        $data = $this->get($input, $output);

        // Getting doctrine manager
        $em = $this->getContainer()->get('doctrine')->getManager();
        // Turning off doctrine default logs queries for saving memory
        $em->getConnection()->getConfiguration()->setSQLLogger(null);

        // Define the size of record, the frequency for persisting the data and the current index of records
        $size = count($data);
        $batchSize = 20;
        $i = 1;

        // Starting progress
        $progress = new ProgressBar($output, $size);
        $progress->start();

        // Processing on each row of data
        foreach($data as $row) {
           $specy = $em->getRepository('AppBundle:Taxref')
                ->findOneByCdNom($row['CD_NOM']);

            // If the user doest not exist we create one
            if(!is_object($specy)){
                $specy = new Taxref();
            }

            // Updating info
            $specy->setCdNom($row['CD_NOM']);
            $specy->setRegne($row['REGNE']);
            $specy->setPhylum($row['PHYLUM']);
            $specy->setClasse($row['CLASSE']);
            $specy->setOrdre($row['ORDRE']);
            $specy->setFamille($row['FAMILLE']);
            $specy->setCdTaxsup($row['CD_TAXSUP']);
            $specy->setCdRef($row['CD_REF']);
            $specy->setRang($row['RANG']);
            $specy->setLbNom($row['LB_NOM']);
            //Remplace les parenthèses pour le champs lb_auteur
            $auteur = $specy->setLbAuteur($row['LB_AUTEUR']);
            $parentheses = array("(", ")");
            $auteur = str_replace($parentheses, "", $specy->getLbAuteur());
            $specy->setLbAuteur(utf8_encode($auteur));
            $specy->setNomComplet($row['NOM_COMPLET']);
            $specy->setNomValide($row['NOM_VALIDE']);
            $specy->setNomVern(utf8_encode($row['NOM_VERN']));
            $specy->setNomVernEng($row['NOM_VERN_ENG']);
            $specy->setHabitat($row['HABITAT']);
            $specy->setFr($row['FR']);
            $specy->setGf($row['GF']);
            $specy->setMar($row['MAR']);
            $specy->setGua($row['GUA']);
            $specy->setSm($row['SM']);
            $specy->setSb($row['SB']);
            $specy->setSpm($row['SPM']);
            $specy->setMay($row['MAY']);
            $specy->setEpa($row['EPA']);
            $specy->setReu($row['REU']);
            $specy->setSa($row['SA']);
            $specy->setTa($row['TA']);
            $specy->setTaaf($row['TAAF']);
            $specy->setNc($row['NC']);
            $specy->setWf($row['WF']);
            $specy->setPf($row['PF']);
            $specy->setFr($row['FR']);
            $specy->setCli($row['CLI']);

            // Do stuff here !

            // Persisting the current user
            $em->persist($specy);

            // Each 20 users persisted we flush everything
            if (($i % $batchSize) === 0) {

                $em->flush();
                // Detaches all objects from Doctrine for memory save
                $em->clear();

                // Advancing for progress display on console
                $progress->advance($batchSize);

                $now = new \DateTime();
                $output->writeln(' of species imported ... | ' . $now->format('d-m-Y G:i:s'));
            }
            $i++;
        }

        // Flushing and clear data on queue
        $em->flush();
        $em->clear();

        // Ending the progress bar process
        $progress->finish();
    }

    protected function get(InputInterface $input, OutputInterface $output)
    {
        // Getting the CSV from filesystem
        $fileName = 'TAXREF10.0.csv';


        // Using service for converting CSV to PHP Array
        $converter = $this->getContainer()->get('app.convert_array');
        $data = $converter->convert($fileName, ';');

        return $data;
    }
}