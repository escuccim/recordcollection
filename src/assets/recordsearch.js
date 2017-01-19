function searchOrSort(sort, page){
	var searchTerm = $('#searchTerm').val();
	var searchBy = $('#searchBy').val();
	var urlstring = 'searchTerm='+searchTerm+'&searchBy='+searchBy+'&page='+page+'&sort='+sort;
	
	$.ajax({
		type: 'get',
		url: '/records/search',
		data: 'searchTerm='+searchTerm+'&searchBy='+searchBy+'&page='+page+'&sort='+sort,
		beforeSend: function(){
			$('#search').attr('disabled', true);
			},
		complete: function(){
			$('#search').attr('disabled', false);
			},
		success: function(data) {
			if(data.type == 'error'){
				// do nothing
			} else {	
				$('#recordList').html(data);
				window.history.pushState({}, null, '/records?' + urlstring);
				$('#loadingMessage').modal('hide');
			}
		}
	});
}