<?php

namespace App\Command;

use App\Service\Command\CreateOrganizationCommand;
use League\Tactician\CommandBus;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class OrganizationCreateCommand extends Command
{
    protected static $defaultName = 'app:organization:create';
    
    private $commandBus;
    
    public function __construct(CommandBus $commandBus)
    {
        parent::__construct();
        $this->commandBus = $commandBus;
    }
    
    protected function configure(): void
    {
        $this
            ->setDescription('Create the organization')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $organizationEmail = $io->ask('Podaj email organizacji');
        $organizationName  = $io->ask('Podaj nazwę organizacji');
        
        $command = new CreateOrganizationCommand($organizationEmail, $organizationName);
        $this->commandBus->handle($command);

        $io->success('Stworzono użytkownika o adresie: '.$organizationEmail);
    }
}
