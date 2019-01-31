<?php

namespace App\Controller;

use App\Entity\Address;
use App\Entity\Cart;
use App\Entity\CartProduct;
use App\Entity\Package;
use App\Entity\Product;
use App\Entity\User;
use App\Form\AddressChooserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class CartController extends AbstractController
{
    /**
     * @Route("/cart", name="cart", methods={"GET"})
     */
    public function cart()
    {
        return $this->render('cart/index.html.twig', [
            'controller_name' => 'CartController',
        ]);
    }

    /**
     * @Route("/cart.json", name="cart_json", methods={"GET"})
     */
    public function cartJson(SessionInterface $session)
    {
        $cartId = $session->get('cart');
        $repositoryCart = $this->getDoctrine()->getRepository(Cart::class);
        $cart = $cartId ? $repositoryCart->find($cartId) : new Cart();
        //get the last product
        $addedProduct = $cart->getCartProducts()[count($cart->getCartProducts()) - 1]
            ? $cart->getCartProducts()[count($cart->getCartProducts()) - 1]->getProduct()
            : new Cart();
        return new JsonResponse([
            'addedProduct' => [
                'name' => $addedProduct->getName(),
                'price' => $addedProduct->getPrice(),
                'pictureUrl' => $addedProduct->getPictureUrl()
            ],
            'newTotalProducts' => count($cart->getCartProducts()),
            'newTotal' => $cart->getTotal()
        ]);
    }

    /**
     * @Route("user/{id}/cart", name="get_cart", methods={"GET"})
     */
    public function getCartUser($id, Request $request, SessionInterface $session)
    {
        $repository = $this->getDoctrine()->getRepository(Cart::class);
        $cart = //$repository->findAll();
            $repository->find(8);

        return new JsonResponse([
            'result' => $cart,
        ]);
    }

    /**
     * @Route("/cart/show", name="show_cart")
     */
    public function showCart(SessionInterface $session)
    {
        $cartId = $session->get('cart');
        $repositoryCart = $this->getDoctrine()->getRepository(Cart::class);
        $cart = $cartId ? $repositoryCart->find($cartId) : new Cart();

        return $this->render('cart/view.html.twig', [
            'cart' => $cart
        ]);

        //return $this->partial($session, true);
    }


    /**
     * Checkout
     *
     * @Route("/cart/checkout", name="checkout_cart", methods={"GET","POST"})
     */
    public function checkout(Request $request, SessionInterface $session, UserInterface $user)
    {
        $cartId = $session->get('cart');
        $repositoryCart = $this->getDoctrine()->getRepository(Cart::class);
        $cart = $cartId ? $repositoryCart->find($cartId) : new Cart();

        $repositoryUser = $this->getDoctrine()->getRepository(User::class);
        $user = $repositoryUser->findOneBy(['email' => $user->getUsername()]);

        $form = $this->createForm(AddressChooserType::class, $user);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            $selectedAddress = $form->get('addresses')->getData();

            $command = new Package();
            
            $command->setPrice($cart->getTotal());
            $command->setAddress($selectedAddress);
            $command->setCreationDate(new \DateTime());
            $command->setUser($user);
            $command->setCart($cart);
            //Find a better way. Pass address value to the next form
            $command->setIsPaid(false);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($command);
            $entityManager->flush();

            return $this->redirectToRoute('checkout_payment', array('cmd' => $command->getId()));
        }

        return $this->render('checkout/checkout.html.twig', [
            'cart' => $cart,
            'addresselect' => $form->createView(),
        ]);
    }

    /**
     * @Route("/cart/{cid}/{pid}", name="cart_delete", methods={"DELETE"})
     */
    public function removeFromCart(Request $request, Product $pid, Cart $cid)
    {
        $repositoryP = $this->getDoctrine()->getRepository(CartProduct::class);
        $product = $repositoryP->findOneBy([
            'product' => $pid,
            'cart' => $cid
        ]);
        if ($this->isCsrfTokenValid('delete' . $product->getId(), $request->request->get('_token'))) {

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($product);
            $entityManager->flush();
        }
        // @pgrimaud : how to redirect user to the last URL known (ex: product/:id page)
        //$routeName = $request->attributes->get('_route');
        return $this->redirectToRoute('index');
    }


    /**
     * @Route("/cart/add.json", name="add_cart_json", methods={"POST"})
     */
    public function addToCartJson(Request $request, SessionInterface $session)
    {
        $repositoryP = $this->getDoctrine()->getRepository(Product::class);
        $product = $repositoryP->find($request->request->get('product_id'));

        $objectManager = $this->getDoctrine()->getManager();

        if (!$product instanceof Product) {
            $status = 'ko';
            $message = 'Product not found';
        } else {
            if ($product->getStock() < $request->request->get('quantity')) {
                $status = 'ko';
                $message = 'Missing quantity for product';
            } else {
                $cartId = $session->get('cart');

                if (!$cartId) {
                    $cart = new Cart();

                    $objectManager->persist($cart);
                    $objectManager->flush();

                    $session->set('cart', $cartId = $cart->getId());
                } else {
                    $repositoryCart = $this->getDoctrine()->getRepository(Cart::class);
                    /** @var Cart $cart */
                    $cart = $repositoryCart->find($cartId);
                }

                $cartProduct = new CartProduct();
                $cartProduct->setCart($cart);
                $cartProduct->setProduct($product);
                $cartProduct->setQuantity((int)$request->request->get('quantity'));


                $objectManager->persist($cartProduct);
                $objectManager->flush();

                $status = 'ok';
                $message = 'Added to cart';
            }
        }

        return new JsonResponse([
            'result' => $status,
            'message' => $message,
            'cart' => $cart,
        ]);
    }

    public function partial(SessionInterface $session/*, $isFullView = false*/)
    {
        $cartId = $session->get('cart');
        $repositoryCart = $this->getDoctrine()->getRepository(Cart::class);

        /** @var Cart $cart */
        $cart = $cartId ? $repositoryCart->find($cartId) : new Cart();

        return $this->render('partials/cart.html.twig', [
            'cart' => $cart
        ]);
        /*
        if ($isFullView) {
            return $this->render('partials/cart.html.twig', [
                'cart' => $cart
            ]);
        } else {
            return $this->render('cart/index.html.twig', [
                'cart' => $cart
            ]);
        }*/
    }
}
