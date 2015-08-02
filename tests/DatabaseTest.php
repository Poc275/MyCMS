<?php

require(dirname(__FILE__)."/../Database.php");

const FIRST_ARTICLE_URL = "chicken-katsu-curry";
const LAST_ARTICLE_URL = "this-is-a-tasty-burger";
const MID_ARTICLE_URL = "bulgogi-beef-sandwich";
const FIRST_ARTICLE_ID = 1;
const LAST_ARTICLE_ID = 19;
const MID_ARTICLE_ID = 8;

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

		$article = $db->getArticle(FIRST_ARTICLE_ID);

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

		$comments = $db->getArticleComments(LAST_ARTICLE_ID);

		$this->assertInternalType('array', $comments);

		$db->closeConnection();
	}

	public function testGetNeighbouringArticleIds()
	{
		$db = new Database;
		$db->openRestrictedConnection();

		$article = $db->getArticle(8);

		$this->assertEquals("pizza-al-forno", $article[0]->getNextArticleUrl());
		$this->assertEquals("creme-fraiche-pasta", $article[0]->getPreviousArticleUrl());

		$db->closeConnection();
	}

	public function testGetEndArticleIds()
	{
		$db = new Database;
		$db->openRestrictedConnection();

		$this->assertEquals(FIRST_ARTICLE_URL, $db->getFirstArticleUrl());
		$this->assertEquals(LAST_ARTICLE_URL, $db->getLastArticleUrl());

		$db->closeConnection();
	}

	public function testGetEdgeArticleIds()
	{
		$db = new Database;
		$db->openRestrictedConnection();

		$article = $db->getArticle(FIRST_ARTICLE_ID);
		$this->assertEquals(LAST_ARTICLE_URL, $article[0]->getPreviousArticleUrl());

		$article = $db->getArticle(LAST_ARTICLE_ID);
		$this->assertEquals(FIRST_ARTICLE_URL, $article[0]->getNextArticleUrl());

		$db->closeConnection();
	}

	public function testUpdateArticle()
	{
		$db = new Database;
		$db->openPrivilegedConnection();

		// get an existing article
		$article = $db->getArticle(FIRST_ARTICLE_ID);

		// modify it and update back into DB
		$article[0]->setTitle("Chicken Katsu Curry");
		$article[0]->setSummary("Wagamama inspired Katsu Curry");
		$article[0]->setTags("Japanese chicken curry");

		$this->assertEquals(true, $db->updateArticle($article[0]));

		// check updated values have been applied
		$article = $db->getArticle(FIRST_ARTICLE_ID);
		$this->assertEquals("Chicken Katsu Curry", $article[0]->getTitle());
		$this->assertEquals("Wagamama inspired Katsu Curry", $article[0]->getSummary());
		$this->assertEquals("Japanese chicken curry", $article[0]->getTags());

		$db->closeConnection();
	}

	public function testGetArticleByTitle()
	{
		$db = new Database;
		$db->openReadOnlyConnection();

		$article = $db->getArticleByUrl("chicken-katsu-curry");
		$this->assertInternalType('array', $article);
		$this->assertEquals(1, count($article));
		$this->assertEquals("Chicken Katsu Curry", $article[0]->getTitle());

		$article = $db->getArticleByUrl("quesa-enchi-rrito");
		$this->assertInternalType('array', $article);
		$this->assertEquals(1, count($article));
		$this->assertEquals("Quesa-enchi-rrito", $article[0]->getTitle());

		$db->closeConnection();
	}

	public function testGetArticleRange()
	{
		$db = new Database;
		$db->openReadOnlyConnection();

		$articles = $db->getArticleRange(0, 5);
		$this->assertInternalType('array', $articles);
		$this->assertEquals(5, count($articles));

		$articles = $db->getArticleRange(5, 3);
		$this->assertInternalType('array', $articles);
		$this->assertEquals(3, count($articles));
		$this->assertEquals("Spicy Chicken and zesty noodles", $articles[0]->getTitle());

		$db->closeConnection();
	}
}