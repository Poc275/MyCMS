<?php

require(dirname(__FILE__)."/../User.php");

class UserTest extends PHPUnit_Framework_TestCase
{
	public function testUserConstructor()
	{
		$user = new User("me@me.com", "123456", "abcdef", "admin");

		$this->assertInstanceOf('User', $user);
	}

	public function testGetters()
	{
		$user = new User("me@me.com", "123456", "abcdef", "user");

		$this->assertEquals("me@me.com", $user->getUsername());
		$this->assertEquals("123456", $user->getHash());
		$this->assertEquals("abcdef", $user->getSalt());
		$this->assertEquals("user", $user->getRole());
	}

	public function testCheckPassword()
	{
		$user = new User("me@me.com", "9bf578735c81bf43213a8b748e29fb67aecc54f23d48f5c18e33fca3a9baffe5", 
			"qj3eA+hg1/0LN65KnO16V2xHy51ModwIla/T73CCtkN1yukVq0IO2XGl4XJXSiNe", "user");

		$valid = $user->checkPassword("#L3tM31n!");
		$invalid = $user->checkPassword("Password");

		$this->assertEquals(false, $invalid);
		$this->assertEquals(true, $valid);
	}

}