<?php

require_once "Database.php";
require_once "ArticleView.php";
require_once "ImagesView.php";
require_once("includes/session.php");
validateUser();

include "includes/header.html";

$db = new Database;

if ($db->openPrivilegedConnection())
{
	$articleView = new ArticleView();
	$articleView->articles = $db->getArticles();
	$articleView->render("articlesTable.phtml");
	$db->closeConnection();

	$imagesView = new ImagesView();
	$imagesView->getImages();
}
else
{

}

include "includes/footer.html";