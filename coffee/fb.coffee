window.fbAsyncInit = () ->
	FB.init
		appId: '193140624094915'
		status: true
		cookie: true
		xfbml: true
		oauth: true

includeFacebookSDK = (d,s,id) ->
	fjs = d.getElementsByTagName(s)[0]
	if d.getElementById id then return
	js = d.createElement s
	js.id = id
	js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=193140624094915"
	fjs.parentNode.insertBefore js,fjs
	return

facebookLogout = () ->
	FB.logout (response) ->
		window.location = "logoutRedirect.php"
		return
	return