<?php

/////////////////////////////////////////////////////////////////////////
// Team Backpack: Sam Brown, Henry Dooley, Gaurav Mathur, Kyle Stafford
// CS 4911 Senior Design for Amy Bruckman
// The Turing Game
/////////////////////////////////////////////////////////////////////////

//Includes
require_once 'include.php';

require_once ROOT_DIR.'/model/questions.php';
require_once ROOT_DIR.'/model/responses.php';
require_once ROOT_DIR.'/model/responseVotes.php';
require_once ROOT_DIR.'/model/users.php';

class Admin
{
	private static function ToPaginatedList($list, $resultsPerPage, $page)
	{
		$newList = null;
		if(count($list) == 1)
			$newList = array($list);
		else if(count($list) > 1)
			$newList = $list;
		
		if($newList == null || count($newList) <= $resultsPerPage * $page)
			return null;
		
		$toReturn = array();
		for($i = 0; $i < $resultsPerPage && $page * $resultsPerPage + $i < count($newList); $i++)
			array_push($toReturn, $newList[$i]);
		
		return $toReturn;
	}

	public static function GetSuggestedQuestions($resultsPerPage, $page)
	{
		return self::ToPaginatedList(Question::FetchAllSuggested(), $resultsPerPage, $page);
	}
	
	public static function FindUsers($search, $resultsPerPage, $page)
	{
		return self::ToPaginatedList(User::Find($search), $resultsPerPage, $page);
	}
	
	public static function FindSelectedQuestions($search, $resultsPerPage, $page)
	{
		return self::ToPaginatedList(Question::FindSelected($search), $resultsPerPage, $page);
	}
	
	public static function FindSuggestedQuestions($search, $resultsPerPage, $page)
	{
		return self::ToPaginatedList(Question::FindSuggested($search), $resultsPerPage, $page);
	}
	
	#
	# returns a list of responses corresponding to a given page/page size.
	# NOTE: page is zero-indexed.
	#
	public static function FindResponses($search, $resultsPerPage, $page)
	{
		return self::ToPaginatedList(Response::FindResponses($search), $resultsPerPage, $page); //not sure if this function exists.
	}
	
	public static function GetResponsesByUID($uid, $resultsPerPage, $page)
	{
		return self::ToPaginatedList(Response::FetchAllByUID($uid), $resultsPerPage, $page);
	}
	
	public static function FindFlaggedResponses($search, $resultsPerPage, $page)
	{
		return self::ToPaginatedList(Response::FindFlaggedResponses($search), $resultsPerPage, $page);
	}
	
	public static function SetQotD($qid, $suggested)
	{
		$question = null;
		if($suggested)
			$question = Question::FetchSuggestedByID($qid);
		else
			$question = Question::FetchSelectedByID($qid);
		
		if($question != null)
		{
			Response::CalculateResponseScores();
			return $question->SelectForPlay();
		}
		
		return -1;
	}
}


?>