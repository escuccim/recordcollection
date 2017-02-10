@extends('layouts.app')

@section('header')
	@if(config('records.use_rich_card'))
		@include('records::records.richCard')
	@endif
@endsection

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<div class="panel panel-info">
				<div class="panel-heading">
					<h4>{{ trans('record-lang::records.info') }}</h4>
				</div>
				<div class="panel-body">
					<div class="row">
						<div class="col-md-9">
							<div class="row">
								<div class="col-md-3"><strong>{{ trans('record-lang::records.artist') }}:</strong></div>
								<div class="col-md-8">{{ $record->artist }}</div>
							</div>
							<div class="row">
								<div class="col-md-3"><strong>{{ trans('record-lang::records.title') }}:</strong></div>
								<div class="col-md-8">{{ $record->title }}</div>
							</div>
							<div class="row">
								<div class="col-md-3"><strong>Label:</strong></div>
								<div class="col-md-8">{{ $record->label }}</div>
							</div>
							<div class="row">
								<div class="col-md-3"><strong>{{ trans('record-lang::records.catalog_no') }}:</strong></div>
								<div class="col-md-8">{{ $record->catalog_no }}</div>
							</div>
							@if($record->discogs)
								<div class="row">
									<div class="col-md-3"><strong>{{ trans('record-lang::records.link') }}:</strong></div>
									<div class="col-md-9"><a href="http://www.discogs.com/{{ $record->discogs }}">{{ $record->discogs }}</a></div>
								</div>
							@endif
							{{-- <div class="row">
								<div class="col-md-3"><strong>Style:</strong></div>
								<div class="col-md-8">{{ $record->style }}</div>
							</div> --}}
						</div>
						<div class="col-md-3">
							@if($record->discogs)
								@if($record->thumb)
									<img src="{{ $record->thumb }}" style="max-height: 100px;">
								@endif
							@endif
						</div>
					</div>

					@if(config('records.is_user_admin')())
						<div class="row">
							<div class="col-md-4 col-md-offset-4">
								<form action="/records/{{$record->id}}" method="POST" onSubmit="return confirm('Are you sure you want to delete this?');">
								<br><a href="{{ action('\Escuccim\RecordCollection\Http\Controllers\RecordsController@edit', [$record->id]) }}" class="btn btn-primary">Edit Record</a>
									{{ csrf_field() }}
									<input type="hidden" name="_method" value="DELETE">
									<button class="btn btn-default">Delete</button>
								</form>
							</div>
						</div>
					@endif
				</div>
			</div>
		</div>
	</div>
</div>
@endsection