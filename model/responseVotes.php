<?php

/////////////////////////////////////////////////////////////////////////
// Team Backpack: Sam Brown, Henry Dooley, Gaurav Mathur, Kyle Stafford
// CS 4911 Senior Design for Amy Bruckman
// The Turing Game
/////////////////////////////////////////////////////////////////////////

//Includes
require_once 'include.php';

require_once ROOT_DIR.'/model/database.php';
require_once ROOT_DIR.'/model/jsonEncodable.php';

class ResponseVote implements JSONEncodable
{
	private $responseId;
	private $userId;
	private $rating;
	
	#
	# Used internally to construct a response vote from either table data, or user
	# specified data.
	#
	private function __construct($_responseId, $_userId, $_rating)
	{
		$this->responseId = $_responseId;
		$this->userId = $_userId;
		$this->rating = $_rating;
	}
	
	#
	# Returns a new response vote object created from the specified associative row
	# data.
	#
	public static function FromRowData($row)
	{
		return new ResponseVote(
			$row['responseID'],
			$row['userID'],
			$row['rating']
		);
	}
	
	#
	# Returns a new response object created from the user specified data.
	#
	public static function Create($_responseId, $_userId, $_rating)
	{
		$responseVote = new ResponseVote($_responseId, $_userId, $_rating);
		$responseVote->CommitChanges();
		
		return $responseVote;
	}
	
	///////////////////////////////////////////////////////////////////////////
	//
	// The following functions modify / return internal object data.
	//
	///////////////////////////////////////////////////////////////////////////
	
	public function GetResponseID()
	{
		return $this->responseId;
	}
	
	public function SetResponseID($_responseId)
	{
		$this->responseId = $_responseId;
	}
	
	public function GetQuestionID()
	{
		return $this->questionId;
	}
	
	public function SetQuestionID($_questionId)
	{
		 $this->questionId = $_questionId;
	}
	
	public function GetRating()
	{
		return $this->rating;
	}
	
	public function SetRating($_rating)
	{
		$this->rating = $_rating;
	}
	
	public function JSONEncode(){
	
		$JSON = array(
			'responseID' => $this->responseId,
			'userID' => $this->userId,
			'rating' => $this->rating	
		);
		
		return json_encode($JSON);
	}

	///////////////////////////////////////////////////////////////////////////
	//
	// The following functions move object data to the database.
	//
	///////////////////////////////////////////////////////////////////////////
	
	public function Remove($dbName='default')
	{
		$query = "DELETE FROM `response_vote` WHERE `responseID`=? AND `userID`=?";
		$params = array($this->responseId, $this->userId);
		Database::SafeQuery($query, $params, $dbName);
	}
	
	public function CommitChanges($dbName='default')
	{
		//TODO: FIX THIS
		$query = "INSERT INTO `response_vote` (`responseID`,`userID`,`rating`) VALUES (?,?,?)"; 
		$params = array($this->responseId, $this->userId, $this->rating);
		Database::SafeQuery($query, $params, $dbName);
	}
	
	///////////////////////////////////////////////////////////////////////////
	//
	// The following static functions provide response-related database access
	// without requiring a response object.
	// 
	///////////////////////////////////////////////////////////////////////////
	
	#
	# Fetches one or more response votes from the database. If there is only one
	# response vote, it returns the response vote object. If there is more than one,
	# it returns an array of response vote objects. If there are no response votes, it
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
			$responseVotes = array();
			while($rowData = Database::FetchAssoc($allRows))
				array_push($responseVotes, self::FromRowData($rowData));
			
			return $responseVotes;
		}
		
		return null;
	}
	
	#
	# Returns a boolean indicating whether or not a user has voted on a particular response.
	#
	public static function HasUserVotedOnResponse($userId, $responseId, $dbName='default')
	{
		$query = "SELECT * FROM response_vote WHERE userID=? AND responseID=?";
		$params = array($userID, $responseID);
		
		return self::Fetch($query, $params, $dbName) != null;
	}
	
	#
	# Looks up a response vote by ID.
	#
	public static function FetchResponseVote($responseId, $userId, $dbName='default')
	{
		$query = "SELECT * FROM `response_vote` WHERE `responseID`=? AND `userID`=?";
		$params = array($responseId, $userId);
		
		return self::Fetch($query, $params, $dbName);
	}
	
	#
	# Returns an array of all response votes by a user.
	#
	public static function FetchAllByUserID($userId, $dbName='default')
	{
		$query = "SELECT * FROM `response_vote` WHERE `userID`=?";
		$params = array($userId);
		
		return self::Fetch($query, $params, $dbName);
	}
	
	#
	# Returns an array of all response votes by response ID.
	#
	public static function FetchByResponseID($responseId, $dbName='default')
	{
		$query = "SELECT * FROM `response_vote` WHERE `responseID`=?";
		$params = array($responseId);
		
		return self::Fetch($query, $params, $dbName);
	}
}

?>