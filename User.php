<?php

class User
{
	private $mUsername;
	private $mHash;
	private $mSalt;
	private $mRole;

	public function __construct($username, $hash, $salt, $role)
	{
		$this->mUsername = $username;
		$this->mHash = $hash;
		$this->mSalt = $salt;
		$this->mRole = $role;
	}

	public function getUsername()
	{
		return $this->mUsername;
	}

	public function getHash()
	{
		return $this->mHash;
	}

	public function getSalt()
	{
		return $this->mSalt;
	}

	public function getRole()
	{
		return $this->mRole;
	}

	public function checkPassword($password)
	{
		$valid = false;

		// prepend salt to given password and hash
		$saltyPassword = $this->mSalt . $password;
		$hash = hash("sha256", $saltyPassword);

		if (strcmp($hash, $this->mHash) === 0)
		{
			$valid = true;
		}

		return $valid;
	}

}