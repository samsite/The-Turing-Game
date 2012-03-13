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
	
	return