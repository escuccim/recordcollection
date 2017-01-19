@extends('layouts.app')

@section('content')
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
				
				@if(Auth::check())
					@if(Auth::user()->type == 1)
						<div class="row">
							<div class="col-md-2 col-md-offset-5">
								<br><a href="{{ action('\Escuccim\RecordCollection\Http\Controllers\RecordsController@edit', [$record->id]) }}" class="btn btn-primary">Edit Record</a>
							</div>
						</div>
					@endif
				@endif
			</div>
		</div>	
	</div>

</div>
		
@endsection