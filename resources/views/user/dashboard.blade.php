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
							<div class="col-md-4">
								<div class="form-group">
									<label class="title">Start Date (Optional)</label>
									<input name="startdate" type="datepicker" placeholder="Select Start Date" class="form-control form-control-lg">
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label class="title">Due Date</label>
									<input name="duedate" type="datepicker" placeholder="Select Due Date" class="form-control form-control-lg">
								</div>
							</div>
							<div class="col-md-4">
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
							<select name="collection_id" class="form-control form-control-lg">
								<option value="">None</option>
								@foreach($cls AS $cl)
								<option value="{{ $cl->id }}">{{ $cl->title }}</option>
								@endforeach
							</select>
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
					<div>
						<i class="fa fa-calendar m-r-5"></i><span id="task_startdate"></span><span id="task_duedate"></span>
					</div>

					<div id="task_desc"></div>

					<input hidden id="task_id">
				</div>
				<div class="panel-footer">
					<div id="btngroup-edit" style="display: none">
						<button id="btn_canceledit" type="button" class="btn btn-default btn-action">Cancel Edit</button>
						<button id="btn_updatetask" type="button" class="btn btn-primary btn-action">Udpdate</button>
					</div>
					<div id="btngroup-show">
						<button id="btn_edittask" type="button" class="btn btn-success btn-action">Edit</button>
						<button id="btn_deletetask" type="button" class="btn btn-danger btn-action">Delete</button>
						<button id="btn_completetask" class="btn btn-lime btn-action pull-right" type="button">I've completed this task</button>
					</div>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.33.1/sweetalert2.all.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.33.1/sweetalert2.min.css" />

<script type="text/javascript">
	$(function(){
		$("input[type='datepicker']").datepicker({
			format: "dd/mm/yyyy",
			autoclose: true,
			todayHighlight: true,
			startDate: new Date()
		});

		$(".task-panel").click(function(){
			triggerUpdate();
			var url = "/tasks/" + $(this).attr("data-task-id");
			$.get(url, function(response){
				console.log(response);
				var data = response.data;
				$("#task_id").val(data.id);
				$("#task_title").prop("readonly", "").val(data.title);
				$("#task_desc").text(data.description);
				$("#task_startdate").text(data.shortStartDate);

				if(data.shortStartDate != null){
					$("#task_duedate").text(" - " + data.shortDueDate);
				} else {
					$("#task_duedate").text(data.shortDueDate);
				}
				
				$("#taskDetail").modal("show");
			});
		});

		$("#btn_edittask").click(function(){
			$("#task_title").removeAttr("readonly")
			.removeClass("form-control-plaintext")
			.focus();

			$("#btngroup-edit").show();
			$("#btngroup-show").hide();
		});

		$("#btn_canceledit").click(function(){
			triggerUpdate();
		});

		$("#btn_updatetask").click(function(){
			$.ajax({
				url: "/tasks/" + $("#task_id").val(),
				method: "PUT",
				data: $("#form_taskedit").serialize(),
				success: function(response){
					if(response.success){
						var data = response.data;
						updateTask(data);
						$("#taskDetail").modal("toggle");
					}
				}
			})
		});

		$("#btn_deletetask").click(function(){
			Swal.fire({
				title: 'Are you sure?',
				text: "Deleted tasks cannot be recovered.",
				type: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Yes, delete it!'
			}).then((result) => {
				if (result.value) {
					$.ajax({
						url: "/tasks/" + $("#task_id").val(),
						method: "DELETE",
						data: $("#form_taskedit").serialize(),
						success: function(response){
							if(response.success){
								window.location.reload();
							}
						}
					})
				}
			})
		});

		function triggerUpdate(){
			$("#task_title").removeAttr("readonly")
			.addClass("form-control-plaintext");

			$("#btngroup-edit").hide();
			$("#btngroup-show").show();
		}

		function updateTask(taskobj){
			var task_modal = $("div[data-task-id='" + taskobj.id + "']");
			task_modal.find(".task_title").text(taskobj.title);
		}
	});
</script>
@stop