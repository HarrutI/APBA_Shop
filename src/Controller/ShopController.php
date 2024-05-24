<?php

namespace App\Controller;

use App\Entity\Tags;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
#[Route('/shop', name: 'app_shop')]
class ShopController extends AbstractController
{
    #[Route('/home', name: '_home')]
    public function home(EntityManagerInterface $entityManager): Response
    {

        $tag = $entityManager->getRepository(Tags::class)->findOneBy(['name' => 'Most Popular']);
        $products = $tag->getProducts();

        $resultArray = [];

        foreach ($products as $product) {
            $resultArray[] = $product;
        }

        return $this->render('shop/home.html.twig', [
            'products' => $resultArray,
        ]);
    }

    #[Route('/search', name: '_search')]
    public function search(): Response
    {
        return $this->render('shop/search.html.twig', [
            'controller_name' => 'ShopController',
        ]);
    }

    #[Route('/contact', name: '_contact')]
    public function contact(): Response
    {
        return $this->render('shop/contact.html.twig', [
            'controller_name' => 'ShopController',
        ]);
    }

    #[Route('/profile', name: '_profile')]
    public function profile(): Response
    {
        $user = $this->getUser();

        return $this->render('shop/profile.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/cart', name: '_cart')]
    public function cart(): Response
    {
        return $this->render('shop/cart.html.twig', [
            'controller_name' => 'ShopController',
        ]);
    }

}
