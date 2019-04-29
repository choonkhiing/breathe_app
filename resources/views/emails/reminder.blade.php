@extends('beautymail::templates.sunny')

@section('content')
@include('beautymail::templates.sunny.contentStart')
<h1 style="text-align: center;margin-bottom: 30px;margin-top: 25px;">Breathe Reminder</h1>
<p>
	Hi <strong>{{ $user->name }}</strong>, 
</p>
<p>
	There is a task due soon! Please make sure that you arrange your tasks well in finishing them on time.</b> 
</p>
<table width="100%" cellpadding="0" cellspacing="0">
	<tr style="background: whitesmoke;">
		<th width="33%" style="padding: 10px 15px; border-bottom: 1px solid gainsboro;">Task Title</th>
		<th width="33%" style="padding: 10px 15px; border-bottom: 1px solid gainsboro;">Due Date</th>
		<th width="33%" style="padding: 10px 15px; border-bottom: 1px solid gainsboro;">Group</th>
	</tr>
	@foreach($tasks AS $task)
	<tr style="background: white;">
		<td width="33%" style="padding: 10px 15px; border-bottom: 1px solid gainsboro; text-align: center;">{{ $task->title }}</td>
		<td width="33%" style="padding: 10px 15px; border-bottom: 1px solid gainsboro; text-align: center;">{{ date('d-m-Y', strtotime($task->due_date)) }}</td>
		@if ($task->group)
		<td width="33%" style="padding: 10px 15px; border-bottom: 1px solid gainsboro; text-align: center;">{{ $task->group->title }}</td>
		@else
		<td width="33%" style="padding: 10px 15px; border-bottom: 1px solid gainsboro; text-align: center;">Individual</td>
		@endif
	</tr>
	@endforeach
</table>

<p style="text-align: center;"><a href="http://breathe.geekycs.com" style="padding: 10px 45px;background: #00acac;color: white;font-size: 16px;margin-top: 37px;display: block;    margin-bottom: -35px;">Visit Us</a></p>

@include('beautymail::templates.sunny.contentEnd')
@stop
