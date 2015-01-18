<?php
require(dirname(__FILE__)."/../ArticleView.php");

class ArticleViewTest extends PHPUnit_Framework_TestCase
{
	/**
	* @expectedException Exception
	*/
	public function testTemplateFileExistence()
	{
		$articleView = new ArticleView();
		$articleView->render("thistemplatedoesnotexist.xml");
	}
}