<?php
// TODO - Check user is validated

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

	$article = new Article($_POST["title"], $_POST["summary"], $_POST["tags"], $_POST["contentMd"], 
		$contentHtml, $date);

	if ($db->addArticle($article))
	{
		$success = true;
	}

	$db->closeConnection();
}

header("Location: index.php?success=$success");