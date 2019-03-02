@extends("layouts.nav")
@section("title", "Tasks")
@section("header", "Tasks")

@section("content")

<div class="task-list row">
	<div class="col-md-4">
		@foreach($tasks->where("priority", "1") AS $task)
		<div class="panel">
			<div class="panel-body">
				#{{ $loop->iteration }}
				<strong class="f-s-13">{{ $task->title }}</strong>
				<span class="label label-danger pull-right f-s-12">High</span>
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
	<div class="col-md-4">
		@foreach($tasks->where("priority", "2") AS $task)
		<div class="panel">
			<div class="panel-body">
				#{{ $loop->iteration }}
				<strong class="f-s-13">{{ $task->title }}</strong>
				<span class="label label-warning pull-right f-s-12">Medium</span>
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
	<div class="col-md-4">
		@foreach($tasks->where("priority", "3") AS $task)
		<div class="panel">
			<div class="panel-body">
				#{{ $loop->iteration }}
				<strong class="f-s-13">{{ $task->title }}</strong>
				<span class="label label-green pull-right f-s-12">Low</span>
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
</div>
@stop