<?php

require_once __DIR__ . '/../dao/OrdersDao.php';
require_once __DIR__ . '/BooksService.php';

class OrdersService {
    private $ordersDao;
    private $booksService;

    public function __construct() {
        $this->ordersDao = new OrdersDao();
        $this->booksService = new BooksService();
    }

    private function validate_order($order, $is_update = false) {
        $required_fields = ['userID', 'bookID', 'quantity', 'orderDate'];

        foreach ($required_fields as $field) {
            if (empty($order[$field]) && !$is_update) {
                throw new Exception("Field '{$field}' is required.");
            }
        }

        if (isset($order['quantity']) && (!is_numeric($order['quantity']) || $order['quantity'] <= 0)) {
            throw new Exception("Quantity must be a positive number.");
        }

        if (isset($order['orderDate']) && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $order['orderDate'])) {
            throw new Exception("Order date must be in YYYY-MM-DD format.");
        }

        if (isset($order['bookID'])) {
            $book = $this->booksService->get_book_by_id($order['bookID']);
            if (!$book) {
                throw new Exception("Book with ID {$order['bookID']} does not exist.");
            }

            if ($book['stock'] < $order['quantity']) {
                throw new Exception("Not enough stock for book ID {$order['bookID']}. Available: {$book['stock']}, requested: {$order['quantity']}.");
            }
        }
    }

    public function get_all_orders() {
        return $this->ordersDao->get_all_orders();
    }

    public function get_orders_by_user($userID) {
        return $this->ordersDao->get_orders_by_user($userID);
    }

    public function get_order_by_id($orderID) {
        $order = $this->ordersDao->get_order_by_id($orderID);
        if (!$order) {
            throw new Exception("Order with ID $orderID not found.");
        }
        return $order;
    }

    public function add_order($order) {
        $this->validate_order($order);

        // Reduce stock from the book
        $book = $this->booksService->get_book_by_id($order['bookID']);
        $newStock = $book['stock'] - $order['quantity'];
        $this->booksService->update_book([
            'bookID' => $book['bookID'],
            'stock' => $newStock
        ]);

        return $this->ordersDao->insert_order($order);
    }

    public function update_order($order) {
        if (empty($order['orderID'])) {
            throw new Exception("Order ID is required for update.");
        }

        $existing = $this->ordersDao->get_order_by_id($order['orderID']);
        if (!$existing) {
            throw new Exception("Order with ID {$order['orderID']} does not exist.");
        }

        $this->validate_order($order, true);
        return $this->ordersDao->update_order($order);
    }

    public function delete_order($id) {
        $existing = $this->ordersDao->get_order_by_id($id);
        if (!$existing) {
            throw new Exception("Order with ID $id does not exist.");
        }

        return $this->ordersDao->delete_order($id);
    }
}
