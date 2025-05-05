<?php

require_once __DIR__ . '/../dao/OrdersDetailsDao.php';
require_once __DIR__ . '/../dao/BooksDao.php';
require_once __DIR__ . '/../dao/OrdersDao.php';

class OrderDetailsService {
    private $orderDetailsDao;
    private $booksDao;
    private $ordersDao;

    public function __construct() {
        $this->orderDetailsDao = new OrderDetailsDao();
        $this->booksDao = new BooksDao();
        $this->ordersDao = new OrdersDao();
    }

    private function validate_order_detail($detail) {
        $required_fields = ['orderID', 'bookID', 'quantity', 'price'];

        foreach ($required_fields as $field) {
            if (empty($detail[$field])) {
                throw new Exception("Field '{$field}' is required.");
            }
        }

        if (!is_numeric($detail['quantity']) || $detail['quantity'] <= 0) {
            throw new Exception("Quantity must be a positive number.");
        }

        if (!is_numeric($detail['price']) || $detail['price'] < 0) {
            throw new Exception("Price must be a non-negative number.");
        }

        if (!$this->ordersDao->get_order_by_id($detail['orderID'])) {
            throw new Exception("Order with ID {$detail['orderID']} does not exist.");
        }

        if (!$this->booksDao->get_book_by_id($detail['bookID'])) {
            throw new Exception("Book with ID {$detail['bookID']} does not exist.");
        }
    }

    public function get_details_by_order_id($orderID) {
        return $this->orderDetailsDao->get_details_by_order_id($orderID);
    }

    public function add_order_detail($detail) {
        $this->validate_order_detail($detail);
        return $this->orderDetailsDao->insert_order_detail($detail);
    }

    public function update_order_detail($detail) {
        if (empty($detail['detailID'])) {
            throw new Exception("Detail ID is required for update.");
        }

        $existing = $this->orderDetailsDao->get_detail_by_id($detail['detailID']);
        if (!$existing) {
            throw new Exception("Order detail with ID {$detail['detailID']} does not exist.");
        }

        $this->validate_order_detail($detail);
        return $this->orderDetailsDao->update_order_detail($detail);
    }

    public function delete_order_detail($detailID) {
        $existing = $this->orderDetailsDao->get_detail_by_id($detailID);
        if (!$existing) {
            throw new Exception("Order detail with ID $detailID does not exist.");
        }

        return $this->orderDetailsDao->delete_order_detail($detailID);
    }
}
