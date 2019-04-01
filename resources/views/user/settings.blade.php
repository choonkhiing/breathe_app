@extends("layouts.nav")
@section("title", "Settings")
@section("header", "Settings")

@section('content')
<form action="{{ action('SettingsController@saveSettings') }}" method="POST" data-parsley-validate autocomplete="off">
	@csrf
	<div class="row">
		<div class="col-md-6 offset-md-3">
			<div class="form-group">
				@if (session('error'))
				<div class="alert alert-danger" id="loginErrorDiv">{{Session::get('error')}}</div>
				@elseif(session('success'))
				<div class="alert alert-success" id="loginErrorDiv">{{Session::get('success')}}</div>
				@endif
			</div>
			<div class="panel">
				<div class="panel-heading">
					<h4 class="panel-title">Maximum Hour/Day</h4>
				</div>
				<div class="panel-body">
					<div class="form-group">
						<label class="max_hour">Hour</label>
						<input name="max_hour" type="text" value="{{$user->max_hour}}" class="form-control form-control-lg" placeholder="Enter Hour" data-parsley-type="digits" data-parsley-required="true">
					</div>
				</div>
				<div class="panel-footer clearfix">
					<button type="submit" class="btn btn-primary btn-action pull-right">Update</button>
				</div>
			</div>
		</div>
	</div>
</form>
@endsection

