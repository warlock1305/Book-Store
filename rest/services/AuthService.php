<?php

require_once __DIR__ . '/../dao/AuthDao.php';
require_once __DIR__ . '/../config.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthService {
    private $authDao;

    public function __construct() {
        $this->authDao = new AuthDao();
    }

    public function register($data) {
        $username = $data['username'] ?? null;
        $firstName = $data['first_name'] ?? null;
        $lastName = $data['last_name'] ?? null;
        $email = $data['email'] ?? null;
        $password = $data['password'] ?? null;
        $confirmPassword = $data['confirm_password'] ?? null;


        if (empty($email) || empty($username) || empty($password)) {
            return ['success' => false, 'error' => 'Email, username, and password are required.'];
        }

        if ($password !== $confirmPassword) {
            return ['success' => false, 'error' => 'Passwords do not match.'];
        }

        if ($this->authDao->getUserByEmail($email)) {
            return ['success' => false, 'error' => 'Email already in use.'];
        }

        if ($this->authDao->getUserByUsername($username)) {
            return ['success' => false, 'error' => 'Username already in use.'];
        }

        $password_hash = password_hash($password, PASSWORD_BCRYPT);
        unset($data['password']); 
        unset($data['confirm_password']);
        $data['password_hash'] = $password_hash;

        $this->authDao->register($data);

        return ['success' => true, 'message' => 'User registered successfully.'];
    }

    public function login($data) {
        $emailOrUsername = $data['email_or_username'] ?? null;
        $password = $data['password'] ?? null;

        if (empty($emailOrUsername) || empty($password)) {
            return ['success' => false, 'error' => 'Email and password are required.'];
        }

        // Check if the input is an email or username
        if (filter_var($emailOrUsername, FILTER_VALIDATE_EMAIL)) {
            $user = $this->authDao->getUserByEmail($emailOrUsername);
        } else {
            $user = $this->authDao->getUserByUsername($emailOrUsername);
        }

        if (!$user || !password_verify($password, $user['password_hash'])) {
            return ['success' => false, 'error' => 'Invalid email or password.'];
        }

        unset($user['password_hash']);

        $user['role'] = null;

        //just for testing purposes
        if($user['username']==='admin') {
            $user['role'] = 'admin';
        } else {
            $user['role'] = 'user';
        }


        $payload = [
            'user' => $user,
            // 'role' => $user['role'],
            'iat' => time(),
            'exp' => time() + (60 * 60 * 24), // valid for 24 hours
        ];

        $token = JWT::encode($payload, Config::JWT_SECRET(), 'HS256');

        return ['success' => true, 'data' => array_merge($user, ['token' => $token])];
    }
}
