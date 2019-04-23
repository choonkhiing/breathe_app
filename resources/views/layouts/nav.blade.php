<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<title>@yield("title") - Breathe</title>
	<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
	<meta content="" name="description" />
	<meta content="" name="author" />

	<!-- ================== BEGIN BASE CSS STYLE ================== -->

	<link href="https://fonts.googleapis.com/css?family=Karla:300,400,600,700" rel="stylesheet" type="text/css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" />
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp" crossorigin="anonymous">
	<link href="/css/default.css" rel="stylesheet" id="theme">
	<link href="/css/style.min.css" rel="stylesheet">
	<link href="/css/style-responsive.min.css" rel="stylesheet">
	<link href="/css/animate.css" rel="stylesheet" />
	<link href="/css/custom.css" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">
	<link href="https://cdnjs.cloudflare.com/ajax/libs/remodal/1.1.1/remodal-default-theme.min.css" rel="stylesheet" >
	<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
	<link rel="stylesheet" href="css/notfy.min.css" />

	<link href="/css/bootstrap-datepicker.min.css" rel="stylesheet">
	<link href="/css/bootstrap-datepicker3.min.css" rel="stylesheet">

	<!-- ================== END BASE CSS STYLE ================== -->
	
	<!-- ================== BEGIN BASE JS ================== -->
	<!-- <script async src="https://cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/pace.min.js"></script> -->
	<!-- ================== END BASE JS ================== -->
</head>
<body>

	<!-- begin #page-container -->
	<div id="page-container" class="page-container fade page-sidebar-fixed page-header-fixed">
		<!-- begin #header -->
		<div id="header" class="header navbar-default">
			<!-- begin navbar-header -->
			<div class="navbar-header">
				<a href="index.html" class="navbar-brand"><span class="navbar-logo"></span> <b>Breathe</b></a>
				<button type="button" class="navbar-toggle" data-click="sidebar-toggled">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
			</div>
			<!-- end navbar-header -->
			<ul class="navbar-nav navbar-right">

				<li class="dropdown">
					<a href="javascript:;" data-toggle="dropdown" class="dropdown-toggle f-s-14">
						<i class="fa fa-bell"></i>
						@if(count(Auth::user()->getInvitations) > 0)
						<span class="label">{{ count(Auth::user()->getInvitations) }}</span>
						@endif
					</a>
					<ul class="dropdown-menu media-list dropdown-menu-right dropdown-inv">
						<li class="dropdown-header">Group Invitation ( {{ count(Auth::user()->getInvitations) ?? '' }} )</li>
						@foreach(Auth::user()->getInvitations AS $inv)
						<li class="media">
							<a href="javascript:;">
								<div class="media-body">
									<h6 class="media-heading">
										{{ $inv->getInviter->name }} invited you to join {{ $inv->getGroup->title }}
										<i class="fa fa-exclamation-circle text-danger"></i>
									</h6>
									<div class="text-muted f-s-11">{{ $inv->created_at->diffForHumans() }}</div>
									<div class="m-t-10" data-id="{{ $inv->id }}">
										<span class="btn btn-success btn-sm btn-invitation" data-action="accept">Accept</span>
										<span class="btn btn-default btn-sm btn-invitation" data-action="decline">Decline</span>
									</div>
								</div>
							</a>
						</li>
						@endforeach
						<!-- <li class="dropdown-footer text-center">
							<a href="javascript:;">View more</a>
						</li> -->
					</ul>
				</li>

				<li class="dropdown navbar-user">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">
						<img src="{{ Auth::user()->profile_pic }}" alt="" /> 
						<span class="d-none d-md-inline">{{ Auth::user()->name }}</span> <b class="caret"></b>
					</a>
					<div class="dropdown-menu dropdown-menu-right">
						<a href="/profile" class="dropdown-item">Profile</a>
						<div class="dropdown-divider"></div>
						<form method="POST" action="{{ action('UserController@logout') }}" class="logout">{{ csrf_field() }}<button class="logout_btn" type="submit" class="logout">Log Out</button></form>

					</div>
				</li>
			</ul>
		</div>	
		
		<!-- begin #sidebar -->
		<div id="sidebar" class="sidebar">
			<!-- begin sidebar scrollbar -->
			<div data-scrollbar="true" data-height="100%">
				<!-- begin sidebar nav -->
				<ul class="nav">
					<li class="nav-header">Navigation</li>
					<li>
						<a href="{{ action('UserController@dashboard') }}">
							<i class="fa fa-chalkboard"></i>
							<span>Dashboard</span>
						</a>
					<!-- 	<ul class="sub-menu">
							<li><a href="index.html">Dashboard v1</a></li>
							<li><a href="index_v2.html">Dashboard v2</a></li>
						</ul> -->
					</li>
					<!-- <li class="has-sub">
						<a href="javascript:;">
							<span class="badge pull-right">10</span>
							<i class="fa fa-hdd"></i> 
							<span>Email</span>
						</a>
						<ul class="sub-menu">
							<li><a href="email_inbox.html">Inbox</a></li>
							<li><a href="email_compose.html">Compose</a></li>
							<li><a href="email_detail.html">Detail</a></li>
						</ul>
					</li> -->
					<li>
						<a href="{{ action('CollectionController@index') }}">
							<i class="fas fa-folder-open"></i> 
							<span>Collections</span> 
						</a>
					</li>
					<!-- <li>
						<a href="{{ action('TaskController@index') }}">
							<i class="fas fa-tasks"></i> 
							<span>Tasks</span> 
						</a>
					</li> -->
					<li>
						<a href="/groups">
							<i class="fas fa-users"></i> 
							<span>Groups</span> 
						</a>
					</li>
					<li>
						<a href="/settings">
							<i class="fas fa-cogs"></i> 
							<span>Settings</span> 
						</a>
					</li>
				</ul>
				<!-- end sidebar nav -->
			</div>
			<!-- end sidebar scrollbar -->
		</div>
		<div class="sidebar-bg"></div>
		<!-- end #sidebar -->
		
		<!-- begin #content -->
		<div id="content" class="content">

			@if(Session::has('message'))
			<div class="alert alert-{{ Session::get('type') }} fade show">
				<span class="close" data-dismiss="alert">×</span>
				{{ Session::get('message') }}
			</div>
			@endif

			<!-- begin breadcrumb -->
<!-- 			<ol class="breadcrumb pull-right">
				<li class="breadcrumb-item"><a href="javascript:;">Home</a></li>
				<li class="breadcrumb-item"><a href="javascript:;">Page Options</a></li>
				<li class="breadcrumb-item active">Blank Page</li>
			</ol> -->
			<!-- end breadcrumb -->
			<!-- begin page-header -->
			<h1 class="page-header">@yield("header")<!-- <small>header small text goes here...</small> --></h1>
			<!-- end page-header -->

			@yield("content")
			
			<!-- begin panel -->
			<!-- <div class="panel panel-inverse">
				<div class="panel-heading">
					<div class="panel-heading-btn">
						<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
						<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload"><i class="fa fa-redo"></i></a>
						<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
						<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger" data-click="panel-remove"><i class="fa fa-times"></i></a>
					</div>
					<h4 class="panel-title">Panel Title here</h4>
				</div>
				<div class="panel-body">
					Panel Content Here
				</div>
			</div> -->
			<!-- end panel -->
		</div>
		<!-- end #content -->

		<!-- begin scroll to top btn -->
		<a href="javascript:;" class="btn btn-icon btn-circle btn-success btn-scroll-to-top fade" data-click="scroll-top"><i class="fa fa-angle-up"></i></a>
		<!-- end scroll to top btn -->

	</div>
	<!-- end page container -->

	<!-- ================== BEGIN BASE JS ================== -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"></script>
	<script src="/js/jquery-3.3.1.min.js"></script>
	<script src="/js/jquery-ui.min.js"></script>
	<script src="/js/bootstrap.min.js"></script>
	<script src="/js/bootstrap-datepicker.min.js"></script>
	<script src="/js/jquery.slimscroll.min.js"></script>
	<script src="/js/js.cookie.js"></script>
	<script src="/js/parsley.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js">
	</script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/remodal/1.1.1/remodal.min.js"></script>
	<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
	<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

	<script src="js/notfy.min.js"></script>

	
	
	<!--[if lt IE 9]>
		<script src="../assets/crossbrowserjs/html5shiv.js"></script>
		<script src="../assets/crossbrowserjs/respond.min.js"></script>
		<script src="../assets/crossbrowserjs/excanvas.min.js"></script>
	<![endif]-->
	<script src="/js/apps.js"></script>
	<!-- ================== END BASE JS ================== -->

	<script>
		$(document).ready(function() {
			App.init();

			var pathname = window.location.pathname;
			console.log(pathname);

			$("#sidebar [href*='" + pathname + "']").closest("li").addClass("active");

			var notfy = new Notyf();

			// $(".pop-up").click(function(){
			// 	$(this).fadeOut();
			// });

			// $("#btm-action").click(function(){
			// 	$(".pop-up").fadeIn();
			// });

			// $(".pop-up-box").click(function(e){
			// 	e.stopPropagation();
			// });

			$(document).on("click", ".btn-invitation", function(e){
				e.stopPropagation();
				e.preventDefault(); 
				var action = $(this).attr("data-action");
				var id = $(this).closest("div").attr("data-id");
				var media = $(this).closest(".media");

				$.ajax({
					type: "POST",
					url: "/processInvitation",
					data: {
						_token: '{{ csrf_token() }}',
						action: action,
						id: id
					},
					success: function(response){
						if(response.success){
							notfy.success(response.msg);
							media.remove();

							// Check if any invitation left
							if($(".dropdown-inv .media").length == 0){
								$(".dropdown-inv").removeClass("show");
							}
						} else {
							notfy.error(response.msg);
						}
					}
				});
				
			});
		});
	</script>

	@yield('page_script')

</body>
</html>