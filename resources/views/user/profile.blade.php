@extends("layouts.nav")
@section("title", "Profile")
@section("header", "Profile")

@section('content')
<form id="profile_form" action="/profile/edit" method="POST" class="pop-up-box" data-parsley-validate>
	@csrf
	<div class="panel mb-0">
		<div class="panel-heading">
			<h4 class="panel-title">Profile</h4>
		</div>
		<div class="panel-body">
			<div class="form-group">
				<div class="profile-header-container">
					<label class="title">Profile Picture</label>
					<div class="d-flex">
						<div class="profile-img" margin-top: 10px;>
							<img class="rounded-circle" src="/img/{{ $user->profile_pic}}" width="60px" />
						</div>
						<div class="update-propic row justify-content" style="display:none; ">
							<form action="/profile" method="post" enctype="multipart/form-data">
								@csrf
								<div class="form-group">
									<input type="file" class="form-control-file" name="avatar" id="avatarFile" aria-describedby="fileHelp">
									<small id="fileHelp" class="form-text text-muted">Please upload a valid image file. Size of image should not be more than 2MB.</small>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
			<br>
			<div class="form-group">
				<label class="title">Username</label>
				<input data-parsley-required="true" name="username" type="text" readonly class="profile-details form-control-plaintext form-control-lg" placeholder="Enter Username" value="{{$user->name}}">
			</div>
			<div class="form-group">
				<label class="title">Email</label>
				<input data-parsley-required="true" name="email" type="email" readonly class="profile-details form-control-plaintext form-control-lg" placeholder="Enter Email Address" value="{{$user->email}}">
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
	});
</script>

@endsection

