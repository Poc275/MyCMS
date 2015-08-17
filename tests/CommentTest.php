<?php
require(dirname(__FILE__)."/../Comment.php");

class CommentTest extends PHPUnit_Framework_TestCase
{
	public function testCommentConstructor()
	{
		$date = new DateTime();
		$comment = new Comment(1, 4, "Fred", "14f944f92cmls92", "Nice recipe!", $date);

		$this->assertInstanceOf('Comment', $comment);
	}

	public function testCommentConstructorWithNonIntegerIds()
	{
		$date = new DateTime();
		$comment = new Comment("One", "Four", "Fred", "14f944f92cmls92", "Nice recipe!", $date);

		$this->assertInstanceOf('Comment', $comment);
	}

	/**
	* @expectedException InvalidArgumentException
	*/
	public function testExceptionIsRaisedForInvalidConstructorArguments()
	{
		new Comment(1, 4, "Fred", "14f944f92cmls92", "Nice recipe!", "now");
	}

	public function testGetters()
	{
		$date = new DateTime();
		$dateString = $date->format('Y-m-d H:i:s');
		$comment = new Comment(1, 10, "Steve", "14f944f92cmls92", "Nice recipe!", $date);

		$this->assertEquals(1, $comment->getId());
		$this->assertEquals(10, $comment->getArticle());
		$this->assertEquals("Steve", $comment->getName());
		$this->assertEquals("14f944f92cmls92", $comment->getGravatarHash());
		$this->assertEquals("Nice recipe!", $comment->getComment());
		$this->assertEquals($dateString, $comment->getDate());
	}
}