<?php

require "Database.php";
require_once "Comment.php";

if (isset($_POST["author"]) && isset($_POST["comment"]) && isset($_POST["article"]))
{
	// AJAX request
	// echo $_POST["author"] . " " . $_POST["comment"] . " " . $_POST["article"];
	$db = new Database;

	if ($db->openConnection())
	{
		$date = new DateTime();
		$comment = new Comment(0, $_POST["article"], $_POST["author"], $_POST["comment"], $date);

		if ($db->addArticleComment($comment))
		{
			echo "Comment added";
		}
		else
		{
			echo "Comment not added";
		}

		$db->closeConnection();
	}
}