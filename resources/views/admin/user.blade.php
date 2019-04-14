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
					<button type="button" class="btn btn-primary">Edit</button>
					<button type="button" class="btn btn-warning">Deactivate</button>
				</td>
			</tr>
			@endforeach
		</tbody>
	</table>
</div>
@stop

@section("page_script")

@stop