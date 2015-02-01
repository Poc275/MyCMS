<?php
session_start();

function loggedOn()
{
    return isset($_SESSION["username"]);
}

function validateUser()
{
    if (!loggedOn())
    {
        header("Location: login.php");
    }

    if (isAdmin() !== "admin")
    {
        header("Location: login.php");
    }
}

function isAdmin()
{
	return $_SESSION["role"];
}