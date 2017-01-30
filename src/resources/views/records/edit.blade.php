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

			{!! Form::model($record, ['method' => 'patch', 'class' => 'form-horizontal', 'action' => ['\Escuccim\RecordCollection\Http\Controllers\RecordsController@update', $record->id]]) !!}
				@include('records::records.recordForm', ['submitButtonText' => trans('record-lang::records.updaterecord')])
			{!! Form::close() !!}
		</div>
	</div>
</div>
@endsection