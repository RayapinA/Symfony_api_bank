<?php

namespace App\Manager;


use App\Entity\Card;
use App\Repository\CardRepository;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CardManager extends AbstractController
{

    private $cardRepository;

    public function __construct(CardRepository $cardRepository)
    {
        $this->cardRepository = $cardRepository;
    }

    public function getAllCard()
    {
        return $this->cardRepository->findAll();
    }

    public function getNbCardbyUserId( int $userId){
        $cards =  $this->cardRepository->findBy(['user' => $userId ]);
        return count($cards);

    }

    public function remove(Card $card,$userId = null)
    {
        $this->getDoctrine()->getManager()->remove($card);
        $this->getDoctrine()->getManager()->flush();
        $this->getDoctrine()->getManager()->clear();
    }


}