@extends('layouts.app')

@section('content')
<div class="panel panel-default">
	<div class="panel-heading">
		<h1>New Record</h1>
	</div>
	<div class="panel-body">
		
		@include('records::errors.list')
		
		{!! Form::open(['url' => 'records', 'class' => 'form-horizontal']) !!}
			@include('records::records.recordForm', ['submitButtonText' => 'Add Record'])
		{!! Form::close() !!}
	</div>	
	
</div>
@endsection