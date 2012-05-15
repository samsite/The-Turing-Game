Pages =
	UserList: "ulst"
	UserEditor: "u"
	PlayedQuestionList: "pqlst"
	PlayedQuestionEditor: "q"
	SuggestedQuestionList: "sqlst"
	SuggestedQuestioEditor: "s"
	ResponseList: "rlst"
	ResponseEditor: "r"

CurrentPage = null

hideAllPages = () ->
	$("#userEditor").hide 0
	$("#questionEditor").hide 0
	$("#responseEditor").hide 0
	$("#contentlist").hide 0
	return

userEditorInit = () ->
	$("#ue_name").html "Loading..."
	$("#ue_alias").html ""
	$("#ue_id").html ""
	$("#ue_banned").html ""
	$("#ue_admin").html ""
	$("#ue_joined").html ""

	$("#userEditor").show 0
	return

questionEditorInit = () ->
	$("#qe_question").html "Loading..."
	$("#qe_id").html ""
	$("#qe_uid").html ""
	$("#qe_ranking").html ""
	$("#qe_date").html ""
	
	$("#questionEditor").show 0
	return

responseEditorInit = () ->
	$("#re_response").html "Loading..."
	$("#re_id").html ""
	$("#re_qid").html ""
	$("#re_uid").html ""
	$("#re_honest").html ""
	$("#re_date").html ""
	$("#re_votes").html ""
	$("#re_rating").html ""

	$("#responseEditor").show 0
	return

selectUser = (id) ->
	setPage Pages.UserEditor

	query =
		"id": id
	
	$.ajax
		url: "ajax/userInformation.php"
		type: "POST"
		data: query
		dataType: "json"
	.success (data) ->
		$("#ue_name").html data.facebookName
		$("#ue_alias").html data.alias
		$("#ue_id").html data.userID
		$("#ue_banned").html data.banned
		$("#ue_admin").html data.adminPrivileges
		$("#ue_joined").html data.joined
		
		if data.banned is "0"
			$("#ue_a_ban").html "Ban User"
		else
			$("#ue_a_ban").html "Unban User"
		
		if data.adminPrivileges is "0"
			$("#ue_a_admin").html "Make Admin"
		else
			$("#ue_a_admin").html "Remove Admin"
		
		return
	.fail () ->
		alert "Failed to retrieve user information. Please try again."
		return
	
	return

selectQuestion = (id, suggested) ->
	if suggested is true
		setPage Pages.SuggestedQuestionEditor
		$("#qe_a_play").show 0
	else
		setPage Pages.PlayedQuestionEditor
		$("#qe_a_play").hide 0
	
	query =
		"id": id
		"suggested": suggested
	
	$.ajax
		url: "ajax/questionInformation.php"
		type: "POST"
		data: query
		dataType: "json"
	.success (data) ->
		$("#qe_question").html data.questionText + " Pose as a " + data.poseAs + "."
		$("#qe_id").html data.questionID
		$("#qe_uid").html "<a href='#' onclick='selectUser(" + data.userID + ");'>" + data.userID + "</a>"
		$("#qe_ranking").html data.ranking
		$("#qe_date").html data.datePosted
		
		return
	.fail () ->
		alert "Failed to retrieve user information. Please try again."
		return
	
	return
	
selectPlayedQuestion = (id) ->
	selectQuestion id, false
	return

selectSuggestedQuestion = (id) ->
	selectQuestion id, true
	return

selectResponse = (id) ->
	setPage Pages.ResponseEditor
	
	query =
		"id": id
	
	$.ajax
		url: "ajax/responseInformation.php"
		type: "POST"
		data: query
		dataType: "json"
	.success (data) ->
		$("#re_response").html data.answerText
		$("#re_id").html data.responseID
		$("#re_qid").html "<a href='#' onclick='selectPlayedQuestion(" + data.questionID + ");'>" + data.questionID + "</a>"
		$("#re_uid").html "<a href='#' onclick='selectUser(" + data.userID + ");'>" + data.userID + "</a>"
		$("#re_honest").html data.honest
		$("#re_date").html data.timeStamp
		$("#re_votes").html data.numVotes
		$("#re_rating").html data.avgRating
		
		return
	.fail () ->
		alert "Failed to retrieve user information. Please try again."
		return
	
	return

toggleBan = () ->
	id = $("#ue_id").html()
	query =
		"id": id

	$.ajax
		url: "ajax/banUser.php"
		type: "POST"
		data: query
	.success (data) ->
		selectUser id
		return
	.fail () ->
		alert "Failed to toggle user ban. Please try again."
		return
	return

toggleAdmin = () ->
	id = $("#ue_id").html()
	query =
		"id": id

	$.ajax
		url: "ajax/makeAdmin.php"
		type: "POST"
		data: query
	.success (data) ->
		selectUser id
		return
	.fail () ->
		alert "Failed to toggle user admin privileges. Please try again."
		return
	return

playQuestion = () ->
	id = $("#qe_id").html()
	query =
		"id": id
		"suggested": true

	$.ajax
		url: "ajax/selectQuestion.php"
		type: "POST"
		data: query
	.success (data) ->
		selectPlayedQuestion data
		return
	.fail () ->
		alert "Failed to toggle user admin privileges. Please try again."
		return
	return

deleteResponse = () ->
	id = $("#re_id").html()
	query =
		"id": id

	$.ajax
		url: "ajax/deleteResponse.php"
		type: "POST"
		data: query
	.success (data) ->
		setPage Pages.ResponseList
		return
	.fail () ->
		alert "Failed to delete response. Please try again."
		return
	return
	
doSearch = (text, datatype, count) ->
	query =
		"searchString": text
		"searchFor": datatype
		"resultsPerPage": count
	
	$.ajax
		url: "ajax/adminSearch.php"
		type: "POST"
		data: query
	.success (data) ->
		$("#listresults").html data
		return
	.fail () ->
		alert "Search failed. Please try again."
		return
	
	return

search = () ->
	datatype = ""
	switch CurrentPage
		when Pages.UserList
			datatype = "Users"
		when Pages.PlayedQuestionList
			datatype = "Selected Questions"
		when Pages.SuggestedQuestionList
			datatype = "Suggested Questions"
		when Pages.ResponseList
			datatype = "Flagged Responses"
	
	searchString = $("#listfilter").val()
	doSearch(searchString, datatype, 100)
	return

setListFilterText = (title) ->
	$("#listfiltertitle").html title
	return

setListResultCount = (count) ->
	if count > -1
		$("#listresultstitle").html "Results ("+count+")"
	else
		$("#listresultstitle").html ""
	return

resetListResults = () ->
	$("#listresults").html "<br/><br/><br/><br/><br/><br/>"
	return
	
listInit = (title) ->
	$("#contentlist").show 0
	setListFilterText "Find "+title
	setListResultCount -1
	resetListResults()
	return

setPage = (pg) ->
	CurrentPage = pg
	
	hideAllPages()
	
	switch pg
		when Pages.UserList
			listInit "Users"
		when Pages.UserEditor
			userEditorInit()
		when Pages.PlayedQuestionList
			listInit "Played Questions"
		when Pages.PlayedQuestionEditor
			questionEditorInit()
		when Pages.SuggestedQuestionList
			listInit "Suggested Questions"
		when Pages.SuggestedQuestionEditor
			questionEditorInit()
		when Pages.ResponseList
			listInit "Responses"
		when Pages.ResponseEditor
			responseEditorInit()
		else return
	
	$("li").removeClass "adminselected"
	switch pg
		when Pages.UserList, Pages.UserEditor
			$("#a_userpg").addClass "adminselected"
		when Pages.PlayedQuestionList, Pages.PlayedQuestionEditor
			$("#a_playedpg").addClass "adminselected"
		when Pages.SuggestedQuestionList, Pages.SuggestedQuestionEditor
			$("#a_suggestpg").addClass "adminselected"
		when Pages.ResponseList, Pages.ResponseEditor
			$("#a_responsepg").addClass "adminselected"
		else return
	
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
	$("#suggestpg").click () ->
		window.location = "suggest.php"
		return
	
	$("#a_userpg").click () ->
		setPage Pages.UserList
		return
	$("#a_playedpg").click () ->
		setPage Pages.PlayedQuestionList
		return
	$("#a_suggestpg").click () ->
		setPage Pages.SuggestedQuestionList
		return
	$("#a_responsepg").click () ->
		setPage Pages.ResponseList
		return
	
	$("#listsearch").click search
	
	$("#userEditor").hide 0
	$("#questionEditor").hide 0
	$("#responseEditor").hide 0
	
	$("#ue_return").click () ->
		setPage Pages.UserList
		return
	$("#qe_return").click () ->
		if CurrentPage is Pages.PlayedQuestionEditor
			setPage Pages.PlayedQuestionList
		else
			setPage Pages.SuggestedQuestionList
		return
	$("#re_return").click () ->
		setPage Pages.ResponseList
		return
	
	$("#ue_a_ban").click toggleBan
	$("#ue_a_admin").click toggleAdmin
	
	$("#qe_a_play").click playQuestion
	
	$("#re_a_delete").click deleteResponse
	
	setPage Pages.UserList
	
	return