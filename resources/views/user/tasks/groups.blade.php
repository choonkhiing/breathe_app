@extends("layouts.nav")
@section("title", "Groups")
@section("header", "Groups")

@section('content')


<button type="button" class="btn btn-primary btn-action">Invite</button>


@if (!empty($groups))
<h1 class="page-header">Group</h1>
<div class="group-list row">
	@foreach ($groups AS $group)
	<div class="col-md-4">
		<div class="panel group-panel">
			<div class="panel-body group-panel-bg">
				<h4>{{ $group->title }}</h4>
				<p>{{ $group->description }}</p>
			</div>
			<div class="panel-footer">
				<a href="tasks?id={{ $group->id }}" class="btn btn-purple btn-sm">View Tasks</a>
			</div>
		</div>
	</div>
	@endforeach
</div>
@endif

<div class="modal" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content pop-up">
			<form id="formTask" autocomplete="off"action="{{ action('GroupsController@store') }}" method="POST" class="pop-up-box" data-parsley-validate>
				@csrf
				<div class="panel mb-0">
					<div class="panel-heading">
						<h4 class="panel-title">Create new group</h4>
					</div>
					<div class="panel-body">
						<div class="form-group">
							<label class="title">Title</label>
							<input data-parsley-required="true" name="title" type="text" class="form-control form-control-lg" placeholder="Enter Title">
						</div>
						<div class="form-group">
							<label class="title">Invite users to group</label>
							<ul id="jquery-tagIt-inverse" class="inverse"></ul>
						</div>
						<div class="form-group">
							<label class="title">Description (Optional)</label>
							<textarea rows="4" name="description" class="form-control form-control-lg" placeholder="Enter Group Description"></textarea>
						</div>
					</div>
					<div class="panel-footer clearfix">
						<button type="button" id="btn_submit" class="btn btn-primary btn-action pull-right">Create group</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
<div id="btm-action" class="btm-nav" data-toggle="modal">
	<div class="btm-item">
		<i class="fa fa-2x fa-plus d-block"></i>
	</div>
</div>
@stop

@section("page_script")
<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.33.1/sweetalert2.all.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.33.1/sweetalert2.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.js"></script>

<link rel="stylesheet" href="css/jquery-tagit.css" />
<script src="js/jquery-tagit.js"></script>

<script type="text/javascript">
	$("#btm-action").click(function(){
		triggerUpdateModal();
	});

	$("#btn_submit").click(function(e){
		$("#formTask").submit();
	});

	function triggerUpdateModal(taskobj){
		var modal = $("#exampleModalCenter");

		if(taskobj != null){
<<<<<<< HEAD
			// modal.find("#btn_submit").text("Update Task");
		} else {
			modal.find("input[name='_method']").remove();
			// modal.find("#btn_submit").text("Create Task");
=======

			modal.find("#btn_submit").text("Update Group");
		} else {
			modal.find("input[name='_method']").remove();
			modal.find("#btn_submit").text("Create Group");
>>>>>>> 762d4cc87743ad80faba4694fc1a989f6523e30c
		}

		modal.modal("toggle");
	}

	$('#jquery-tagIt-inverse').tagit({
		fieldName: 'invitation[]',
		beforeTagAdded: function(event, ui) {
			var email = ui.tag[0].innerText;
			var add = true;
        	// Check if email address exists
        	$.ajax({
        		async: false,
        		type: "POST",
        		url: "/groups/validateEmail",
        		data:{
        			_token: '{{ csrf_token() }}',
        			email: email.slice(0, -1)
        		},
        		success: function(response){
        			if(response.success === false){
        				add = false;
		        		// $('#jquery-tagIt-inverse').tagit("removeTagByLabel", email);
        			}
        		}
        	});
        	return add;
        	// $('#jquery-tagIt-inverse').tagit("removeTagByLabel", label);
        }
    });
</script>
@stop