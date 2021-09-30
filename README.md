## Installation Steps

-   clone Project to localhost:git clone https://github.com/MohammedGoud/fatura.git.
-   run local server at 'php artisan serve --port=8080'.
-   composer install.
-   add .env file and create database.
-   php artisan migrate to create database structure.
-   if you want to run postman web services got to routes\api.php, http://127.0.0.1:8080/api/login as example, this api give token so i can put it onto authorization body Bearer.
-   run units tests using command: 'php artisan test'.

## How It's Work

-** Project work based laravel framework.

-**  It's uses JWT package for auhtentication and provide token for it. and use middleware "AuthenticationMiddleware".

1- I can create user with following request data 
URL : http://127.0.0.1:8080/api/register
{
    "name": "Muhammed AboElgoud",
    "email": "good20@gmail.com",
    "password": "123456",
    "role_id" : 1,
    "permissions": [
        "products-lists",
        "products-show",
        "products-store",
        "products-update",
        "products-delete",
        "users-logout",
        "users-profile",
        "roles-lists",
        "roles-store"
    ]
}

1- I can login with following request data
URL : http://127.0.0.1:8080/api/login
{
    "email": "good20@gmail.com",
    "password": "123456",
}
Response will be token to authenticated project access.

permissions or role assign to user also verify accessabilty to resouces.

    

-** Also use Routing system to check user authorized to access the resource or not, and use middleware "AuthorizationMiddleware".

## Units Tests
** **- Four Unit Tests
1- for unauthenticated users.
2- for unauthorized users
3- for invalid tokens.
4- for happy scenario 'add product successfully'


    
