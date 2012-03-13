<?php

/////////////////////////////////////////////////////////////////////////
// Team Backpack: Sam Brown, Henry Dooley, Gaurav Mathur, Kyle Stafford
// CS 4911 Senior Design for Amy Bruckman
// The Turing Game
/////////////////////////////////////////////////////////////////////////

//Includes
require_once 'include.php';

require_once ROOT_DIR.'/model/database.php';
require_once ROOT_DIR.'/model/responses.php';
require_once ROOT_DIR.'/model/jsonEncodable.php';

class Question implements JSONEncodable
{
	// These are all private to provide read only access for some vars
	private $id;
	private $userId;
	private $text;
	private $poseAs;
	private $ranking;
	private $date;
	private $isSuggested;
	
	#
	# Used internally to construct a question from table data or user
	# specified data.
	#
	private function __construct($_id, $_userId, $_text, $_poseAs, $_ranking, $_date, $_isSuggested)
	{
		$this->id          = $_id;
		$this->userId      = $_userId;
		$this->text        = $_text;
		$this->poseAs      = $_poseAs;
		$this->ranking     = $_ranking;
		$this->date        = $_date;
		$this->isSuggested = $_isSuggested;
	}
	
	#
	# Returns a new question object from the specified associative row
	# data.
	#
	private static function FromRowData($row, $inSuggestedTable)
	{
		return new Question(
			$row['questionID'],
			$row['userID'],
			$row['questionText'],
			$row['poseAs'],
			$row['ranking'],
			$row['datePosted'],
			$inSuggestedTable
		);
	}
	
	#
	# Returns a new suggested question object from the user specified
	# data.
	#
	public static function CreateSuggested($_userId, $_text, $_poseAs, $dbName='default')
	{
		$q = new Question(-1, $_userId, $_text, $_poseAs, 0, null, true);
		$q->CommitChanges($dbName);
		
		$id = Database::GetLastInsertID($dbName);
		return self::FetchSuggestedByID($id, $dbName);
	}
	
	#
	# Returns a new selected question object from the user specified
	# data. Not sure if this should ever be used since selected questions
	# should only be created by moving from the suggested table to the
	# selected.
	#
	public static function Create($_userId, $_text, $_poseAs, $dbName='default')
	{
		$q = new Question(-1, $_userId, $_text, $_poseAs, 0, null, false);
		$q->CommitChanges($dbName);
		
		$id = Database::GetLastInsertID($dbName);
		return self::FetchSelectedByID($id, $dbName);
	}
	
	public function Copy($toCopy)
	{
		$this->id          = $toCopy->id;
		$this->userId      = $toCopy->userId;
		$this->text        = $toCopy->text;
		$this->poseAs      = $toCopy->poseAs;
		$this->ranking     = $toCopy->ranking;
		$this->date        = $toCopy->date;
		$this->isSuggested = $toCopy->isSuggested;
	}
	
	///////////////////////////////////////////////////////////////////////////
	//
	// The following functions modify / return internal object data.
	//
	///////////////////////////////////////////////////////////////////////////
	
	#
	# Returns this questions ID.
	#
	public function GetID()
	{
		return $this->id;
	}
	
	#
	# Returns the ID of the user who suggested this question.
	#
	public function GetUserID()
	{
		return $this->userId;
	}
	
	#
	# Returns the question text.
	#
	public function GetText()
	{
		return $this->text;
	}
	
	#
	# Returns the text for the "pose as" part of the question.
	#
	public function GetPoseAs()
	{
		return $this->poseAs;
	}
	
	#
	# Returns this questions ranking.
	#
	public function GetRanking()
	{
		return $this->ranking;
	}
	
	#
	# Returns the date the question was suggested / selected.
	#
	public function GetDate()
	{
		return $this->date;
	}
	
	#
	# Returns a boolean indicating if this is a suggested question.
	#
	public function IsSuggested()
	{
		return ($this->isSuggested == true);
	}
	
	#
	# Sets the question text.
	#
	public function SetText($_text)
	{
		$this->text = $_text;
	}
	
	#
	# Sets the text for the "pose as" part of the question.
	#
	public function SetPoseAs($_poseAs)
	{
		$this->poseAs = $_poseAs;
	}
	
	#
	# Sets the question's ranking.
	#
	public function SetRanking($_ranking)
	{
		$this->ranking = $_ranking;
	}
	
	#
	# Gets all responses for this question.
	#
	public function GetResponses($dbName='default')
	{
		return Response::FetchAllByQuestionID($this->id, $dbName);
	}
	
	#
	# Gets all unflagged responses (by this user) for this question.
	#
	public function GetUnflaggedResponsesForUser($user, $dbName='default')
	{
		return Response::FetchAllUnflaggedByUIDAndQID($user->GetID(), $this->id, $dbName);
	}
	
	#
	# Returns if the specified user has answered this question.
	#
	public function HasUserAnswered($user, $dbName='default')
	{
		return $this->GetUserResponse($user, $dbName) != null;
	}
	
	#
	# Gets a user's response to this question. $user is a user object.
	#
	public function GetUserResponse($user, $dbName='default')
	{
		return Response::FetchByQIDAndUID($this->id, $user->GetID(), $dbName);
	}
	
	#
	# Gets all responses a user hasn't voted on. Returns an array of response objects.
	# This query will ignore the user's own responses, as we dont want them voting on their own.
	#
	public function GetUnvotedResponses($user, $dbName='default')
	{
		return Response::FetchUnvotedForQuestion($this->id, $user->GetID(), $dbName);
	}
	
	#
	# Returns a number of responses to the current question starting at a given offset.
	#
	public function GetNResponsesAtOffset($numResponses, $offset, $dbName='default')
	{
		return Response::FetchNAtOffsetForQuestion($this->id, $numResponses, $offset, $dbName);
	}
	
	///////////////////////////////////////////////////////////////////////////
	//
	// The following functions move object data to the database.
	//
	///////////////////////////////////////////////////////////////////////////

	#
	# Removes this question from the database.
	#
	public function Remove($dbName='default')
	{
		$table = self::TableName($this->isSuggested);
		$query = "DELETE FROM `${table}` WHERE `questionID`=?";
		$params = array($this->id);
		Database::SafeQuery($query, $params, $dbName);
	}
	
	#
	# Updates this question in the appropriate table (for suggested or
	# selected). If no question exists in the table, it is inserted.
	#
	public function CommitChanges($dbName='default')
	{
		$table = self::TableName($this->isSuggested);
		$query = "INSERT INTO `${table}` (`userID`,`questionText`,`poseAs`) VALUES (?,?,?) ON DUPLICATE KEY UPDATE `userID`=?,`questionText`=?,`poseAs`=?";
		$params = array($this->userId, $this->text, $this->poseAs, $this->userId, $this->text, $this->poseAs);
		Database::SafeQuery($query, $params, $dbName);
	}
	
	#
	# Selects a suggested question as the question of the day.
	#
	public function SelectForPlay($dbName='default')
	{
		$this->Remove($dbName);
		$this->isSuggested = false;
		$this->id = -1;
		$this->CommitChanges($dbName);
		
		$id = Database::GetLastInsertID($dbName);
		$this->Copy(self::FetchSelectedByID($id, $dbName));
	}
	
	#
	# returns a JSON encoded version of the current object
	#
	public function JSONEncode(){
	
		$JSON = array(
			'questionID' => $this->id,
			'userID' => $this->userId,
			'questionText' => $this->text,
			'poseAs' => $this->poseAs,
			'ranking' => $this->ranking,
			'datePosted' => $this->date,
			'isSuggested' => $this->isSuggested
	
		);
		
		return json_encode($JSON);
		
	}
	
	///////////////////////////////////////////////////////////////////////////
	//
	// The following static functions provide user-related database access
	// without requiring a user object.
	//
	///////////////////////////////////////////////////////////////////////////
	
	#
	# Returns the table name to use for suggested / selected questions.
	#
	private static function TableName($suggested)
	{
		if($suggested)
			return 'suggested_questions';
		
		return 'question'; // This should be renamed
	}
	
	#
	# Returns the question of the day.
	#
	public static function FetchQOTD($dbName='default')
	{
		$query = "SELECT * FROM `question` WHERE `questionID`=(SELECT MAX(`questionID`) FROM `question`)";
		return self::Fetch($query, false, false, $dbName);
	}
	
	#
	# Returns the previous question of the day.
	#
	public static function FetchPreviousQOTD($dbName='default')
	{
		$query = "SELECT * FROM `question` ORDER BY `questionID` DESC LIMIT 1 OFFSET 1";
		return self::Fetch($query, false, false, $dbName);
	}
	
	#
	# Looks up a suggested question by its ID.
	#
	public static function FetchSuggestedByID($id, $dbName='default')
	{
		return self::FetchByID($id, true, $dbName);
	}
	
	#
	# Looks up a selected question by its ID.
	#
	public static function FetchSelectedByID($id, $dbName='default')
	{
		return self::FetchByID($id, false, $dbName);
	}
	
	#
	# Returns an array of all suggested questions.
	#
	public static function FetchAllSuggested($dbName='default')
	{
		return self::Fetch(true, $dbName);
	}
	
	#
	# Returns an array of all selected questions.
	#
	public static function FetchAllSelected($dbName='default')
	{
		return self::Fetch(false, $dbName);
	}
	
	#
	# Removes a suggested question from the database by its ID.
	#
	public static function RemoveSuggestedByID($id, $dbName='default')
	{
		self::RemoveByID($id, true, $dbName);
	}
	
	#
	# Removes a selected question from the database by its ID.
	#
	public static function RemoveSelectedByID($id, $dbName='default')
	{
		self::RemoveByID($id, false, $dbName);
	}
	
	#
	# Searches the selected question table for any questions that contain $str
	# in either the question text or pose as text.
	#
	public static function FindSelected($str, $dbName='default')
	{
		return self::Find($str, false, $dbName);
	}
	
	#
	# Searches the suggested question table for any questions that contain $str
	# in either the question text or pose as text.
	#
	public static function FindSuggested($str, $dbName='default')
	{
		return self::Find($str, true, $dbName);
	}
	
	#
	# Searches the question table for any questions that contain $str in
	# either the question text or pose as text.
	#
	private static function Find($str, $suggested, $dbName='default')
	{
		$table = self::TableName($suggested);
		$escapedStr = Database::Escape($str);
		$query = "SELECT * FROM `${table}` WHERE " .
				 "questionText LIKE '%${escapedStr}%' OR " .
				 "poseAs LIKE '%${escapedStr}%'";
		
		return self::Fetch($query, false, $suggested, $dbName);
	}
	
	///////////////////////////////////////////////////////////////////////////
	//
	// The following static functions are helper methods for the above
	// functions.
	//
	///////////////////////////////////////////////////////////////////////////
	
	#
	# Fetches one or more questions from the database. If there is only one
	# question, it returns the question object. If there is more than one,
	# it returns an array of question objects. If there are no questions, it
	# returns null.
	#
	private static function Fetch($query, $params, $suggested, $dbName='default')
	{
		$allRows = Database::SafeQuery($query, $params, $dbName);
		$rowCount = Database::CountRows($allRows);
		
		if($rowCount == 1)
		{
			$rowData = Database::FetchAssoc($allRows);
			return self::FromRowData($rowData, $suggested);
		}
		else if($rowCount > 1)
		{
			$questions = array();
			while($rowData = Database::FetchAssoc($allRows))
				array_push($questions, self::FromRowData($rowData, $suggested));
			
			return $questions;
		}
		
		return null;
	}
	
	#
	# Removes a question from the database by its ID.
	#
	private static function RemoveByID($id, $suggested, $dbName='default')
	{
		$table = self::TableName($suggested);
		$query = "DELETE FROM `${table}` WHERE `id`=?";
		$params = array($id);
		Database::SafeQuery($query, $params, $dbName);
	}
	
	#
	# Returns all questions of a specified type from the database as an array.
	#
	private static function FetchAll($suggested, $dbName='default')
	{
		$table = self::TableName($suggested);
		$query = "SELECT * FROM `${table}`";
		
		return self::Fetch($query, false, $suggested, $dbName);
	}
	
	#
	# Looks up and returns a question from the database by its ID.
	#
	private static function FetchByID($id, $suggested, $dbName='default')
	{
		$table = self::TableName($suggested);
		$query = "SELECT * FROM `${table}` WHERE `questionID`=?";
		$params = array($id);
		
		return self::Fetch($query, $params, $suggested, $dbName);
	}
}

?>