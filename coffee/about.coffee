$(document).ready () ->
	commonInit()
	
	$("#indexpg").click () ->
		window.location = "index.php"
		return
	$("#suggestpg").click () ->
		window.location = "suggest.php"
		return
	$("#previouspg").click () ->
		window.location = "previous.php"
		return
	
	return