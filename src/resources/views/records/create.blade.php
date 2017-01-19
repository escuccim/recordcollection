@extends('layouts.app')

@section('header')
	<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
	<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
@endsection

@section('content')
<div class="container">
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
</div>
@endsection