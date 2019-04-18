@extends("layouts.nav")
@if ($group == null)
@section("title", "Individual Tasks")
@section("header", "Individual Tasks")
@else
@section("title", "Groups Tasks")
@section("header", "Group Tasks")
@endif

@section("content")
@if ($datefilter == null) 
<div class="stressLevelBar">
	<div class="progress rounded-corner">
		@if ($organizedTasks->stressLevel == 0) 
		<div class="progress-bar bg-green progress-bar-striped progress-bar-animated" style="width: 100%">
		@elseif ($organizedTasks->stressLevel >= 0 && $organizedTasks->stressLevel < 25) 
		<div class="progress-bar bg-green progress-bar-striped progress-bar-animated" style="width: {{ $organizedTasks->stressLevel }}%">
		@elseif ($organizedTasks->stressLevel >= 25 && $organizedTasks->stressLevel < 50) 
		<div class="progress-bar bg-success progress-bar-striped progress-bar-animated" style="width: {{ $organizedTasks->stressLevel }}%">
		@elseif ($organizedTasks->stressLevel >= 50 && $organizedTasks->stressLevel < 75)
		<div class="progress-bar bg-info progress-bar-striped progress-bar-animated" style="width: {{ $organizedTasks->stressLevel }}%">
		@elseif ($organizedTasks->stressLevel >= 75 && $organizedTasks->stressLevel < 100)
		<div class="progress-bar bg-warning progress-bar-striped progress-bar-animated" style="width: {{ $organizedTasks->stressLevel }}%">
		@elseif ($organizedTasks->stressLevel >= 100)
		<div class="progress-bar bg-danger progress-bar-striped progress-bar-animated" style="width: {{ $organizedTasks->stressLevel }}%">
		@endif 
		{{ $organizedTasks->stressLevel }}% Stress Level
		</div>
	</div>
</div>
@endif

<div class="row">
	<div class="col-md-6">
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
	</div>
	<div class="col-md-6">
		<!--Filter date for displaying task-->
		<div class="filterActionDiv" style="margin-bottom: 25px; float:right;">
			<form method="GET" id="filterForm">
				Filter By: <div id="filterdaterange" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; margin: 0px 10px; display: inline-block;">
					<i class="fa fa-calendar"></i>&nbsp;
					<span></span> <i class="fa fa-caret-down"></i>
					<input type="hidden" id="datefilter" name="datefilter" />
					<input type="hidden" id="groupid" name="groupid" />
				</div> 
				<button class="btn btn-primary">Submit</button>
			</form>
		</div>
	</div>
</div>
@if ($group != null)
<div class="note note-info">
  <div class="note-content">
    <h4><b>{{ $group->title }}</b></h4>
    <p style="margin-bottom: 0px;">{{ $group->description }}</p>
  </div>
</div>

@endif 
@if ($datefilter == null)
<h3>Today</h3>
<div class="tab-content p-0 bg-transparent">
	<!-- begin tab-pane -->
	<div class="tab-pane fade active show" id="nav-pills-tab-1">
		<div class="task-list row">
			@foreach(\App\Task::TASK_PRIORITY AS $key => $priority)
			@if(!empty($organizedTasks->todayTasks[$key]))
			<div class="col-md-4" data-priority="{{ $key }}">
				@foreach($organizedTasks->todayTasks[$key] AS $task)
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
			@if(!empty($organizedTasks->completedTasks[$key]))
			@foreach($organizedTasks->completedTasks[$key] AS $task)
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
		@if(!empty($organizedTasks->upcomingTasks[$key]))
		<div class="col-md-4">
			@foreach($organizedTasks->upcomingTasks[$key] AS $task)
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
@else 
<h3 class="fliterDate">From: <span class="filterBy">All</span> <a href="tasks{{ $group != null ? '?id='.$group->id : '' }}">Reset</a></h3>
<div class="tab-content p-0 bg-transparent">
	<!-- begin tab-pane -->
	<div class="tab-pane fade active show" id="nav-pills-tab-1">
		<div class="task-list row">
			@foreach(\App\Task::TASK_PRIORITY AS $key => $priority)
			@if(!empty($organizedTasks->filterTasks[$key]))
			<div class="col-md-4" data-priority="{{ $key }}">
				@foreach($organizedTasks->filterTasks[$key] AS $task)
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
			@if(!empty($organizedTasks->completedTasks[$key]))
			@foreach($organizedTasks->completedTasks[$key] AS $task)
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
@endif

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
							<div class="form-group">
								<label class="title">Priority</label>
								<select name="priority" data-parsley-required="true" class="form-control form-control-lg">
									<option value="3">Low</option>
									<option value="2">Medium</option>
									<option value="1">High</option>
								</select>
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
							<div class="form-group">
								<div class="form-check">
  									<input type="checkbox" name="reminderCheckbox" class="form-check-input" id="reminderCheckbox" />
  									<label class="form-check-label" for="reminderCheckbox">Set Reminder?<label>
  								</div>
							</div>
							<div class="row" id="reminderRow"> 
								<div class="col-md-6 d-none">
									<div class="form-group">
										<label class="reminder_time">Reminder Time</label>
										<input name="reminder_time" type="text" id="datetimepicker" class="form-control form-control-lg" data-parsley-requiredoncheckbox="reminderCheckbox" placeholder="Select Reminder Time">
									</div>
								</div>
								<div class="col-md-6 d-none">
									<div class="form-group">
										<label class="day_before_remind">Day Before Remind</label>
										<input name="day_before_remind" type="text" class="form-control form-control-lg" placeholder="Enter Day" data-parsley-requiredoncheckbox="reminderCheckbox" data-parsley-type="digits">
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
						//Get url param
			var urlParams = new URLSearchParams(window.location.search);
			
			// //daterangepicker
			if (urlParams.has('datefilter')) {
				var datefilterStr = urlParams.get('datefilter');
				var dates = datefilterStr.split(" - ");
				start = moment(dates[0], 'DD-MM-YYYY');
				end = moment(dates[1], 'DD-MM-YYYY');
			}
			else {
				//daterangepicker
				start = moment().startOf('month');
				end = moment().endOf('month');
			}
			
			console.log(start, end);

			function cb(start, end) {
				$('#filterdaterange span').html(start.format('DD-MM-YYYY') + ' - ' + end.format('DD-MM-YYYY'));
			}
			
			$('#filterdaterange').daterangepicker({
				locale: {
					format: 'DD-MM-YYYY'
				},
				startDate: start,
				endDate: end,
				ranges: {
					'Today': [moment(), moment()],
					'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
					'Last 7 Days': [moment().subtract(6, 'days'), moment()],
					'Last 30 Days': [moment().subtract(29, 'days'), moment()],
					'This Month': [moment().startOf('month'), moment().endOf('month')],
					'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month')		.endOf('month')]
				}
			}, cb);
			
			cb(start, end);
			
			var datefilter = start + ' - ' + end;
			
			//Get url param
			var urlParams = new URLSearchParams(window.location.search);

			if (urlParams.has('datefilter')) {
				datefilter = urlParams.get('datefilter');
				$("#filterForm input#datefilter").attr('value',  datefilter);
				$('.filterBy').html(datefilter);
			}	
			else {
				$('.filterBy').html(start.format('DD-MM-YYYY') + ' - ' + end.format('DD-MM-YYYY'));
			}
			
			$(".daterangepicker  .ranges li").click(function () {
				datefilter = $(this).text();
			});
			
			$("#filterForm button").click(function (e) {
				@if ($group != null)
				$("#filterForm input#groupid").attr('value',  "{{ $group->id }}");
				@endif
				$("#filterForm input#datefilter").attr('value',  $('#filterdaterange span').html());
			});
			

			$("input[type='datepicker']").datepicker({
				format: "dd/mm/yyyy",
				autoclose: true,
				todayHighlight: true,
				startDate: new Date()
			});

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
 
			var stressLevel = "{{ $organizedTasks->stressLevel }}";
			$("#btn_submit").click(function(e){
				var hourOutput = "Please dont overload yourself with too many tasks, are you sure you want to continue add task?";

				if(parseFloat(stressLevel)+12.5 > 100){
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

			var stressLvl = "{{ $organizedTasks->stressLevel }}";

			$("#btn_completetask").click(function(){
				var task_id = $(this).closest("form").find("#task_id").val();
				var elem = $("div[data-task-id='" + task_id + "']");
				var url = "/tasks/" + task_id;
				$.get(url, function(response){
					var data = response.data;
					var weightage = data.weightage;					
					stressLvl = parseInt(stressLvl) - weightage;

					console.log(weightage);
					console.log(stressLvl);
					$.ajax({
						type: "POST",
						url: "/task/completetask/" + task_id,
						data: { _token: '{{ csrf_token() }}' },
					}).done(function(response){

						if(response.success){
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
				var cssClass = "bg-green";
				if (stress_lvl >= 25 && stress_lvl < 50) {
					cssClass = "bg-success";
				} else if(stress_lvl >= 50 && stress_lvl < 75) {
					cssClass = "bg-info";
				} else if(stress_lvl >= 75  && stress_lvl < 100) {
					cssClass = "bg-warning";
				}
				else if (stressLevel >= 100) {
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
					$("#taskDetail").modal("toggle");
					modal.find(".panel-title").text("Edit Task");
					modal.find("input[name='title']").val(taskobj.title);
					modal.find("textarea[name='description']").text(taskobj.description);
					modal.find("input[name='startdate']").val(taskobj.shortStartDate);
					modal.find("input[name='duedate']").val(taskobj.shortDueDate);
					modal.find("input[name='min_duration']").val(taskobj.min_duration);
					modal.find("select[name='priority']").val(taskobj.priority);
					modal.find("select[name='collection_id']").val(taskobj.collection_id);

					if (taskobj.settings) {
						$("#reminderCheckbox").prop("checked", true);
						$('[name="day_before_remind"]').prop('required',true);
   						$('[name="reminder_time"]').prop('required',true);
   						$('#reminderRow .col-md-6').removeClass('d-none');
						modal.find("input[name='day_before_remind']").val(taskobj.settings.day_before_remind);
						modal.find("input[name='reminder_time']").val(taskobj.settings.reminder_time);
					}
					else {
						$("#reminderCheckbox").prop("checked", false);
						$('[name="day_before_remind"]').prop('required',false);
   						$('[name="reminder_time"]').prop('required',false);
   						$('#reminderRow .col-md-6').addClass('d-none');
					}

					modal.find("form").attr("action", "/tasks/" + taskobj.id).append('<input type="hidden" name="_method" value="PUT">');
					modal.find("#btn_submit").text("Update Task");
				} else {
					modal.find("input[name='_method']").remove();
					modal.find("form").attr("action", "/tasks").attr("method", "POST").trigger("reset");
					modal.find("#btn_submit").text("Create Task");
				}

				modal.modal("toggle");
			}
		});

		$(function(){
			$('#datetimepicker').datetimepicker({
				format: "HH:mm",
			});
		});


		//if checkbox is checked, then the settings are required
        $("#reminderCheckbox").click( function(){
   			if( $(this).is(':checked') ) {
   				$('[name="day_before_remind"]').prop('required',true);
   				$('[name="reminder_time"]').prop('required',true);
   				$('#reminderRow .col-md-6').removeClass('d-none');
   			}
   			else {
   				$('[name="day_before_remind"]').prop('required',false);
   				$('[name="reminder_time"]').prop('required',false);
   				$('#reminderRow .col-md-6').addClass('d-none');
   			}
		});
	</script>
	@stop
