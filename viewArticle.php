<?php

require "Database.php";
require "BlogEntryView.php";

if (isset($_GET["article"]))
{
	$articleId = htmlspecialchars($_GET["article"]);
	$db = new Database;

	if ($db->openConnection())
	{
		if (count($db->getArticle($articleId)) == 1)
		{
			$article = $db->getArticle($articleId)[0];
			$blogEntryView = new BlogEntryView();
			$blogEntryView->article = $db->getArticle($articleId)[0];
			$blogEntryView->render("blogEntryView.phtml");
		}
		
		$db->closeConnection();
	}
}