## Projeto Imobiliario

# Frameworks:

    - Laravel '5.7'

# Parametrização inicial:

    - Apos ter lidado com a estrutura de login, logout e a dashboard da home, tudo isso dentro do controlador de Auth, vamos partir para controlador User, para lidar com os usuarios e clientes;

# Comandos Terminal:
    - php artisan make:model User -m
    - php artisan make:controller Admin\\UserController --resource
    - php artisan route:list
    - php artisan migrate
    - php artisan make:request Admin\\User

    - php php artisan storage:link

    - composer require coffeecode/cropper

    - php artisan make:model Company -m
    - php artisan make:controller Admin\\CompanyController --resource
    - php artisan route:list
    - php artisan migrate
    - php artisan make:request Admin\\Company

    - php artisan make:model Property -m 
    - php artisan make:controller Admin\\PropertyController --resource
    - php artisan route:list
    - php artisan migrate
    - php artisan make:request Admin\\Property

# Fluxo de construção da aplicação
