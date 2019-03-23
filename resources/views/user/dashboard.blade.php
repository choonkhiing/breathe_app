@extends("layouts.nav")
@section("title", "Dashboard")
@section("header", "Dashboard")

@section("content")
<div class="task-list row">
	@foreach(\App\Task::TASK_PRIORITY AS $key => $priority)
	@if(!empty($tasks[$key]))
	<div class="col-md-4">
		@foreach($tasks[$key] AS $task)
		<div class="panel task-panel" data-task-id="{{ $task->id }}">
			<div class="panel-body">
				#{{ $loop->iteration }}
				<strong class="f-s-13 task_title">{{ $task->title }}</strong>
				<span class="label {{ \App\Task::TASK_PRIORITY_CLASS[$key] }} pull-right f-s-12">{{ $priority }}</span>
			</div>
			<div class="panel-footer">
				<i class="fa fa-calendar m-r-5"></i>
				{{ optional($task->due_date)->format('d/m/Y') }}
				@if($task->description)
				<i class="fa fa-list pull-right m-t-3"></i>
				@endif
			</div>
		</div>
		@endforeach
	</div>
	@else
	<div class="col-md-4">
		<div class="panel">
			<div class="panel-body">
				{{ $priority }} priority tasks goes here!
			</div>
		</div>
	</div>
	@endif
	@endforeach
</div>

<div class="modal" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content pop-up">
			<form id="formTask" autocomplete="off" action="{{ action('TaskController@store') }}" method="POST" class="pop-up-box" data-parsley-validate>
				@csrf
				<div class="panel mb-0">
					<div class="panel-heading">
						<h4 class="panel-title">Create new task</h4>
					</div>
					<div class="panel-body">
						<div class="form-group">
							<label class="title">Title</label>
							<input data-parsley-required="true" name="title" type="text" class="form-control form-control-lg" placeholder="Enter Title">
						</div>
						<div class="form-group">
							<label class="title">Description (Optional)</label>
							<textarea rows="4" name="description" class="form-control form-control-lg" placeholder="Enter Task Description"></textarea>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label class="title">Start Date (Optional)</label>
									<input name="startdate" type="datepicker" placeholder="Select Start Date" class="form-control form-control-lg">
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label class="title">Due Date</label>
									<input name="duedate" type="datepicker" placeholder="Select Due Date" class="form-control form-control-lg">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label class="title">Priority</label>
									<select name="priority" class="form-control form-control-lg">
										<option value="3">Low</option>
										<option value="2">Medium</option>
										<option value="1">High</option>
									</select>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="title">Save your task into a collection for better organization</label>
							<select class="form-control form-control-lg"></select>
						</div>
					</div>
					<div class="panel-footer clearfix">
						<button type="submit" class="btn btn-primary btn-action pull-right">Create task</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>

<div class="modal" id="taskDetail" tabindex="-1" role="dialog" aria-labelledby="taskDetailTitle" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content pop-up">
			<form id="form_taskedit">
				@csrf
				<div class="panel-heading d-flex p-t-5 p-b-5" style="justify-content: space-between;">
					<h4 class="panel-title" style="flex: 2">
						<input id="task_title" name="edit_title" class="form-control form-control-lg form-control-plaintext" readonly="">
					</h4>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="panel-body">
					<div id="task_desc"></div>
					<input hidden id="task_id">
				</div>
				<div class="panel-footer">
					<div id="btngroup-edit" style="display: none">
						<button id="btn_canceledit" type="button" class="btn btn-default btn-action">Cancel Edit</button>
						<button id="btn_updatetask" type="button" class="btn btn-primary btn-action">Udpdate</button>
					</div>
					<button id="btn_edittask" type="button" class="btn btn-success btn-action">Edit</button>
				</div>
			</form>
		</div>
	</div>
</div>

<div id="btm-action" class="btm-nav" data-toggle="modal" data-target="#exampleModalCenter">
	<div class="btm-item">
		<i class="fa fa-2x fa-plus d-block"></i>
	</div>
</div>
@stop

@section("page_script")
<script type="text/javascript">
	$(function(){
		$("input[type='datepicker']").datepicker({
			format: "dd/mm/yyyy",
			autoclose: true,
			todayHighlight: true,
			startDate: new Date()
		});

		$(".task-panel").click(function(){
			var url = "/tasks/" + $(this).attr("data-task-id");
			$.get(url, function(response){
				console.log(response);
				var data = response.data;
				$("#task_id").val(data.id);
				$("#task_title").prop("readonly", "").val(data.title);
				$("#task_desc").text(data.description);
				$("#taskDetail").modal("show");
			});
		});

		$("#btn_edittask").click(function(){
			$("#task_title").removeAttr("readonly")
			.removeClass("form-control-plaintext")
			.focus();

			$("#btngroup-edit").show();
			$(this).hide();
		});

		$("#btn_canceledit").click(function(){
			$("#btngroup-edit").hide();
			$("#btn_edittask").show();
		});

		$("#btn_updatetask").click(function(){
			$.ajax({
				url: "/tasks/" + $("#task_id").val(),
				method: "PUT",
				data: $("#form_taskedit").serialize(),
				success: function(response){
					console.log(response);
					if(response.success){
						var data = response.data;
						updateTask(data);
						$("#taskDetail").modal("toggle");
					}
				}
			})
		});

		function updateTask(taskobj){
			var task_modal = $("div[data-task-id='" + taskobj.id + "']");
			task_modal.find(".task_title").text(taskobj.title);
		}
	});
</script>
@stop