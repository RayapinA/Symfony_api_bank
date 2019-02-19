<?php

namespace App\Controller;

use App\Entity\Subscription;
use App\Manager\CardManager;
use App\Manager\SubscriptionManager;
use App\Manager\UserManager;
use App\Repository\SubscriptionRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Controller\Annotations\View as ViewAnnotation;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Swagger\Annotations as SWG;

class SubscriptionController extends AbstractFOSRestController
{

    private $subscriptionRepository;
    private $em;

    public function __construct(SubscriptionRepository $subscriptionRepository, EntityManagerInterface $em)
    {
        $this->subscriptionRepository = $subscriptionRepository;
        $this->em = $em;
    }

    /**
     * @Route("/subscription", name="subscription")
     */
    public function index()
    {
        return $this->render('subscription/index.html.twig', [
            'controller_name' => 'SubscriptionController',
        ]);
    }


    /**
     * @SWG\Get(
     *     path="/api/subscription/{id}",
     *     summary="Get one subscription",
     *     tags={"Subscription"},
     * )
     * @SWG\Response(
     *     response=200,
     *     description="OK",
     * )
     * @Rest\Get("/api/subscription/{id}")
     * @Rest\View(serializerGroups={"subscription"})
     */
    public function getApiSubscription(Subscription $subscription)
    {
        return $this->view($subscription);
    }

    /**
     *  * @SWG\Get(
     *     path="/api/subscriptions",
     *     summary="Get All subscription",
     *     tags={"Subscription"},
     * )
     * @SWG\Response(
     *     response=200,
     *     description="OK",
     * )
     * @Rest\Get("/api/subscriptions")
     * @Rest\View(serializerGroups={"subscription"})
     */
    public function getApiSubscriptions(SubscriptionManager $subscriptionManager)
    {
        $subscription = $subscriptionManager->getAllSubscription();
        return $this->view($subscription);
    }

    /**
     * * @SWG\Post(
     *     path="/api/subscription",
     *     summary="Update One subscription",
     *     tags={"Subscription"},
     * )
     * @SWG\Response(
     *     response=200,
     *     description="OK",
     * )
     * @Rest\Post("/api/subscription")
     * @ParamConverter("subscription", converter="fos_rest.request_body")
     */
    public function postApiSubscription(Subscription $subscription, SubscriptionManager $subscriptionManager, ValidatorInterface $validator)
    {
        $subscriptionManager->save($subscription);

        $validationErrors = $validator->validate($subscription);
         $errors = array();
         if($validationErrors->count() > 0){
             foreach ($validationErrors as $constraintViolation){
                 $message = $constraintViolation->getMessage();
                 $propertyPath = $constraintViolation->getPropertyPath();

                 $errors[] = ['messsage' => $message, 'propertyPath' => $propertyPath];
             }
         }

         if (!empty($errors)){
             throw new BadRequestHttpException(\json_encode($errors));
         }

        return $this->json($subscription);

    }

    /**
     * @SWG\Patch(
     *     path="/api/subscription/{id}",
     *     summary=" Update subscription",
     *     tags={"Subscription"},
     * )
     * @SWG\Response(
     *     response=200,
     *     description="OK",
     * )
     * @Rest\View(serializerGroups={"setSubscription"})
     * @Rest\Patch("/api/subscription/{id}")
     */
    public function patchApiSubscription(Subscription $subscription, Request $request, ValidatorInterface $validator, SubscriptionManager $subscriptionManager)
    {

        $name = $request->get('name');
        $slogan = $request->get("slogan");
        $url = $request->get("url");



        if (null !== $name) {
            $subscription->setName($name);
        }

        if (null !== $slogan) {
            $subscription->setSlogan($slogan);
        }

        if (null !== $url) {
            $subscription->setUrl($url);
        }

        $validationErrors = $validator->validate($subscription);

        if ($validationErrors->count() > 0) {
            foreach ($validationErrors as $constraintViolation) {
                $message = $constraintViolation->getMessage();
                $propertyPath = $constraintViolation->getPropertyPath();

                $errors[] = ['messsage' => $message, 'propertyPath' => $propertyPath];
            }

        }

        if (!empty($errors)) {
            throw new BadRequestHttpException(\json_encode($errors));
        }

        $subscriptionManager->save($subscription);

        return $this->view($subscription);
    }


    /**
     * @SWG\Delete(
     *     path="/api/subscription/{id}",
     *     summary="Delete subscription",
     *     tags={"Subscription"},
     * )
     * @SWG\Response(
     *     response=200,
     *     description="OK",
     * )
     * @Rest\View(serializerGroups={"setSubscription"})
     * @Rest\Delete("/api/subscription/{id}")
     */
    public function deleteApiSubscription(Subscription $subscription, UserRepository $userRepository, SubscriptionManager $subscriptionManager, UserManager $userManager){
        // Expliquer au professeur cette partie !!

        $userForThisSubscription = $subscription->getUser();
        $subscriptionDeRechange = $subscriptionManager->Rechange();

        foreach($userForThisSubscription as $user){
            $user->setSubscription($subscriptionDeRechange);
        }

        $userManager->save($user);
        $subscriptionManager->remove($subscription);

        return $this->view($subscriptionManager->getAllSubscription());
    }

}
