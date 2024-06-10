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
