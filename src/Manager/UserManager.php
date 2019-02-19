<?php

namespace App\Manager;


use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserManager extends AbstractController
{
    private $userRepository;
    private $userDoctrine;

    public function __construct(UserRepository $userRepository, EntityManagerInterface $em)
    {
        $this->userRepository = $userRepository;
        $this->userDoctrine = $em;
    }

    public function getAllUser()
    {

        return $this->userRepository->findAll();
    }

    public function getUserByEmail($email)
    {
        return $this->userRepository->findOneBy(['email' => $email]);

    }
    public function save(User $user)
    {
        $this->userDoctrine->persist($user);
        $this->userDoctrine->flush();
    }
    public function remove(User $user)
    {
        $this->userDoctrine->remove($user);
        $this->userDoctrine->flush();
        $this->userDoctrine->clear();
    }
}