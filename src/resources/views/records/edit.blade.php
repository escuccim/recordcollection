@extends('layouts.app')

@push('scripts')
	<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
	<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
@endpush

@section('content')
<div class="container">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h1>{{ trans('record-lang::records.editrecord') }}</h1>
		</div>
		<div class="panel-body">
			@include('records::errors.list')
			<form method="POST" action="http://skooch.app/records/{{ $record->id }}" accept-charset="UTF-8" class="form-horizontal">
				{{ csrf_field() }}
				<input name="_method" type="hidden" value="PATCH">
				@include('records::records.recordForm', ['submitButtonText' => trans('record-lang::records.updaterecord')])
			</form>
		</div>
	</div>
</div>
@endsection