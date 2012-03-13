<?php

/////////////////////////////////////////////////////////////////////////
// Team Backpack: Sam Brown, Henry Dooley, Gaurav Mathur, Kyle Stafford
// CS 4911 Senior Design for Amy Bruckman
// The Turing Game
/////////////////////////////////////////////////////////////////////////

//Includes
require_once 'include.php';

require_once ROOT_DIR.'/model/database.php';

class Flag
{
	private $userId;
	private $type;
	private $contentId;
	private $id;
	
	private function __construct($_userId, $_contentId, $_type, $_id)
	{
		$this->userId    = $_userId;
		$this->contentId = $_contentId;
		$this->type      = $_type;
		$this->id        = $_id;
	}
	
	private static function FromRowData($row)
	{
		return new Flag(
			$row['userID'],
			$row['contentID'],
			$row['type'],
			$row['id']
		);
	}
	
	public static function Create($userId, $contentId, $type, $dbName='default')
	{
		$flag = new Flag($userId, $contentId, $type, -1);
		$flag->Insert($dbName);
		
		$id = Database::GetLastInsertID($dbName);
		return self::FetchByID($id, $dbName);
	}
	
	public function Remove($dbName='default')
	{
		$query = "DELETE FROM `flags` WHERE `id`=?";
		$params = array($this->id);
		Database::SafeQuery($query, $params, $dbName);
	}
	
	public function GetUserID()
	{
		return $this->userId;
	}
	
	public function GetContentID()
	{
		return $this->contentId;
	}
	
	public function GetID()
	{
		return $this->id;
	}
	
	public function GetType()
	{
		return $this->type;
	}
	
	#
	# Inserts this flag object into the database.
	#
	# This is not called 'CommitChanges' since you cannot change any flag data.
	# This is also why it is private.
	#
	private function Insert($dbName='default')
	{
		$flag = self::FetchByContentAndUser($this->userId, $this->contentId, $this->type, $dbName);
		if($flag == null)
		{
			$query = "INSERT INTO `flags` (`userID`,`contentID`,`type`) VALUES (?,?,?)";
			$params = array($this->userId, $this->contentId, $this->type,
							$this->userId, $this->contentId, $this->type);
			
			Database::SafeQuery($query, $params, $dbName);
		}
	}
	
	#
	# Fetches one or more flags from the database. If there is only one
	# flag, it returns the flag object. If there is more than one,
	# it returns an array of flag objects. If there are no flags, it
	# returns null.
	#
	private static function Fetch($query, $params, $dbName='default')
	{
		$allRows = Database::SafeQuery($query, $params, $dbName);
		$rowCount = Database::CountRows($allRows);
		
		if($rowCount == 1)
		{
			$rowData = Database::FetchAssoc($allRows);
			return self::FromRowData($rowData);
		}
		else if($rowCount > 1)
		{
			$flags = array();
			while($rowData = Database::FetchAssoc($allRows))
				array_push($flags, self::FromRowData($rowData));
			
			return $flags;
		}
		
		return null;
	}
	
	public static function IsValidFlagType($type)
	{
		switch($type)
		{
			case 'question':
			case 'response':
				return true;
				break;
			default:
				return false;
				break;
		}
	}
	
	public static function FetchByID($id, $dbName='default')
	{
		$query = "SELECT * FROM `flags` WHERE `id`=?";
		$params = array($id);
		return self::Fetch($query, $params, $dbName);
	}
	
	public static function FetchByContentAndUser($userId, $contentId, $type, $dbName='default')
	{
		$query = "SELECT * FROM `flags` WHERE `userID`=? AND `contentID`=? AND `type`=?";
		$params = array($userId, $contentId, $type);
		return self::Fetch($query, $params, $dbName);
	}
	
	public static function FetchAllResponseFlags($dbName='default')
	{
		return self::FetchAllForType('response', $dbName);
	}
	
	public static function FetchAllQuestionFlags($dbName='default')
	{
		return self::FetchAllForType('question', $dbName);
	}
	
	public static function FetchAllForQuestion($qid, $dbName='default')
	{
		$query = "SELECT * FROM `flags` WHERE `type`=? AND `contentID`=?";
		$params = array('question', $qid);
		return self::Fetch($query, $params, $dbName);
	}
	
	public static function FetchAllForResponse($rid, $dbName='default')
	{
		$query = "SELECT * FROM `flags` WHERE `type`=? AND `contentID`=?";
		$params = array('response', $rid);
		return self::Fetch($query, $params, $dbName);
	}
	
	private static function FetchAllForType($type, $dbName='default')
	{
		$query = "SELECT * FROM `flags` WHERE `type`=?";
		$params = array($type);
		return self::Fetch($query, $params, $dbName);
	}
}

?>