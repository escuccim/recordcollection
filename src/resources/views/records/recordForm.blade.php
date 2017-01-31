<div id="app">
<div class="form-group">
	<label for="artist" class="control-label col-md-1">{!! trans('record-lang::records.artist') !!}:</label>
	<div class="col-md-10">
		<input class="form-control" v-model="artist" name="artist" type="text" id="artist" value="{{$record->artist}}">
	</div>
</div>

<div class="form-group">
	<label for="title" class="control-label col-md-1">{!! trans('record-lang::records.title') !!}:</label>
	<div class="col-md-10">
		<input class="form-control" v-model="title" name="title" type="text" value="{{$record->title}}">
	</div>
</div>

<div class="form-group">
	<label for="label" class="control-label col-md-1">Label:</label>
	<div class="col-md-10">
		<select id="label" class="form-control" v-model="label" name="label"><option value=""></option>
			@foreach($labels as $label)
				<option value="{{$label}}" {{ $label== $record->label ? 'selected="selected"' : '' }}>{{ $label }}</option>
			@endforeach
		</select>
	</div>
</div>

<div class="form-group">
	<label for="catalog_no" class="control-label col-md-1">Cat #:</label>
	<div class="col-md-10">
		<input class="form-control" name="catalog_no" type="text" value="{{$record->catalog_no}}">
	</div>
</div>

<div class="form-group">
	<label for="style" class="control-label col-md-1">Style:</label>
	<div class="col-md-10">
		<input class="form-control" name="style" type="text" value="{{$record->style}}">
	</div>
</div>

<div class="form-group">
	<label for="discogs" class="control-label col-md-1">Discogs:</label>
	<div class="col-md-9">
		<input class="form-control" name="discogs" type="text" value="{{$record->discogs}}">
	</div>
	<div class="col-md-2">
		@if($record->discogs)
			<a href="http://www.discogs.com{{ $record->discogs }}" target="_new">Check Link</a>
		@endif
	</div>
</div>

<div class="form-group">
	<label for="thumb" class="control-label col-md-1">Thumb:</label>
	<div class="col-md-9">
		<input class="form-control" name="thumb" type="text" id="thumb" value="{{$record->thumb}}">
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

<script>
	$('#label').select2({
		placeholder: 'Choose a label:',
		allowClear: true,
		tags: true,
	});
</script>