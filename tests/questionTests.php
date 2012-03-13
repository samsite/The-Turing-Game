<?php

include_once '../model/database.php';
include_once '../model/questions.php';

testAccessorSelectedQuestions();
testAccessorSuggestedQuestions();
$question = testCreateQuestion();
testModifyQuestion($question);
testRemoveQuestion();

function testAccessorSelectedQuestions()
{
    echo '<strong>Accessor Method Tests (Selected):</strong><br/><br />';
    $arr = Question::FetchAllSelected();

    for($i = 0; $i < count($arr); $i++)
    {
	    echo $arr[$i]->GetID();
	    echo '<br/>';
	    echo $arr[$i]->GetUserID();
	    echo '<br/>';
	    echo $arr[$i]->GetText();
	    echo '<br/>';
	    echo $arr[$i]->GetPoseAs();
	    echo '<br/>';
	    echo $arr[$i]->GetRanking();
	    echo '<br/>';
	    echo $arr[$i]->GetDate();
	    echo '<br/>';
	    echo $arr[$i]->IsSuggested();
	    echo '<br/>';
	    echo '<br/>';
    }
}



function testAccessorSuggestedQuestions()
{
    echo '<strong>Accessor Method Tests (Suggested):</strong><br/><br />';
    $arr = Question::FetchAllSuggested();

    for($i = 0; $i < count($arr); $i++)
    {
        printQuestion($arr[$i]);
    }
}

function testCreateQuestion()
{
    echo '<strong>Create Tests:</strong><br/><br />';
    $newSuggestedQuestion = Question::CreateSuggested(123, 'Testing Question', 'bot');
    printQuestion($newSuggestedQuestion);
    //Selecting as QofD

    $newSuggestedQuestion->SelectForPlay();
    return $newSuggestedQuestion;
}

function testModifyQuestion($question)
{
    echo '<strong>Modify Tests:</strong><br/><br />';
    $question->SetText('Different Question');
    $question->SetPoseAs('still a bot');
    $question->SetRanking(3);
    printQuestion($question);
}

function testRemoveQuestion()
{
    echo '<strong>Remove Tests:</strong><br/><br />';
    $question = Question::FetchQOTD();
    printQuestion($question);
    $question->Remove();
}

function printQuestion($question)
{
    echo $question->GetID();
    echo '<br/>';
    echo $question->GetUserID();
    echo '<br/>';
    echo $question->GetText();
    echo '<br/>';
    echo $question->GetPoseAs();
    echo '<br/>';
    echo $question->GetRanking();
    echo '<br/>';
    echo $question->GetDate();
	echo '<br/>';
	echo $question->IsSuggested();
    echo '<br/>';
    echo '<br/>';
}
//$newQuestion = Question::
//$newsr = User::Create(123,'This is an alias',false);
/**
echo '<strong>Modify Tests:</strong><br/><br />';

$newUsr->SetAlias('this is a new alias');
$newUsr->Ban();
$newUsr->CommitChanges();

echo '<strong>Remove Tests:</strong><br/><br />';

$newUsr->Remove();
*/
?>

