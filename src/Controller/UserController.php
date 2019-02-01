<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\SubscriptionRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\Null_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Controller\Annotations\View as ViewAnnotation;

class UserController extends AbstractFOSRestController
{
    private $userRepository;
    private $em;

    public function  __construct(UserRepository $userRepository, EntityManagerInterface $em)
    {
        $this->userRepository = $userRepository;
        $this->em = $em;
    }

    /**
     * @Route("/user", name="user")
     */
    public function index()
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    /**
     * @Rest\Get("/api/user/{email}")
     * @Rest\View(serializerGroups={"user"})
     */
    public function getApiUser(User $user){
        //dump($user);exit();
        return $this->view($user);
    }

    /**
     * @Rest\Get("/api/users")
     * @Rest\View(serializerGroups={"user"})
     */
    public function getApiUsers(){
        $user = $this->userRepository->findAll();
        return $this->view($user);
    }

    /**
     * @Rest\Post("/api/user")
     * @ParamConverter("user", converter="fos_rest.request_body")
     */
    public function postApiUser(User $user)
    {
        $this->em->persist($user);
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

        return $this->json($user);

    }

    /**
     * @Rest\View(serializerGroups={"SetUser"})
     * @Rest\Patch("/api/users/{id}")
     */
    public function patchApiUser(User $user, Request $request, ValidatorInterface $validator,SubscriptionRepository $subscriptionRepository){

        $firstname = $request->get('firstname');
        $lastname = $request->get("lastname");
        $createdAt = $request->get("createdAt");
        $email = $request->get('email');
        $apiKey = $request->get('apiKey');
        $subscriptionId = $request->get('subscription');

        dump($user->getSubscription());exit();

        if (null !== $firstname){
            $user->setFirstname($firstname);
        }

        if (null !== $lastname){
            $user->setLastname($lastname);
        }

        if (null !== $createdAt){
            $user->setCreatedAt(\DateTime::createFromFormat('d-m-Y',$createdAt));
        }

        if (null !== $email){
            $user->setEmail($email);
        }
        if(null !== $apiKey){
            $user->setApiKey($apiKey);
        }
        if (null !== $subscriptionId){
            $newSubscription = $subscriptionRepository->find($subscriptionId);
            $user->setSubscription($newSubscription);
        }

        $validationErrors = $validator->validate($user);

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

        $this->em->persist($user);
        $this->em->flush();



        return $this->json($user);

    }

    /**
     * @Rest\Delete("/api/user/{id}")
     */
    public function deleteApiUser(User $user){

        $this->em->remove($user);
        $this->em->flush();

        return $this->json($this->userRepository->findAll());
    }

}
