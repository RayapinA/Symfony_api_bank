<?php

namespace App\Manager;


use App\Entity\Card;
use App\Repository\CardRepository;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CardManager extends AbstractController
{

    private $cardRepository;
    private $cardDoctrine;

    public function __construct(CardRepository $cardRepository, EntityManagerInterface $em)
    {
        $this->cardRepository = $cardRepository;
        $this->cardDoctrine = $em;
    }

    public function getAllCard()
    {
        return $this->cardRepository->findAll();
    }

    public function getNbCardbyUserId( int $userId)
    {
        $cards =  $this->cardRepository->findBy(['user' => $userId ]);
        return count($cards);

    }

    public function save($card)
    {
        $this->cardDoctrine->persist($card);
        $this->cardDoctrine->flush();
    }

    public function remove(Card $card,$userId = null)
    {
        $this->cardDoctrine->remove($card);
        $this->cardDoctrine->clear();
    }


}