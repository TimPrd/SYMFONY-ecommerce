<?php

namespace App\Controller;

use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    /**
     * @Route("/product/{slug}", name="product", methods={"GET"})
     */
    public function index($slug)
    {
        $repositoryP = $this->getDoctrine()->getRepository(Product::class);
        $product     = $repositoryP->findOneBy([
            'slug' => $slug
        ]);

        if (!$product instanceof Product) {
            throw new NotFoundHttpException('Product not found');
        }

        return $this->render('product/index.html.twig', [
            'product' => $product
        ]);
    }
}
