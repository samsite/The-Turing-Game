class Popup
	constructor: (@name, @width, @height) ->
		fore = @name + "Popup"
		back = fore + "Back"
		close = @name + "Close"

		backHTML = back.slice 1
		if $(back).size() is 0
			$('body').append "<div class='popupBack' id='#{backHTML}' />"

		ref = "ref": this
		
		$(fore).hide 0
		$(fore).css
			"width": @width
			"height": @height
		
		$(back).click ref, (event) ->
			event.data.ref.hide()
			return
		$(close).click ref, (event) ->
			event.data.ref.hide()
			return

		return

	show: () ->
		fore = @name + "Popup"
		back = fore + "Back"

		windowWidth = document.documentElement.clientWidth
		windowHeight = document.documentElement.clientHeight
		popupHeight = $(fore).height()
		popupWidth = $(fore).width()

		$(fore).css
			"position": "absolute"
			"top": windowHeight/2 - popupHeight/2
			"left": windowWidth/2 - popupWidth/2
		$(back).css
			"height": windowHeight

		$(back).css "opacity": "0.7"
		$(back).fadeIn "slow"
		$(fore).fadeIn "slow"

		return

	hide: () ->
		fore = @name + "Popup"
		back = fore + "Back"

		$(back).fadeOut "slow"
		$(fore).fadeOut "slow"

		return