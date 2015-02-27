<?php

class ImagesView
{
	private $templateDir = "templates/";
	private $images = array();

	public function __construct()
	{
	}

	public function render($templateFile)
	{
		if (file_exists($this->templateDir . $templateFile))
		{
			include $this->templateDir . $templateFile;
		}
		else
		{
			throw new Exception("Template " . $templateFile . "does not exist");
		}
	}

	public function getImages()
	{
		$dir = 'img';
		$files = scandir($dir);
		$nFiles = count($files);

		// start indexing at 2 to avoid .. and . UNIX type file listings
		for ($i = 2; $i < $nFiles; $i++)
		{
			array_push($this->images, $files[$i]);
		}

		$this->render("imagesList.phtml");
	}
}