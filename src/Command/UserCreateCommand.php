<?php

namespace App\Command;

use App\Service\Command\CreateUserCommand;
use League\Tactician\CommandBus;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class UserCreateCommand extends Command
{
    protected static $defaultName = 'app:user:create';
    
    private $commandBus;
    
    public function __construct(CommandBus $commandBus)
    {
        parent::__construct();
        $this->commandBus = $commandBus;
    }
    
    protected function configure(): void
    {
        $this
            ->setDescription('Add a short description for your command')
           
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $userEmail = $io->ask('Podaj email użytkownika');
        $userName  = $io->ask('Podaj imię użytkownika');
        $userLastName = $io->ask('Podaj nazwisko użytkownika');
        $userPassword = $io->askHidden('Podaj hasło');
        $userRole = $io->ask('Wpisz rolę użytkownika');
        
        $command = new CreateUserCommand($userEmail, $userPassword, $userRole, $userName, $userLastName);
        $this->commandBus->handle($command);

        $io->success('Stworzono użytkownika o adresie: '.$userEmail);
    }
}
