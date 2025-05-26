<?php

require_once __DIR__ . '/BaseDao.php';

class UsersDao extends BaseDao {
    public function __construct() {
        parent::__construct("users");
    }

    public function get_all_users() {
        return $this->query("SELECT * FROM users", []);
    }

    public function get_user_by_id($id) {
        return $this->query_unique("SELECT * FROM users WHERE userID = :id", [
            "id" => $id
        ]);
    }

    public function get_user_by_email($email) {
        return $this->query_unique("SELECT * FROM users WHERE email = :email", [
            "email" => $email
        ]);
    }

    public function insert_user($user) {
        try {
            $query = "INSERT INTO users (userID, username, email, password_hash, firstName, lastName)
                      VALUES (:userID, :username, :email, :password_hash, :firstName, :lastName)";
            $params = [
                ':userID' => $user['userID'],
                ':username' => $user['username'],
                ':email' => $user['email'],
                ':password_hash' => password_hash($user['password'], PASSWORD_DEFAULT), // securely hash the password
                ':firstName' => $user['firstName'],
                ':lastName' => $user['lastName'],
            ];
            $this->execute($query, $params);

            return [
                "message" => "User inserted successfully",
                "userID" => $user['userID']
            ];
        } catch (PDOException $e) {
            return [
                "error" => "Failed to insert user",
                "details" => $e->getMessage()
            ];
        }
    }

    public function update_user($user) {
        try {
            $query = "UPDATE users SET 
                        username = :username,
                        email = :email,
                        password = :password,
                        firstName = :firstName,
                        lastName = :lastName,
                        dateJoined = :dateJoined,
                        address = :address
                      WHERE userID = :userID";

            $params = [
                ':userID' => $user['userID'],
                ':username' => $user['username'],
                ':email' => $user['email'],
                ':password' => password_hash($user['password'], PASSWORD_DEFAULT), // securely hash the password
                ':firstName' => $user['firstName'],
                ':lastName' => $user['lastName'],
                ':dateJoined' => $user['dateJoined'],
                ':address' => $user['address']
            ];

            $this->execute($query, $params);

            return ["message" => "User updated successfully"];
        } catch (PDOException $e) {
            return [
                "error" => "Failed to update user",
                "details" => $e->getMessage()
            ];
        }
    }

    public function delete_user($id) {
        try {
            $this->execute("DELETE FROM users WHERE userID = :id", ["id" => $id]);
            return ["message" => "User deleted successfully"];
        } catch (PDOException $e) {
            return [
                "error" => "Failed to delete user",
                "details" => $e->getMessage()
            ];
        }
    }
}
