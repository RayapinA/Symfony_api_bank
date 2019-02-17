<?php

namespace App\Command;

use App\Manager\CardManager;
use App\Manager\UserManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;


class NbCardByUserMailCommand extends Command
{
    protected static $defaultName = 'app:NbCardByUserMail';
    private $userManager;
    private $cardManager;

    public function __construct(UserManager $userManager, CardManager $cardManager, $name = null)
    {
        $this->userManager = $userManager;
        $this->cardManager = $cardManager;
        parent::__construct($name);
    }

    protected function configure()
    {
        $this
            ->setDescription('Add a short description for your command')
            ->addArgument('email', InputArgument::REQUIRED, 'user you are looking for')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $nbCard = 0;
        $email = $input->getArgument('email');

        $user = $this->userManager->getUserByEmail($email);


        if ($user !== null) {

            $nbCard = $this->cardManager->getNbCardbyUserId($user->getId());


            $io->note(sprintf('Nb card for this user : %s', $nbCard));
        }
        else{
            $io->error(sprintf('Error for this email: %s',$email));
        }
    }
}
