<?php

require_once "Database.php";

$db = new Database;

if ($db->openReadOnlyConnection())
{
	$offsetPost = $_POST["offset"];
	$offset = 0;

	// first retrieval fetches 5 articles, due to layouts
	// the next fetch needs to get 6 articles for a smooth appearance
	if ($offsetPost == 1)
	{
		$offset = 5;
	}
	else
	{
		$offset = $offsetPost * 6;
	}

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