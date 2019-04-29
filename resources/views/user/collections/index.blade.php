@extends("layouts.nav")
@section("title", "Collections")
@section("header", "Collections")

@section("content")

<div class="row">
	<div class="col-md-6">
		<form action="{{ action('CollectionController@store') }}" data-parsley-validate="true" method="POST" autocomplete="off">
			@csrf
			<div class="panel">
				<div class="panel-heading">
					<h4 class="panel-title">Create new collection</h4>
				</div>
				<div class="panel-body">
					<div class="form-group">
						<label class="title">Title</label>
						<input name="title" type="text" required="" class="form-control form-control-lg" placeholder="Enter Title">
					</div>
					<div class="form-group">
						<label class="title">Description (Optional)</label>
						<textarea class="form-control form-control-lg" rows="10" name="description" placeholder="Enter Description"></textarea>
					</div>
				</div>
				<div class="panel-footer clearfix">
					<button type="submit" class="btn btn-primary btn-action pull-right">Create collection</button>
				</div>
			</div>
		</form>
	</div>

	<div class="col-md-6">
		<div class="table-responsive">
			<table class="table table-bordered table-striped bg-white">
				<thead>
					<tr>
						<th>Title</th>
						<th width="1"></th>
					</tr>
				</thead>
				<tbody>
					@foreach($collections AS $cl)
					<tr data-cl="{{ $cl->id }}">
						<td>{{ $cl->title }}<br>{{ $cl->description }}</td>
						<td class="nowrap">
							<button type="button" class="btn btn-success btn-edit btn-action">Edit</button>
							<button type="button" class="btn btn-danger btn-delete btn-action">Delete</button>
						</td>
					</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
</div>

<div class="modal" id="modalEdit" tabindex="-1" role="dialog" aria-labelledby="taskDetailTitle" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content pop-up">
			<form id="form_edit" data-parsley-validate="true" method="POST">
				@method("PATCH")
				@csrf
				<div class="modal-header">
					<h4 class="modal-title">Edit Collection</h4>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="form-group">
						<label class="title">Title</label>
						<input name="title" type="text" class="form-control form-control-lg" placeholder="Enter Title">
					</div>
					<div class="form-group">
						<label class="title">Description (Optional)</label>
						<textarea class="form-control form-control-lg" rows="10" name="description" placeholder="Enter Description"></textarea>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default btn-action" data-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-primary btn-action">Udpdate</button>
				</div>
			</form>
		</div>
	</div>
</div>

@stop

@section("page_script")
<script type="text/javascript">
	$(function(){
		$(".btn-delete").click(function(){

			var $tr = $(this).closest("tr");
			var collection_id = $tr.attr('data-cl');

			Swal.fire({
				title: 'Are you sure?',
				text: "Deleted collection cannot be recovered.",
				type: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Yes, delete it!'
			}).then((result) => {
				if (result.value) {
					$.ajax({
						url: "/collections/" + collection_id,
						method: "DELETE",
						data: {
							_token: '{{ csrf_token() }}',
						},
						success: function(response){
							if(response.success){
								notyf.success(response.msg);
								$tr.remove();
							}
						}
					})
				}
			})
		});

		$(".btn-edit").click(function(){
			var $tr = $(this).closest("tr");
			var collection_id = $tr.attr('data-cl');

			$.get("/collections/" + collection_id, function(response){
				console.log(response);
				var form = $("#form_edit");
				var cl = response.data;
				form.find("input[name='title']").val(cl.title);
				form.find("textarea[name='description']").val(cl.description);
				form.find("input[name='cl_id']").val(cl.id);

				form.attr("action", "/collections/" + cl.id);

				$("#modalEdit").modal("toggle");
			});
		});


	});
</script>
@stop