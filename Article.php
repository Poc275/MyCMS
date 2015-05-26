<?php

class Article
{
	private $mId;
	private $mTitle;
	private $mSummary;
	private $mTags;
	private $mContentMd;
	private $mContentHtml;
	private $mPubDate;
	private $mBannerImagePath;
	private $mDirectionsMd;
	private $mDirectionsHtml;
	private $mUrl;

	private $mNextArticleUrl;
	private $mPreviousArticleUrl;

	private $mComments;


	public function __construct($id, $title, $summary, $tags, $contentMd, $contentHtml, $pubDate, $bannerImagePath, 
		$directionsMd, $directionsHtml, $url)
	{
		$this->mId = $id;
		$this->mTitle = $title;
		$this->mSummary = $summary;
		$this->mTags = $tags;
		$this->mContentMd = $contentMd;
		$this->mContentHtml = $contentHtml;
		$this->mBannerImagePath = $bannerImagePath;
		$this->mDirectionsMd = $directionsMd;
		$this->mDirectionsHtml = $directionsHtml;
		$this->mUrl = $url;

		if ($pubDate instanceof DateTime)
		{
			$this->mPubDate = $pubDate;
		}
		else
		{
			throw new InvalidArgumentException("$pubDate must be of type DateTime");
		}
	}


	public function getId()
	{
		return $this->mId;
	}

	public function getTitle()
	{
		return $this->mTitle;
	}

	public function setTitle($title)
	{
		$this->mTitle = $title;
	}

	public function getSummary()
	{
		return $this->mSummary;
	}

	public function setSummary($summary)
	{
		$this->mSummary = $summary;
	}

	public function getTags()
	{
		return $this->mTags;
	}

	public function setTags($tags)
	{
		$this->mTags = $tags;
	}

	public function getContentMd()
	{
		return $this->mContentMd;
	}

	public function setContentMd($contentMd)
	{
		$this->mContentMd = $contentMd;
	}

	public function getContentHtml()
	{
		return $this->mContentHtml;
	}

	public function getPubDate()
	{
		return $this->mPubDate->format('Y-m-d H:i:s');
	}

	public function getPubDateRssFormat()
	{
		return $this->mPubDate->format(DATE_RSS);
	}

	public function getNicePubDate()
	{
		return $this->mPubDate->format('F jS, Y');
	}

	public function getHtmlPubDate()
	{
		return $this->mPubDate->format('Y-m-d');
	}

	public function getBannerImagePath()
	{
		return $this->mBannerImagePath;
	}

	public function setBannerImagePath($bannerImagePath)
	{
		$this->mBannerImagePath = $bannerImagePath;
	}

	public function getDirectionsMd()
	{
		return $this->mDirectionsMd;
	}

	public function setDirectionsMd($directionsMd)
	{
		$this->mDirectionsMd = $directionsMd;
	}

	public function getDirectionsHtml()
	{
		return $this->mDirectionsHtml;
	}

	public function getUrl()
	{
		return $this->mUrl;
	}

	public function setUrl($url)
	{
		$this->mUrl = $url;
	}

	public function getComments()
	{
		return $this->mComments;
	}

	public function setComments($comments)
	{
		$this->mComments = $comments;
	}

	public function getNextArticleUrl()
	{
		return $this->mNextArticleUrl;
	}

	public function setNextArticleUrl($nextUrl)
	{
		$this->mNextArticleUrl = $nextUrl;
	}

	public function getPreviousArticleUrl()
	{
		return $this->mPreviousArticleUrl;
	}

	public function setPreviousArticleUrl($prevUrl)
	{
		$this->mPreviousArticleUrl = $prevUrl;
	}


	/*
	*  METHODS
	*/
	public function getTagsAsArray()
	{
		return explode(" ", $this->mTags);
	}


	public function __toString()
	{
		return $this->mTitle . "<br />" . $this->mSummary . "<br />" . $this->mTags . "<br />" . 
			$this->mContentMd . "<br />" . $this->mContentHtml . "<br />" . $this->mPubDate->format('Y-m-d H:i:s');
	}
}