accountMsg     = "#accountMsg"
aliasText      = "#aliastext"
username       = "#username"

accountPopup = null
logoutPopup = null

startLogout = () ->
	logoutPopup.show()
	facebookLogout()
	return

logoutFinished = () ->
	logoutPopup.hide()
	window.location = "index.php"
	return

closeAccountPage = () ->
	hideAccountMsg()
	accountPopup.hide()
	return

openAccountPage = () ->
	$.ajax
		url: "ajax/fetchSettings.php"
		type: "GET"
		dataType: "json"
	.success (data) ->
		name = data
		$(aliasText).val data
		return
	.fail () ->
		alert "Failed to retrieve user settings."
		return
	
	accountPopup.show()
	return

showAccountMsg = (msg) ->
	$(accountMsg).html msg + "<br /><br />"
	$(accountMsg).show "fast"
	return

hideAccountMsg = () ->
	$(accountMsg).hide "fast"
	return

saveUserSettings = () ->
	if $(aliasText).val() is name
		return
	
	showAccountMsg "Saving settings..."
	
	settings =
		"alias": $(aliasText).val()
	
	$.ajax
		url: "ajax/updateSettings.php"
		type: "POST"
		data: settings
		dataType: "json"
	.success (data) ->
		if data[1] is true
			closeAccountPage()
			$(username).html data[0]
		else
			showAccountMsg "Alias already taken. Please select another."
		return
	.fail () ->
		alert "Failed to save user settings. Please try again."
		return
	return

commonInit = () ->
	$(".popup").hide 0
	
	FB.XFBML.parse element for element in $ ".answerlike"

	$("#logoutLink").click startLogout
	$("#accountLink").click openAccountPage
	$("#savesettings").click saveUserSettings
	
	accountPopup = new Popup "#account",500,100
	logoutPopup = new Popup "#logout",500,65
	
	return