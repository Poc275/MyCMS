<?php

require(dirname(__FILE__)."/../Database.php");
//require(dirname(__FILE__)."/../User.php");

class DBTest extends PHPUnit_Framework_TestCase
{
	protected function setUp()
	{
		if (!extension_loaded('mysqli'))
		{
			$this->markTestSkipped('The MySQLi extension is not available');
		}
	}

	public function testConnection()
	{
		$db = new Database;

		$this->assertEquals(true, $db->openConnection());

		$db->closeConnection();
	}

	public function testGetArticles()
	{
		$db = new Database;
		$db->openConnection();

		$this->assertInternalType('array', $db->getArticles());

		$db->closeConnection();
	}

	public function testGetArticle()
	{
		$db = new Database;
		$db->openConnection();

		$article = $db->getArticle(1);

		$this->assertInternalType('array', $article);
		// should only be 1 result
		$this->assertEquals(1, count($article));

		$db->closeConnection();
	}

	public function testCheckUsernameExists()
	{
		$db = new Database;
		$db->openConnection();

		$user = $db->checkUsernameExists("poc275@gmail.com");
		$this->assertInstanceOf('User', $user);
		$this->assertEquals("poc275@gmail.com", $user->getUsername());
		$this->assertEquals("9bf578735c81bf43213a8b748e29fb67aecc54f23d48f5c18e33fca3a9baffe5", $user->getHash());
		$this->assertEquals("qj3eA+hg1/0LN65KnO16V2xHy51ModwIla/T73CCtkN1yukVq0IO2XGl4XJXSiNe", $user->getSalt());
		$this->assertEquals("admin", $user->getRole());

		$fakeUser = $db->checkUsernameExists("phoney@fraud.com");
		$this->assertNull($fakeUser);

		$db->closeConnection();
	}

	public function testGetArticleComments()
	{
		$db = new Database;
		$db->openConnection();

		$comments = $db->getArticleComments(10);

		$this->assertInternalType('array', $comments);

		$db->closeConnection();
	}

}