<?php

namespace App\Command;

use App\Repository\CardRepository;
use App\Repository\UserRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * @property  userRepository
 */
class NbCardByUserMailCommand extends Command
{
    protected static $defaultName = 'app:NbCardByUserMail';
    private $userRepository;
    private $cardRepository;

    public function __construct(UserRepository $userRepository, CardRepository $cardRepository, $name = null)
    {
        $this->userRepository = $userRepository;
        $this->cardRepository = $cardRepository;
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
        $user = $this->userRepository->findOneBy(['email' => $email]);


        if ($user !== null) {

            $allCards = $this->cardRepository->findAll();
            foreach($allCards as $card){

                if($card->getUser()->getId() == $user->getId()){
                    $nbCard++;
                }
            }

            $io->note(sprintf('Nb card for this user : %s', $nbCard));
        }
        else{
            $io->error(sprintf('Error for this email: %s',$email));
        }
    }
}
