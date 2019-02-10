<?php

namespace App\Controller;

use App\Entity\Card;
use App\Repository\CardRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CardController extends AbstractFOSRestController
{
    private $cardRepository;
    private $em;

    public function __construct( CardRepository $cardRepository, EntityManagerInterface $em)
    {
        $this->cardRepository = $cardRepository;
        $this->em = $em;
    }

    /**
     * @Route("/card", name="card")
     */
    public function index()
    {
        return $this->render('card/index.html.twig', [
            'controller_name' => 'CardController',
        ]);
    }
    /**
     * @Rest\Get("/api/card/{id}")
     * @Rest\View(serializerGroups={"card"})
     */
    public function getApiCard(Card $card)
    {
        return $this->view($card);
    }

    /**
     * @Rest\Get("/api/cards")
     * @Rest\View(serializerGroups={"card"})
     */
    public function getApiCards(){

        $cards = $this->cardRepository->findAll();

        return $this->view($cards);
    }


    /**
     * @Rest\Post("/api/card")
     * @Rest\View(serializerGroups={"card"})
     * @ParamConverter("card", converter="fos_rest.request_body")
     */
    public function postApiCard(Card $card)
    {
        $this->em->persist($card);
        $this->em->flush();

        /* $errors = array();
         if($validationErrors->count() > 0){
             foreach ($validationErrors as $constraintViolation){
                 $message = $constraintViolation->getMessage();
                 $propertyPath = $constraintViolation->getPropertyPath();

                 $errors[] = ['messsage' => $message, 'propertyPath' => $propertyPath];
             }
         }

         if (!empty($errors)){
             throw new BadRequestHttpException(\json_encode($errors));
         }*/

        return $this->view($card);

    }

    /**
     * @Rest\View(serializerGroups={"setCard"})
     * @Rest\Patch("/api/card/{id}")
     */
    public function patchApiCard(Card $card, Request $request, ValidatorInterface $validator)
    {

        $name = $request->get('name');
        $creditCardType = $request->get('creditCardType');
        $creditCardNumber = $request->get('creditCardNumber');
        $currencyCode = $request->get('currencyCode');
        $value = $request->get('value');


        if (null !== $name) {
            $card->setName($name);
        }

        if (null !== $creditCardType) {
            $card->setCreditCardType($creditCardType);
        }

        if (null !== $creditCardNumber) {
            $card->setCreditCardNumber($creditCardNumber);
        }

        if (null !== $currencyCode) {
            $card->setCurrencyCode($currencyCode);
        }

        if (null !== $value) {
            $card->setValue($value);
        }


        $validationErrors = $validator->validate($card);

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
        $this->em->persist($card);
        $this->em->flush();

        return $this->view($card);



    }
    /**
     * @Rest\View(serializerGroups={"setCard"})
     * @Rest\Delete("/api/card/{id}")
     */
    public function deleteApiCard(Card $card){


        $this->em->remove($card);
        $this->em->flush();

        return $this->view($this->cardRepository->findAll());
    }

}
