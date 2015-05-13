<?php

require(dirname(__FILE__)."/../Database.php");

const FIRST_ARTICLE = 1;
const LAST_ARTICLE = 12;

class DBTest extends PHPUnit_Framework_TestCase
{
	protected function setUp()
	{
		if (!extension_loaded('mysqli'))
		{
			$this->markTestSkipped('The MySQLi extension is not available');
		}
	}

	public function testPrivilegedConnection()
	{
		$db = new Database;

		$this->assertEquals(true, $db->openPrivilegedConnection());

		$db->closeConnection();
	}

	public function testRestrictedConnection()
	{
		$db = new Database;

		$this->assertEquals(true, $db->openRestrictedConnection());

		$db->closeConnection();
	}

	public function testReadOnlyConnection()
	{
		$db = new Database;

		$this->assertEquals(true, $db->openReadOnlyConnection());

		$db->closeConnection();
	}

	public function testGetArticles()
	{
		$db = new Database;
		$db->openRestrictedConnection();

		$this->assertInternalType('array', $db->getArticles());

		$db->closeConnection();
	}

	public function testGetArticle()
	{
		$db = new Database;
		$db->openRestrictedConnection();

		$article = $db->getArticle(FIRST_ARTICLE);

		$this->assertInternalType('array', $article);
		// should only be 1 result
		$this->assertEquals(1, count($article));

		$db->closeConnection();
	}

	public function testCheckUsernameExists()
	{
		$db = new Database;
		$db->openRestrictedConnection();

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
		$db->openRestrictedConnection();

		$comments = $db->getArticleComments(LAST_ARTICLE);

		$this->assertInternalType('array', $comments);

		$db->closeConnection();
	}

	public function testGetNeighbouringArticleIds()
	{
		$db = new Database;
		$db->openRestrictedConnection();

		$article = $db->getArticle(8);

		$this->assertEquals(11, $article[0]->getNextArticleId());
		$this->assertEquals(7, $article[0]->getPreviousArticleId());

		$db->closeConnection();
	}

	public function testGetEndArticleIds()
	{
		$db = new Database;
		$db->openRestrictedConnection();

		$this->assertEquals(FIRST_ARTICLE, $db->getFirstArticleId());
		$this->assertEquals(LAST_ARTICLE, $db->getLastArticleId());

		$db->closeConnection();
	}

	public function testGetEdgeArticleIds()
	{
		$db = new Database;
		$db->openRestrictedConnection();

		$article = $db->getArticle(FIRST_ARTICLE);
		$this->assertEquals(LAST_ARTICLE, $article[0]->getPreviousArticleId());

		$article = $db->getArticle(LAST_ARTICLE);
		$this->assertEquals(FIRST_ARTICLE, $article[0]->getNextArticleId());

		$db->closeConnection();
	}
}