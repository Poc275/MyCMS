<?php

require_once "Database.php";

$db = new Database;

if ($db->openReadOnlyConnection())
{
	$offset = $_POST["offset"] * 6;
	$articles = $db->getArticleRange($offset, 6);
	$db->closeConnection();

	$output = array();

	foreach ($articles as $article)
	{
		array_push($output, $article);
	}

	echo json_encode($output);
}