

function admininit()
{
	$("#search").click(function(){
		doQuery();
	});
	
	$("#Results").hide();
}

function doQuery()
{

	var query = {
		"searchString": $('#searchString').val(),
		"searchFor": $('#searchFor').val(),
		"resultsPerPage": $('#resultsPerPage').val()
	};

	$.ajax({ url: "ajax/adminSearch.php",
				type: 'POST',
				data: query })
		.success(function(data)
		{
			$('#Results').html(data);
			$('#Results').show('fast');
		})
		.fail(function(jqXHR, textStatus)
		{
			alert( "Request failed: " + textStatus );
		});
}

function makeAdmin(id, on)
{
	var query = {
		"userid": id,
		"on": on
	};
	
	$.ajax({ url: "ajax/makeAdmin.php",
				type: 'POST',
				data: query })
		.success(function(data)
		{
			alert("User privileges changed successfully.");
		})
		.fail(function(jqXHR, textStatus)
		{
			alert( "Request failed: " + textStatus );
		});
}

function showUserResponses(id)
{
	var query = {
		"userid": id,
		"resultsPerPage": $('#resultsPerPage').val()
	};
	
	$.ajax({ url: "ajax/fetchUserResponses.php",
				type: 'POST',
				data: query })
		.success(function(data)
		{
			$('#Results').html(data);
			$('#Results').show('fast');
		})
		.fail(function(jqXHR, textStatus)
		{
			alert( "Request failed: " + textStatus );
		});
}

function selectQuestion(id, isSuggested)
{
	var params = {
		"qid": id,
		"suggested": isSuggested
	};
	
	$.ajax({ url: "ajax/selectQuestion.php",
				type: 'POST',
				data: params })
		.success(function(data)
		{
			alert('New question selected successfully.');
		})
		.fail(function(jqXHR, textStatus)
		{
			alert( "Request failed: " + textStatus );
		});
}

function deleteResponse(responseID)
{
	var params = {
		"responseID": responseID
	};
	
	$.ajax({ url: "ajax/deleteResponse.php",
				type: 'POST',
				data: params })
		.success(function(data)
		{
			alert('Response deleted successfully.');
		})
		.fail(function(jqXHR, textStatus)
		{
			alert( "Request failed: " + textStatus );
		});
	
	
	
}

