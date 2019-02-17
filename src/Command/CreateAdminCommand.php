<?php

namespace App\Command;

use App\Entity\User;
use App\Manager\UserManager;
use App\Repository\SubscriptionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class CreateAdminCommand extends Command
{
    protected static $defaultName = 'app:create-admin';
    private $userManager;
    private $encoder;

    public function __construct(UserManager $userManager){

        $this->userManager = $userManager;
        parent::__construct();

    }
    protected function configure()
    {
        $this
            ->setDescription('Command for create a new Admin')
            ->addArgument('email', InputArgument::REQUIRED, 'email ')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $email = $input->getArgument('email');
        $io->note(sprintf('You passed an argument: %s', $email));

        $user = new User();
        $user->setEmail($email);
        // GENERER UN APIKEY ALEATOIRE
        $user->setApiKey("ADMINAPIKEY");

        $user->setRoles(['ROLE_USER','ROLE_ADMIN']);
        $user->setFirstname('FirstName_Admin');
        $user->setLastname('LastName_Admin');

        $this->userManager->save($user);

        $io->success(sprintf('You have created a User with email: %s',$email));

    }
}
