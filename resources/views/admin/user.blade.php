@extends("layouts.adminnav")
@section("title", "Users")
@section("header", "Users")

@section("content")
<div class="table-response">
	<table id="table_users" class="table bg-white table-bordered table-valign-middle f-s-14">
		<thead>
			<tr>
				<th>Name</th>
				<th>Email</th>
				<th width="1%">Phone</th>
				<th width="1%">Status</th>
				<th width="1%">Actions</th>
			</tr>
		</thead>
		<tbody>
			@foreach($users AS $user)
			<tr>
				<td>{{ $user->name }}</td>
				<td>{{ $user->email }}</td>
				<td>{{ $user->phone }}</td>
				<td>{!! $user->getStatus() !!}</td>
				<td class="nowrap">
					<button type="button" class="btn btn-primary btn-edit" data-rownid="{{$user->id}}">Edit</button>
					@if($user->status == 0)
					<button type="button" class="btn btn-success btn-activate" data-rowname="{{$user->name}}" data-rownid="{{$user->id}}">Activate</button>
					@else
					<button type="button" class="btn btn-danger btn-deactivate" data-rowname="{{$user->name}}" data-rownid="{{$user->id}}">Deactivate</button>
					@endif
					
				</td>
			</tr>
			@endforeach
		</tbody>
	</table>
</div>

<div class="modal" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content pop-up">
			<form id="formTask" autocomplete="off"action="{{ action('AdminController@update') }}" method="POST" class="pop-up-box" data-parsley-validate>
				@csrf
				<div class="panel mb-0">
					<div class="panel-heading">
						<h4 class="panel-title">Edit Member</h4>
					</div>
					<div class="panel-body">
						<div class="form-group">
							<label class="title">Name</label>
							<input data-parsley-required="true" id="user_name" name="name" type="text" class="profile-details form-control" placeholder="Enter Username" value="" data-parsley-pattern="/^[a-zA-Z\s]*$/" data-pattern-message="Only alphabet letter(s) is allowed.">
						</div>

						<div class="form-group">
							<label class="title">Phone</label>
							<input data-parsley-required="true" id="user_phone" name="phone" type="text" class="profile-details form-control" placeholder="Enter Phone Number" value="" data-parsley-minlength="10" data-parsley-minlength-message="Please enter a valid phone number." data-parsley-pattern="/^[\+]?[0-9]{2,4}[-]?[0-9]{7,10}$/">
						</div>
					</div>
					<div class="panel-footer clearfix" style="padding-bottom: 15px;">
						<input type="hidden" name="id" id="user_id">
						<button type="submit" class="update_profile_btn btn btn-primary btn-action pull-right" style="margin-right: 5px">Update Profile</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
@stop

@section("page_script")
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<script>
	$(document).ready(function() 
	{
		$('#table_users').on('click', '.btn-deactivate', function(e)
		{	
			var row_id =  $(this).data("rownid");
			var row_name =  $(this).data("rowname");
			swal({
				title: 'Deactivate Member',
				text: 'Are you sure you want to deactivate this member: '+$(this).data("rowname"),
				icon: 'warning',
				buttons: {
					confirm: {
						text: 'Deactivate',
						value: true,
						visible: true,
						className: 'btn btn-danger',
						closeModal: true,
					},
					cancel: {
						text: 'Cancel',
						value: false,
						visible: true,
						className: 'btn btn-default',
						closeModal: true,
					}
				}
			}).then((value) => {
				if (value)
				{
					$.ajax({
						url: "/admin/deactivate/"+$(this).data("rownid"),
						type: 'POST',
						data: {_token: '{{csrf_token()}}'},
						success: function (data) 
						{
							if (data.error != null)
							{
								swal("Error", data.error, "error");
							}
							else
							{
								window.location.reload();
							}
						}
					});
				}
			});
		}); //end deactivate function

		$('#table_users').on('click', '.btn-activate', function(e)
		{	
			var row_id =  $(this).data("rownid");
			var row_name =  $(this).data("rowname");
			swal({
				title: 'Activate Member',
				text: 'Are you sure you want to activate this member: '+$(this).data("rowname"),
				icon: 'warning',
				buttons: {
					confirm: {
						text: 'Activate',
						value: true,
						visible: true,
						className: 'btn btn-success',
						closeModal: true,
					},
					cancel: {
						text: 'Cancel',
						value: false,
						visible: true,
						className: 'btn btn-default',
						closeModal: true,
					}
				}
			}).then((value) => {
				if (value)
				{
					$.ajax({
						url: "/admin/activate/"+$(this).data("rownid"),
						type: 'POST',
						data: {_token: '{{csrf_token()}}'},
						success: function (data) 
						{
							if (data.error != null)
							{
								swal("Error", data.error, "error");
							}
							else
							{
								window.location.reload();
							}
						}
					});
				}
			});
		}); //end activate function

		$('#table_users').on('click', '.btn-edit', function(e) {	
			var modal = $("#editModal");

			$.ajax({
				url: "/admin/user/"+$(this).data("rownid"),
				type: 'POST',
				data: {_token: '{{csrf_token()}}'},
				success: function (data) 
				{
					if (data.error != null)
					{
						swal("Error", data.error, "error");
					}
					else
					{
						console.log(data);
						$("#user_id").val(data.id);
						$("#user_name").val(data.name);
						$("#user_phone").val(data.phone);
						//window.location.reload();
						modal.modal("toggle");
					}
				}
			});
		});
}); //end
</script>
@stop