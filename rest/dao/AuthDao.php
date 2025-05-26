<?php

require_once __DIR__ . '/BaseDao.php';

class AuthDao extends BaseDao {
    public function __construct() {
        parent::__construct('users');
    }

    public function getUserByEmail($email) {
        $query = "SELECT * FROM users WHERE email = :email";
        $params = [':email' => $email];
        return $this->query_unique($query, $params);
    }

    public function getUserByUsername($username) {
        $query = "SELECT * FROM users WHERE username = :username";
        $params = [':username' => $username];
        return $this->query_unique($query, $params);
    }

    public function register($data) {
        $query = "INSERT INTO users (username, email, password_hash, firstName, lastName) VALUES (:username, :email, :password_hash, :firstName, :lastName)";
        $params = [
            ':username' => $data['username'],
            ':email' => $data['email'],
            ':password_hash' => $data['password_hash'],
            ':firstName' => $data['first_name'],
            ':lastName' => $data['last_name']
        ];
        return $this->execute($query, $params);
    }

    public function login($data) {
        $query = "SELECT * FROM users WHERE email = :email AND password_hash = :password_hash";
        $params = [
            ':email' => $data['email_or_username'],
            ':password_hash' => $data['password_hash']
        ];
        return $this->query_unique($query, $params);
    }
}

