<?php

require "Database.php";
require_once("includes/session.php");
validateUser();

$db = new Database;

if ($db->openPrivilegedConnection())
{
	$id = $_POST["id"];

	$deleted = $db->deleteArticle($id);

	$db->closeConnection();

	if ($deleted)
	{
		echo "success";
	}
	else
	{
		echo "failed";
	}
}