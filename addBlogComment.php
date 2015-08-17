<?php

require_once "Database.php";
require_once "Comment.php";

if (isset($_POST["author"]) && isset($_POST["comment"]) && isset($_POST["article"]))
{
	// AJAX request
	$db = new Database;

	if ($db->openRestrictedConnection())
	{
		$gravatarHash = getGravatarHash($_POST["email"]);
		$date = new DateTime();
		$comment = new Comment(0, $_POST["article"], $_POST["author"], $gravatarHash, $_POST["comment"], $date);

		if ($db->addArticleComment($comment))
		{
			echo "Thankyou for your comment";
		}
		else
		{
			echo "Apologies, an error has occurred. <a href='mailto:poc275@gmail.com?Subject=A%20Food%20Odyssey%20comment%20error'>Report</a>";
		}

		$db->closeConnection();
	}

}


function getGravatarHash($email)
{
	$emailHash = "";

	if (isset($email) && !(empty($email)))
	{
		// validate
		if (filter_var($email, FILTER_VALIDATE_EMAIL))
		{
			$emailHash = md5(strtolower(trim($email)));
		}
	}

	return $emailHash;
}