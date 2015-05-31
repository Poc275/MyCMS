<?php

require_once("includes/session.php");
require_once "Database.php";

if (isset($_POST["username"]) && isset($_POST["credentials"]))
{
	$db = new Database;

	if ($db->openReadOnlyConnection())
	{
		$username = $_POST["username"];
		$password = $_POST["credentials"];

		$user = $db->checkUsernameExists($username);

		// TODO - sanitise password input before checking
		if ($user !== null)
		{
			if ($user->checkPassword($password))
			{
				$_SESSION["username"] = $user->getUsername();
				$_SESSION["role"] = $user->getRole();
				header("Location: content.php");
			}
			else
			{
				echo "Invalid username/password combination";
			}
		}
		else
		{
			echo "Invalid username/password combination";
		}

		$db->closeConnection();
	}
}
else
{
	header("Location: index.html");
}
