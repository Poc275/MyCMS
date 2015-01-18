<?php
require(dirname(__FILE__)."/../Article.php");

class ArticleTest extends PHPUnit_Framework_TestCase
{
	public function testArticleConstructor()
	{
		$date = new DateTime();
		$article = new Article("Title", "Summary", "Tags", "ContentMd", "ContentHtml", $date);

		$this->assertInstanceOf('Article', $article);
	}

	/**
	* @expectedException InvalidArgumentException
	*/
	public function testExceptionIsRaisedForInvalidConstructorArguments()
	{
		new Article("Title", "Summary", "Tags", "ContentMd", "ContentHtml", "now");
	}


	public function testGetters()
	{
		$date = new DateTime();
		$dateString = $date->format('Y-m-d H:i:s');
		$article = new Article("Chicken Katsu Curry",
								"Wagamama inspired Katsu Curry", 
								"chicken curry japanese", 
								"#h1##h2###h3[!this is a link]this is the main content",
								"<h1></h1><h2></h2><h3></h3><img src='' /><p>content</p>", 
								$date);

		$this->assertEquals("Chicken Katsu Curry", $article->getTitle());
		$this->assertEquals("Wagamama inspired Katsu Curry", $article->getSummary());
		$this->assertEquals("chicken curry japanese", $article->getTags());
		$this->assertEquals("#h1##h2###h3[!this is a link]this is the main content", $article->getContentMd());
		$this->assertEquals("<h1></h1><h2></h2><h3></h3><img src='' /><p>content</p>", $article->getContentHtml());
		$this->assertEquals($dateString, $article->getPubDate());
	}
}