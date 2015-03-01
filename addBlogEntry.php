<?php
require "Database.php";
require "includes/Parsedown.php";
require_once("includes/session.php");
validateUser();

$db = new Database;
$success = false;

if ($db->openConnection())
{
	$parsedown = new Parsedown();
	$contentHtml = $parsedown->text($_POST["contentMd"]);
	$date = new DateTime();

	// TODO - does Article need 2 constructors? 1 with an id field for when the DB has generated it, 
	// and 1 without when it is first created and before it is submitted to the DB.
	// PHP doesn't allow multiple constructors so must use static functions
	$article = new Article(0, $_POST["title"], $_POST["summary"], $_POST["tags"], $_POST["contentMd"], 
		$contentHtml, $date);

	if ($db->addArticle($article))
	{
		$success = true;
	}

	$db->closeConnection();
}

header("Location: content.php");