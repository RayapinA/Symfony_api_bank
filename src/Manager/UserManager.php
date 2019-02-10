<?php

namespace App\Manager;


use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserManager extends AbstractController
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getAllUser(){

        return $this->userRepository->findAll();
    }
    public function save(User $user)
    {
        $this->getDoctrine()->getManager()->persist($user);
        $this->getDoctrine()->getManager()->flush();
    }
    public function remove(User $user)
    {

        $this->getDoctrine()->getManager()->remove($user);
        $this->getDoctrine()->getManager()->flush();
    }
}