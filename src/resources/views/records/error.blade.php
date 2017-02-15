@extends('layouts.app')

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<div class="panel panel-info">
				<div class="panel-heading">
					<h4>{{ trans('record-lang::records.info') }}</h4>
				</div>
				<div class="panel-body">
					<p>{{ trans('record-lang::records.notfound') }}</p>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection