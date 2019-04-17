@extends("layouts.nav")
@section("title", "Groups")
@section("header", "Groups")

@section('content')
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
@endsection

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