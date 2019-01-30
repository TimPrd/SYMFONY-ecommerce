<?php

namespace App\Controller;

use App\Entity\Collection;
use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index()
    {
        $repository = $this->getDoctrine()->getRepository(Collection::class);
        $collections = $repository->findAll();

        $repositoryProduct = $this->getDoctrine()->getRepository(Product::class);
        $products = $repositoryProduct->findBy([], ['dateAdd' => 'DESC'], 8);

        $session = $this->get('session');
        $session->set('collections', $collections);

        return $this->render('index/index.html.twig', ['collections' => $collections, 'products'=>$products]);
    }

    /**
     * @Route("/empty", name="empty")
     */
    public function empty()
    {
        return $this->render('empty.html.twig');
    }


}
