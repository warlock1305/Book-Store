<?php

require_once __DIR__ . '/../dao/UsersDao.php';

class UsersService {
    private $usersDao;

    public function __construct() {
        $this->usersDao = new UsersDao();
    }

    private function validate_user($user, $is_update = false) {
        $required_fields = ['name', 'email', 'password'];

        foreach ($required_fields as $field) {
            if (empty($user[$field]) && !$is_update) {
                throw new Exception("Field '{$field}' is required.");
            }
        }

        if (isset($user['email']) && !filter_var($user['email'], FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Invalid email format.");
        }

        if (isset($user['password']) && strlen($user['password']) < 6) {
            throw new Exception("Password must be at least 6 characters long.");
        }

        if (!$is_update && isset($user['email'])) {
            $existing = $this->usersDao->get_user_by_email($user['email']);
            if ($existing) {
                throw new Exception("A user with email '{$user['email']}' already exists.");
            }
        }
    }

    public function get_all_users() {
        return $this->usersDao->get_all_users();
    }

    public function get_user_by_id($userID) {
        $user = $this->usersDao->get_user_by_id($userID);
        if (!$user) {
            throw new Exception("User with ID $userID not found.");
        }
        return $user;
    }

    public function get_user_by_email($email) {
        return $this->usersDao->get_user_by_email($email);
    }

    public function add_user($user) {
        $this->validate_user($user);
        return $this->usersDao->insert_user($user);
    }

    public function update_user($user) {
        if (empty($user['userID'])) {
            throw new Exception("User ID is required for update.");
        }

        $existing = $this->usersDao->get_user_by_id($user['userID']);
        if (!$existing) {
            throw new Exception("User with ID {$user['userID']} does not exist.");
        }

        $this->validate_user($user, true);
        return $this->usersDao->update_user($user);
    }

    public function delete_user($userID) {
        $existing = $this->usersDao->get_user_by_id($userID);
        if (!$existing) {
            throw new Exception("User with ID $userID does not exist.");
        }

        return $this->usersDao->delete_user($userID);
    }
}
