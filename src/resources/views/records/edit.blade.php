@extends('layouts.app')

@section('content')
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
@endsection