<?php

require "Database.php";
include "includes/header.html";

$db = new Database;

if ($db->openConnection())
{
	echo "Connected!";

	var_dump($db->getArticles());

	$db->closeConnection();

	$date = new DateTime();
	echo $date->format('Y-m-d H:i:s');
}
else
{
	echo "Not Connected";
}


include "includes/footer.html";