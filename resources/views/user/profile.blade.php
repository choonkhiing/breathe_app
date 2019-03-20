@extends("layouts.nav")
@section("title", "Profile")
@section("header", "Profile")

@section('content')
<form id="profile_form" action="/profile/edit" method="POST" class="pop-up-box" data-parsley-validate>
	@csrf
	<div class="form-group">
		@if (session('error'))
			<div class="alert alert-danger" id="loginErrorDiv">{{Session::get('error')}}</div>
		@elseif(session('success'))
			<div class="alert alert-success" id="loginErrorDiv">{{Session::get('success')}}</div>
		@endif
	</div>
	<div class="panel mb-0">
		<div class="panel-heading" style="padding-bottom: 35px;">
			<h4 class="panel-title" style="float:left;">Profile</h4>
			<p style="float:right;">{{$user->name}} ({{$user->email}})</p>
		</div>
		
		<div class="panel-body">
			<div class="form-group">
				<label class="title">Profile Picture</label>
				<div class="d-flex" style="align-items: center;">
					<div class="profile-img m-r-10">
						<img class="rounded-circle rounded-profile-pic" id="profile_pic" src="{{ $user->profile_pic}}" width="60px" />
					</div>
					<div class="update-propic justify-content" style="display:none; ">
						<input type="file" name="avatar" id="avatar">
						<small id="fileHelp" class="form-text text-muted">Please upload a valid image file.</small>
					</div>
				</div>
			</div>
			<br>
			<div class="form-group">
				<label class="title">Name</label>
				<input data-parsley-required="true" name="name" type="text" readonly class="profile-details form-control-plaintext form-control-lg" placeholder="Enter Username" value="{{$user->name}}" data-parsley-pattern="/^[a-zA-Z\s]*$/" data-pattern-message="Only alphabet letter(s) is allowed.">
			</div>
			
			<div class="form-group">
				<label class="title">Phone</label>
				<input data-parsley-required="true" name="phone" type="text"readonly class="profile-details form-control-plaintext form-control-lg" placeholder="Enter Phone Number" value="{{$user->phone}}" data-parsley-minlength="10" data-parsley-minlength-message="Please enter a valid phone number." data-parsley-pattern="/^[\+]?[0-9]{2,4}[-]?[0-9]{7,10}$/">
			</div>
		</div>
		<div class="panel-footer clearfix">
			<input type="hidden" name="user_id" value="{{$user->id}}"/>
			<button type="button" class="edit_profile_btn btn btn-primary btn-action pull-right">Edit Profile</button>
			<button type="button" class="cancel_profile_btn btn btn-primary btn-action pull-right" style="display: none;">Cancel Profile</button>
			<button type="button" class="update_profile_btn btn btn-primary btn-action pull-right" style="display: none; margin-right: 5px">Update Profile</button>

		</div>
	</div>
</form>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script type="text/javascript">
	$(document).ready(function () {
		$('.edit_profile_btn').click(function() {
			$('.profile-details').each(function() {
				$(this).removeClass('form-control-plaintext');
				$(this).prop('readonly', false);
				$(this).addClass('form-control');
			});

			$(this).fadeOut(function() {
				$('.update_profile_btn').fadeIn();
				$('.cancel_profile_btn').fadeIn();
				$('.update-propic').fadeIn();
			});
		});

		$('.cancel_profile_btn').click(function() {
			location.reload();
		});

		$('.update_profile_btn').click(function() {
			$('#profile_form').submit();
		});

		$(":file").change(function () {
			if (this.files && this.files[0]) {
				var reader = new FileReader();
				reader.onload = imageIsLoaded;
				reader.readAsDataURL(this.files[0]);
			}
		});
	});

	function imageIsLoaded(e) {
		$('#profile_pic').attr('src', e.target.result);
	};
</script>

@endsection

