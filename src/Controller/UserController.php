<?php

namespace App\Controller;

use App\Entity\User;
use App\Manager\CardManager;
use App\Manager\UserManager;
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
use Swagger\Annotations as SWG;

class UserController extends AbstractFOSRestController
{
    private $userRepository;

    public function  __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
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
     * @SWG\Get(
     *     path="/api/user/{email}",
     *     summary="Get one user",
     *     tags={"User"},
     * )
     * @SWG\Response(
     *     response=200,
     *     description="OK",
     * )
     * @Rest\Get("/api/user/{email}")
     * @Rest\View(serializerGroups={"user"})
     */
    public function getApiUser(User $user){

        return $this->view($user);
    }

    /**
     * @SWG\Get(
     *     path="/api/users",
     *     summary="Get all users",
     *     tags={"User"},
     * )
     * @SWG\Response(
     *     response=200,
     *     description="OK",
     * )
     * @Rest\Get("/api/users")
     * @Rest\View(serializerGroups={"user"})
     */
    public function getApiUsers(UserManager $userManager){

        $users = $userManager->getAllUser();
        return $this->view($users);
    }

    /**
     * @SWG\Post(
     *     path="/api/user",
     *     summary="Update One user",
     *     tags={"User"},
     * )
     * @SWG\Response(
     *     response=200,
     *     description="OK",
     * )
     * @Rest\Post("/api/user")
     * @ParamConverter("user", converter="fos_rest.request_body")
     */
    public function postApiUser(User $user, UserManager $userManager)
{
    $userManager->save($user);

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
     * @SWG\Patch(
     *     path="/api/users/{id}",
     *     summary="Update One user",
     *     tags={"User"},
     * )
     * @SWG\Response(
     *     response=200,
     *     description="OK",
     * )
     * @Rest\View(serializerGroups={"setUser"})
     * @Rest\Patch("/api/users/{id}")
     */
    public function patchApiUser(User $user, Request $request, ValidatorInterface $validator,SubscriptionRepository $subscriptionRepository, UserManager $userManager){

        $firstname = $request->get('firstname');
        $lastname = $request->get("lastname");
        $createdAt = $request->get("createdAt");
        $email = $request->get('email');
        $apiKey = $request->get('apiKey');
        $subscriptionId = $request->get('subscription');

        //dump($user->getSubscription());exit();

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

        $userManager->save($user);

        return $this->view($user);

    }

    /**
     * @SWG\Delete(
     *     path="/api/user/{id}",
     *     summary="Delete one user",
     *     tags={"User"},
     * )
     * @SWG\Response(
     *     response=200,
     *     description="OK",
     * )
     * @Rest\View(serializerGroups={"removeUser"})
     * @Rest\Delete("/api/user/{id}")
     */
    public function deleteApiUser(User $user,UserManager $userManager)
    {

        $userManager->remove($user);

        return $this->view($this->userRepository->findAll());
    }

}
