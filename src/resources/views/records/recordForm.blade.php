<div id="app">
<div class="form-group">
	{!! Form::label('artist', 'Artist:', ['class' => 'control-label col-md-1']) !!}
	<div class="col-md-10">
		{!! Form::text('artist', null, ['class' => 'form-control', 'v-model' => 'artist']) !!}
	</div>
</div>

<div class="form-group">
	{!! Form::label('Title', 'Title:', ['class' => 'control-label col-md-1']) !!}
	<div class="col-md-10">
		{!! Form::text('title', null, ['class' => 'form-control', 'v-model' => 'title']) !!}
	</div>
</div>

<div class="form-group">
	{!! Form::label('Label', 'Label:', ['class' => 'control-label col-md-1']) !!}
	<div class="col-md-10">
		{!! Form::select('label', [null => null] + $labels, $record->label, ['id' => 'label', 'class' => 'form-control', 'v-model' => 'label']) !!}
	</div>
</div>

<div class="form-group">
	{!! Form::label('Catalog_No', 'Cat #:', ['class' => 'control-label col-md-1']) !!}
	<div class="col-md-10">
		{!! Form::text('catalog_no', null, ['class' => 'form-control']) !!}
	</div>
</div>

<div class="form-group">
	{!! Form::label('Style', 'Style:', ['class' => 'control-label col-md-1']) !!}
	<div class="col-md-10">
		{!! Form::text('style', null, ['class' => 'form-control']) !!}
	</div>
</div>

<div class="form-group">
	{!! Form::label('discogs', 'Discogs:', ['class' => 'control-label col-md-1']) !!}
	<div class="col-md-9">
		{!! Form::text('discogs', null, ['class' => 'form-control']) !!}
	</div>
	<div class="col-md-2">
		@if($record->discogs)
			<a href="http://www.discogs.com{{ $record->discogs }}" target="_new">Check Link</a>
		@endif
	</div>
</div>

<div class="form-group">
	{!! Form::label('thumb', 'Thumb:', ['class' => 'control-label col-md-1']) !!}
	<div class="col-md-9">
		{!! Form::text('thumb', null, ['class' => 'form-control']) !!}
	</div>
	<div class="col-md-2">
		@if($record->thumb)	
			<img src="{{ $record->thumb }}" style="max-height: 75px">
		@else
			<img src="/images/question_mark" style="max-height: 75px">
		@endif
	</div>
</div>

<div class="form-group text-center">
	<button type="submit" class="btn btn-primary" :disabled="!(artist && title)">{{ $submitButtonText }}</button>
</div>
</div>

@section('footer')
	<script src="/js/select2.min.js"></script>
	<script>
		$('#label').select2({
			placeholder: 'Choose a label:',
			allowClear: true,
			tags: true,
		});
		new Vue({
			el: '#app',
			data: {
				title: '',
				artist: '',
				label: '',
			},
		});
		$('#tags').select2({
			placeholder: 'Choose a tag:',
			allowClear: true,
			tags: true,
		});
	</script>
@endsection