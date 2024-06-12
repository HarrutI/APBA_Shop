# APBA SHOP

The project consists of developing an exclusive online store for the employees of the Port Authority of the Bay of Algeciras. The platform will offer merchandise and security items with the entity's logo at reduced prices. This online store aims to provide a diverse and appealing range of products for the workers, offering them the opportunity to acquire high-quality products in a convenient and accessible manner. The project includes the design and development of the web platform, the acquisition of initial inventory, the implementation of internal marketing strategies, and the training of the personnel responsible for managing the online store.

## System Requirements

- PHP >= Last version
- Composer
- Symfony CLI

## Installation

1. Clone this repository to your local machine:

    ```bash
    git clone https://github.com/HarrutI/APBA_Shop.git
    ```

2. Install project dependencies using Composer:

    ```bash
    composer install
    ```

3. Configure your database in the `.env` file:

    ```
    DATABASE_URL=mysql://username:password@host:port/database_name
    ```

4. Create the database and execute migrations:

    ```bash
    php bin/console doctrine:database:create
    php bin/console doctrine:migrations:migrate
    ```

## Usage

Run Symfony's local server:

```bash
symfony server:start
```

## Entities

### BagProducts.php
This entity represents the relationship between Bags and Products. Each record in this table relates each product in a cart. Its variables are:

### Bags.php
Represents the carts stored in the system and to which user they belong.

### BillingDetails.php
Represents the billing details associated with an Order. It can include information such as billing address, name, etc.

### Materials.php
Stores extra information about a product and the quantities in stock.

### OrderProducts.php
This entity represents the relationship between orders and products. Each record in this table relates a product to a specific order.

### Orders.php
The Orders entity represents the orders placed by users. It contains information about each order, such as the user who placed the order, the order date, etc.

### Products.php
Represents the products available in the store. It contains detailed information about each product, such as name, description, price, etc.

### Tags.php
The Tags entity stores tags that can be associated with products to facilitate searching and organization.

### Users.php
Represents the users registered in the system. It contains information about each user, such as name, email, password, etc.


## Controllers and Routes

### DefaultController.php: 
- `app_homepage`: Manages navigation to the website's homepage. When accessing this route, it checks if the user has an active session. If the user has an active session, it redirects to the /shop/home page where the shop with the most popular products is displayed. If the user does not have an active session, it redirects to the /login page for the user to log in

### LoginController.php
- `app_login`: This route is responsible for rendering the login form. It uses the AuthenticationUtils service to retrieve any errors that occurred during the login attempt and to retrieve the last username entered by the user. This data is passed to the login.html.twig template to display the login form with appropriate information (such as error messages or the last attempted username).
- `app_logout`: Handles the user logout action. This method contains no logic, as the logout process is handled automatically by Symfony. The route simply defines the point where the logout request is intercepted and the corresponding action is processed, ending the session of the current user.

### ShopController.php
- `app_shop_home`: Displays the most popular products. Queries the database to retrieve products labeled as "Most Popular". These products are sent to the home.html.twig template, where they are rendered and presented to the user on the store's homepage.
- `app_shop_search`: Allows searching for products. Renders a search form and processes user search queries. It uses the form data to search for products matching the specified name and tags. The search results are sent to the search.html.twig template for display.
- `app_shop_contact`: Displays the contact page. Renders the contact.html.twig template, providing the user with contact information and possibly a contact form.
- `app_shop_privacy-policy`: Displays the privacy policies. Renders the simpleText.html.twig template with the corresponding text for the store's privacy policies.
- `app_shop_cookies-policy`: Displays the cookies policies. Renders the simpleText.html.twig template with the corresponding text for the store's cookies policies.
- `app_shop_legal_advice`: Displays the legal notice. Renders the simpleText.html.twig template with the corresponding text for the store's legal notice.
- `app_shop_profile`: Displays the user profile. Renders the profile.html.twig template, providing the user with information about their account, such as their name, email, and other personal information.
- `app_shop_cart`: Displays the user's shopping cart. Queries the database to retrieve the current user's shopping cart, including the added products and their quantities. It calculates the total price of the products in the cart and renders this information in the cart.html.twig template.
- `app_shop_product_detail`: Displays the details of a specific product. Queries the database to retrieve detailed information about a specific product, including its name, description, price, and materials. This information is sent to the product_detail.html.twig template for display.
- `app_shop_checkout`: Manages the checkout process. Renders a billing details form, which the user must complete to proceed with payment. It processes the billing details and creates a new order in the database, moving the products from the cart to the order and adjusting the stock quantities of the products. Finally, it redirects the user to the order details page.
- `app_shop_order_details`: Displays the details of a specific order. Queries the database to retrieve information about a specific order, including the ordered products, their quantities, and billing details. This information is sent to the orderDetails.html.twig template for display.

### POST Routes
- `remove_from_cart`: This route allows the user to remove a specific product from their shopping cart. It receives the cart ID and the product ID as parameters. Once the request is received, the controller removes the corresponding product from the cart and updates the database to reflect the change. Then, it redirects the user back to the cart to show the updated status.
- `update_quantity`: This route allows the user to update the quantity of a specific product in their shopping cart. It receives the cart ID and the product ID as parameters, as well as the new desired quantity via the request body. The controller validates and applies the update in the database and redirects the user back to the cart to show its updated status.


## Forms

### BillingType
The BillingType class is responsible for constructing the form for the BillingDetails entity, which contains the billing details of an order. This form includes several fields that are essential for collecting user billing information.

- `buildForm`: This method constructs the form by adding different fields, each representing an aspect of the billing details.
  - name: A text field for entering the user's name.
  - surname: A text field for entering the user's surname.
  - phone_number: A text field for entering the user's phone number.
  - email: An email type field for entering the user's email address.
  - addressLine1: A text field for entering the first line of the user's address.
  - addressLine2: An optional text field for entering the second line of the user's address.
  - city: A text field for entering the user's city.
  - state: A text field for entering the user's state or province.
  - postalCode: A text field for entering the user's postal code.
  - country: A text field for entering the user's country.
- `configureOptions`: This method configures the form options, specifying that the data handled by the form corresponds to the BillingDetails class.

### SearchProductType
The SearchProductType class is responsible for constructing the form used for searching products. This form allows users to enter search criteria and select tags associated with the products.

- `buildForm`: This method constructs the form by adding different fields, each representing a search criterion.
  - query: A search type field that allows users to enter search terms. It has a custom label "Search products" and is not required.
  - tags: An entity type field that allows selecting multiple tags associated with products. This field is related to the Tags entity, using the entity's name field as a label for each option. Options are presented as a list of checkboxes and the field is not required.
- `configureOptions`: This method configures the form options, specifying that the form's submission method is GET.


## Templates

### Security Folder
- `login.html.twig`: This template is used to display the login form. It contains fields for entering email and password, as well as an option to remember the email and a button to submit the form.

### Shop Folder
- `cart.html.twig`: This template displays the products that the user has added to the shopping cart. It shows a table with details of each product, including image, name, price, quantity, and subtotal. It also shows the total purchase amount and provides a button to proceed to checkout.
- `checkout.html.twig`: This template is used for the checkout process. It displays a summary of the total purchase price and a form for the user to enter their billing information, such as name, surname, phone number, address, etc.
- `contact.html.twig`: This template displays a contact form where users can submit questions or comments. It contains fields for entering the user's name, email, and message, as well as a button to submit the form.
- `home.html.twig`: This template represents the store's homepage. It displays a selection of featured products and provides links for users to browse different product categories.
- `index.html.twig`: This template is the base structure for all site pages. It defines the basic HTML structure, including the header, main content, and footer. It also loads CSS styles and other necessary resources.
- `order_details.html.twig`: This template displays the details of a specific order. It shows detailed information about the products included in the order, as well as the total price and shipping information.
- `orders.html.twig`: This template displays a list of all orders placed by the user. It shows summary information about each order, such as date, status, and total price.
- `product_detail.html.twig`: This template displays the details of a specific product, including its name, description, price, and purchasing options. It may also include additional images and customer reviews.
- `profile.html.twig`: This template displays the user's profile, including personal information such as name, email, address, etc. It may also provide options for the user to update their information.
- `search.html.twig`: This template displays the results of a product search performed by the user. It shows a list of products that match the specified search criteria.
- `simpleText.html.twig`: This template displays simple text on the page. Depending on the value obtained from the controller, it displays one text or another on the screen.


## JavaScript

### `search_filter.js`

This script implements a function called `toggleFilters()` that controls the visibility of search filters in a web application. When calling this function, it checks if the filters container is currently visible or not. If the container is visible, it hides it by removing any set minimum height. On the other hand, if the container is hidden, it sets a minimum height for the container equal to the total height of the filters, resulting in the complete display of the filters.

This script leverages JavaScript's Document Object Model (DOM) to access the filters container element by its unique identifier. It then dynamically adjusts the minimum height of the container to show or hide the filters as needed.

### `modifyQuantityCart.js`

This script handles operations related to managing the shopping cart in a web application, such as updating and removing products. Upon fully loading the HTML document content, this script defines two asynchronous functions: `updateQuantity()` and `removeFromCart()`.

The `updateQuantity()` function is used to send a request to the server to update the quantity of a specific product in the shopping cart. It uses the `fetch()` API to make asynchronous HTTP requests to the server, sending the necessary data in JSON format.

On the other hand, the `removeFromCart()` function is responsible for removing a product from the shopping cart. Similar to `updateQuantity()`, it uses the `fetch()` API to send a request to the server, this time to remove the selected product from the cart.

Both functions use DOM manipulations to dynamically update the user interface in response to user actions, such as increasing or decreasing the quantity of products, or removing products from the cart. This allows for a smooth and uninterrupted user experience during shopping cart management in the web application.


## Security Measures

### `config/packages/security.yaml`

This security configuration file describes the security measures implemented in a Symfony application. It focuses on user authentication, access control, and other relevant security considerations.

- `password_hashers`: Defines how user passwords should be stored and verified. In this case, the password hasher provided by Symfony is used for password-authenticated user interfaces. Automatic hash algorithm is used to select the best available method depending on the platform.

- `providers`: Specifies user providers, which are responsible for loading users from different sources. In this case, an entity provider is used to load users from the database.

- `firewalls`: Defines the security rules that apply to different parts of the application. Two firewalls are configured: "dev" for the development environment, where security is disabled for internal routes, and "main" for the production environment, where user authentication is implemented using a custom authenticator and logout is managed.

- `remember_me`: Configures the "remember user" functionality to keep the user session even after closing the browser. A secret is used to sign the authentication data, and the session duration is specified.

- `access_control`: Defines access control rules that determine which user roles can access which routes. In this case, access to the "/shop" route is restricted only to users with the ROLE_USER role.
