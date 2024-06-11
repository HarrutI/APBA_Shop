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
    
    /* 
      This function renders the home page of the shop.
      It retrieves the 'Most Popular' tag from the database and
      then gets the products associated with that tag.
      The products are then stored in an array and passed to the
      home.html.twig template as a variable named 'products'.
    */

    #[Route('/home', name: '_home')]
    public function home(EntityManagerInterface $entityManager): Response
    {

        // Get the 'Most Popular' tag from the database
        $tag = $entityManager->getRepository(Tags::class)->findOneBy(['name' => 'Most Popular']);

        // Get the products associated with the 'Most Popular' tag
        $products = $tag->getProducts();

        // Create an array to store the products
        $resultArray = [];

        // Loop through the products and add them to the array
        foreach ($products as $product) {
            $resultArray[] = $product;
        }

        // Render the home.html.twig template and pass the products array to it
        return $this->render('shop/home.html.twig', [
            'products' => $resultArray,
        ]);
    }
    /*
      This function handles the search functionality of the shop.
      It uses a form to collect the search query and selected tags from the user.
      It then uses the search query and selected tags to retrieve products from the database.
      The retrieved products are then passed to the search.html.twig template as a variable named 'products'.
    */
     
     
    #[Route('/search', name: '_search')]
    public function search(Request $request, EntityManagerInterface $em): Response
    {
        // Create a form to collect the search query and selected tags from the user
        $form = $this->createForm(SearchProductType::class);

        // Handle the form submission and validation
        $form->handleRequest($request);

        // Get the product repository from the entity manager
        $productRepository = $em->getRepository(Products::class);

        // Initialize an empty array to store the retrieved products
        $products = [];

        // Check if the form is submitted and valid
        if ($form->isSubmitted() && $form->isValid()) {
            // Get the search query and selected tags from the form data
            $data = $form->getData();
            $query = $data['query'] ?? '';
            $tags = $data['tags'] ?? [];

            // Convert the tags collection to an array if it is not already an array
            if ($tags instanceof \Doctrine\Common\Collections\Collection) {
                $tags = $tags->toArray();
            }

            // Use the search query and selected tags to retrieve products from the database
            $products = $productRepository->searchByNameAndTags($query, $tags);
        } else {
            // If the form is not submitted or not valid, retrieve all products from the database
            $products = $productRepository->findAll();
        }

        // Render the search.html.twig template and pass the retrieved products as a variable
        return $this->render('shop/search.html.twig', [
            'form' => $form->createView(),
            'products' => $products,
        ]);
    }

    //Renders the contact form for the user.
    #[Route('/contact', name: '_contact')]
    public function contact(): Response
    {
        // Get the current user from the security context
        $user = $this->getUser();

        // Render the contact.html.twig template and pass the current user as a variable
        return $this->render('shop/contact.html.twig', [
            'user' => $user,
        ]);
    }
    /*
      Renders the privacy policy page for the user.
          
      The function renders the 'shop/simpleText.html.twig' template and passes
      the 'typeText' variable with the value 'Privacy Policies' to the template.
      This variable is used to display the appropriate text on the page.
    */

    #[Route('/pricacy-policy', name: '_pricacy-policy')]
    public function pricacy_policy(): Response
    {
        return $this->render('shop/simpleText.html.twig', [
            'typeText' => 'Privacy Policies',
        ]);
    }

    /*
    Renders the privacy policy page for the user.
          
      The function renders the 'shop/simpleText.html.twig' template and passes
      the 'typeText' variable with the value 'Cookies Policies' to the template.
      This variable is used to display the appropriate text on the page.
    */

    #[Route('/cookies-policy', name: '_cookies-policy')]
    public function cookies_policy(): Response
    {
        return $this->render('shop/simpleText.html.twig', [
            'typeText' => 'Cookies Policies',
        ]);
    }

    /*
      Renders the privacy policy page for the user.
          
      The function renders the 'shop/simpleText.html.twig' template and passes
      the 'typeText' variable with the value 'Legal Advice' to the template.
      This variable is used to display the appropriate text on the page.
    */

    #[Route('/legal-advice', name: '_legal_advice')]
    public function legal_advice(): Response
    {
        return $this->render('shop/simpleText.html.twig', [
            'typeText' => 'Legal Advice',
        ]);
    }

    /*
      Render the user's profile page.
     
      This function is mapped to the '/profile' route.
     
      The function gets the user from the security context
      and renders the 'shop/profile.html.twig' template.
     
      The template receives the 'user' variable which is
      used to display the user's information.
    */

    #[Route('/profile', name: '_profile')]
    public function profile(): Response
    {
        // Get the user from the security context
        $user = $this->getUser();

        // Render the 'shop/profile.html.twig' template
        // and pass the 'user' variable
        return $this->render('shop/profile.html.twig', [
            'user' => $user,
        ]);
    }


    /*
      Renders the user's shopping cart page.
        
      This function is mapped to the '/cart' route.
        
      The function retrieves the user from the security context
      and checks if they have a bag associated with them. If not,
      a new bag is created and associated with the user.
        
      The function then retrieves the products in the user's bag
      and calculates the total price of all the products.
        
      Finally, the function renders the 'shop/cart.html.twig' template
      and passes the necessary variables to the template: 'bag' for the
      user's bag, 'productList' for the list of products in the bag,
      and 'totalPrice' for the total price of all the products.
    */
      
    #[Route('/cart', name: '_cart')]
    public function cart(EntityManagerInterface $entityManager): Response
    {
        // Get the user from the security context
        $user = $entityManager->getRepository(Users::class)->findOneBy(['email' => $this->getUser()->getUserIdentifier()]);
        
        // Check if the user has a bag
        $bag = $entityManager->getRepository(Bags::class)->findOneBy(['user_id' => $user->getId()]);

        // If the user doesn't have a bag, create a new one
        if ($bag == null) {
            $newBag = new Bags();
            $newBag->setUserId($user);

            $entityManager->persist($newBag);
            $entityManager->flush();

            // Get the newly created bag
            $bag = $entityManager->getRepository(Bags::class)->findOneBy(['user_id' => $user->getId()]);
        }

        // Get the products in the user's bag
        $bagProducts = $bag->getBagProducts()->toArray();
        
        // Initialize variables to store the product list and total price
        $totalPrice = 0;
        $productList = [];
        
        // Loop through each product in the bag
        foreach ($bagProducts as $bagProduct) {
            // Prepare the product data
            $productList[] = [
                'product_id' => $bagProduct->getProductId()->getId(),
                'product_name' => $bagProduct->getProductId()->getName(),
                'product_img' => $bagProduct->getProductId()->getImg(),
                'quantity' => $bagProduct->getQuantity(),
                'price' => floatval($bagProduct->getProductId()->getPrize()),
            ];
            
            // Calculate the total price
            $totalPrice += floatval($bagProduct->getProductId()->getPrize()) * $bagProduct->getQuantity();
        }

        // Render the 'shop/cart.html.twig' template and pass the necessary variables
        return $this->render('shop/cart.html.twig', [
            'bag' => $bag,
            'productList' => $productList,
            'totalPrice' => $totalPrice
        ]);
    }

    /*
      This function renders the product detail page
      
      It takes an integer $id as a parameter, which is the id of the product to be displayed
     
      It uses the EntityManagerInterface to get the product with the given id from the database
      
      It then gets the materials associated with the product and converts them to an array
      
      Finally, it renders the 'shop/product_detail.html.twig' template and passes the product and materials as variables
    */
    #[Route('/product_detail/{id}', name: '_product_detail')]
    public function product_detail($id, EntityManagerInterface $entityManager): Response
    {
        // Get the product with the given id from the database
        $product = $entityManager->getRepository(Products::class)->findOneBy(['id' => $id]);

        // Get the materials associated with the product
        $materials = $product->getMaterials()->toArray();

        // Render the 'shop/product_detail.html.twig' template and pass the product and materials as variables
        return $this->render('shop/product_detail.html.twig', [
            'product' => $product,
            'materials' => $materials
        ]);
    }

    /*
      This function handles the checkout process.
     
      It takes a Request object, a Security object, and an EntityManagerInterface object as parameters.
     
      It gets the current user from the database and creates a new BillingDetails object.
      It then gets the current user's bag from the database.
     
      It calculates the total price of all the products in the bag.
     
      If the user is logged in, it fills the BillingDetails object with the user's information.
     
      It creates a form for the BillingDetails object and handles the form submission.
      If the form is submitted and valid, it creates a new Order object and associates it with the user and the current date.
      It persists the order and flushed the changes to the database.
     
      It gets all the orders associated with the user and gets the most recent one.
     
      It creates OrderProducts objects for each product in the user's bag and associates them with the order.
      It updates the quantity of the product's material in the database and persists the changes.
      It removes the BagProduct objects corresponding to the products in the user's bag and persists the changes.
     
      It associates the BillingDetails object with the Order object and persists the changes.
     
      Finally, it renders the 'shop/checkout.html.twig' template and passes the form and the total price as variables.
     */
    #[Route('/checkout', name: '_checkout')]
    public function checkout(Request $request, Security $security, EntityManagerInterface $entityManager): Response
    {
        // Get the current user from the database
        $user = $entityManager->getRepository(Users::class)->findOneBy(['email' => $this->getUser()->getUserIdentifier()]);
        
        // Create a new BillingDetails object
        $billingDetails = new BillingDetails();
        
        // Get the current user's bag from the database
        $bag = $entityManager->getRepository(Bags::class)->findOneBy(['user_id' => $user->getId()]);

        // Calculate the total price of all the products in the bag
        $totalPrice = 0;
        $bagProducts = $entityManager->getRepository(BagProducts::class)->findBy(['bag_id' => $bag->getId()]);
        foreach ($bagProducts as $product) {
            $totalPrice += round($product->getProductId()->getPrize() * $product->getQuantity(),2);
        }

        // If the user is logged in, fill the BillingDetails object with their information
        if ($user) {
            $billingDetails->setEmail($user->getEmail());
            $billingDetails->setName($user->getName());
            $billingDetails->setSurname($user->getSurname());
            $billingDetails->setPhoneNumber(strval($user->getPhoneNumber()));
        }

        // Create a form for the BillingDetails object and handle the form submission
        $form = $this->createForm(BillingType::class, $billingDetails);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Create a new Order object and associate it with the user and the current date
            $order = new Orders();
            $order->setUserId($user);
            $order->setDate(new DateTime());

            $entityManager->persist($order);
            $entityManager->flush();

            // Get all the orders associated with the user
            $orders = $entityManager->getRepository(Orders::class)->findBy(['User_id' => $user->getId()]);

            // Get the most recent order
            $order = end($orders);

            // Create OrderProducts objects for each product in the user's bag and associate them with the order
            foreach ($bagProducts as $product) {
                $newP = new OrderProducts();

                $newP->setOrderId($order);
                $newP->setProductId($product->getProductId());
                $newP->setQuantity($product->getQuantity());

                // Update the quantity of the product's material in the database and persist the changes
                $material = $entityManager->getRepository(Materials::class)->findOneBy(['product_id' => $product->getProductId()->getId()]);
                $material->setQuantity($material->getQuantity() - $product->getQuantity());
                $entityManager->persist($material);

                // Remove the BagProduct objects corresponding to the products in the user's bag and persist the changes
                $entityManager->persist($newP);
                $entityManager->remove($product);
            }

            // Associate the BillingDetails object with the Order object and persist the changes
            $order->setBillingDetails($billingDetails);

            $entityManager->persist($billingDetails);
            $entityManager->persist($order);
            $entityManager->flush();

            // Redirect to the order details page
            return $this->redirectToRoute('app_shop_order_details', ['order_id' => $order->getId()]);
        }

        // Render the 'shop/checkout.html.twig' template and pass the form and the total price as variables
        return $this->render('shop/checkout.html.twig', [
            'form' => $form->createView(),
            'totalPrice' => $totalPrice
        ]);
    }

    //Adds a product to the user's shopping bag.
    #[Route('/add_cart/{id}/{quant}', name: '_add_cart')]
    public function add_cart($id,$quant, EntityManagerInterface $entityManager): Response
    {
        // Get the user from the security context and the product from the database
        $user = $entityManager->getRepository(Users::class)->findOneBy(['email' => $this->getUser()->getUserIdentifier()]);
        $product = $entityManager->getRepository(Products::class)->findOneBy(['id' => $id]);

        // Check if the user has a shopping bag. If not, create a new one.
        $bag = $entityManager->getRepository(Bags::class)->findOneBy(['user_id' => $user->getId()]);
        if($bag == null){
            $newBag = new Bags();
            $newBag->setUserId($user); // Associate the new bag with the user.

            $entityManager->persist($newBag); // Add the new bag to the entity manager.
            $entityManager->flush(); // Persist the changes to the database.

            // Get the newly created bag from the database.
            $bag = $entityManager->getRepository(Bags::class)->findOneBy(['user_id' => $user->getId()]); 
        }

        // Check if the product is already in the bag. If not, add it.
        $prodBag = $entityManager->getRepository(BagProducts::class)->findOneBy([
            'bag_id' => $bag->getId(),
            'product_id' => $product->getId()
        ]);
        if($prodBag == null)
        {
            $bagProduct = new BagProducts();
            $bagProduct->setProductId($product); // Associate the product with the bag.
            $bagProduct->setBagId($bag); // Associate the bag with the product.
            $bagProduct->setQuantity($quant); // Set the quantity of the product in the bag.
            $entityManager->persist($bagProduct); // Add the new product to the entity manager.
        }else{
            $prodBag->setQuantity($prodBag->getQuantity()+$quant); // Update the quantity of the product in the bag.
            $entityManager->persist($prodBag); // Add the updated product to the entity manager.

        }

        $entityManager->flush(); // Persist the changes to the database.


        return $this->redirectToRoute('app_shop_cart'); // Redirect to the shopping cart page.
    }
    

    // Updates the quantity of a product in the shopping cart.
   
    #[Route('/update-quantity/{bagId}/{productId}', name: 'update_quantity', methods: ['POST'])]
    public function updateQuantity($bagId, $productId, Request $request, EntityManagerInterface $entityManager)
    {
        // Get the quantity from the request data.
        $data = json_decode($request->getContent(), true);
        $quantity = $data['quantity'];

        // Check if the quantity is valid.
        if ($quantity < 1) {
            // Return an error response if the quantity is invalid.
            return new JsonResponse(['success' => false, 'message' => 'La cantidad debe ser al menos 1.']);
        }

        // Find the bag product in the database.
        $bagProduct = $entityManager->getRepository(BagProducts::class)->findOneBy(['bag_id' => $bagId, 'product_id' => $productId]);

        // Check if the bag product exists.
        if (!$bagProduct) {
            // Return an error response if the bag product does not exist.
            return new JsonResponse(['success' => false, 'message' => 'El producto no fue encontrado en el carrito.']);
        }

        // Update the quantity of the bag product.
        $bagProduct->setQuantity($quantity);
        $entityManager->persist($bagProduct);
        $entityManager->flush();

        // Calculate the subtotal by multiplying the product price with the quantity.
        $subtotal = round($bagProduct->getProductId()->getPrize() * $quantity,2);

        // Calculate the total price by summing up the subtotals of all products in the bag.
        $totalPrice = 0;
        $bagProducts = $entityManager->getRepository(BagProducts::class)->findBy(['bag_id' => $bagId]);
        foreach ($bagProducts as $product) {
            $totalPrice += round($product->getProductId()->getPrize() * $product->getQuantity(),2);
        }

        // Return a success response with the updated subtotal and total price.
        return new JsonResponse(['success' => true, 'subtotal' => $subtotal, 'totalPrice' => $totalPrice]);
    }

    // Removes a product from the shopping cart.
     
    #[Route('/remove-from-cart/{bagId}/{productId}', name: 'remove_from_cart', methods: ['POST'])]
    public function removeFromCart(int $bagId, int $productId, EntityManagerInterface $entityManager): JsonResponse
    {
        // Find the BagProduct associated with the specified bag and product IDs.
        $bagProduct = $entityManager->getRepository(BagProducts::class)->findOneBy([
            'bag_id' => $bagId,
            'product_id' => $productId
        ]);

        // If the BagProduct exists, remove it from the database and calculate the total price.
        if ($bagProduct) {
            $entityManager->remove($bagProduct);
            $entityManager->flush();

            $totalPrice = 0;

            // Calculate the total price by summing up the subtotals of all products in the bag.
            $bagProducts = $entityManager->getRepository(BagProducts::class)->findBy(['bag_id' => $bagId]);
            foreach ($bagProducts as $bagProduct) {
                $totalPrice += round($bagProduct->getProductId()->getPrize() * $bagProduct->getQuantity(),2);
            }

            // Return a success response with the updated total price.
            return new JsonResponse(['success' => true, 'totalPrice' => $totalPrice]);
        }

        // If the BagProduct does not exist, return an error response.
        return new JsonResponse(['success' => false, 'message' => 'Product not found in bag']);
    }

    // Renders the order details page for a specific order.
     
    #[Route('/order_details/{order_id}', name: '_order_details')]
    public function order_details(int $order_id, EntityManagerInterface $entityManager): Response
    {
        // Get the order with the specified order ID from the database.
        $order = $entityManager->getRepository(Orders::class)->findOneBy(['id' => $order_id]);

        // Get the array of OrderProduct objects associated with the order.
        $orderProducts = $order->getOrderProducts()->toArray();

        // Initialize variables to store the list of products and the total price.
        $productList = [];
        $totalPrice = 0;

        // Loop through each OrderProduct object and create an array of product data.
        foreach ($orderProducts as $product) {
            $productList[] = [
                'product_id' => $product->getProductId()->getId(),
                'product_name' => $product->getProductId()->getName(),
                'product_img' => $product->getProductId()->getImg(),
                'quantity' => $product->getQuantity(),
                'price' => floatval($product->getProductId()->getPrize()),
            ];

            // Calculate the total price by multiplying the product's price by its quantity and summing up the results.
            $totalPrice += $product->getProductId()->getPrize() * $product->getQuantity();
        }

        // Render the order_details.html.twig template and pass the product list and total price as variables.
        return $this->render('shop/order_details.html.twig', [
            'products' => $productList,
            'totalPrice' => $totalPrice
        ]);
    }

    // This function renders the orders page for a specific user.
    
    #[Route('/orders', name: '_orders')]
    public function orders(EntityManagerInterface $entityManager): Response
    {
        // Get the user from the security context
        $user = $entityManager->getRepository(Users::class)->findOneBy(['email' => $this->getUser()->getUserIdentifier()]);
        
        // Get all the orders associated with the user
        $orders = $entityManager->getRepository(Orders::class)->findBy(['User_id' => $user->getId()]);

        // Initialize an empty array to store the list of orders
        $orderList = [];

        // Loop through each order
        foreach ($orders as $order) {
            // Initialize a variable to store the total price of the order
            $totalPrice = 0;
            
            // Get all the products associated with the order
            $orderProducts = $order->getOrderProducts()->toArray();

            // Loop through each product in the order
            foreach ($orderProducts as $product){

                // Calculate the total price of the product by multiplying its price by its quantity
                $totalPrice += round($product->getProductId()->getPrize() * $product->getQuantity(),2);
            }

            // Create an array to store the order data
            $orderList[] = [
                // Store the date of the order in a formatted string
                'date' => $order->getDate()->format('d/m/Y'),
                // Store the total price of the order
                'price' => $totalPrice,
                // Store the ID of the order
                'id' => $order->getId(),
            ];
        }

        // Render the 'shop/orders.html.twig' template and pass the order list as a variable
        return $this->render('shop/orders.html.twig', [
            'orders' => $orderList
        ]);
    }
}