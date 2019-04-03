@extends("layouts.nav")
@section("title", "Dashboard")
@section("header", "Dashboard")

@section("content")

<div class="stressLevelBar">
	<div class="progress rounded-corner">
		@if ($stressLevel == 0) 
		<div class="progress-bar bg-success progress-bar-striped progress-bar-animated" style="width: 100%">
		@elseif ($stressLevel >= 0 && $stressLevel < 25) 
		<div class="progress-bar bg-success progress-bar-striped progress-bar-animated" style="width: {{ $stressLevel }}%">
		@elseif ($stressLevel >= 25 && $stressLevel < 50)
		<div class="progress-bar bg-info progress-bar-striped progress-bar-animated" style="width: {{ $stressLevel }}%">
		@elseif ($stressLevel >= 50 && $stressLevel < 75)
		<div class="progress-bar bg-warning progress-bar-striped progress-bar-animated" style="width: {{ $stressLevel }}%">
		@elseif ($stressLevel >= 75)
		<div class="progress-bar bg-danger progress-bar-striped progress-bar-animated" style="width: {{ $stressLevel }}%">
		@endif 
		{{ $stressLevel }}% Stress Level
		</div>
	</div>
</div>

<h3>Today: {{ $setting->max_hour }} hour(s) per day.</h3>

<ul class="nav nav-pills">
	<li class="nav-items">
		<a href="#nav-pills-tab-1" data-toggle="tab" class="nav-link active">
			<span class="d-sm-none">Pending</span>
			<span class="d-sm-block d-none">Pending</span>
		</a>
	</li>
	<li class="nav-items">
		<a href="#nav-pills-tab-2" data-toggle="tab" class="nav-link">
			<span class="d-sm-none">Completed</span>
			<span class="d-sm-block d-none">Completed</span>
		</a>
	</li>
</ul>

<div class="tab-content p-0 bg-transparent">
	<!-- begin tab-pane -->
	<div class="tab-pane fade active show" id="nav-pills-tab-1">
		<div class="task-list row">
			@foreach(\App\Task::TASK_PRIORITY AS $key => $priority)
			@if(!empty($todayTasks[$key]))
			<div class="col-md-4" data-priority="{{ $key }}">
				@foreach($todayTasks[$key] AS $task)
				<div class="panel task-panel" data-task-id="{{ $task->id }}">
					<div class="panel-body">
						<strong class="f-s-13 task_title pull-left">
							#{{ $loop->iteration }} {{ $task->title }}
						</strong>
						<span class="label {{ \App\Task::TASK_PRIORITY_CLASS[$key] }} pull-right f-s-12">{{ $priority }}</span>
						@if($task->getCollection)
						<span class="label label-primary pull-right f-s-12 m-r-5">{{ optional($task->getCollection)->title }}</span>
						@endif
						<br>
						<strong class="f-s-13">Min. Duration: {{ $task->min_duration }} hours(s)</strong>
					</div>
					<div class="panel-footer clearfix">
						@if($task->due_date->isToday())
						<span class="today_warning hvr-pulse">
							<i class="fa fa-calendar m-r-5"></i>
							{{ optional($task->start_date)->format('d/m/Y') }}
							-			
							{{ optional($task->due_date)->format('d/m/Y') }}
						</span>
						@else 
						<i class="fa fa-calendar m-r-5"></i>
						{{ optional($task->start_date)->format('d/m/Y') }}
						-			
						{{ optional($task->due_date)->format('d/m/Y') }}
						@endif
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
	</div>
	<!-- end tab-pane -->
	<!-- begin tab-pane -->
	<div class="tab-pane fade" id="nav-pills-tab-2">
		<div class="task-list row"> 
			@foreach(\App\Task::TASK_PRIORITY AS $key => $priority)
			@if(!empty($completedTasks[$key]))
			@foreach($completedTasks[$key] AS $task)
			<div class="col-md-4" data-priority="{{ $key }}">
				<div class="panel" data-task-id="{{ $task->id }}">
					<div class="panel-body">
						<strong class="f-s-13 task_title pull-left">
							#{{ $loop->iteration }} {{ $task->title }}
						</strong>
						<span class="label {{ \App\Task::TASK_PRIORITY_CLASS[$key] }} pull-right f-s-12">{{ $priority }}</span>
						@if($task->getCollection)
						<span class="label label-primary pull-right f-s-12 m-r-5">{{ optional($task->getCollection)->title }}</span>
						@endif
						<br>
						<strong class="f-s-13">Min. Duration: {{ $task->min_duration }} hours(s)</strong>
					</div>
					<div class="panel-footer clearfix">
						@if($task->due_date->isToday())
						<span class="today_warning hvr-pulse">
							<i class="fa fa-calendar m-r-5"></i>
							{{ optional($task->start_date)->format('d/m/Y') }}
							-			
							{{ optional($task->due_date)->format('d/m/Y') }}
						</span>
						@else 
						<i class="fa fa-calendar m-r-5"></i>
						{{ optional($task->start_date)->format('d/m/Y') }}
						-			
						{{ optional($task->due_date)->format('d/m/Y') }}
						@endif
						@if($task->description)
						<i class="fa fa-list pull-right m-t-3"></i>
						@endif
					</div>
				</div>
			</div>
			@endforeach
			@endif
			@endforeach
		</div>
	</div>

	<h3>Upcomings</h3>
	<div class="task-list row">
		@foreach(\App\Task::TASK_PRIORITY AS $key => $priority)
		@if(!empty($upcomingTasks[$key]))
		<div class="col-md-4">
			@foreach($upcomingTasks[$key] AS $task)
			<div class="panel task-panel" data-task-id="{{ $task->id }}">
				<div class="panel-body">
					<strong class="f-s-13 task_title pull-left">
						#{{ $loop->iteration }} {{ $task->title }}
					</strong>
					<span class="label {{ \App\Task::TASK_PRIORITY_CLASS[$key] }} pull-right f-s-12">{{ $priority }}</span>
					@if($task->getCollection)
					<span class="label label-primary pull-right f-s-12 m-r-5">{{ optional($task->getCollection)->title }}</span>
					@endif
				</div>
				<div class="panel-footer clearfix">
					@if($task->start_date || $task->end_date)
					<i class="fa fa-calendar m-r-5"></i>
					@if($task->start_date)
					{{ optional($task->start_date)->format('d/m/Y') }}
					-
					@endif
					@endif
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
										<label class="title">Start Date</label>
										<input id="start_date" name="startdate" data-parsley-required="true" type="datepicker" placeholder="Select Start Date" class="form-control form-control-lg">
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label class="title">Due Date</label>
										<input name="duedate" data-parsley-required="true" type="datepicker" placeholder="Select Due Date" class="form-control form-control-lg">
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label class="title">Min. Duration</label>
										<input id="min_input" name="min_duration" data-parsley-required="true" data-parsley-type="integer" placeholder="Min. Duration" class="form-control form-control-lg">
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label class="title">Priority</label>
										<select name="priority" data-parsley-required="true" class="form-control form-control-lg">
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
							<button type="button" id="btn_submit" class="btn btn-primary btn-action pull-right">Create task</button>
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
		$(function(){
			$("input[type='datepicker']").datepicker({
				format: "dd/mm/yyyy",
				autoclose: true,
				todayHighlight: true,
				startDate: new Date()
			});

			$("#btm-action").click(function(){
				triggerUpdateModal();
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
				var url = "/tasks/" + $("#task_id").val();
				$.get(url, function(response){
					console.log(response);
					if(response.success){
						var data = response.data;
						triggerUpdateModal(data);
					}
				});
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

			var stresslvl = parseInt("{{ $used_hour }}");
			var oldMinDuration = 0; 

			$("#btn_submit").click(function(e){

				var today = moment().format("DD/MM/YYYY");
				var min = parseInt($("#min_input").val());						
				var max = "{{ $setting->max_hour }}";
				var taskDate = $("#start_date").datepicker("getDate");
				taskDate = moment(taskDate).format("DD/MM/YYYY");
				var newMin = stresslvl - oldMinDuration;
				var leftHour = max - newMin;
				newMin += min;
				var hourOutput = "Please dont overload yourself with too many tasks, are you sure you want to continue add task?";

				if (leftHour>0){
					hourOutput="You have only " +leftHour + " hour(s) left for today. <br/>" + hourOutput;
				}
				else{
					hourOutput="You have exceeded " +max+" hours of works for today."+hourOutput; 
				}


				if( taskDate == today && newMin > max){
					Swal.fire({
						title: 'Stress Overload',
						html: hourOutput,
						type: 'warning',
						showCancelButton: true,
						confirmButtonColor: '#3085d6',
						confirmButtonText: 'Yes!',
						cancelButtonColor: '#d33',
					}).then((result) => {
						if (result.value) {
							$("#formTask").submit();
						}
					});

				}
				else{
					$("#formTask").submit();
				}
			});



			function triggerUpdate(){
				$("#task_title").removeAttr("readonly")
				.addClass("form-control-plaintext");

				$("#btngroup-edit").hide();
				$("#btngroup-show").show();
			}

			var stressLvl = "{{ $stressLevel }}";

			$("#btn_completetask").click(function(){
				var task_id = $(this).closest("form").find("#task_id").val();
				var elem = $("div[data-task-id='" + task_id + "']");
				var url = "/tasks/" + task_id;
				$.get(url, function(response){
					console.log(response);
					var data = response.data;
					var weightage = data.weightage;
					stressLvl = parseInt(stressLvl) - weightage;

					$.ajax({
						type: "POST",
						url: "/task/completetask/" + task_id,
						data: { _token: '{{ csrf_token() }}' },
					}).done(function(response){

						if(response.success){
							alert(data.priority);
							var target_elem = $("div[data-priority='" + data.priority + "']");
							$("#taskDetail").modal("hide");
							elem.remove();

							if(target_elem.find("div[data-task-id]").length == 0){
								appendPlaceholder(target_elem, data.priority);
							}

							updateBar(stressLvl);
						} else {
							alert(response.msg);
						}
					});
				});

			});

			function updateBar(stress_lvl){
				var cssClass = "bg-success";
				if(stress_lvl >= 25 && stress_lvl < 50) {
					cssClass = "bg-info";
				} else if(stress_lvl >= 50 && stress_lvl < 75) {
					cssClass = "bg-warning";
				} else if(stress_lvl >= 75) {
					cssClass = "bg-danger";
				}

				var stresslvltext = stress_lvl;

				if(stress_lvl <= 0){
					stress_lvl = 100;
					stresslvltext = 0;
				}

				$(".progress-bar").removeClass().addClass("progress-bar progress-bar-striped progress-bar-animated " + cssClass)
				.css("width", stress_lvl + "%")
				.html(stresslvltext + "% Stress Level");
			}

			function appendPlaceholder(target, priority){
				var priority_text = "Low";

				switch(priority){
					case 1:
					priority_text = "High";
					breakl
					case 2:
					priority_text = "Medium";
					break;
					case 3:
					priority_text = "Low";
				}

				target.html(
					'<div class="panel"><div class="panel-body">' +
					priority_text + ' priority tasks goes here!' +
					'</div></div>'
					);
			}

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

			function triggerUpdateModal(taskobj){
				var modal = $("#exampleModalCenter");

				if(taskobj != null){

					oldMinDuration = taskobj.min_duration;

					$("#taskDetail").modal("toggle");
					modal.find(".panel-title").text("Edit Task");
					modal.find("input[name='title']").val(taskobj.title);
					modal.find("textarea[name='description']").text(taskobj.description);
					modal.find("input[name='startdate']").val(taskobj.shortStartDate);
					modal.find("input[name='duedate']").val(taskobj.shortDueDate);
					modal.find("input[name='min_duration']").val(taskobj.min_duration);
					modal.find("select[name='priority']").val(taskobj.priority);
					modal.find("select[name='collection_id']").val(taskobj.collection_id);
					modal.find("form").attr("action", "/tasks/" + taskobj.id).append('<input type="hidden" name="_method" value="PUT">');
					modal.find("#btn_submit").text("Update Task");
				} else {

					oldMinDuration = 0;

					modal.find("input[name='_method']").remove();
					modal.find("form").attr("action", "/tasks/").attr("method", "POST").trigger("reset");
					modal.find("#btn_submit").text("Create Task");
				}

				modal.modal("toggle");
			}
		});
	</script>
	@stop
