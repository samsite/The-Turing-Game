submitQuestion = () ->
	if $("#questionText").val() is "" or $("#answerAs").val() is ""
		alert "Please complete form before submitting."
		return
	
	answer =
		"questionText": $("#questionText").val()
		"answerAs": $("#answerAs").val()
	
	$.ajax
		url: "ajax/suggestQuestion.php"
		type: "POST"
		data: answer
	.success (data) ->
		$("#qform").hide "fast"
		$("#thanksMsg").show "fast"
		return
	.fail () ->
		alert "Failed to submit question! Please try again."
		return
	
	return

resetForm = () ->
	$("#questionText").val ""
	$("#answerAs").val ""
	
	$("#thanksMsg").hide "fast"
	$("#qform").show "fast"
	
	return

$(document).ready () ->
	commonInit()
	
	$("#indexpg").click () ->
		window.location = "index.php"
		return
	$("#aboutpg").click () ->
		window.location = "about.php"
		return
	$("#previouspg").click () ->
		window.location = "previous.php"
		return
	
	$("#submitButton").click submitQuestion
	$("#submitNewQ").click resetForm
	
	return