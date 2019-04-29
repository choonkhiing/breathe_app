@extends("layouts.nav")
@section("title", "$group->title")
@section("header", "$group->title")

@section("breadcrumb")
<form id="form_leave" method="POST" action="{{ action('GroupsController@leaveGroup', $group->id) }}">
	@csrf
	<button id="btn_leave" type="button" class="btn btn-danger btn-action mr-2">Exit Group</button>
</form>
<button id="btn_invite" type="button" class="btn btn-primary btn-action">Invite members</button>
@stop

@section("content")
<div class="row">
	@foreach($group->members AS $member)
	<div class="col-xl-2 col-lg-3 col-sm-3 col-sm-12 col-mem">
		<div class="panel member-panel" data-gmid="{{ $member->id }}">
			<div class="d-flex justify-content-between align-items-center">
				<div class="d-flex align-items-center">
					<img src="/{{ $member->user->profile_pic }}" /> 
					<p class="member-name">{{ $member->user->name }}<br><span>{{ ucfirst($member->type) }}</span></p>
				</div>
				@if($member->user->id != Auth::user()->id && $member->user->id != $group->created_by)
				<i class="fas fa-wrench btn-edit"></i>
				@endif
			</div>
		</div>
	</div>
	@endforeach
</div>

@if(count($group->invitations) > 0)
<h5 id="h5_inv">Pending Invitations</h5>
<div class="row"> 
	@foreach($group->invitations AS $inv)
	<div class="col-xl-2 col-lg-3 col-sm-3 col-sm-12 col-inv">
		<div class="panel member-panel" data-inv="{{ $inv->id }}">
			<div class="d-flex justify-content-between align-items-center">
				<div class="d-flex align-items-center">
					<img src="/{{ $inv->getInvitee->profile_pic }}" /> 
					<p class="member-name">{{ $inv->getInvitee->name }}<br>{{ $inv->getInvitee->email }}</p>
				</div>
				<i class="fas fa-times btn-removeInv"></i>
			</div>
		</div>
	</div>
	@endforeach
</div>
@endif

<div class="modal" id="memberModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="memberTitle"></h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="form_groupmember">
					@csrf
					<div class="form-group">
						<label>Member Type</label>
						<select id="memberType" name="type" class="form-control form-control-lg">
							<option value="Guest">Guest (Able to view tasks)</option>
							<option value="Member">Member (Able to view, add, edit and remove tasks)</option>
							<option value="Admin">Admin (All of the above, manage group)</option>
						</select>
					</div>
					<input hidden name="gmID" />
				</form>
			</div>
			<div class="modal-footer justify-content-between">
				<button id="btn_remove" type="button" class="btn btn-danger btn-action float-left">Remove Member</button>
				<div>
					<button type="button" class="btn btn-default btn-action" data-dismiss="modal">Close</button>
					<button id="btn_save" type="button" class="btn btn-primary btn-action">Save changes</button>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal" id="inviteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Invite members into group</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div id="div_alert" style="display: none" class="alert alert-danger"></div>
				<form id="form_invite" method="POST" action="{{ action('GroupsController@inviteGroupMember') }}">
					@csrf
					<div class="form-group">
						<label>Enter emails to send group invitation</label>
						<ul id="jquery-tagIt-inverse" class="inverse"></ul>
					</div>
					<input hidden name="groupID" value="{{ $group->id }}" />
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default btn-action" data-dismiss="modal">Close</button>
				<button id="btn_send" type="button" class="btn btn-primary btn-action">Send</button>
			</div>
		</div>
	</div>
</div>
@stop

@section("page_script")
<link rel="stylesheet" href="/css/jquery-tagit.css" />
<script src="/js/jquery-tagit.js"></script>

<script type="text/javascript">
	$(function(){
		$(".btn-edit").click(function(){
			var gmID = $(this).closest(".member-panel").attr("data-gmid");
			$.ajax({
				type: "GET",
				url: "/groupMember/" + gmID,
				success: function(response){
					if(response.success){
						var member = response.member;
						$("#memberTitle").text(member.memberName);
						$("#memberType").val(member.type).trigger("change");
						$("input[name='gmID']").val(gmID);
						$("#memberModal").modal("toggle");
					}
				}
			})
		});

		$("#btn_save").click(function(){
			$.ajax({
				type: "POST",
				url: "/updateGroupMember",
				data: $("#form_groupmember").serialize(),
				success: function(response){
					if(response.success){
						var id = $("#form_groupmember input[name='gmID']").val();
						var type = $("#form_groupmember #memberType").val();
						$("div[data-gmid='" + id + "']").find(".member-name span").text(type);
						$("#memberModal").modal("toggle");

						notyf.success("Successfully updated group member.");
					}
				}
			})
		});

		$("#btn_remove").click(function(){
			var gmID = $("#form_groupmember input[name='gmID']").val();
			var panel = $("div[data-gmid='" + gmID + "']");
			$.ajax({
				type: "POST",
				url: "/removeGroupMember",
				data: {
					_token: '{{ csrf_token() }}',
					gmID: gmID
				},
				success: function(response){
					if(response.success){
						$("#memberModal").modal("toggle");
						panel.closest(".col-mem").remove();
						notyf.success("Successfully removed " + response.username + " from the group.");
					}
				}
			});
		});

		$("#btn_leave").click(function(){
			Swal.fire({
				title: 'Exit group',
				text: "You will be unable to view any activity in this group.",
				type: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Confirm'
			}).then((result) => {
				if (result.value) {
					$("#form_leave").submit();
				}
			});
		});

		$(".btn-removeInv").click(function(){
			var elem = $(this).closest(".member-panel");
			var inv = elem.attr("data-inv");

			Swal.fire({
				title: 'Are you sure?',
				text: "You are about to delete this group invitation.",
				type: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Yes, delete it!'
			}).then((result) => {
				if (result.value) {
					$.ajax({
						type: "POST",
						url: "/processInvitation",
						data: {
							_token: '{{ csrf_token() }}',
							action: "delete",
							id: inv
						},
						success: function(response){
							if(response.success){
								notyf.success(response.msg);
								elem.closest(".col-inv").remove();

								if($(".col-inv").length == 0){
									$("#h5_inv").remove();
								}

							} else {
								notyf.error(response.msg);
							}
						}
					});
				}
			});
		});

		$("#btn_invite").click(function(){
			$("#inviteModal").modal("toggle");
		});

		$("#btn_send").click(function(){
			var emails = $('#jquery-tagIt-inverse').tagit('assignedTags');
			if(emails.length > 0){
				$("#form_invite").submit();
			} else {
				errMessage("#div_alert", "Enter email to invite members into the group!");
			}
		});

		$('#jquery-tagIt-inverse').tagit({
			fieldName: 'invitation[]',
			beforeTagAdded: function(event, ui) {
				var text = ui.tag[0].innerText;
				var email = text.slice(0, -1);
				var add = true;
        	// Check if email address exists
        	$.ajax({
        		async: false,
        		type: "POST",
        		url: "/groups/validateEmail",
        		data:{
        			_token: '{{ csrf_token() }}',
        			email: email,
        			group_id: "{{ $group->id }}"
        		},
        		success: function(response){
        			if(response.success === false){
        				add = false;
        				errMessage("#div_alert", response.msg);
        			} else {
        				$("#div_alert").hide();
        			}
        		}
        	});
        	return add;
        }
    });


	});
</script>
@stop