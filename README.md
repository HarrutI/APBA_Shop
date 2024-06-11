# APBA SHOP


El proyecto consiste en el desarrollo de una tienda online exclusiva para los empleados de la Autoridad Portuaria de la Bahía de Algeciras. 
La plataforma ofrecerá productos de merchandising y elementos de seguridad con el logotipo de la entidad a precios reducidos. 
Esta tienda online busca proporcionar una oferta diversificada y atractiva para los trabajadores, brindándoles la oportunidad de adquirir 
productos de alta calidad de una manera conveniente y accesible. El proyecto incluye el diseño y desarrollo de la plataforma web, la adquisición de 
inventario inicial, la implementación de estrategias de marketing internas y la capacitación del personal encargado de gestionar la tienda online.

## Requisitos del Sistema

- PHP >= Last version
- Composer
- Symfony CLI

## Instalación

1. Clona este repositorio en tu máquina local:

    ```bash
    git clone https://github.com/HarrutI/APBA_Shop.git
    ```

2. Instala las dependencias del proyecto utilizando Composer:

    ```bash
    composer install
    ```

3. Configura tu base de datos en el archivo `.env`:

    ```
    DATABASE_URL=mysql://usuario:contraseña@host:puerto/nombre_base_de_datos
    ```

4. Crea la base de datos y ejecuta las migraciones:

    ```bash
    php bin/console doctrine:database:create
    php bin/console doctrine:migrations:migrate
    ```

## Uso

Ejecuta el servidor local de Symfony:

```bash
symfony server:start
```

## Entidades

### BagProducts.php
Esta entidad representa la relación entre Bolsas y Productos. Cada registro en esta tabla relaciona cada producto que tiene un carrito. 
Sus variables son:

### Bags.php
Representa los carritos almacenados en el sistema y a qué usuario pertenecen.

### BillingDetails.php
Representa los detalles de facturación asociados a un pedido Order. Puede incluir información como la dirección de facturación, nombre, etc.

### Materials.php
Almacena información extra de un producto y las cantidades que hay en almacén.

### OrderProducts.php
Esta entidad representa la relación entre órdenes y productos. Cada registro en esta tabla relaciona un producto con una orden específica.

### Orders.php
La entidad Orders representa las órdenes realizadas por los usuarios. Contiene información sobre cada orden, como el usuario que realizó
la orden, la fecha de la orden, etc.

### Products.php
Representa los productos disponibles en la tienda. Contiene información detallada sobre cada producto, como nombre, descripción, precio, etc.

### Tags.php
La entidad Tags almacena etiquetas que se pueden asociar con productos para facilitar la búsqueda y organización.

### Users.php
Representa los usuarios registrados en el sistema. Contiene información sobre cada usuario, como nombre, correo electrónico, contraseña, etc.

## Controladores y Rutas

### LoginController.php
- **app_login**: Esta ruta se encarga de renderizar el formulario de inicio de sesión. Utiliza el servicio AuthenticationUtils para obtener
  cualquier error que haya ocurrido durante el intento de inicio de sesión y para recuperar el último nombre de usuario ingresado por el usuario.
  Estos datos se pasan a la plantilla login.html.twig para mostrar el formulario de inicio de sesión con la información adecuada (como mensajes
  de error o el último nombre de usuario intentado).
- **app_logout**: Gestiona la acción de cierre de sesión del usuario. Este método no contiene lógica, ya que el proceso de cierre de sesión es
  manejado automáticamente por Symfony. La ruta simplemente define el punto donde se intercepta la solicitud de cierre de sesión y se procesa la
  acción correspondiente, terminando la sesión del usuario actual.

### ShopController.php
- **app_shop_home**: Muestra los productos más populares. Consulta la base de datos para obtener los productos etiquetados como "Más Populares".
   Estos productos se envían a la plantilla home.html.twig, donde se renderizan y se presentan al usuario en la página de inicio de la tienda.
- **app_shop_search**: Permite buscar productos. Renderiza un formulario de búsqueda y procesa las consultas de búsqueda de los usuarios.
  Utiliza los datos del formulario para buscar productos que coincidan con el nombre y las etiquetas especificadas. Los resultados de la búsqueda
  se envían a la plantilla search.html.twig para su visualización.
- **app_shop_contact**: Muestra la página de contacto. Renderiza la plantilla contact.html.twig, proporcionando al usuario información de contacto
   y posiblemente un formulario de contacto.
- **app_shop_pricacy-policy**: Muestra las políticas de privacidad. Renderiza la plantilla simpleText.html.twig con el texto correspondiente a las
   políticas de privacidad de la tienda.
- **app_shop_cookies-policy**: Muestra las políticas de cookies. Renderiza la plantilla simpleText.html.twig con el texto correspondiente a las
  políticas de cookies de la tienda.
- **app_shop_legal_advice**: Muestra el aviso legal. Renderiza la plantilla simpleText.html.twig con el texto correspondiente al aviso legal de la tienda.
- **app_shop_profile**: Muestra el perfil del usuario. Renderiza la plantilla profile.html.twig, proporcionando al usuario información sobre su cuenta,
   como su nombre, correo electrónico y otra información personal.
- **app_shop_cart**: Muestra el carrito de compras del usuario. Consulta la base de datos para obtener el carrito de compras del usuario actual, incluyendo
   los productos añadidos y sus cantidades. Calcula el precio total de los productos en el carrito y renderiza esta información en la plantilla cart.html.twig.
- **app_shop_product_detail**: Muestra los detalles de un producto específico. Consulta la base de datos para obtener la información detallada de un producto
   específico, incluyendo su nombre, descripción, precio y materiales. Esta información se envía a la plantilla product_detail.html.twig para su visualización.
- **app_shop_checkout**: Gestiona el proceso de pago. Renderiza un formulario de detalles de facturación, que el usuario debe completar para proceder con el
  pago. Procesa los detalles de facturación y crea un nuevo pedido en la base de datos, moviendo los productos del carrito al pedido y ajustando las
  cantidades de stock de los productos. Finalmente, redirige al usuario a la página de detalles del pedido.
- **app_shop_order_details**: Muestra los detalles de un pedido específico. Consulta la base de datos para obtener la información de un pedido específico,
-  incluyendo los productos pedidos, sus cantidades y detalles de facturación. Esta información se envía a la plantilla orderDetails.html.twig para su visualización.
