<?php

require_once "Database.php";
require_once "RssFeed.php";
require_once("includes/session.php");
validateUser();

$db = new Database;
$rssFeed = new RssFeed;

if ($db->openPrivilegedConnection())
{
	$id = $_POST["id"];
	$deleted = $db->deleteArticle($id);
	$db->closeConnection();
	$rssFeed->updateFeed();

	if ($deleted)
	{
		echo "success";
	}
	else
	{
		echo "failed";
	}
}