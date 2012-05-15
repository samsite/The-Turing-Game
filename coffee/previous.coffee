flagResponse = (id, flagOn) ->
	flagData =
		"contentId": id
		"type": "response"
		"on": flagOn
	
	$.ajax
		url: "ajax/flagContent.php"
		type: 'POST'
		data: flagData
	.fail () ->
		alert "Failed to modify content flag. Please try again."
		setResponseFlagged(!flagOn);
		return
	
	return

setResponseFlagged = (id, flagOn) ->
	if flagOn is true
		$('#f_'+id).addClass 'flagged'
		$('#f_'+id).attr 'src', 'images/flag.png'
		$('#c_'+id).hide 'fast'
		$('#u_'+id).html "'s answer was marked as inappropriate."
	else
		$('#f_'+id).removeClass 'flagged'
		$('#f_'+id).attr 'src', 'images/flag_gray.png'
		$('#c_'+id).show 'fast'
		$('#u_'+id).html " said:"
	
	flagResponse id, flagOn
	return

toggleResponseFlag = (id) ->
	setResponseFlagged id, !($('#f_'+id).hasClass 'flagged')
	return

hideAllQs = () ->
	$(".qtag").hide 0
	return

toggleQ = (id) ->
	responseBox = $ "#q" + id + "_r"
	answerBox = $ "#q" + id + "_a"
	img = $ "#expand" + id
	if ((responseBox.hasClass "qVis") is true) or ((answerBox.hasClass "qVis") is true)
		responseBox.hide "fast"
		answerBox.hide "fast"
		responseBox.removeClass "qVis"
		answerBox.removeClass "qVis"
		img.attr 'src', 'images/pqexpand.png'
	else
		responseBox.show "fast"
		answerBox.show "fast"
		responseBox.addClass "qVis"
		answerBox.addClass "qVis"
		img.attr 'src', 'images/pqcollapse.png'
	return

$(document).ready () ->
	commonInit()
	
	$("#indexpg").click () ->
		window.location = "index.php"
		return
	$("#aboutpg").click () ->
		window.location = "about.php"
		return
	$("#suggestpg").click () ->
		window.location = "suggest.php"
		return
	
	FB.XFBML.parse element for element in $ ".answerlike"
	
	hideAllQs()
	
	return