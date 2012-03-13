var honestPopupName = "honestAnswerPopup";
var honestBackPopupName = "honestAnswerPopupBack";

function qofdayinit()
{
	$(".answerlike").each(function(index,element) {
		FB.XFBML.parse(element);
	});
	
	$("#answerbutton").click(function(){
		//centering with css
		centerPopup(honestPopupName, honestBackPopupName);
		//load popup
		loadPopup(honestPopupName, honestBackPopupName);
	});
	//Click out event!
	$("#" + honestBackPopupName).click(closeHonestPopup);
	$('#answertrue').click(answertrue);
	$('#answerfalse').click(answerfalse);
	$('#answerCloseButton').click(closeHonestPopup);
}

function closeHonestPopup()
{
	disablePopup(honestPopupName, honestBackPopupName);
}

function answertrue()
{
	submitAnswer(true);
	closeHonestPopup();
}

function answerfalse()
{
	submitAnswer(false);
	closeHonestPopup();
}

function submitAnswer(isHonest)
{
	var answer = {
		"answerText": $('#answerText').val(),
		"honest": isHonest
	};

	$.ajax({ url: "ajax/answerQuestion.php",
				type: 'POST',
				data: answer })
		.success(function()
		{
			loadNext();
		})
		.fail(function(jqXHR, textStatus)
		{
			alert( "Request failed: " + textStatus );
		});
}

function vote(response)
{
	var responseVote = {
		"response": response,
		"rating": $('#v_'+response).val()
	};
	
	$('#s_'+response).show(0);
	
	$.ajax({ url: "ajax/voteResponse.php",
				type: 'POST',
				data: responseVote })
		.success(function()
		{
			$('#s_'+response).hide(0);
			$('#a_'+response).hide('fast');
			$('#a_'+response).removeClass('answerTag');
			
			if($('.answerTag').length == 0)
				$('#answermsg').show('fast');
		})
		.fail(function(jqXHR, textStatus)
		{
			alert( "Request failed: " + textStatus );
		});
}

function loadNext()
{
	$('#content').fadeOut('fast', function(){
		$.ajax({ url: "qofday.php",
			 type: 'GET' })
			.success(function(data)
			{
				$('#content').html(data);
				$('#content').fadeIn();
			})
			.fail(function(jqXHR, textStatus)
			{
				alert( "Request failed: " + textStatus );
			});
	});
}
