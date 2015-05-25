<?php

require "Database.php";
require_once("includes/session.php");
validateUser();

$db = new Database;

if ($db->openReadOnlyConnection())
{
	$id = $_POST["id"];
	$article = $db->getArticle($id);

	$db->closeConnection();

	$response = array('articleTitle'=>$article[0]->getTitle(), 
					  'articleSummary'=>$article[0]->getSummary(),
					  'articleTags'=>$article[0]->getTags(),
					  'articleBannerImage'=>$article[0]->getBannerImagePath(),
					  'articleInstructions'=>$article[0]->getDirectionsMd(),
					  'articleContent'=>$article[0]->getContentMd());
	
	echo json_encode($response);
}