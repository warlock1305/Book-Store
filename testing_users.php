<?php

require_once __DIR__ . '/rest/dao/UsersDao.php';

$usersDao = new UsersDao();

$testUser = [
    "userID" => "U001",
    "username" => "testuser",
    "email" => "testuser@example.com",
    "password" => "TestPassword123", // This will be hashed in the database
    "firstName" => "Test",
    "lastName" => "User",
    "dateJoined" => "2025-04-01",
    "address" => "123 Test St, Test City, TC"
];

$insertResult = $usersDao->insert_user($testUser);
echo "Insert:\n";
print_r($insertResult);

$allUsers = $usersDao->get_all_users();
echo "\nAll Users:\n";
print_r($allUsers);

$singleUser = $usersDao->get_user_by_id("U001");
echo "\nSingle User by ID:\n";
print_r($singleUser);

$singleUserByEmail = $usersDao->get_user_by_email("testuser@example.com");
echo "\nSingle User by Email:\n";
print_r($singleUserByEmail);

$testUserUpdate = [
    "userID" => "U001",
    "username" => "updateduser",
    "email" => "updateduser@example.com",
    "password" => "UpdatedPassword123",  // New password, will be hashed
    "firstName" => "Updated",
    "lastName" => "User",
    "dateJoined" => "2025-04-01",
    "address" => "456 Updated St, Updated City, UC"
];

$updateResult = $usersDao->update_user($testUserUpdate);
echo "\nUpdate:\n";
print_r($updateResult);

$deleteResult = $usersDao->delete_user("U001");
echo "\nDelete:\n";
print_r($deleteResult);
