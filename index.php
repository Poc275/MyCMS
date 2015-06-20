<?php

require_once "Database.php";
require_once "HomePageView.php";

$db = new Database;

if ($db->openReadOnlyConnection())
{
	// TODO - use url to paginate
	$articles = $db->getArticleRange(0, 5);
	$db->closeConnection();

	$homePageView = new HomePageView();
	$homePageView->articles = $articles;
	$homePageView->render("homePageView.phtml");
}