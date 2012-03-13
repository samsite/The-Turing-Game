<?php

/////////////////////////////////////////////////////////////////////////
// Team Backpack: Sam Brown, Henry Dooley, Gaurav Mathur, Kyle Stafford
// CS 4911 Senior Design for Amy Bruckman
// The Turing Game
/////////////////////////////////////////////////////////////////////////

//Includes
require_once 'include.php';

require_once ROOT_DIR.'/model/mysql.php';

class Database
{
	private static $internalDBs = array();
	
	#
	# Returns a database instance of the given name (should match
	# the entry in config.php. If only one database is being used,
	# no parameter needs to be specified.
	#
	private static function Instance($dbName='default')
	{
		if(!isset(self::$internalDBs[$dbName]))
		{
			include 'config.php';
		
			$srvType = $servers[$dbName]['type'];
			switch($srvType)
			{
				case "mysql":
					self::$internalDBs[$dbName] = new MySQLDatabase($servers[$dbName], $dbName);
					break;
				default:
					die("Unsupported database type: " . $srvType);
			}
		}
		
		return self::$internalDBs[$dbName];
	}

	#
	# Performs a safe, properly escaped query.
	#
	public static function SafeQuery($query, $params=false, $dbName='default')
	{
		return self::Instance($dbName)->SafeQuery($query, $params);
	}
	
	#
	# Performs an unsafe, non-escaped query.
	#
	public static function UnsafeQuery($query, $dbName='default')
	{
		return self::Instance($dbName)->UnsafeQuery($query);
	}
	
	#
	# Escapes a string.
	#
	public static function Escape($str, $dbName='default')
	{
		return self::Instance($dbName)->Escape($str);
	}
	
	#
	# Returns a row as an associative array with column names as keys.
	#
	public static function FetchAssoc($row, $dbName='default')
	{
		return self::Instance($dbName)->FetchAssoc($row);
	}
	
	#
	# Returns the ID of the last inserted row.
	#
	public static function GetLastInsertID($dbName='default')
	{
		return self::Instance($dbName)->GetLastInsertID();
	}
	
	#
	# Counts the number of rows in a SQL result.
	#
	public static function CountRows($rows, $dbName='default')
	{
		return self::Instance($dbName)->CountRows($rows);
	}
}

?>