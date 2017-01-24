@extends('layouts.app')

@section('header')
<script src="/js/recordsearch.js"></script>
@endsection

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default" style="margin-bottom: 10px;">
				<div class="panel-body">
				<!--   -->
					<form action="/records/search" method="post" class="form-horizontal" id="searchForm">
					{{ csrf_field() }}
						<div class="form-group">
							<div class="col-md-1"></div>
							<label for="searchTerm" class="control-label col-md-1">{{ trans('record-lang::records.search') }}</label>
							<div class="col-md-2">
								<input type="text" id="searchTerm" name="searchTerm" value="{{ $recordSearch->formData['searchTerm'] }}" class="form-control input-sm">
							</div>

							<label for="searchBy" class="control-label col-md-2">{{ trans('record-lang::records.searchby') }}</label>

							<div class="col-md-2">
								<select name="searchBy" id="searchBy" class="form-control input-sm">
									<option value="all" {{ ($recordSearch->formData['searchBy'] == 'all') ? 'selected' : '' }}>{{ trans('record-lang::records.all') }}
									<option value="artist" {{ ($recordSearch->formData['searchBy'] == 'artist') ? 'selected' : '' }}>{{ trans('record-lang::records.artist') }}
									<option value="title" {{ ($recordSearch->formData['searchBy'] == 'title') ? 'selected' : '' }}>{{ trans('record-lang::records.title') }}
									<option value="label" {{ ($recordSearch->formData['searchBy'] == 'label') ? 'selected' : '' }}>Label
									<option value="catalog_no" {{ ($recordSearch->formData['searchBy'] == 'catalog_no') ? 'selected' : '' }}>{{ trans('record-lang::records.catno') }}
								</select>
							</div>

							<div class="col-md-4">
								<input type="submit" name="search" id="search" value="{{ trans('record-lang::records.submit') }}" class="btn btn-primary btn-sm">
								@if(config('records.is_user_admin')())
										<a href="{{ action('\Escuccim\RecordCollection\Http\Controllers\RecordsController@create') }}" id="add" class="btn btn-primary btn-sm">{{ trans('record-lang::records.addrecord') }}</a>
								@endif
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-md-12" id="recordList">
			@include('records::records.recordList')
		</div>
	</div>

	<!-- Modal -->
	<div id="loadingMessage" class="modal" style="position: absolute; top: 200px;">
		<div class="modal-dialog">
			<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title"><span class="glyphicon glyphicon-time"></span> &nbsp; {{ trans('record-lang::records.pleasewait') }}</h4>
				</div>
				<div class="modal-body">
					<div class="progress">
						<div class="progress-bar progress-bar-info progress-bar-striped active" style="width: 100%"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
$("#search").click(function(){
	$("#loadingMessage").modal('show');
	var sort = '{{ $sort }}';
	var page = '{{ $records->currentPage() }}';
	searchOrSort(sort, page);
});
</script>
@endsection