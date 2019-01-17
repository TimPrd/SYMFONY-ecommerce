<?php

namespace App\Controller;

use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class CartController extends AbstractController
{
    /**
     * @Route("/cart", name="cart")
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
    public function cartJson()
    {
        $cart = ['products' => [
            'id' => 1,
            'quantity' => 2]
        ];
        return new JsonResponse($cart);
    }

    /**
     * @Route("/cart/add.json", name="cart_add_json", methods={"POST"})
     */
    public function addToCartJson(Request $request)
    {
        $repositoryP = $this->getDoctrine()->getRepository(Product::class);
        $product = $repositoryP->find($request->request->get('product_id'));

        if (!$product instanceof Product) {
            $status = "ko";
            $message = 'Product not found';
        }else
        {
            if ($product->getStock() < $request->request->get('quantity')){
                $status = "ko";
                $message = 'We have not enough quantity. Sorry 😭';
            }
            else{
                $status = "ok";
                $message = 'Added to cart 🙌';
            }


        }
        return new JsonResponse([
            'result' => $status,
            'message' => $message
        ]);
    }

}
