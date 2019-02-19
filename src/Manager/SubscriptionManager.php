<?php
/**
 * Created by PhpStorm.
 * User: AR_Gwada
 * Date: 2019-02-19
 * Time: 19:40
 */

namespace App\Manager;


use App\Entity\Subscription;
use App\Repository\SubscriptionRepository;
use Doctrine\ORM\EntityManagerInterface;

class SubscriptionManager
{

    private $subscriptionRepository;
    private $subscriptionDoctrine;

    public function __construct(SubscriptionRepository $subscriptionRepository , EntityManagerInterface $em)
    {
        $this->subscriptionRepository = $subscriptionRepository;
        $this->subscriptionDoctrine = $em;
    }

    public function getAllSubscription()
    {
        return $this->subscriptionRepository->findAll();
    }

    public function save( Subscription $subscription)
    {
        $this->subscriptionDoctrine->persist($subscription);
        $this->subscriptionDoctrine->flush();
    }

    public function remove(Subscription $subscription)
    {
        $this->subscriptionDoctrine->persist($subscription);
        $this->subscriptionDoctrine->flush();
    }
}