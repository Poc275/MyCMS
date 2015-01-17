<?php
// TODO - Check user is validated

require "Database.php";
//include "Article.php";
require "includes/Parsedown.php";

$db = new Database;

if ($db->openConnection())
{
	$parsedown = new Parsedown();
	$contentHtml = $parsedown->text($_POST["contentMd"]);
	$date = new DateTime();

	$article = new Article($_POST["title"], $_POST["summary"], $_POST["tags"], $_POST["contentMd"], 
		$contentHtml, $date);

	// validate input?
	// echo $article->toString();

	// TODO - INSERT INTO DB


	$db->closeConnection();
}
else
{
	
}