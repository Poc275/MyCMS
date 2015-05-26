<?php
require(dirname(__FILE__)."/../Article.php");
require(dirname(__FILE__)."/../Comment.php");

class ArticleTest extends PHPUnit_Framework_TestCase
{
	public function testArticleConstructor()
	{
		$date = new DateTime();
		$article = new Article(1, "Title", "Summary", "Tags", "ContentMd", "ContentHtml", $date, 
			"banner-image-filename", "DirectionsMd", "DirectionsHtml", "title");

		$this->assertInstanceOf('Article', $article);
	}

	/**
	* @expectedException InvalidArgumentException
	*/
	public function testExceptionIsRaisedForInvalidConstructorArguments()
	{
		new Article(1, "Title", "Summary", "Tags", "ContentMd", "ContentHtml", "now", 
			"banner-image-filename", "DirectionsMd", "DirectionsHtml", "title");
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
								"<ol><li>Step 1</li></ol>",
								"chicken-katsu-curry");

		$this->assertEquals("Chicken Katsu Curry", $article->getTitle());
		$this->assertEquals("Wagamama inspired Katsu Curry", $article->getSummary());
		$this->assertEquals("chicken curry japanese", $article->getTags());
		$this->assertEquals("#h1##h2###h3[!this is a link]this is the main content", $article->getContentMd());
		$this->assertEquals("<h1></h1><h2></h2><h3></h3><img src='' /><p>content</p>", $article->getContentHtml());
		$this->assertEquals($dateString, $article->getPubDate());
		$this->assertEquals("1.   Step 1", $article->getDirectionsMd());
		$this->assertEquals("<ol><li>Step 1</li></ol>", $article->getDirectionsHtml());
		$this->assertEquals("chicken-katsu-curry", $article->getUrl());
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
								"<ol><li>Step 1</li></ol>",
								"chicken-katsu-curry");


		array_push($comments, new Comment(1, 10, "Steve", "Nice recipe!", $date));
		array_push($comments, new Comment(2, 10, "Paul", "Agree!", $date));
		$article->setComments($comments);

		$article->setNextArticleUrl("next-article-url");
		$article->setPreviousArticleUrl("prev-article-url");

		$this->assertInternalType('array', $article->getComments());
		$this->assertEquals(2, count($article->getComments()));

		$returnedComments = $article->getComments();
		$firstComment = $returnedComments[0];
		$this->assertInstanceOf('Comment', $firstComment);

		$this->assertEquals("next-article-url", $article->getNextArticleUrl());
		$this->assertEquals("prev-article-url", $article->getPreviousArticleUrl());

		$article->setTitle("Wagamama Chicken Katsu Curry");
		$article->setSummary("Wagamama inspired Katsu Curry is easier than you think");
		$article->setTags("Japanese chicken curry Wagamama");
		$article->setContentMd("#h1##h2###h3[!this is a link]this is the main blog entry");
		$article->setBannerImagePath("new-banner-image-filename");
		$article->setDirectionsMd("1.   Step 12.   Step 2");
		$article->setUrl("wagamama-chicken-katsu-curry");

		$this->assertEquals("Wagamama Chicken Katsu Curry", $article->getTitle());
		$this->assertEquals("Wagamama inspired Katsu Curry is easier than you think", $article->getSummary());
		$this->assertEquals("Japanese chicken curry Wagamama", $article->getTags());
		$this->assertEquals("#h1##h2###h3[!this is a link]this is the main blog entry", $article->getContentMd());
		$this->assertEquals("new-banner-image-filename", $article->getBannerImagePath());
		$this->assertEquals("1.   Step 12.   Step 2", $article->getDirectionsMd());
		$this->assertEquals("wagamama-chicken-katsu-curry", $article->getUrl());
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
								"<ol><li>Step 1</li></ol>",
								"chicken-katsu-curry");

		$tagsAsArray = $article->getTagsAsArray();

		$this->assertInternalType('array', $tagsAsArray);
		$this->assertCount(3, $tagsAsArray);
		$this->assertEquals("chicken", $tagsAsArray[0]);
		$this->assertEquals("curry", $tagsAsArray[1]);
		$this->assertEquals("japanese", $tagsAsArray[2]);
	}

}