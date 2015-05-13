<?php

require "Database.php";
require_once "Comment.php";

if (isset($_POST["author"]) && isset($_POST["comment"]) && isset($_POST["article"]))
{
	// AJAX request
	$db = new Database;

	if ($db->openRestrictedConnection())
	{
		$date = new DateTime();
		$comment = new Comment(0, $_POST["article"], $_POST["author"], $_POST["comment"], $date);

		if ($db->addArticleComment($comment))
		{
			echo "Thankyou for your comment";
		}
		else
		{
			echo "Apologies, an error has occurred. <a href='mailto:poc275@gmail.com?Subject=A%20Food%20Odyssey'>Report</a>";
		}

		$db->closeConnection();
	}
}