<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

/**
 * Description of CreateClientCommand
 *
 * @author Alsciende <alsciende@icloud.com>
 */
class ClientCreateCommand extends ContainerAwareCommand
{

    protected function configure ()
    {
        $this
            ->setName('security:client:create')
            ->setDescription('Creates a new client')
            ->addOption(
                'redirect-uri', null, InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY, 'Sets redirect uri for client. Use this option multiple times to set multiple redirect URIs.', null
            )
            ->addOption(
                'grant-type', null, InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY, 'Sets allowed grant type for client. Use this option multiple times to set multiple grant types..', null
            )
            ->addOption(
                'client-name', null, InputOption::VALUE_REQUIRED, 'Sets the displayed name of the client'
            )
            ->addOption(
                'client-email', null, InputOption::VALUE_REQUIRED, 'Sets the email of contact of the client'
            );
    }

    protected function execute (InputInterface $input, OutputInterface $output)
    {
        $redirectURIs = $input->getOption('redirect-uri');
        $grantTypes = $input->getOption('grant-type');
        $clientName = $input->getOption('client-name');
        $clientEmail = $input->getOption('client-email');

        if (empty($redirectURIs) or empty($grantTypes) or empty($clientName) or empty($clientEmail)) {
            throw new \Exception("Mandatory parameter missing.");
        }

        $clientManager = $this->getContainer()->get('fos_oauth_server.client_manager.default');
        $client = $clientManager->createClient();
        $client->setRedirectUris($redirectURIs);
        $client->setAllowedGrantTypes($grantTypes);
        $client->setName($clientName);
        $client->setEmail($clientEmail);
        $clientManager->updateClient($client);
        $output->writeln(
            sprintf(
                'Added a new client with public id <info>%s</info>, secret <info>%s</info>', $client->getPublicId(), $client->getSecret()
            )
        );
    }

    protected function interact (InputInterface $input, OutputInterface $output)
    {
        $questions = array();

        if (count($input->getOption('redirect-uri')) === 0) {
            $question = new Question('Please choose a redirect URI:');
            $question->setValidator(function ($redirectURI) {
                if (empty($redirectURI)) {
                    throw new \Exception('Redirect URI can not be empty');
                }

                return array($redirectURI);
            });
            $questions['redirect-uri'] = $question;
        }

        if (count($input->getOption('grant-type')) === 0) {
            $question = new Question('Please choose a grant type:');
            $question->setValidator(function ($grantType) {
                if (empty($grantType)) {
                    throw new \Exception('Grant type can not be empty');
                }

                return array($grantType);
            });
            $questions['grant-type'] = $question;
        }

        if (count($input->getOption('client-name')) === 0) {
            $question = new Question('Please choose a client name:');
            $question->setValidator(function ($clientName) {
                if (empty($clientName)) {
                    throw new \Exception('Client name can not be empty');
                }

                return $clientName;
            });
            $questions['client-name'] = $question;
        }

        if (count($input->getOption('client-email')) === 0) {
            $question = new Question('Please choose a client email:');
            $question->setValidator(function ($clientEmail) {
                if (empty($clientEmail)) {
                    throw new \Exception('Client email can not be empty');
                }

                return $clientEmail;
            });
            $questions['client-email'] = $question;
        }

        foreach ($questions as $name => $question) {
            $answer = $this->getHelper('question')->ask($input, $output, $question);
            $input->setOption($name, $answer);
        }
    }
}
