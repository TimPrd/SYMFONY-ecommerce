<?php

namespace App\Controller;

use App\Entity\Address;
use App\Entity\User;
use App\Form\AddresseType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class AddressController extends AbstractController
{
    /**
     * @Route("/address/new", name="new_address", methods={"GET","POST"})
     */
    public function createAddress(Request $request, SessionInterface $session, UserInterface $user)
    {
        $addresse = new Address();
        $form = $this->createForm(AddresseType::class, $addresse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $repositoryUser = $this->getDoctrine()->getRepository(User::class);

            $user = $repositoryUser->findOneBy(['email' => $user->getUsername()]);

            $addresse->setNumber($form->get('number')->getData());
            $addresse->setStreetname($form->get('streetname')->getData());
            $addresse->setCity($form->get('city')->getData());
            $addresse->setCountry($form->get('country')->getData());
            $addresse->setZipcode($form->get('zipcode')->getData());
            $addresse->setUser($user);


            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($addresse);

            $entityManager->flush();


            return $this->redirectToRoute('checkout_cart');

        }
        return $this->render('checkout/form-addresse.html.twig', [
            'addressForm' => $form->createView(),
        ]);
    }
}
