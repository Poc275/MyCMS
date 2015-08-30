<?php

require_once "Database.php";
require_once "BlogEntryViewByTag.php";

if (isset($_GET["tag"]))
{
	$tag = htmlspecialchars($_GET["tag"]);
	$db = new Database;

	if ($db->openReadOnlyConnection())
	{
		$articles = $db->getArticlesByTagName($tag);
		$db->closeConnection();

		if (count($articles) != 0)
		{
			$blogEntryViewByTag = new BlogEntryViewByTag();
			$blogEntryViewByTag->articles = $articles;
			$blogEntryViewByTag->render("blogEntryViewByTag.phtml");
		}
	}
}