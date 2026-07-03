<?php
// Φέρνει μέσα την κλάση TestCase του PHPUnit — τη γονική κλάση που δίνει
// πρόσβαση σε όλα τα assert*() methods (assertTrue, assertFalse, κ.λπ.)
use PHPUnit\Framework\TestCase;

// Το project δεν έχει PSR-4 autoloading, οπότε φέρνουμε χειροκίνητα
// την κλάση Auth (αυτή που θέλουμε να τεστάρουμε) — ίδιο pattern με
// κάθε require_once που έχεις ήδη σε όλο το project.
require_once __DIR__ . '/../app/core/Auth.php';
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