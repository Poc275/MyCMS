<?php

require(dirname(__FILE__)."/../Database.php");

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

}