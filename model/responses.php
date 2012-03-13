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
require_once ROOT_DIR.'/model/questions.php';
require_once ROOT_DIR.'/model/responseVotes.php';

class Response implements JSONEncodable
{
	private $id;
	private $userId;
	private $questionId;
	private $answerText;
	private $timeStamp;
	private $honest;
	private $numVotes;
	private $rating;
	private $avgRating;
	
	#
	# Used internally to construct a response from either table data, or user
	# specified data.
	#
	private function __construct($_userId, $_questionId, $_answerText, $_honest, $_timeStamp, $_id,
								 $_numVotes, $_rating, $_avgRating)
	{
		$this->id         = $_id;
		$this->userId     = $_userId;
		$this->questionId = $_questionId;
		$this->answerText = $_answerText;
		$this->timeStamp  = $_timeStamp;
		$this->honest     = $_honest;
		$this->numVotes   = $_numVotes;
		$this->rating     = $_rating;
		$this->avgRating  = $_avgRating;
	}
	
	#
	# Returns a new response object created from the specified associative row
	# data.
	#
	public static function FromRowData($row)
	{
		return new Response(
			$row['userID'],
			$row['questionID'],
			$row['answerText'],
			$row['honest'],
			$row['timeStamp'],
			$row['responseID'],
			$row['numVotes'],
			$row['rating'],
			$row['avgRating']
		);
	}
	
	#
	# Returns a new response object created from the user specified data.
	#
	public static function Create($_userId, $_questionId, $_answerText, $_honest, $dbName='default')
	{
		$response = new Response($_userId, $_questionId, $_answerText, $_honest, null, null, null, null, null);
		$response->CommitChanges($dbName);
		
		$id = Database::GetLastInsertID($dbName);
		return self::FetchByID($id, $dbName);
	}
	
	///////////////////////////////////////////////////////////////////////////
	//
	// The following functions modify / return internal object data.
	//
	///////////////////////////////////////////////////////////////////////////
	
	#
	# Returns this response's ID.
	#
	public function GetID()
	{
		return $this->id;
	}
	
	#
	# Returns the ID of the question associated with this response.
	#
	public function GetQuestionID()
	{
		return $this->questionId;
	}
	
	#
	# Sets the associated question by ID.
	#
	public function SetQuestionID($_questionId)
	{
		$this->questionId = $_questionId;
	}
	
	#
	# Gets the response text.
	#
	public function GetAnswerText()
	{
		return $this->answerText;
	}
	
	#
	# Sets the response text.
	#
	public function SetAnswerText($_answerText)
	{
		$this->answerText = $_answerText;
	}
	
	#
	# Returns a boolean indicating if the user answered honestly.
	#
	public function IsHonest()
	{
		return ($this->honest == 1);
	}
	
	#
	# Marks this response as answered honestly.
	#
	public function SetHonest()
	{
		$this->honest = 1;
	}
	
	#
	# Marks this response as answered dishonestly.
	#
	public function SetDishonest()
	{
		$this->honest = 0;
	}
	
	#
	# Gets this response's timestamp.
	#
	public function GetTimeStamp()
	{
		return $this->timeStamp;
	}
	
	#
	# Gets the number of votes on this response.
	#
	public function GetNumVotes()
	{
		return $this->numVotes;
	}
	
	#
	# Gets this response's rating.
	#
	public function GetRating()
	{
		return $this->rating;
	}
	
	#
	# Gets this response's average rating.
	#
	public function GetAverageRating()
	{
		return $this->avgRating;
	}
	
	#
	# Gets this response's associated user ID.
	#
	public function GetUserID()
	{
		return $this->userId;
	}

	#
	# returns a JSON encoded version of the current object
	#
	public function JSONEncode(){
	
		$JSON = array(
			'responseID' => $this->id,
			'userID' => $this->userId,
			'questionID' => $this->questionId,
			'answerText' => $this->answerText,
			'timeStamp' => $this->timeStamp,
			'honest' => $this->honest,
			'numVotes' => $this->numVotes,
			'rating' => $this->rating,
			'avgRating' => $this->avgRating	
		);
		
		return json_encode($JSON);
		
	}
	
	public function SetAverageRating($rating)
	{
		$this->avgRating = $rating;
	}
	///////////////////////////////////////////////////////////////////////////
	//
	// The following functions move object data to the database.
	//
	///////////////////////////////////////////////////////////////////////////
	
	#
	# Removes this response from the database.
	#
	public function Remove($dbName='default')
	{
		$query = "DELETE FROM `response` WHERE `responseID`=?";
		$params = array($this->id);
		Database::SafeQuery($query, $params, $dbName);
	}
	
	#
	# Updates this response's row in the database to reflect the current state
	# of the object. It is inserted if it doesn't exist in the database.
	#
	public function CommitChanges($dbName='default')
	{
		if ($this->id == 0)
		{
			$query = "INSERT INTO `response` ".
					 "(`userID`,`questionID`,`answerText`,`honest`) ".
					 "VALUES (?,?,?,?) ".
					 "ON DUPLICATE KEY UPDATE ".
					 "`userID`=?,`questionID`=?,`answerText`=?,`honest`=?,`rating`=?,`numVotes`=?,`avgRating`=?";
			$params = array($this->userId, $this->questionId, $this->answerText, $this->honest,
							$this->userId, $this->questionId, $this->answerText, $this->honest, $this->rating, $this->numVotes, $this->avgRating);
			Database::SafeQuery($query, $params, $dbName);
		}
		
		else
		{
			$query = "UPDATE `response` ".
			"SET `userID`=?,`questionID`=?,`answerText`=?,`honest`=?,`rating`=?,`numVotes`=?,`avgRating`=? ".
			"WHERE responseID=?";
			$params = array($this->userId, $this->questionId, $this->answerText, $this->honest,
							$this->rating, $this->numVotes, $this->avgRating, $this->id);
			Database::SafeQuery($query, $params, $dbName);
		}
	}
	
	///////////////////////////////////////////////////////////////////////////
	//
	// The following static functions provide response-related database access
	// without requiring a response object.
	//
	///////////////////////////////////////////////////////////////////////////
	
	#
	# Fetches one or more responses from the database. If there is only one
	# response, it returns the response object. If there is more than one,
	# it returns an array of response objects. If there are no responses, it
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
			$responses = array();
			while($rowData = Database::FetchAssoc($allRows))
				array_push($responses, self::FromRowData($rowData));
			
			return $responses;
		}
		
		return null;
	}
	
	#
	# Removes a response by ID.
	#
	public static function RemoveByID($id, $dbName='default')
	{
		$query = "DELETE FROM `response` WHERE `responseID`=?";
		$params = array($id);
		Database::SafeQuery($query, $params, $dbName);
	}
	
	#
	# Looks up a response by ID.
	#
	public static function FetchByID($id, $dbName='default')
	{
		$query = "SELECT * FROM `response` WHERE `responseID`=?";
		$params = array($id);
		
		return self::Fetch($query, $params, $dbName);
	}
	
	#
	# Looks up all responses from a user ID.
	#
	public static function FetchAllByUID($uid, $dbName='default')
	{
		$query = "SELECT * FROM `response` WHERE `userID`=?";
		$params = array($uid);
		
		return self::Fetch($query, $params, $dbName);
	}
	
	#
	# Returns an array of all responses.
	#
	public static function FetchAll($dbName='default')
	{
		$query = "SELECT * FROM `response`";
		
		return self::Fetch($query, false, $dbName);
	}
	
	#
	# Returns an array of all unflagged responses (by given user) for the
	# specified question.
	#
	public static function FetchAllUnflaggedByUIDAndQID($uid, $qid, $dbName='default')
	{
		$query = "SELECT * FROM `response` WHERE `questionID`=? ".
			"AND `responseID` NOT IN(SELECT `contentID` FROM `flags` WHERE `userID`=? AND `type`='response')";
		$params = array($qid, $uid);
		
		return self::Fetch($query, $params, $dbName);
	}
	
	#
	# Returns an array of all responses for a specified question.
	#
	public static function FetchAllByQuestionID($qid, $dbName='default')
	{
		$query = "SELECT * FROM `response` WHERE `questionID`=?";
		$params = array($qid);
		
		return self::Fetch($query, $params, $dbName);
	}
	
	#
	# Looks up a response by QID and UID.
	#
	public static function FetchByQIDAndUID($qid, $uid, $dbName='default')
	{
		$query = "SELECT * FROM `response` WHERE `questionID`=? AND `userID`=?";
		$params = array($qid, $uid);
		
		return self::Fetch($query, $params, $dbName);
	}
	
	#
	# Gets all responses a user hasn't voted on. Returns an array of response objects.
	# This query will ignore the user's own responses, as we dont want them voting on their own.
	#
	public static function FetchUnvotedForQuestion($qid, $uid, $dbName='default')
	{
		$query = "SELECT * FROM `response` WHERE `userID`!=? " .
					"AND `questionID`=? " .
					"AND `responseID` NOT IN(SELECT `responseID` FROM `response_vote` WHERE `userID`=?) " .
					"AND `responseID` NOT IN(SELECT `contentID` FROM `flags` WHERE `userID`=? AND `type`='response')";
		$params = array($uid, $qid, $uid, $uid);
		
		return self::Fetch($query, $params, $dbName);
	}
	
	#
	# Returns a number of responses to the current question starting at a given offset.
	#
	public static function FetchNAtOffsetForQuestion($qid, $numResponses, $offset, $dbName='default')
	{
		$query = "SELECT * FROM `response` WHERE `questionID`=? LIMIT ?, ?";
		$params = array($qid, $offset, $numResponses);
		
		return self::Fetch($query, $params, $dbName);
	}
	
	#
	# Calculates response ratings for all responses.
	#
	public static function CalculateResponseScores()
	{
		$QoTD = Question::FetchQOTD();
		
		if($QoTD == null)
			return;
		
		$responses = Response::FetchAllByQuestionID($QoTD->GetID());
		for($i = 0; $i < count($responses); $i++)
		{
			$votes = ResponseVote::FetchByResponseID($responses[$i]->GetID());
			$runningTotal = 0;
			
			if(count($votes) <= 0) //don't want to divide by zero
				continue;
			
			else if (count($votes) == 1)
			{
				$runningTotal += $votes->GetRating();
			}
			else
			{
				for($j = 0; $j < count($votes); $j++)
				{
					$runningTotal += $votes[$j]->GetRating();
				}
			}
			
			$newRating = ($runningTotal + 0.0) / count($votes);
			$responses[$i]->SetAverageRating($newRating);
			$responses[$i]->CommitChanges();
		}
	}
	
	public static function FindFlaggedResponses($str, $dbName='default')
	{
		$escapedStr = Database::Escape($str);
		$query = "SELECT DISTINCT * FROM `response` WHERE " .
				 "`answerText` LIKE '%${escapedStr}%' AND ".
				 "responseID IN(SELECT DISTINCT `contentID` FROM `flags` WHERE `type`='response')";
		
		return self::Fetch($query, false, $dbName);
	}
}

?>