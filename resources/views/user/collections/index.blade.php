@extends("layouts.nav")
@section("title", "Collections")
@section("header", "Collections")

@section("content")
<form action="{{ action('CollectionController@store') }}" method="POST">
	@csrf
	<div class="row">
		<div class="col-md-6 offset-md-3">
			<div class="panel">
				<div class="panel-heading">
					<h4 class="panel-title">Create new collection</h4>
				</div>
				<div class="panel-body">
					<div class="form-group">
						<label class="title">Title</label>
						<input name="title" type="text" class="form-control form-control-lg" placeholder="Enter Title">
					</div>
					<div class="form-group">
						<label class="title">Description (Optional)</label>
						<textarea class="form-control form-control-lg" rows="10" placeholder="Enter Description"></textarea>
					</div>
				</div>
				<div class="panel-footer clearfix">
					<button type="submit" class="btn btn-primary btn-action pull-right">Create collection</button>
				</div>
			</div>
		</div>
	</div>
</form>
@stop