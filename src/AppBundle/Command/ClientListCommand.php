<?php

namespace AppBundle\Command;

use AppBundle\Entity\Client;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Description of ClientListCommand
 *
 * @author Alsciende <alsciende@icloud.com>
 */
class ClientListCommand extends ContainerAwareCommand
{
    protected function configure ()
    {
        $this
            ->setName('security:client:list')
            ->setDescription('Lists all OAuth2 clients')
        ;
    }

    protected function execute (InputInterface $input, OutputInterface $output)
    {
        /* @var $em \Doctrine\ORM\EntityManager */
        $em = $this->getContainer()->get('doctrine')->getManager();

        $clients = $em->getRepository(Client::class)->findAll();

        $table = new Table($output);
        $table->setHeaders(['Id', 'RandomId', 'Name', 'Email', 'Grant Types', 'Redirect URIs']);

        /** @var Client $client */
        foreach($clients as $client) {
            $table->addRow([
                $client->getId(),
                $client->getRandomId(),
                $client->getName(),
                $client->getEmail(),
                implode("\n", $client->getAllowedGrantTypes()),
                implode("\n", $client->getRedirectUris()),
            ]);
        }

        $table->render();
    }
}