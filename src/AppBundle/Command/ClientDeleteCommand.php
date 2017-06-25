<?php

namespace AppBundle\Command;

use AppBundle\Entity\Client;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;

/**
 * Description of CreateClientCommand
 *
 * @author Alsciende <alsciende@icloud.com>
 */
class ClientDeleteCommand extends ContainerAwareCommand
{

    protected function configure ()
    {
        $this
                ->setName('security:client:delete')
                ->setDescription('Deletes a client')
                ->addArgument('client_id', \Symfony\Component\Console\Input\InputArgument::REQUIRED, "Id of the client to delete")
        ;
    }

    protected function execute (InputInterface $input, OutputInterface $output)
    {
        $clientId = $input->getArgument('client_id');
        
        /* @var $em \Doctrine\ORM\EntityManager */
        $em = $this->getContainer()->get('doctrine')->getManager();
        
        $client = $em->getRepository('AlsciendeSecurityBundle:Client')->find($clientId);
        
        $em->remove($client);
        $em->flush();
        
        $output->writeln("Done");
    }

    /**
     * {@inheritdoc}
     */
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        $questions = array();

        if (!$input->getArgument('client_id')) {
            /* @var $em \Doctrine\ORM\EntityManager */
            $em = $this->getContainer()->get('doctrine')->getManager();

            $clients = $em->getRepository(Client::class)->findAll();

            $choices = [];
            /** @var Client $client */
            foreach($clients as $client) {
                $choices[] = sprintf("%d (\"%s\" by %s)",
                    $client->getId(),
                    $client->getName(),
                    $client->getEmail()
                );
            }

            $question = new ChoiceQuestion('Please choose a client to delete:', $choices);
            $question->setNormalizer(function ($value) {
               return substr($value, 0, strpos($value, ' '));
            });
            $questions['client_id'] = $question;
        }

        foreach ($questions as $name => $question) {
            $answer = $this->getHelper('question')->ask($input, $output, $question);
            $input->setArgument($name, $answer);
        }
    }
}
