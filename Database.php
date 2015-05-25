<?php

require "Article.php";
require "Comment.php";
require "User.php";

class Database
{
	private $mConnection;

	public function openPrivilegedConnection()
	{
		$result = false;
		$this->mConnection = new mysqli("localhost", "cmsAdmin", "h6tn2uCA5xpDraC8", "cms");

		if ($this->mConnection)
        {
            $result = true;
        }

        return $result;
	}

	public function openRestrictedConnection()
	{
		$result = false;
		$this->mConnection = new mysqli("localhost", "cmsUser", "t8PhNs62fubWbW6U", "cms");

		if ($this->mConnection)
		{
			$result = true;
		}

		return $result;
	}

	public function OpenReadOnlyConnection()
	{
		$result = false;
		$this->mConnection = new mysqli("localhost", "cmsVisitor", "JxYZTwDxt2LyULpQ", "cms");

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
		$query = "SELECT * FROM articles ORDER BY pubDate DESC";
	    $stmt = mysqli_prepare($this->mConnection, $query);

	    if (!mysqli_stmt_execute($stmt))
	    {
	        throw new Exception("Database query failed");
	    }
	    else
	    {
	        mysqli_stmt_bind_result($stmt, $idCol, $titleCol, $summaryCol, $tagsCol, $bannerImagePathCol, 
	        	$directionsMdCol, $directionsHtmlCol, $contentMdCol, $contentHtmlCol, $pubDateCol);

	        while (mysqli_stmt_fetch($stmt))
	        {
	        	$article = new Article($idCol, $titleCol, $summaryCol, $tagsCol, $contentMdCol, 
	        		$contentHtmlCol, date_create_from_format('Y-m-d H:i:s', $pubDateCol), $bannerImagePathCol, 
	        		$directionsMdCol, $directionsHtmlCol);
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
			mysqli_stmt_bind_result($stmt, $idCol, $titleCol, $summaryCol, $tagsCol, $bannerImagePathCol, 
	        	$directionsMdCol, $directionsHtmlCol, $contentMdCol, $contentHtmlCol, $pubDateCol);

	        while (mysqli_stmt_fetch($stmt))
	        {
	        	$article = new Article($idCol, $titleCol, $summaryCol, $tagsCol, $contentMdCol, 
	        		$contentHtmlCol, date_create_from_format('Y-m-d H:i:s', $pubDateCol), $bannerImagePathCol, 
	        		$directionsMdCol, $directionsHtmlCol);
	        	array_push($articles, $article);
	        }

	        mysqli_stmt_close($stmt);

	        // get article comments (if we have an article, URL could be entered incorrectly),
	        // and get next and previous article ids for links to work
	        if (count($articles) !== 0)
	        {
	        	$articles[0]->setComments($this->getArticleComments($id));
	        	$articles[0]->setNextArticleId($this->getNextArticleId($id));
	        	$articles[0]->setPreviousArticleId($this->getPreviousArticleId($id));
	        }
		}

		return $articles;
	}


	public function getNextArticleId($id)
	{
		$nextArticleId = 0;

		$query = "SELECT id FROM articles WHERE id = (SELECT min(id) FROM articles WHERE id > ?)";
		$stmt = mysqli_prepare($this->mConnection, $query);
		mysqli_stmt_bind_param($stmt, 'i', $id);

		// Do not throw an error just because the links do not work
		if (mysqli_stmt_execute($stmt))
		{
			mysqli_stmt_bind_result($stmt, $idCol);

			while (mysqli_stmt_fetch($stmt))
			{
				$nextArticleId = $idCol;
			}

			mysqli_stmt_close($stmt);
		}

		// if there isn't a next article, go back to the beginning
		if ($nextArticleId === 0)
		{
			$nextArticleId = $this->getFirstArticleId();
		}

		return $nextArticleId;
	}


	public function getPreviousArticleId($id)
	{
		$previousArticleId = 0;

		$query = "SELECT id FROM articles WHERE id = (SELECT max(id) FROM articles WHERE id < ?)";
		$stmt = mysqli_prepare($this->mConnection, $query);
		mysqli_stmt_bind_param($stmt, 'i', $id);

		// Do not throw an error just because the links do not work
		if (mysqli_stmt_execute($stmt))
		{
			mysqli_stmt_bind_result($stmt, $idCol);

			while (mysqli_stmt_fetch($stmt))
			{
				$previousArticleId = $idCol;
			}

			mysqli_stmt_close($stmt);
		}

		// if there isn't a previous article, go to the end
		if ($previousArticleId === 0)
		{
			$previousArticleId = $this->getLastArticleId();
		}

		return $previousArticleId;
	}


	public function getFirstArticleId()
	{
		$firstArticleId = 0;

		$query = "SELECT id FROM articles ORDER BY id ASC LIMIT 1";
		$stmt = mysqli_prepare($this->mConnection, $query);

		if (mysqli_stmt_execute($stmt))
		{
			mysqli_stmt_bind_result($stmt, $idCol);

			while (mysqli_stmt_fetch($stmt))
			{
				$firstArticleId = $idCol;
			}

			mysqli_stmt_close($stmt);
		}

		return $firstArticleId;
	}


	public function getLastArticleId()
	{
		$lastArticleId = 0;

		$query = "SELECT id FROM articles ORDER BY id DESC LIMIT 1";
		$stmt = mysqli_prepare($this->mConnection, $query);

		if (mysqli_stmt_execute($stmt))
		{
			mysqli_stmt_bind_result($stmt, $idCol);

			while (mysqli_stmt_fetch($stmt))
			{
				$lastArticleId = $idCol;
			}

			mysqli_stmt_close($stmt);
		}

		return $lastArticleId;
	}

	public function getArticleIdFromTitle($title)
	{
		$id = 0;

		$query = "SELECT id FROM articles WHERE title = ?";
		$stmt = mysqli_prepare($this->mConnection, $query);
		mysqli_stmt_bind_param($stmt, 's', $title);

		if (!mysqli_stmt_execute($stmt))
		{
			throw new Exception("Database query failed");
		}
		else
		{
			mysqli_stmt_bind_result($stmt, $idCol);

			while (mysqli_stmt_fetch($stmt))
			{
				$id = $idCol;
			}

			mysqli_stmt_close($stmt);
		}

		return $id;
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

		$insert = "INSERT INTO articles (title, summary, tags, banner_image_path, directions_md, directions_html, 
			content_md, content_html, pubDate) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

		$stmt = mysqli_prepare($this->mConnection, $insert);
		mysqli_stmt_bind_param($stmt, 'sssssssss', $title, $summary, $tags, $bannerImagePath, 
			$directionsMd, $directionsHtml, $contentMd, $contentHtml, $pubDate);

		$title = $article->getTitle();
		$summary = $article->getSummary();
		$tags = $article->getTags();
		$bannerImagePath = $article->getBannerImagePath();
		$directionsMd = $article->getDirectionsMd();
		$directionsHtml = $article->getDirectionsHtml();
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


	public function updateArticle($article)
	{
		$update = false;

		$query = "UPDATE articles SET title = ?, summary = ?, tags = ?, banner_image_path = ?, directions_md = ?,
			directions_html = ?, content_md = ?, content_html = ? WHERE id = ?";

		$stmt = mysqli_prepare($this->mConnection, $query);
		mysqli_stmt_bind_param($stmt, 'ssssssssi', $title, $summary, $tags, $bannerImagePath, $directionsMd,
			$directionsHtml, $contentMd, $contentHtml, $id);

		$title = $article->getTitle();
		$summary = $article->getSummary();
		$tags = $article->getTags();
		$bannerImagePath = $article->getBannerImagePath();
		$directionsMd = $article->getDirectionsMd();
		$directionsHtml = $article->getDirectionsHtml();
		$contentMd = $article->getContentMd();
		$contentHtml = $article->getContentHtml();
		$id = $article->getId();

		if (mysqli_stmt_execute($stmt))
		{
			$update = true;
		}

		mysqli_stmt_close($stmt);

		return $update;
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