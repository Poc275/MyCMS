<?php
require(dirname(__FILE__)."/../Article.php");
require(dirname(__FILE__)."/../Comment.php");

class ArticleTest extends PHPUnit_Framework_TestCase
{
	public function testArticleConstructor()
	{
		$date = new DateTime();
		$article = new Article(1, "Title", "Summary", "Tags", "ContentMd", "ContentHtml", $date, 
			"banner-image-filename", "DirectionsMd", "DirectionsHtml");

		$this->assertInstanceOf('Article', $article);
	}

	/**
	* @expectedException InvalidArgumentException
	*/
	public function testExceptionIsRaisedForInvalidConstructorArguments()
	{
		new Article(1, "Title", "Summary", "Tags", "ContentMd", "ContentHtml", "now", 
			"banner-image-filename", "DirectionsMd", "DirectionsHtml");
	}

	public function testGetters()
	{
		$date = new DateTime();
		$dateString = $date->format('Y-m-d H:i:s');
		$article = new Article(1,
								"Chicken Katsu Curry",
								"Wagamama inspired Katsu Curry", 
								"chicken curry japanese", 
								"#h1##h2###h3[!this is a link]this is the main content",
								"<h1></h1><h2></h2><h3></h3><img src='' /><p>content</p>", 
								$date,
								"banner-image-filename",
								"1.   Step 1",
								"<ol><li>Step 1</li></ol>");

		$this->assertEquals("Chicken Katsu Curry", $article->getTitle());
		$this->assertEquals("Wagamama inspired Katsu Curry", $article->getSummary());
		$this->assertEquals("chicken curry japanese", $article->getTags());
		$this->assertEquals("#h1##h2###h3[!this is a link]this is the main content", $article->getContentMd());
		$this->assertEquals("<h1></h1><h2></h2><h3></h3><img src='' /><p>content</p>", $article->getContentHtml());
		$this->assertEquals($dateString, $article->getPubDate());
		$this->assertEquals("1.   Step 1", $article->getDirectionsMd());
		$this->assertEquals("<ol><li>Step 1</li></ol>", $article->getDirectionsHtml());
	}

	public function testSetters()
	{
		$comments = array();
		$date = new DateTime();
		$dateString = $date->format('Y-m-d H:i:s');
		$article = new Article(1,
								"Chicken Katsu Curry",
								"Wagamama inspired Katsu Curry", 
								"chicken curry japanese", 
								"#h1##h2###h3[!this is a link]this is the main content",
								"<h1></h1><h2></h2><h3></h3><img src='' /><p>content</p>", 
								$date,
								"banner-image-filename",
								"1.   Step 1",
								"<ol><li>Step 1</li></ol>");


		array_push($comments, new Comment(1, 10, "Steve", "Nice recipe!", $date));
		array_push($comments, new Comment(2, 10, "Paul", "Agree!", $date));
		$article->setComments($comments);

		$article->setNextArticleId(3);
		$article->setPreviousArticleId(7);


		$this->assertInternalType('array', $article->getComments());
		$this->assertEquals(2, count($article->getComments()));

		$returnedComments = $article->getComments();
		$firstComment = $returnedComments[0];
		$this->assertInstanceOf('Comment', $firstComment);

		$this->assertEquals(3, $article->getNextArticleId());
		$this->assertEquals(7, $article->getPreviousArticleId());
	}

	public function testTagsAsArray()
	{
		$date = new DateTime();
		$dateString = $date->format('Y-m-d H:i:s');
		$article = new Article(1,
								"Chicken Katsu Curry",
								"Wagamama inspired Katsu Curry", 
								"chicken curry japanese", 
								"#h1##h2###h3[!this is a link]this is the main content",
								"<h1></h1><h2></h2><h3></h3><img src='' /><p>content</p>", 
								$date,
								"banner-image-filename",
								"1.   Step 1",
								"<ol><li>Step 1</li></ol>");

		$tagsAsArray = $article->getTagsAsArray();

		$this->assertInternalType('array', $tagsAsArray);
		$this->assertCount(3, $tagsAsArray);
		$this->assertEquals("chicken", $tagsAsArray[0]);
		$this->assertEquals("curry", $tagsAsArray[1]);
		$this->assertEquals("japanese", $tagsAsArray[2]);
	}
}