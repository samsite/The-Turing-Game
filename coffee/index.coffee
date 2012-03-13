respondButton  = "#respondButton"
answerTrue     = "#answertrue"
answerFalse    = "#answerfalse"
answerText     = "#answerText"
votedAllMsg    = "#votedAll"

honestPopup = null
name = null

loadAnswers = () ->
	window.location = "index.php"
	return

submitAnswer = (isHonest) ->
	answer =
		"answerText": $(responseText).val()
		"honest": isHonest
	
	$.ajax
		url: "ajax/answerQuestion.php"
		type: "POST"
		data: answer
	.success () ->
		loadAnswers()
		return
	.fail (jqXHR, textStatus) ->
		alert "Request failed: " + textStatus
		return
	return

vote = (response) ->
	responseRating  = "#v_"+response
	responseSpinner = "#s_"+response
	responseContent = "#a_"+response
	
	responseVote =
		"response": response
		"rating": $(responseRating).slider "option", "value"
	
	# $(responseSpinner).show 0
	
	$.ajax
		url: "ajax/voteResponse.php"
		type: "POST"
		data: responseVote
	.success () ->
		# $(responseSpinner).hide 0
		$(responseContent).hide "fast"
		$(responseContent).removeClass "answerTag"
		
		showVotedAllMsg()
		return
	.fail (jqXHR, textStatus) ->
		alert "Request failed: " + textStatus
		return
	return
	
showVotedAllMsg = () ->
	if $(".answerTag").length is 0
		$(votedAllMsg).show "fast"
	return

$(document).ready () ->
	commonInit()
	
	$(respondButton).click () ->
		honestPopup.show()
		return
	$(answerTrue).click () ->
		submitAnswer true
		honestPopup.hide()
		return
	$(answerFalse).click () ->
		submitAnswer false
		honestPopup.hide()
		return
	
	honestPopup = new Popup "#honest",600,75
	
	$(".slider").slider "min": 0, "max": 10, "value": 5
	
	$("#aboutpg").click () ->
		window.location = "about.php"
		return
	$("#previouspg").click () ->
		window.location = "previous.php"
		return
	$("#suggestpg").click () ->
		window.location = "suggest.php"
		return
		
	showVotedAllMsg()
	
	return