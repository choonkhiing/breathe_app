<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
<head>
	<meta charset="utf-8" />
	<title>Breathe | Login Page</title>
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
							<div class="isa_error" id="loginErrorDiv" style="display:none"></div>
						</div>
						<div class="form-group m-b-20">
							<input type="text" class="form-control form-control-lg" placeholder="Email Address" name="email" data-parsley-type="email"  data-parsley-required="true" />
						</div>
						<div class="form-group m-b-20">
							<input type="password" class="form-control form-control-lg" placeholder="Password" name="password" data-parsley-required="true" />
						</div>
						<div class="checkbox checkbox-css m-b-20">
							<input type="checkbox" id="remember_checkbox" /> 
							<label for="remember_checkbox">
								Remember Me
							</label>
						</div>
						<div class="login-buttons">
							<button type="submit" class="btn btn-success btn-block btn-lg">Sign me in</button>
						</div>
						<div class="m-t-20">
							Not a member yet? Click <a href="/register">here</a> to register.
						</div>
						<hr class="hr-text mb-1" data-content="OR">
						<a href="/fb/redirect" class="btn btn-block fb-button mb-1"><i class="fab fa-facebook"></i> Login with Facebook</a>
						<a href ="/google/redirect" class="btn btn-block google-button mb-1"><div style="width:18px;height:18px;" class="googleIcon"><svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="18px" height="18px" viewBox="0 0 48 48" class="abcRioButtonSvg"><g><path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"></path><path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"></path><path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"></path><path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"></path><path fill="none" d="M0 0h48v48H0z"></path></g></svg></div> Login with Google</a>
					</form>
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
	<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
	
	<!-- ================== END BASE JS ================== -->
	
	<!-- ================== BEGIN PAGE LEVEL JS ================== -->
	<script src="/assets/js/demo/login-v2.demo.min.js"></script>
	<!-- ================== END PAGE LEVEL JS ================== -->

	<script>
		$(document).ready(function() {
			App.init();

			@if(Session::has('success'))
			swal("{{ Session::get('success-title') }}", "{{ Session::get('success') }}", "success");
			@endif

			@if(Session::has('error'))
			swal("{{ Session::get('error-title') }}", "{{ Session::get('error') }}", "error");
			@endif
		});
	</script>
</body>
</html>
