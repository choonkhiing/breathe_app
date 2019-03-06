<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
<head>
	<meta charset="utf-8" />
	<title>Breathe | Register Page</title>
	<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
	<meta content="" name="description" />
	<meta content="" name="author" />
	
	<!-- ================== BEGIN BASE CSS STYLE ================== -->
	<link href="http://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
	<link href="/assets/plugins/jquery-ui/jquery-ui.min.css" rel="stylesheet" />
	<link href="/assets/plugins/bootstrap/4.1.0/css/bootstrap.min.css" rel="stylesheet" />
	<link href="/assets/plugins/font-awesome/5.0/css/fontawesome-all.min.css" rel="stylesheet" />
	<link href="/assets/plugins/animate/animate.min.css" rel="stylesheet" />
	<link href="/assets/css/default/style.min.css" rel="stylesheet" />
	<link href="/assets/css/default/style-responsive.min.css" rel="stylesheet" />
	<link href="/assets/css/default/theme/default.css" rel="stylesheet" id="theme" />
	<link href="/css/custom.css" rel="stylesheet" />
	<!-- ================== END BASE CSS STYLE ================== -->
	
	<!-- ================== BEGIN BASE JS ================== -->
	<script src="/assets/plugins/pace/pace.min.js"></script>
	<!-- ================== END BASE JS ================== -->
</head>
<body class="pace-top bg-white">
	<!-- begin #page-loader -->
	<div id="page-loader" class="fade show"><span class="spinner"></span></div>
	<!-- end #page-loader -->
	
	<!-- begin #page-container -->
	<div id="page-container" class="fade">
		<!-- begin login -->
		<div class="login login-with-news-feed">
			<!-- begin news-feed -->
			<div class="news-feed">
				<div class="news-image" style="background-image: url(/img/login-bg1.jpg)"></div>
				<div class="news-caption">
					<h4 class="caption-title"><b>Breathe</b></h4>
					<p>
						Organize your daily tasks
					</p>
				</div>
			</div>
			<!-- end news-feed -->
			<!-- begin right-content -->
			<div class="right-content">
				<!-- begin login-header -->
				<div class="login-header">
					<div class="brand">
						<b>Breathe</b>
					</div>
					<div class="icon">
						<i class="fa fa-sign-in"></i>
					</div>
				</div>
				<!-- end login-header -->
				<!-- begin login-content -->
				<div class="login-content">
					<form action="{{ action('UserController@login') }}" method="POST" class="margin-bottom-0" data-parsley-validate="true">
						{{ csrf_field() }}
						<div class="form-group">
							@if (session('error'))
							<div class="alert alert-danger" id="loginErrorDiv">{{Session:get('error')}}</div>
							@endif
						</div>
						<div class="form-group m-b-20">
							<input type="text" class="form-control form-control-lg" placeholder="Username" name="username" data-parsley-required="true" />
						</div>
						<div class="form-group m-b-20">
							<input type="text" class="form-control form-control-lg" placeholder="Email Address" name="email" data-parsley-type="email"  data-parsley-required="true" />
						</div>
						<div class="form-group m-b-20">
							<input type="password" class="form-control form-control-lg" placeholder="Password" name="password" data-parsley-required="true" />
						</div>
						<div class="form-group m-b-20">
							<input type="password" class="form-control form-control-lg" placeholder="Confirm Password" name="confirmpassword" data-parsley-required="true" />
						</div>
						<div class="form-group m-b-20">
							<input type="text" class="form-control form-control-lg" placeholder="Phone" name="phone" data-parsley-minlength="10" data-parsley-minlength-message="Please enter a valid phone number." data-parsley-pattern="/^[\+]?[0-9]{2,4}[-]?[0-9]{7,10}$/" />
						</div>
						<!-- username email phone password confirmpassword = javascript for conpass -->
						<div class="login-buttons">
							<button type="submit" class="btn btn-success btn-block btn-lg">Sign me in</button>
						</div>
						<div class="m-t-20">
							Click <a href="/">here</a> to Login.
						</div>
						
				</div>
				<!-- end login-content -->
			</div>
			<!-- end right-container -->
		</div>
		<!-- end login -->

	</div>
	<!-- end page container -->
	
	<!-- ================== BEGIN BASE JS ================== -->
	<script src="/assets/plugins/jquery/jquery-3.2.1.min.js"></script>
	<script src="/assets/plugins/jquery-ui/jquery-ui.min.js"></script>
	<script src="/assets/plugins/bootstrap/4.1.0/js/bootstrap.bundle.min.js"></script>
	<!--[if lt IE 9]>
		<script src="/assets/crossbrowserjs/html5shiv.js"></script>
		<script src="/assets/crossbrowserjs/respond.min.js"></script>
		<script src="/assets/crossbrowserjs/excanvas.min.js"></script>
	<![endif]-->
	<script src="/assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>
	<script src="/assets/plugins/js-cookie/js.cookie.js"></script>
	<script src="/assets/js/theme/default.min.js"></script>
	<script src="/assets/js/apps.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/parsley.js/2.8.1/parsley.js"></script>
	<!-- ================== END BASE JS ================== -->
	
	<!-- ================== BEGIN PAGE LEVEL JS ================== -->
	<script src="/assets/js/demo/login-v2.demo.min.js"></script>
	<!-- ================== END PAGE LEVEL JS ================== -->

	<script>
		$(document).ready(function() {
			App.init();
			LoginV3.init();
		});
	</script>
</body>
</html>
