@extends('layouts.app')

@section('header')
	<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
	<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
@endsection

@section('content')
<div class="container">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h1>Edit Record</h1>
		</div>
		<div class="panel-body">
			@include('records::errors.list')

			{!! Form::model($record, ['method' => 'patch', 'class' => 'form-horizontal', 'action' => ['\Escuccim\RecordCollection\Http\Controllers\RecordsController@update', $record->id]]) !!}
				@include('records::records.recordForm', ['submitButtonText' => 'Update Record'])
			{!! Form::close() !!}
		</div>
	</div>
</div>
@endsection