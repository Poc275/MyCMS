<?php

require_once "Database.php";
require_once "HomePageView.php";

$db = new Database;

if ($db->openReadOnlyConnection())
{
	$offset = $_POST["offset"] * 6;
	$articles = $db->getArticleRange($offset, 6);
	$db->closeConnection();

	$output = array();

	foreach ($articles as $article)
	{
		$articleArray = array('articleId'=>$article->getId(),
	  							'articleTitle'=>$article->getTitle(), 
		  						'articleSummary'=>$article->getSummary(),
		  						'articleTags'=>$article->getTags(),
		  						'articleBannerImage'=>$article->getBannerImagePath(),
		  						'articleUrl'=>$article->getUrl());

		array_push($output, $articleArray);
	}

	echo json_encode($output);
}