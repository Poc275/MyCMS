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

	private $mNextArticleId;
	private $mPreviousArticleId;

	private $mComments;


	public function __construct($id, $title, $summary, $tags, $contentMd, $contentHtml, $pubDate, $bannerImagePath, 
		$directionsMd, $directionsHtml)
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

	public function getSummary()
	{
		return $this->mSummary;
	}

	public function getTags()
	{
		return $this->mTags;
	}

	public function getContentMd()
	{
		return $this->mContentMd;
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

	public function getDirectionsMd()
	{
		return $this->mDirectionsMd;
	}

	public function getDirectionsHtml()
	{
		return $this->mDirectionsHtml;
	}

	public function getComments()
	{
		return $this->mComments;
	}

	public function setComments($comments)
	{
		$this->mComments = $comments;
	}

	public function getNextArticleId()
	{
		return $this->mNextArticleId;
	}

	public function setNextArticleId($id)
	{
		$this->mNextArticleId = $id;
	}

	public function getPreviousArticleId()
	{
		return $this->mPreviousArticleId;
	}

	public function setPreviousArticleId($id)
	{
		$this->mPreviousArticleId = $id;
	}

	public function __toString()
	{
		return $this->mTitle . "<br />" . $this->mSummary . "<br />" . $this->mTags . "<br />" . 
			$this->mContentMd . "<br />" . $this->mContentHtml . "<br />" . $this->mPubDate->format('Y-m-d H:i:s');
	}
}