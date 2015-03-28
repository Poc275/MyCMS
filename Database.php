<?php

require "Article.php";
require "Comment.php";
require "User.php";

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


	/*
	** ARTICLE QUERIES
	*/
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
		$query = "SELECT * FROM articles WHERE id = ?";
		$stmt = mysqli_prepare($this->mConnection, $query);
		mysqli_stmt_bind_param($stmt, 'i', $id);

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

	        // get article comments (if we have an article, URL could be entered incorrectly)
	        if (count($articles) !== 0)
	        {
	        	$articles[0]->setComments($this->getArticleComments($id));
	        }
		}

		return $articles;
	}

	public function getArticleComments($id)
	{
		$comments = array();
		$query = "SELECT * FROM comments WHERE article = ?";
		$stmt = mysqli_prepare($this->mConnection, $query);
		mysqli_stmt_bind_param($stmt, 'i', $id);

		if (!mysqli_stmt_execute($stmt))
		{
			throw new Exception("Database query failed");
		}
		else
		{
			mysqli_stmt_bind_result($stmt, $idCol, $articleCol, $nameCol, $commentCol, $dateCol);

	        while (mysqli_stmt_fetch($stmt))
	        {
	        	$comment = new Comment($idCol, $articleCol, $nameCol, $commentCol, 
	        		date_create_from_format('Y-m-d H:i:s', $dateCol));
	        	array_push($comments, $comment);
	        }

	        mysqli_stmt_close($stmt);
		}

		return $comments;
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

	public function addArticleComment($comment)
	{
		$created = false;

		$insert = "INSERT INTO comments (article, name, comment, date) 
					VALUES (?, ?, ?, ?)";

		$stmt = mysqli_prepare($this->mConnection, $insert);
		mysqli_stmt_bind_param($stmt, 'ssss', $article, $name, $commentText, $date);

		$article = $comment->getArticle();
		$name = $comment->getName();
		$commentText = $comment->getComment();
		$date = $comment->getDate();

		if (mysqli_stmt_execute($stmt))
		{
			$created = true;
		}

		mysqli_stmt_close($stmt);

		return $created;
	}


	/*
	** USER CREDENTIALS QUERIES
	*/
	public function checkUsernameExists($username)
	{
		$user = null;

		$query = "SELECT * FROM users WHERE username = ?";

		$stmt = mysqli_prepare($this->mConnection, $query);
        mysqli_stmt_bind_param($stmt, 's', $username);

        if (!mysqli_stmt_execute($stmt))
        {
            throw new Exception("Database query failed");
        }
        else
        {
            mysqli_stmt_store_result($stmt);

            if (mysqli_stmt_num_rows($stmt) == 1)
			{
            	mysqli_stmt_bind_result($stmt, $username_col, $password_col, $salt_col, $role_col);
            	mysqli_stmt_fetch($stmt);
            	$user = new User($username_col, $password_col, $salt_col, $role_col);
        	}
		}

		mysqli_stmt_close($stmt);

		return $user;
	}
}