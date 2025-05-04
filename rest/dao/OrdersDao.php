<?php

require_once __DIR__ . '/BaseDao.php';

class OrdersDao extends BaseDao {
    public function __construct() {
        parent::__construct("orders");
    }

    public function get_all_orders() {
        return $this->query("SELECT * FROM orders", []);
    }

    public function get_order_by_id($id) {
        return $this->query_unique("SELECT * FROM orders WHERE orderID = :id", [
            "id" => $id
        ]);
    }

    public function get_orders_by_user($userID) {
        return $this->query("SELECT * FROM orders WHERE userID = :userID", [
            "userID" => $userID
        ]);
    }

    public function insert_order($order) {
        try {
            $query = "INSERT INTO orders (orderID, userID, orderDate, shippingAddress, totalPrice, orderStatus)
                      VALUES (:orderID, :userID, :orderDate, :shippingAddress, :totalPrice, :orderStatus)";
            $params = [
                ':orderID' => $order['orderID'],
                ':userID' => $order['userID'],
                ':orderDate' => $order['orderDate'],
                ':shippingAddress' => $order['shippingAddress'],
                ':totalPrice' => $order['totalPrice'],
                ':orderStatus' => $order['orderStatus']
            ];
            $this->execute($query, $params);

            return [
                "message" => "Order inserted successfully",
                "orderID" => $order['orderID']
            ];
        } catch (PDOException $e) {
            return [
                "error" => "Failed to insert order",
                "details" => $e->getMessage()
            ];
        }
    }

    public function update_order($order) {
        try {
            $query = "UPDATE orders SET 
                        userID = :userID,
                        orderDate = :orderDate,
                        shippingAddress = :shippingAddress,
                        totalPrice = :totalPrice,
                        orderStatus = :orderStatus
                      WHERE orderID = :orderID";

            $params = [
                ':orderID' => $order['orderID'],
                ':userID' => $order['userID'],
                ':orderDate' => $order['orderDate'],
                ':shippingAddress' => $order['shippingAddress'],
                ':totalPrice' => $order['totalPrice'],
                ':orderStatus' => $order['orderStatus']
            ];

            $this->execute($query, $params);

            return ["message" => "Order updated successfully"];
        } catch (PDOException $e) {
            return [
                "error" => "Failed to update order",
                "details" => $e->getMessage()
            ];
        }
    }

    public function delete_order($id) {
        try {
            $this->execute("DELETE FROM orders WHERE orderID = :id", ["id" => $id]);
            return ["message" => "Order deleted successfully"];
        } catch (PDOException $e) {
            return [
                "error" => "Failed to delete order",
                "details" => $e->getMessage()
            ];
        }
    }
}
