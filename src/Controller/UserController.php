<?php

namespace App\Controller;

use App\Entity\Package;
use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Security\AppAuthenticator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;

class UserController extends AbstractController
{
    /**
     * User page info (orders)
     *
     * @Route("/user/{id}", name="me")
     */
    public function me($id,UserRepository $userRepository):Response
    {

        $user = $userRepository->find($id);
        $repositoryOrder = $this->getDoctrine()->getRepository(Package::class);
        $orders = $repositoryOrder->findBy(['user' => $user->getId()]);

        return $this->render('user/index.html.twig', [
            'orders' => $orders,
        ]);
    } 
}
