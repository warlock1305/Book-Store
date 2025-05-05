<?php

require_once __DIR__ . '/vendor/autoload.php';


require_once __DIR__ . '/rest/routes/UsersRoutes.php';
require_once __DIR__ . '/rest/routes/BooksRoutes.php';
require_once __DIR__ . '/rest/routes/OrdersRoutes.php';
require_once __DIR__ . '/rest/routes/ReviewsRoutes.php';
require_once __DIR__ . '/rest/routes/GenresRoutes.php';
require_once __DIR__ . '/rest/routes/OrdersDetailsRoutes.php';

Flight::route('GET /', function() {

});

Flight::start();
