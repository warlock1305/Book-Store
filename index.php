<?php

require_once __DIR__ . '/vendor/autoload.php';

require_once 'rest/services/AuthService.php';
require_once 'rest/services/BooksService.php';
require_once 'rest/services/UsersService.php';
require_once 'rest/services/OrdersService.php';
require_once 'rest/services/ReviewsService.php';
require_once 'rest/services/GenresService.php';
require_once 'rest/services/OrdersDetailsService.php';

Flight::register('auth_service', "AuthService");
Flight::register('books_service', "BooksService");
Flight::register('users_service', "UsersService");
Flight::register('orders_service', "OrdersService");
Flight::register('reviews_service', "ReviewsService");
Flight::register('genres_service', "GenresService");
Flight::register('orders_details_service', "OrdersDetailsService");

require_once 'rest/middleware/AuthMiddleware.php';

Flight::register('auth_middleware', "AuthMiddleware");

Flight::route('/*', function() {
   if(
       strpos(Flight::request()->url, '/auth/login') === 0 ||
       strpos(Flight::request()->url, '/auth/register') === 0
   ) {
       return TRUE;
   } else {
       try {
           $token = Flight::request()->getHeader("Authentication");
            if(Flight::auth_middleware()->verifyToken($token))
                return TRUE;
       } catch (\Exception $e) {
           Flight::halt(401, $e->getMessage());
       }
   }
});

require_once __DIR__ . '/rest/routes/AuthRoutes.php';
require_once __DIR__ . '/rest/routes/UsersRoutes.php';
require_once __DIR__ . '/rest/routes/BooksRoutes.php';
require_once __DIR__ . '/rest/routes/OrdersRoutes.php';
require_once __DIR__ . '/rest/routes/ReviewsRoutes.php';
require_once __DIR__ . '/rest/routes/GenresRoutes.php';
require_once __DIR__ . '/rest/routes/OrdersDetailsRoutes.php';

Flight::route('GET /', function() {
    
});

Flight::start();
