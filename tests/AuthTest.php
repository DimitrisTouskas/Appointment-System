<?php
// Φέρνει μέσα την κλάση TestCase του PHPUnit — τη γονική κλάση που δίνει
// πρόσβαση σε όλα τα assert*() methods (assertTrue, assertFalse, κ.λπ.)
use PHPUnit\Framework\TestCase;
use App\Core\Auth;

class AuthTest extends TestCase
{
    public function testLogin()
    {
        unset($_SESSION['User_id']);
        $this->assertFalse(Auth::isLoggedIn());
    }

    public function testIsLoggedInReturnsTrueWhenSessionSet(){
        $_SESSION['User_id'] = 1;
        $this->assertTrue(Auth::isLoggedIn());
    }
}