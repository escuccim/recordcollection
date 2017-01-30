@extends('layouts.app')

@push('scripts')
	<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
	<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
@endpush

@section('content')
<div class="container">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h1>{{ trans('record-lang::records.addrecord') }}</h1>
		</div>
		<div class="panel-body">

			@include('records::errors.list')

			{!! Form::open(['url' => 'records', 'class' => 'form-horizontal']) !!}
				@include('records::records.recordForm', ['submitButtonText' => trans('record-lang::records.save')])
			{!! Form::close() !!}
		</div>
	</div>
</div>
@endsection