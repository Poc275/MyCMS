<?php

require_once("session.php");
require "Database.php";

$db = new Database;

if ($db->openConnection())
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