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
	        	$directionsMdCol, $directionsHtmlCol, $contentMdCol, $contentHtmlCol, $pubDateCol, $urlCol);

	        while (mysqli_stmt_fetch($stmt))
	        {
	        	$article = new Article($idCol, $titleCol, $summaryCol, $tagsCol, $contentMdCol, 
	        		$contentHtmlCol, date_create_from_format('Y-m-d H:i:s', $pubDateCol), $bannerImagePathCol, 
	        		$directionsMdCol, $directionsHtmlCol, $urlCol);
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
	        	$directionsMdCol, $directionsHtmlCol, $contentMdCol, $contentHtmlCol, $pubDateCol, $urlCol);

	        while (mysqli_stmt_fetch($stmt))
	        {
	        	$article = new Article($idCol, $titleCol, $summaryCol, $tagsCol, $contentMdCol, 
	        		$contentHtmlCol, date_create_from_format('Y-m-d H:i:s', $pubDateCol), $bannerImagePathCol, 
	        		$directionsMdCol, $directionsHtmlCol, $urlCol);
	        	array_push($articles, $article);
	        }

	        mysqli_stmt_close($stmt);

	        // get article comments (if we have an article, URL could be entered incorrectly),
	        // and get next and previous article ids for links to work
	        if (count($articles) !== 0)
	        {
	        	$articles[0]->setComments($this->getArticleComments($id));
	        	$articles[0]->setNextArticleUrl($this->getNextArticleUrl($id));
	        	$articles[0]->setPreviousArticleUrl($this->getPreviousArticleUrl($id));
	        }
		}

		return $articles;
	}


	public function getArticleByUrl($url)
	{
		$articles = array();
		$query = "SELECT * FROM articles WHERE url = ?";
		$stmt = mysqli_prepare($this->mConnection, $query);

		mysqli_stmt_bind_param($stmt, 's', $url);

		if (!mysqli_stmt_execute($stmt))
		{
			throw new Exception("Database query failed");
		}
		else
		{
			mysqli_stmt_bind_result($stmt, $idCol, $titleCol, $summaryCol, $tagsCol, $bannerImagePathCol, 
	        	$directionsMdCol, $directionsHtmlCol, $contentMdCol, $contentHtmlCol, $pubDateCol, $urlCol);

	        while (mysqli_stmt_fetch($stmt))
	        {
	        	$article = new Article($idCol, $titleCol, $summaryCol, $tagsCol, $contentMdCol, 
	        		$contentHtmlCol, date_create_from_format('Y-m-d H:i:s', $pubDateCol), $bannerImagePathCol, 
	        		$directionsMdCol, $directionsHtmlCol, $urlCol);
	        	array_push($articles, $article);
	        }

	        mysqli_stmt_close($stmt);

	        // get article comments (if we have an article, URL could be entered incorrectly),
	        // and get next and previous article ids for links to work
	        if (count($articles) !== 0)
	        {
	        	$articles[0]->setComments($this->getArticleComments($articles[0]->getId()));
	        	$articles[0]->setNextArticleUrl($this->getNextArticleUrl($articles[0]->getId()));
	        	$articles[0]->setPreviousArticleUrl($this->getPreviousArticleUrl($articles[0]->getId()));
	        }
		}

		return $articles;
	}


	public function getNextArticleUrl($id)
	{
		$nextArticleUrl = 0;

		$query = "SELECT url FROM articles WHERE id = (SELECT min(id) FROM articles WHERE id > ?)";
		$stmt = mysqli_prepare($this->mConnection, $query);
		mysqli_stmt_bind_param($stmt, 'i', $id);

		// Do not throw an error just because the links do not work
		if (mysqli_stmt_execute($stmt))
		{
			mysqli_stmt_bind_result($stmt, $urlCol);

			while (mysqli_stmt_fetch($stmt))
			{
				$nextArticleUrl = $urlCol;
			}

			mysqli_stmt_close($stmt);
		}

		// if there isn't a next article, go back to the beginning
		if ($nextArticleUrl === 0)
		{
			$nextArticleUrl = $this->getFirstArticleUrl();
		}

		return $nextArticleUrl;
	}


	public function getPreviousArticleUrl($id)
	{
		$previousArticleUrl = 0;

		$query = "SELECT url FROM articles WHERE id = (SELECT max(id) FROM articles WHERE id < ?)";
		$stmt = mysqli_prepare($this->mConnection, $query);
		mysqli_stmt_bind_param($stmt, 'i', $id);

		// Do not throw an error just because the links do not work
		if (mysqli_stmt_execute($stmt))
		{
			mysqli_stmt_bind_result($stmt, $urlCol);

			while (mysqli_stmt_fetch($stmt))
			{
				$previousArticleUrl = $urlCol;
			}

			mysqli_stmt_close($stmt);
		}

		// if there isn't a previous article, go to the end
		if ($previousArticleUrl === 0)
		{
			$previousArticleUrl = $this->getLastArticleUrl();
		}

		return $previousArticleUrl;
	}


	public function getFirstArticleUrl()
	{
		$firstArticleUrl = 0;

		$query = "SELECT url FROM articles ORDER BY id ASC LIMIT 1";
		$stmt = mysqli_prepare($this->mConnection, $query);

		if (mysqli_stmt_execute($stmt))
		{
			mysqli_stmt_bind_result($stmt, $urlCol);

			while (mysqli_stmt_fetch($stmt))
			{
				$firstArticleUrl = $urlCol;
			}

			mysqli_stmt_close($stmt);
		}

		return $firstArticleUrl;
	}


	public function getLastArticleUrl()
	{
		$lastArticleUrl = 0;

		$query = "SELECT url FROM articles ORDER BY id DESC LIMIT 1";
		$stmt = mysqli_prepare($this->mConnection, $query);

		if (mysqli_stmt_execute($stmt))
		{
			mysqli_stmt_bind_result($stmt, $urlCol);

			while (mysqli_stmt_fetch($stmt))
			{
				$lastArticleUrl = $urlCol;
			}

			mysqli_stmt_close($stmt);
		}

		return $lastArticleUrl;
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
			content_md, content_html, pubDate, url) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

		$stmt = mysqli_prepare($this->mConnection, $insert);
		mysqli_stmt_bind_param($stmt, 'ssssssssss', $title, $summary, $tags, $bannerImagePath, 
			$directionsMd, $directionsHtml, $contentMd, $contentHtml, $pubDate, $url);

		$title = $article->getTitle();
		$summary = $article->getSummary();
		$tags = $article->getTags();
		$bannerImagePath = $article->getBannerImagePath();
		$directionsMd = $article->getDirectionsMd();
		$directionsHtml = $article->getDirectionsHtml();
		$contentMd = $article->getContentMd();
		$contentHtml = $article->getContentHtml();
		$pubDate = $article->getPubDate();
		$url = $article->getUrl();

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
			directions_html = ?, content_md = ?, content_html = ?, url = ? WHERE id = ?";

		$stmt = mysqli_prepare($this->mConnection, $query);
		mysqli_stmt_bind_param($stmt, 'sssssssssi', $title, $summary, $tags, $bannerImagePath, $directionsMd,
			$directionsHtml, $contentMd, $contentHtml, $url, $id);

		$title = $article->getTitle();
		$summary = $article->getSummary();
		$tags = $article->getTags();
		$bannerImagePath = $article->getBannerImagePath();
		$directionsMd = $article->getDirectionsMd();
		$directionsHtml = $article->getDirectionsHtml();
		$contentMd = $article->getContentMd();
		$contentHtml = $article->getContentHtml();
		$url = $article->getUrl();
		$id = $article->getId();

		if (mysqli_stmt_execute($stmt))
		{
			$update = true;
		}

		mysqli_stmt_close($stmt);

		return $update;
	}


	public function deleteArticle($id)
	{
		$deleted = false;

		$query = "DELETE FROM articles WHERE id = ?";

		$stmt = mysqli_prepare($this->mConnection, $query);
		mysqli_stmt_bind_param($stmt, 'i', $id);

		if (mysqli_stmt_execute($stmt))
		{
			$deleted = true;
		}

		mysqli_stmt_close($stmt);

		return $deleted;
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