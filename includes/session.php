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
        header("Location: login.html");
    }

    if (isAdmin() !== "admin")
    {
        header("Location: login.html");
    }
}

function isAdmin()
{
	return $_SESSION["role"];
}