<?php
use PHPUnit\Framework\TestCase;
use App\Core\Database;
use App\Models\User;

class UserModelTest extends TestCase{

    public function testCreateUserInsertsRow(){
        $db = new Database();
        $connection = $db->connect();
        $user = new User($connection);
        $unique = uniqid();
        
        $username = "testuser_$unique";
        $email = "test_$unique@example.com";
        $password = "dummy_hash";
        $first_name = "Test";
        $last_name = "User";

        $result = $user->createUser($username, $email, $password, $first_name, $last_name);

            $this->assertTrue($result);
    }


}