<?php

namespace App\Controller;

use App\Entity\BagProducts;
use App\Entity\Bags;
use App\Entity\Products;
use App\Entity\Tags;
use App\Entity\Users;
use App\Form\SearchProductType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
    public function search(Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(SearchProductType::class);
        $form->handleRequest($request);

        $productRepository = $em->getRepository(Products::class);
        $products = [];

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $query = $data['query'] ?? '';
            $tags = $data['tags'] ?? [];

            if ($tags instanceof \Doctrine\Common\Collections\Collection) {
                $tags = $tags->toArray();
            }

            $products = $productRepository->searchByNameAndTags($query, $tags);
        } else {
            $products = $productRepository->findAll();
        }

        return $this->render('shop/search.html.twig', [
            'form' => $form->createView(),
            'products' => $products,
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
    public function cart(EntityManagerInterface $entityManager): Response
    {
        $user = $entityManager->getRepository(Users::class)->findOneBy(['email' => $this->getUser()->getUserIdentifier()]);
        $bag = $entityManager->getRepository(Bags::class)->findOneBy(['user_id' => $user->getId()]);


        if($bag == null){
            $newBag = new Bags();
            $newBag->setUserId($user);

            $entityManager->persist($newBag);
            $entityManager->flush();

            $bag = $entityManager->getRepository(Bags::class)->findOneBy(['user_id' => $user->getId()]);
        }

        $bagProducts = $bag->getBagProducts()->toArray();

        $productList = [];
        foreach ($bagProducts as $bagProduct) {
            $productList[] = [
                'product_id' => $bagProduct->getProductId()->getId(),
                'product_name' => $bagProduct->getProductId()->getName(),
                'product_img' => $bagProduct->getProductId()->getImg(),
                'quantity' => $bagProduct->getQuantity()
            ];
        }


        return $this->render('shop/cart.html.twig', [
            'bag' => $bag,
            'productList' => $productList,
        ]);
    }

    #[Route('/product_detail/{id}', name: '_product_detail')]
    public function product_detail($id, EntityManagerInterface $entityManager): Response
    {
        $product = $entityManager->getRepository(Products::class)->findOneBy(['id' => $id]);

        $materials = $product->getMaterials()->toArray();

        return $this->render('shop/product_detail.html.twig', [
            'product' => $product,
            'materials' => $materials
        ]);
    }

    #[Route('/add_cart/{id}/{quant}', name: '_add_cart')]
    public function add_cart($id,$quant, EntityManagerInterface $entityManager): Response
    {
        $user = $entityManager->getRepository(Users::class)->findOneBy(['email' => $this->getUser()->getUserIdentifier()]);
        $product = $entityManager->getRepository(Products::class)->findOneBy(['id' => $id]);
        $bag = $entityManager->getRepository(Bags::class)->findOneBy(['user_id' => $user->getId()]);

        if($bag == null){
            $newBag = new Bags();
            $newBag->setUserId($user);

            $entityManager->persist($newBag);
            $entityManager->flush();

            $bag = $entityManager->getRepository(Bags::class)->findOneBy(['user_id' => $user->getId()]);
        }

        $prodBag = $entityManager->getRepository(BagProducts::class)->findOneBy([
            'bag_id' => $bag->getId(),
            'product_id' => $product->getId()
        ]);

        dump($prodBag);
        if($prodBag == null)
        {
            $bagProduct = new BagProducts();
            $bagProduct->setProductId($product);
            $bagProduct->setBagId($bag);
            $bagProduct->setQuantity($quant);
            $entityManager->persist($bagProduct);
        }else{
            $prodBag->setQuantity($prodBag->getQuantity()+$quant);
            $entityManager->persist($prodBag);

        }

        $entityManager->flush();


        return $this->redirectToRoute('app_shop_cart');
    }

}
