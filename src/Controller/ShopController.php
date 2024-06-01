<?php

namespace App\Controller;

use App\Entity\BagProducts;
use App\Entity\Bags;
use App\Entity\BillingDetails;
use App\Entity\Materials;
use App\Entity\OrderProducts;
use App\Entity\Orders;
use App\Entity\Products;
use App\Entity\Tags;
use App\Entity\Users;
use App\Form\BillingType;
use App\Form\SearchProductType;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
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
        $user = $this->getUser();

        return $this->render('shop/contact.html.twig', [
            'user' => $user,
        ]);
    }
    #[Route('/pricacy-policy', name: '_pricacy-policy')]
    public function pricacy_policy(): Response
    {
        return $this->render('shop/simpleText.html.twig', [
            'typeText' => 'Privacy Policies',
        ]);
    }
    #[Route('/cookies-policy', name: '_cookies-policy')]
    public function cookies_policy(): Response
    {
        return $this->render('shop/simpleText.html.twig', [
            'typeText' => 'Cookies Policies',
        ]);
    }
    #[Route('/legal-advice', name: '_legal_advice')]
    public function legal_advice(): Response
    {
        return $this->render('shop/simpleText.html.twig', [
            'typeText' => 'Legal Advice',
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
        $totalPrice = 0;
        $productList = [];
        foreach ($bagProducts as $bagProduct) {
            $productList[] = [
                'product_id' => $bagProduct->getProductId()->getId(),
                'product_name' => $bagProduct->getProductId()->getName(),
                'product_img' => $bagProduct->getProductId()->getImg(),
                'quantity' => $bagProduct->getQuantity(),
                'price' => floatval($bagProduct->getProductId()->getPrize()),
            ];
            $totalPrice += floatval($bagProduct->getProductId()->getPrize())*$bagProduct->getQuantity();
        }

        return $this->render('shop/cart.html.twig', [
            'bag' => $bag,
            'productList' => $productList,
            'totalPrice' => $totalPrice
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

    #[Route('/checkout', name: '_checkout')]
    public function checkout(Request $request, Security $security, EntityManagerInterface $entityManager): Response
    {
        $user = $entityManager->getRepository(Users::class)->findOneBy(['email' => $this->getUser()->getUserIdentifier()]);
        $billingDetails = new BillingDetails();
        $bag = $entityManager->getRepository(Bags::class)->findOneBy(['user_id' => $user->getId()]);

        $totalPrice = 0;
        $bagProducts = $entityManager->getRepository(BagProducts::class)->findBy(['bag_id' => $bag->getId()]);
        foreach ($bagProducts as $product) {
            $totalPrice += round($product->getProductId()->getPrize() * $product->getQuantity(),2);
        }

        if ($user) {
            $billingDetails->setEmail($user->getEmail());
            $billingDetails->setName($user->getName());
            $billingDetails->setSurname($user->getSurname());
            $billingDetails->setPhoneNumber(strval($user->getPhoneNumber()));
        }

        $form = $this->createForm(BillingType::class, $billingDetails);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $order = new Orders();
            $order->setUserId($user);
            $order->setDate(new DateTime());

            $entityManager->persist($order);
            $entityManager->flush();

            $orders = $entityManager->getRepository(Orders::class)->findBy(['User_id' => $user->getId()]);

            $order = end($orders);

            foreach ($bagProducts as $product) {
                $newP = new OrderProducts();

                $newP->setOrderId($order);
                $newP->setProductId($product->getProductId());
                $newP->setQuantity($product->getQuantity());

                $material = $entityManager->getRepository(Materials::class)->findOneBy(['product_id' => $product->getProductId()->getId()]);
                $material->setQuantity($material->getQuantity() - $product->getQuantity());
                $entityManager->persist($material);

                $entityManager->persist($newP);
                $entityManager->remove($product);
            }

            $order->setBillingDetails($billingDetails);

            $entityManager->persist($billingDetails);
            $entityManager->persist($order);
            $entityManager->flush();

            return $this->redirectToRoute('app_shop_order_details', ['order_id' => $order->getId()]);
        }

        return $this->render('shop/checkout.html.twig', [
            'form' => $form->createView(),
            'totalPrice' => $totalPrice
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

    #[Route('/update-quantity/{bagId}/{productId}', name: 'update_quantity', methods: ['POST'])]
    public function updateQuantity($bagId, $productId, Request $request, EntityManagerInterface $entityManager)
    {
        $data = json_decode($request->getContent(), true);
        $quantity = $data['quantity'];

        if ($quantity < 1) {
            return new JsonResponse(['success' => false, 'message' => 'La cantidad debe ser al menos 1.']);
        }

        $bagProduct = $entityManager->getRepository(BagProducts::class)->findOneBy(['bag_id' => $bagId, 'product_id' => $productId]);

        if (!$bagProduct) {
            return new JsonResponse(['success' => false, 'message' => 'El producto no fue encontrado en el carrito.']);
        }

        $bagProduct->setQuantity($quantity);
        $entityManager->persist($bagProduct);
        $entityManager->flush();

        $subtotal = round($bagProduct->getProductId()->getPrize() * $quantity,2);
        $totalPrice = 0;
        $bagProducts = $entityManager->getRepository(BagProducts::class)->findBy(['bag_id' => $bagId]);
        foreach ($bagProducts as $product) {
            $totalPrice += round($product->getProductId()->getPrize() * $product->getQuantity(),2);
        }

        return new JsonResponse(['success' => true, 'subtotal' => $subtotal, 'totalPrice' => $totalPrice]);
    }

    #[Route('/remove-from-cart/{bagId}/{productId}', name: 'remove_from_cart', methods: ['POST'])]
    public function removeFromCart(int $bagId, int $productId, EntityManagerInterface $entityManager): JsonResponse
    {
        $bagProduct = $entityManager->getRepository(BagProducts::class)->findOneBy([
            'bag_id' => $bagId,
            'product_id' => $productId
        ]);

        if ($bagProduct) {
            $entityManager->remove($bagProduct);
            $entityManager->flush();

            $totalPrice = 0;
            $bagProducts = $entityManager->getRepository(BagProducts::class)->findBy(['bag_id' => $bagId]);

            foreach ($bagProducts as $bagProduct) {
                $totalPrice += round($bagProduct->getProductId()->getPrize() * $bagProduct->getQuantity(),2);
            }

            return new JsonResponse(['success' => true, 'totalPrice' => $totalPrice]);
        }

        return new JsonResponse(['success' => false, 'message' => 'Product not found in bag']);
    }

    #[Route('/order_details/{order_id}', name: '_order_details')]
    public function order_details(int $order_id, EntityManagerInterface $entityManager): Response
    {
        $order = $entityManager->getRepository(Orders::class)->findOneBy(['id' => $order_id]);

        $orderProducts = $order->getOrderProducts()->toArray();

        $productList = [];
        $totalPrice = 0;
        foreach ($orderProducts as $product) {

            $productList[] = [
                'product_id' => $product->getProductId()->getId(),
                'product_name' => $product->getProductId()->getName(),
                'product_img' => $product->getProductId()->getImg(),
                'quantity' => $product->getQuantity(),
                'price' => floatval($product->getProductId()->getPrize()),
            ];

            $totalPrice += $product->getProductId()->getPrize() * $product->getQuantity();
        }

        return $this->render('shop/order_details.html.twig', [
            'products' => $productList,
            'totalPrice' => $totalPrice
        ]);
    }

    #[Route('/orders', name: '_orders')]
    public function orders(EntityManagerInterface $entityManager): Response
    {
        $user = $entityManager->getRepository(Users::class)->findOneBy(['email' => $this->getUser()->getUserIdentifier()]);
        $orders = $entityManager->getRepository(Orders::class)->findBy(['User_id' => $user->getId()]);

        $orderList = [];

        foreach ($orders as $order) {
            $totalPrice = 0;
            $orderProducts = $order->getOrderProducts()->toArray();

            foreach ($orderProducts as $product){

                $totalPrice += round($product->getProductId()->getPrize() * $product->getQuantity(),2);
            }

            $orderList[] = [
                'date' => $order->getDate()->format('d/m/Y'),
                'price' => $totalPrice,
                'id' => $order->getId(),
            ];
        }

        return $this->render('shop/orders.html.twig', [
            'orders' => $orderList
        ]);
    }

}
