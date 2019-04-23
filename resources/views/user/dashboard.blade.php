@extends("layouts.nav")
@section("title", "Dashboard")
@section("header", "Dashboard")

@section("content")
<div class="dashboard-pane">
<div class="stressLevelBar">
	<div class="progress rounded-corner">
		@if ($stressLevel == 0) 
		<div class="progress-bar bg-green progress-bar-striped progress-bar-animated" style="width: 100%">
		@elseif ($stressLevel >= 0 && $stressLevel < 25) 
		<div class="progress-bar bg-green progress-bar-striped progress-bar-animated" style="width: {{ $stressLevel }}%">
		@elseif ($stressLevel >= 25 && $stressLevel < 50) 
		<div class="progress-bar bg-success progress-bar-striped progress-bar-animated" style="width: {{ $stressLevel }}%">
		@elseif ($stressLevel >= 50 && $stressLevel < 75)
		<div class="progress-bar bg-info progress-bar-striped progress-bar-animated" style="width: {{ $stressLevel }}%">
		@elseif ($stressLevel >= 75 && $stressLevel < 100)
		<div class="progress-bar bg-warning progress-bar-striped progress-bar-animated" style="width: {{ $stressLevel }}%">
		@elseif ($stressLevel >= 100)
		<div class="progress-bar bg-danger progress-bar-striped progress-bar-animated" style="width: {{ $stressLevel }}%">
		@endif 
		{{ $stressLevel }}% Stress Level
		</div>
	</div>
</div>

<div class="group-list row">
	<div class="col-md-4">
		<div class="panel group-panel">
			<div class="panel-body individual-panel">
				<h4>Individual</h4>
			</div>
			<div class="panel-footer">
				<div class="">
				<div class="row">
				<div class="col-md-6">
					<a href="tasks" class="btn btn-purple btn-sm">View Tasks</a>
				</div>
				<div class="col-md-6">
					@if ($individual->taskDue > 0)
					<span class="taskCount">Today's Task(s): <span class="badge badge-danger badge-square">{{ $individual->taskCount }}</span></span>
					@else
					<span class="taskCount">Today's Task(s): <span class="badge badge-success badge-square">{{ $individual->taskCount }}</span></span>
				 	@endif		
						</div>	
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

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
				<div class="">
				<div class="row">
				<div class="col-md-6">
					<a href="tasks?id={{ $group->id }}" class="btn btn-purple btn-sm">View Tasks</a>
				</div>
				<div class="col-md-6">
					@if ($group->taskDue > 0)
					<span class="taskCount">Today's Task(s): <span class="badge badge-danger badge-square">{{ $group->taskCount }}</span></span>
					@else
					<span class="taskCount">Today's Task(s): <span class="badge badge-success badge-square">{{ $group->taskCount }}</span></span>
					@endif	
				</div>
			</div>
			</div>
			</div>
		</div>
	</div>
	@endforeach
</div>
@endif
</div>
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
			modal.find("#btn_submit").text("Update Task");
		} else {
			modal.find("input[name='_method']").remove();
			modal.find("#btn_submit").text("Create Task");
		}


		modal.modal("toggle");
	}	
</script>
@stop

