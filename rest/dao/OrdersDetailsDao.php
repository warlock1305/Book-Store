<?php

require_once __DIR__ . '/BaseDao.php';

class OrderDetailsDao extends BaseDao {
    public function __construct() {
        parent::__construct("orderdetails");
    }

    public function get_all_order_details() {
        return $this->query("SELECT * FROM orderdetails", []);
    }

    public function get_order_details_by_order_id($orderID) {
        return $this->query("SELECT * FROM orderdetails WHERE orderID = :orderID", [
            "orderID" => $orderID
        ]);
    }

    public function get_order_details_by_book_id($bookID) {
        return $this->query("SELECT * FROM orderdetails WHERE bookID = :bookID", [
            "bookID" => $bookID
        ]);
    }

    public function insert_order_detail($orderDetail) {
        try {
            $query = "INSERT INTO orderdetails (orderDetailID, orderID, bookID, quantity, price)
                      VALUES (:orderDetailID, :orderID, :bookID, :quantity, :price)";
            $params = [
                ':orderDetailID' => $orderDetail['orderDetailID'],
                ':orderID' => $orderDetail['orderID'],
                ':bookID' => $orderDetail['bookID'],
                ':quantity' => $orderDetail['quantity'],
                ':price' => $orderDetail['price']
            ];
            $this->execute($query, $params);

            return [
                "message" => "Order detail inserted successfully",
                "orderDetailID" => $orderDetail['orderDetailID']
            ];
        } catch (PDOException $e) {
            return [
                "error" => "Failed to insert order detail",
                "details" => $e->getMessage()
            ];
        }
    }

    public function update_order_detail($orderDetail) {
        try {
            $query = "UPDATE orderdetails SET
                        orderID = :orderID,
                        bookID = :bookID,
                        quantity = :quantity,
                        price = :price
                      WHERE orderDetailID = :orderDetailID";

            $params = [
                ':orderDetailID' => $orderDetail['orderDetailID'],
                ':orderID' => $orderDetail['orderID'],
                ':bookID' => $orderDetail['bookID'],
                ':quantity' => $orderDetail['quantity'],
                ':price' => $orderDetail['price']
            ];

            $this->execute($query, $params);

            return ["message" => "Order detail updated successfully"];
        } catch (PDOException $e) {
            return [
                "error" => "Failed to update order detail",
                "details" => $e->getMessage()
            ];
        }
    }

    public function delete_order_detail($orderDetailID) {
        try {
            $this->execute("DELETE FROM orderdetails WHERE orderDetailID = :orderDetailID", ["orderDetailID" => $orderDetailID]);
            return ["message" => "Order detail deleted successfully"];
        } catch (PDOException $e) {
            return [
                "error" => "Failed to delete order detail",
                "details" => $e->getMessage()
            ];
        }
    }
}
