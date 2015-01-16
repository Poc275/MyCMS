<?php
require(dirname(__FILE__)."/../Article.php");

class ArticleTest extends PHPUnit_Framework_TestCase
{
	public function testArticleConstructor()
	{
		$date = new DateTime();
		$article = new Article("Title", "Summary", "Tags", "Content", $date);

		$this->assertInstanceOf('Article', $article);
	}

	/**
	* @expectedException InvalidArgumentException
	*/
	public function testExceptionIsRaisedForInvalidConstructorArguments()
	{
		new Article("Title", "Summary", "Tags", "Content", "now");
	}

}