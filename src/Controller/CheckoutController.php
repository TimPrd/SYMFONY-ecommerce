<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\Package;
use App\Entity\Transaction;
use App\Form\CardType;
use App\Form\PayementType;
use App\Model\Card;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/checkout")
 */
class CheckoutController extends AbstractController
{
    /**
     * @Route("/payment/{cmd}", name="checkout_payment", methods={"GET","POST"})
     */
    public function payment($cmd, Request $request,SessionInterface $session)
    {
        $card = new Transaction();
        $form = $this->createForm(PayementType::class, $card);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $repositoryCmd = $this->getDoctrine()->getRepository(Package::class);
            $currentCmd = $repositoryCmd->find($cmd);

            $currentCmd->setIsPaid(true);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($currentCmd);
            $entityManager->flush();
            $session->remove('cart');

            $request->getSession()
                ->getFlashBag()
                ->add('success', 'Your order is on the road ! 🚚');
            return $this->redirectToRoute('index');

        }

        return $this->render('checkout/payment.html.twig', [
            'card' => $card,
            'form' => $form->createView(),
        ]);

    }
}
