<?php

require_once "Database.php";
require_once "BlogEntryView.php";

if (isset($_GET["url"]))
{
	$url = htmlspecialchars($_GET["url"]);
	$db = new Database;

	if ($db->openReadOnlyConnection())
	{
		$article = $db->getArticleByUrl($url);
		$db->closeConnection();

		if (count($article) == 1)
		{
			$blogEntryView = new BlogEntryView();
			$blogEntryView->article = $article[0];
			$blogEntryView->render("blogEntryView.phtml");
		}
	}
}