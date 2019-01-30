<?php

namespace App\Controller;

use App\Entity\Collection;
use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class CollectionController extends AbstractController
{
    /**
     * @Route("/collection/{slug}", name="collection", methods={"GET"})
     */
    public function index($slug)
    {
        $repository = $this->getDoctrine()->getRepository(Collection::class);
        $collection = $repository->findOneBy([
            'slug' => $slug
        ]);


        if (!$collection instanceof Collection) {
            throw new NotFoundHttpException('Collection not found');
        }

        $repositoryProduct = $this->getDoctrine()->getRepository(Product::class);
        $products = $repositoryProduct->findBy(
            ['collection' => $collection]
        );

        return $this->render('collection/index.html.twig', [
            'collection' => $collection,
            'products' => $products
        ]);
    }


    /**
     * @Route("/collections", name="all_collections")
     */
    public function getAllCollections()
    {
        $repository = $this->getDoctrine()->getRepository(Collection::class);
        $collections = $repository->findAll();
        return $this->render('collection/all-collections.html.twig', [
            'collections' => $collections,
        ]);

    }

}
