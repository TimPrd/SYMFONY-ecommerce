<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Repository\PackageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/order")
 */
class OrderController extends AbstractController
{
    /**
     * @Route("/", name="view_orders", methods={"GET"})
     */
    public function index(PackageRepository $packageRepository): Response
    {
        return $this->render('admin/orders/index.html.twig', [
            'orders' => $packageRepository->findAll(),
        ]);
    }

    /**
     * @Route("/user/{id}", name="view_orders_user", methods={"GET"})
     */
    public function viewOrderForUser($id, PackageRepository $packageRepository): Response
    {
        $repositoryUser = $this->getDoctrine()->getRepository(User::class);
        $user = $repositoryUser->find($id);

        $orders = $packageRepository->findBy(['user' => $user->getId()]);

        return $this->render('admin/orders/user-orders.html.twig', [
            'orders' => $orders
        ]);
    }

    /**
     * @Route("/order", name="order_orders", methods={"GET"})
     */
    public function orderOrders($ord = 'price', PackageRepository $packageRepository): Response
    {
        return $this->render('admin/orders/order.html.twig', [
            'orders' => $packageRepository->findBy([], [$ord => 'DESC'])
        ]);
    }


    /**
     * @Route("/city/{city}", name="view_orders_city", methods={"GET"})
     */
    public function viewOrderForCity($city, PackageRepository $packageRepository): Response
    {

        /* $entityManager = $this->getDoctrine()->getManager();

         $query = $entityManager->createQuery(
             'SELECT p
         FROM App\Entity\Address p
         WHERE p.city = :city'
         )->setParameter('city', $city);

         // returns an array of Product objects
         $orders = $query->execute();

         return $this->render('admin/orders/user-orders.html.twig', [
             'orders' => $orders
         ]);*/
    }


    /*
        /**
         * @Route("/new", name="product_new", methods={"GET","POST"})
         */
    /*  public function new(Request $request): Response
      {
          $product = new Product();
          $form = $this->createForm(ProductType::class, $product);
          $form->handleRequest($request);

          if ($form->isSubmitted() && $form->isValid()) {
              $entityManager = $this->getDoctrine()->getManager();
              $entityManager->persist($product);
              $entityManager->flush();

              return $this->redirectToRoute('product_index');
          }

          return $this->render('admin/product/new.html.twig', [
              'product' => $product,
              'form' => $form->createView(),
          ]);
      }

      /**
       * @Route("/{id}", name="product_show", methods={"GET"})
       */
    /*  public function show(Product $product): Response
      {
          return $this->render('admin/product/show.html.twig', [
              'product' => $product,
          ]);
      }

      /**
       * @Route("/{id}/edit", name="product_edit", methods={"GET","POST"})
       */
    /*public function edit(Request $request, Product $product): Response
    {
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('product_index', [
                'id' => $product->getId(),
            ]);
        }

        return $this->render('admin/product/edit.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="product_delete", methods={"DELETE"})
     */
    /*public function delete(Request $request, Product $product): Response
    {
        if ($this->isCsrfTokenValid('delete'.$product->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($product);
            $entityManager->flush();
        }

        return $this->redirectToRoute('product_index');
    }*/
}
