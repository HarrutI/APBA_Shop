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
   incluyendo los productos pedidos, sus cantidades y detalles de facturación. Esta información se envía a la plantilla orderDetails.html.twig para su visualización.

### Rutas POST
- **remove_from_cart**: Esta ruta permite al usuario eliminar un producto específico de su carrito de compras. Recibe el ID del carrito y el ID del producto
   como parámetros. Una vez recibida la solicitud, el controlador elimina el producto correspondiente del carrito y actualiza la base de datos para reflejar el
   cambio. Después, redirige al usuario de vuelta al carrito para mostrar el estado actualizado.
- **update_quantity**: Esta ruta permite al usuario actualizar la cantidad de un producto específico en su carrito de compras. Recibe el ID del carrito y el ID
  del producto como parámetros, así como la nueva cantidad deseada a través del cuerpo de la solicitud. El controlador valida y aplica la actualización en la base
  de datos y redirige al usuario de vuelta al carrito para mostrar el estado actualizado del mismo.

## Formularios

### BillingType
La clase BillingType es responsable de construir el formulario para la entidad BillingDetails, que contiene los detalles de facturación de un pedido. Este formulario
incluye varios campos que son esenciales para recopilar información de facturación del usuario.

- **buildForm**: Este método construye el formulario agregando diferentes campos, cada uno representando un aspecto de los detalles de facturación.
  - name: Un campo de texto para ingresar el nombre del usuario.
  - surname: Un campo de texto para ingresar el apellido del usuario.
  - phone_number: Un campo de texto para ingresar el número de teléfono del usuario.
  - email: Un campo de tipo email para ingresar la dirección de correo electrónico del usuario.
  - addressLine1: Un campo de texto para ingresar la primera línea de la dirección del usuario.
  - addressLine2: Un campo de texto opcional para ingresar la segunda línea de la dirección del usuario.
  - city: Un campo de texto para ingresar la ciudad del usuario.
  - state: Un campo de texto para ingresar el estado o provincia del usuario.
  - postalCode: Un campo de texto para ingresar el código postal del usuario.
  - country: Un campo de texto para ingresar el país del usuario.
- **configureOptions**: Este método configura las opciones del formulario, especificando que los datos manejados por el formulario corresponden a la clase BillingDetails.

### SearchProductType
La clase SearchProductType es responsable de construir el formulario utilizado para buscar productos. Este formulario permite a los usuarios ingresar criterios de 
búsqueda y seleccionar etiquetas asociadas a los productos.

- **buildForm**: Este método construye el formulario agregando diferentes campos, cada uno representando un criterio de búsqueda.
  - query: Un campo de tipo búsqueda que permite a los usuarios ingresar términos de búsqueda. Tiene una etiqueta personalizada "Buscar productos" y no es obligatorio.
  - tags: Un campo de tipo entidad que permite seleccionar múltiples etiquetas asociadas a los productos. Este campo se relaciona con la entidad Tags, utilizando el campo
    name de la entidad como etiqueta para cada opción. Las opciones se presentan como una lista de casillas de verificación y el campo no es obligatorio.
- **configureOptions**: Este método configura las opciones del formulario, especificando que el método de envío del formulario es GET.

## Templates

### Carpeta Security
- **login.html.twig**: Esta plantilla se utiliza para mostrar el formulario de inicio de sesión. Contiene campos para ingresar el correo electrónico y la contraseña, así
 como una opción para recordar el correo electrónico y un botón para enviar el formulario.

### Carpeta Shop
- **cart.html.twig**: Esta plantilla muestra los productos que el usuario ha agregado al carrito de compras. Muestra una tabla con detalles de cada producto, incluyendo
  imagen, nombre, precio, cantidad y subtotal. También muestra el total de la compra y proporciona un botón para proceder al pago.
- **checkout.html.twig**: Esta plantilla se utiliza para el proceso de pago. Muestra un resumen del precio total de la compra y un formulario para que el usuario ingrese
   su información de facturación, como nombre, apellido, número de teléfono, dirección, etc.
- **contact.html.twig**: Esta plantilla muestra un formulario de contacto donde los usuarios pueden enviar preguntas o comentarios. Contiene campos para ingresar el nombre,
   correo electrónico y mensaje del usuario, así como un botón para enviar el formulario.
- **home.html.twig**: Esta plantilla representa la página de inicio de la tienda. Muestra una selección de productos destacados y proporciona enlaces para que los usuarios
  naveguen por diferentes categorías de productos.
- **index.html.twig**: Esta plantilla es la estructura base de todas las páginas del sitio. Define la estructura básica del HTML, incluyendo la cabecera, el contenido principal
  y el pie de página. También carga los estilos CSS y otros recursos necesarios.
- **order_details.html.twig**: Esta plantilla muestra los detalles de un pedido específico. Muestra información detallada sobre los productos incluidos en el pedido, así como
   el precio total y la información de envío.
- **orders.html.twig**: Esta plantilla muestra una lista de todos los pedidos realizados por el usuario. Muestra información resumida sobre cada pedido, como la fecha, el estado
   y el precio total.
- **product_detail.html.twig**: Esta plantilla muestra los detalles de un producto específico, incluyendo su nombre, descripción, precio y opciones de compra. También puede
  incluir imágenes adicionales y comentarios de los clientes.
- **profile.html.twig**: Esta plantilla muestra el perfil del usuario, incluyendo información personal como nombre, correo electrónico, dirección, etc. También puede
  proporcionar opciones para que el usuario actualice su información.
- **search.html.twig**: Esta plantilla muestra los resultados de una búsqueda de productos realizada por el usuario. Muestra una lista de productos que coinciden con los
  criterios de búsqueda especificados.
- **simpleText.html.twig**: Esta plantilla muestra un texto simple en la página. Dependiendo del valor obtenido desde el controlador, muestra en pantalla un texto u otro.

## JS

### `search_filter.js`

Este script implementa una función denominada `toggleFilters()` que permite controlar la visibilidad de los filtros de búsqueda en una aplicación web. Al llamar a esta 
función, se verifica si el contenedor de los filtros está actualmente visible o no. Si el contenedor está visible, se oculta, eliminando cualquier altura mínima establecida.
Por otro lado, si el contenedor está oculto, se establece una altura mínima para el contenedor igual a la altura total de los filtros, lo que resulta en la visualización completa de los mismos.

Este script aprovecha el modelo de objetos del documento (DOM) de JavaScript para acceder al elemento del contenedor de los filtros mediante su identificador único. Luego,
ajusta dinámicamente la altura mínima del contenedor para mostrar u ocultar los filtros según sea necesario.

### `modifyQuantityCart.js`

Este script se encarga de manejar las operaciones relacionadas con la gestión del carrito de compras en una aplicación web, tales como la actualización y eliminación de productos.
Al cargar completamente el contenido del documento HTML, este script define dos funciones asincrónicas: `updateQuantity()` y `removeFromCart()`.

La función `updateQuantity()` se utiliza para enviar una solicitud al servidor con el propósito de actualizar la cantidad de un producto específico en el carrito de compras.
Utiliza la API `fetch()` para realizar solicitudes HTTP asincrónicas al servidor, enviando los datos necesarios en formato JSON.

Por otro lado, la función `removeFromCart()` se encarga de eliminar un producto del carrito de compras. Similar a `updateQuantity()`, utiliza la API `fetch()` para enviar una 
solicitud al servidor, esta vez para eliminar el producto seleccionado del carrito.

Ambas funciones utilizan manipulaciones del DOM para actualizar dinámicamente la interfaz de usuario en respuesta a las acciones del usuario, como aumentar o disminuir la cantidad de 
productos, o eliminar productos del carrito. Esto permite una experiencia de usuario fluida y sin interrupciones durante la gestión del carrito de compras en la aplicación web.

## Medidas de seguridad

### `config/packages/security.yaml`

Este archivo de configuración de seguridad describe las medidas de seguridad implementadas en una aplicación Symfony. Se centra en la autenticación de usuarios, el control de
acceso y otras consideraciones de seguridad relevantes.

- `password_hashers`: Define cómo se deben almacenar y verificar las contraseñas de los usuarios. En este caso, se utiliza el autenticador de contraseñas proporcionado por Symfony
  para las interfaces de usuario autenticadas por contraseña. Se utiliza el algoritmo de hash automático para seleccionar el mejor método disponible según la plataforma.

- `providers`: Especifica los proveedores de usuarios, que son responsables de cargar usuarios desde diferentes fuentes. En este caso, se utiliza un proveedor de entidad para
  cargar usuarios desde la base de datos.

- `firewalls`: Define las reglas de seguridad que se aplican a las diferentes partes de la aplicación. Se configuran dos firewalls: "dev" para el entorno de desarrollo, donde la
  seguridad está desactivada para las rutas internas, y "main" para el entorno de producción, donde se implementa la autenticación de usuarios mediante un autenticador personalizado
  y se gestiona el cierre de sesión.

- `remember_me`: Configura la funcionalidad de "recordar usuario" para mantener la sesión del usuario incluso después de cerrar el navegador. Se utiliza un secreto para firmar
   los datos de autenticación y se especifica la duración de la sesión.

- `access_control`: Define las reglas de control de acceso que determinan qué roles de usuario pueden acceder a qué rutas. En este caso, se restringe el acceso a la ruta "/shop"
  solo a los usuarios con el rol ROLE_USER.


