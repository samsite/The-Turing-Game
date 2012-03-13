<?php

/////////////////////////////////////////////////////////////////////////
// Team Backpack: Sam Brown, Henry Dooley, Gaurav Mathur, Kyle Stafford
// CS 4911 Senior Design for Amy Bruckman
// The Turing Game
/////////////////////////////////////////////////////////////////////////

class MySQLDatabase
{
	public $host;
	public $database;
	public $username;
	public $password;
	public $name;
	
	private $connection;

	#
	# Constructs the MySQLDatabase object with the specified
	# server settings.
	#
	public function __construct($settings, $dbName)
	{
		$this->host       = $settings['host'];
		$this->database   = $settings['database'];
		$this->username   = $settings['username'];
		$this->password   = $settings['password'];
		$this->name = $dbName;
		$this->connection = null;
	}
	
	#
	# Retrieves a connection to the database only if one hasn't
	# already been established (lazy method).
	#
	private function GetConnection()
	{
		if($this->connection == null)
		{
			// Connect to the database
			$this->connection = mysql_connect($this->host,
											  $this->username,
											  $this->password);
			
			if(!$this->connection)
				die("ERROR: FAILED TO CONNECT TO DATABASE");
			
			// Select the specified database
			mysql_select_db($this->database, $this->connection);
		}
		
		return $this->connection;
	}

	#
	# Performs a safe, properly escaped query.
	#
	public function SafeQuery($query, $params=false)
	{
		$sql_query = $query;
	
		// A connection must be established with the server before
		// we can call mysql_real_escape_string.
		$this->GetConnection();
		
		if ($params)
		{
			// Escape all parameters
			foreach ($params as &$v)
				$v = mysql_real_escape_string($v);
	
			// Replace all ? with %s, then with values
			$sql_query = vsprintf( str_replace("?","'%s'",$query), $params );
		}
	
		return mysql_query($sql_query, $this->GetConnection());
	}
	
	#
	# Performs an unsafe, non-escaped query.
	#
	public function UnsafeQuery($query)
	{
		return mysql_query($query, $this->GetConnection());
	}
	
	#
	# Escapes a string.
	#
	public function Escape($str)
	{
		$this->GetConnection();
		return mysql_real_escape_string($str);
	}
	
	#
	# Returns a row as an associative array with column names as keys.
	#
	public function FetchAssoc($row)
	{
		return mysql_fetch_assoc($row);
	}
	
	#
	# Returns the ID of the last inserted row.
	#
	public function GetLastInsertID()
	{
		return mysql_insert_id();
	}
	
	#
	# Counts the number of rows in a SQL result.
	#
	public function CountRows($rows)
	{
		return mysql_num_rows($rows);
	}
}

?>