<?php

require "Database.php";
require "ArticleView.php";

include "includes/header.html";

$db = new Database;

if ($db->openConnection())
{
	$articleView = new ArticleView();
	$articleView->articles = $db->getArticles();
	$articleView->render("articlesTable.phtml");

	$db->closeConnection();
}
else
{

}

include "includes/footer.html";