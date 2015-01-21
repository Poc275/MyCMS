<?php

require "Article.php";

class Database
{
	private $mConnection;

	public function openConnection()
	{
		$result = false;
		$this->mConnection = new mysqli("localhost", "cmsAdmin", "h6tn2uCA5xpDraC8", "cms");

		if ($this->mConnection)
        {
            $result = true;
        }

        return $result;
	}

	public function closeConnection()
	{
		mysqli_close($this->mConnection);
	}

	public function getArticles()
	{
		$articles = array();
		$query = "SELECT * FROM articles";
	    $stmt = mysqli_prepare($this->mConnection, $query);

	    if (!mysqli_stmt_execute($stmt))
	    {
	        throw new Exception("Database query failed");
	    }
	    else
	    {
	        mysqli_stmt_bind_result($stmt, $idCol, $titleCol, $summaryCol, $tagsCol, $contentMdCol, 
	        	$contentHtmlCol, $pubDateCol);

	        while (mysqli_stmt_fetch($stmt))
	        {
	        	$article = new Article($idCol, $titleCol, $summaryCol, $tagsCol, $contentMdCol, 
	        		$contentHtmlCol, date_create_from_format('Y-m-d H:i:s', $pubDateCol));
	        	array_push($articles, $article);
	        }

	        mysqli_stmt_close($stmt);
		}

		return $articles;
	}

	public function getArticle($id)
	{
		$articles = array();
		$query = "SELECT * FROM articles WHERE id = " . $id;
		$stmt = mysqli_prepare($this->mConnection, $query);

		if (!mysqli_stmt_execute($stmt))
		{
			throw new Exception("Database query failed");
		}
		else
		{
			mysqli_stmt_bind_result($stmt, $idCol, $titleCol, $summaryCol, $tagsCol, $contentMdCol, 
	        	$contentHtmlCol, $pubDateCol);

	        while (mysqli_stmt_fetch($stmt))
	        {
	        	$article = new Article($idCol, $titleCol, $summaryCol, $tagsCol, $contentMdCol, 
	        		$contentHtmlCol, date_create_from_format('Y-m-d H:i:s', $pubDateCol));
	        	array_push($articles, $article);
	        }

	        mysqli_stmt_close($stmt);
		}

		return $articles;
	}

	public function addArticle($article)
	{
		$created = false;

		$insert = "INSERT INTO articles (title, summary, tags, content_md, content_html, pubDate) 
					VALUES (?, ?, ?, ?, ?, ?)";

		$stmt = mysqli_prepare($this->mConnection, $insert);
		mysqli_stmt_bind_param($stmt, 'ssssss', $title, $summary, $tags, $contentMd, $contentHtml, $pubDate);

		$title = $article->getTitle();
		$summary = $article->getSummary();
		$tags = $article->getTags();
		$contentMd = $article->getContentMd();
		$contentHtml = $article->getContentHtml();
		$pubDate = $article->getPubDate();

		if (mysqli_stmt_execute($stmt))
		{
			$created = true;
		}

		mysqli_stmt_close($stmt);

		return $created;
	}
}