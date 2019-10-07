<?php

class Konekcija
{
	public $conn;
	private static $instance;

	private $username = "root";
	private $password = "";
	private $dbname = "school";
	private $host = "localhost";

	private function __construct()
	{

		$this->conn = new PDO('mysql:host='. $this->host .';dbname='. $this->dbname .'', $this->username, $this->password);
	}


	public static function getInstance()
	{
		if (!self::$instance) {
			self::$instance = new self;
		}
		return self::$instance;
	}
}