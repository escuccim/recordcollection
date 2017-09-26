<div class="panel panel-default">
	<div class="panel-body">
		<table border="0" width="100%" class="table-striped">
			<thead>
				<tr>
					<td></td><!--  -->
					<th width="35%"><a id="artist" class="sortlink" data-val="artist">{{ trans('record-lang::records.artist') }}</a>
						@if($sort == 'artist')
							<span class="caret"></span>
						@endif
					</th>
					<th width="31%"><a id="title" class="sortlink" data-val="title" href="{{ action('\Escuccim\RecordCollection\Http\Controllers\RecordsController@index', ['sort=title', 'searchBy' => $recordSearch->formData['searchBy'], 'searchTerm' => $recordSearch->formData['searchTerm'], 'page' => $records->currentPage()]) }}">{{ trans('record-lang::records.title') }}</a>
						@if($sort == 'title')
							<span class="caret"></span>
						@endif
					</th>
					<th width="24%"><a id="label" class="sortlink" data-val="label" href="{{ action('\Escuccim\RecordCollection\Http\Controllers\RecordsController@index', ['sort=labels.name', 'searchBy' => $recordSearch->formData['searchBy'], 'searchTerm' => $recordSearch->formData['searchTerm'], 'page' => $records->currentPage()]) }}">{{ trans('record-lang::records.label') }}</a>
						@if($sort == 'label')
							<span class="caret"></span>
							@endif
					</th>
					<th width="10%"><a id="catalog_no" class="sortlink" data-val="catalog_no" href="{{ action('\Escuccim\RecordCollection\Http\Controllers\RecordsController@index', ['sort=catalog_no', 'searchBy' => $recordSearch->formData['searchBy'], 'searchTerm' => $recordSearch->formData['searchTerm'], 'page' => $records->currentPage()]) }}">{{ trans('record-lang::records.catno') }}</a>
						@if($sort == 'catalog_no')
							<span class="caret"></span>
						@endif
					</th>
				</tr>
			</thead>
			@if($records->count())
				@foreach($records as $record)
					<tr>
						<td></td>
						<td>{{ $record->artist }}</td>
						<td><a href="{{ route('record.show.slug', ['id' => $record->id, 'slug' => str_slug($record->title)]) }}" title="See detailed info about this record">{{ $record->title }}</a></td>
						<td>{{ $record->label }}</td>
						<td>{{ $record->catalog_no }}</td>
					</tr>
				@endforeach
			@else
				<tr>
					<td></td>
					<td colspan="4" align="center"><strong>{{ trans('record-lang::records.norecordsfound') }}</strong></td>
				</tr>
			@endif
		</table>
	</div>
</div>

<div class="visible-desktop navbar navbar-fixed-bottom text-center">
	{{ $records->appends(['sort' => $sort, 'searchBy' => $recordSearch->formData['searchBy'], 'searchTerm' => $recordSearch->formData['searchTerm']])->links() }}
</div>	

<script language="Javascript">
$(".sortlink").click(function(e){
	e.preventDefault();
	$("#loadingMessage").modal('show');
	var sort = $(this).data('val');
	var page = {{ $records->currentPage() }};
	searchOrSort(sort,page);
});
$(".page-link").click(function(e){
	e.preventDefault();
	var page = $(this).data('val');
	var sort = '{{ $sort }}';
	searchOrSort(sort, page);
});
</script>