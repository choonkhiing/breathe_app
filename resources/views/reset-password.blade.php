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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/remodal/1.1.1/remodal.min.css" rel="stylesheet" >
    <link href="https://cdnjs.cloudflare.com/ajax/libs/remodal/1.1.1/remodal-default-theme.min.css" rel="stylesheet" >
    <!-- ================== END BASE CSS STYLE ================== -->
    
    <!-- ================== BEGIN BASE JS ================== -->
    <script src="/assets/plugins/pace/pace.min.js"></script>
    <!-- ================== END BASE JS ================== -->

    <style>
        .header-location {
            display: none;
        }

        .btn.btn-success {
            width: inherit;
            font-size: 14px;
            margin-top: 25px;
        }

        b {
            color: white;
        }
    </style>
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
                        <b>Reset Password</b>
                    </div>
                    <div class="icon">
                        <i class="fa fa-sign-in"></i>
                    </div>
                </div>
                <!-- end login-header -->
                <!-- begin login-content -->
                <div class="login-content">
                    @if ($isExpired == true)
                    <h2>Reset Password</h2>
                    <p>Your token has been expired! Please request again.</p>
                    <a href="/" style="color:#f5c333;">BACK TO HOME</a>
                    @else 
                    <form method="post" id="resetForm" data-parsley-validate="true">
                        <div class="form-group">
                            <div class="isa_error" id="errorDiv" style="white-space:pre-wrap;display:none"></div>
                        </div>
                        {{ csrf_field() }}
                        <div class="input-label"><label class="control-label">Email Address <span class="text-danger">*</span></label></div>
                        <div class="form-group m-b-15">
                            <b>{{ $reset_password->email }}</b>
                            <input id="input_email" type="hidden" name="email" value="{{ $reset_password->email }}" />
                            <input id="input_token" type="hidden" name="token" value="{{ $token }}" />
                        </div>
                        <div class="input-label"><label class="control-label">New Password <span class="text-danger">*</span></label></div>
                        <div class="form-group m-b-15">
                            <input type="password" class="form-control form-control-lg" data-parsley-minlength="6" name="password" id="reset-password" placeholder="Password" data-parsley-required="true" />
                        </div>
                        <div class="input-label"><label class="control-label">Confirm New Password <span class="text-danger">*</span></label></div>
                        <div class="form-group m-b-15">
                            <input type="password" class="form-control form-control-lg" data-parsley-minlength="6" name="confirmPassword" id="reset-confirmpass" placeholder="Confirm Password" data-parsley-required="true" />
                        </div>
                        <div class="reset-button">
                            <button type="submit" class="btn btn-success btn-block btn-lg" id="resetBtn">Reset Password</button>
                        </div>
                        <div class="loader">
                            <img src="/img/loader.gif" />
                        </div>
                    </form>
                    @endif
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/remodal/1.1.1/remodal.min.js"></script> 
    
    <!-- ================== END BASE JS ================== -->
    
    <!-- ================== BEGIN PAGE LEVEL JS ================== -->
    <script src="/assets/js/demo/login-v2.demo.min.js"></script>
    <!-- ================== END PAGE LEVEL JS ================== -->

    <script>
        $(".loader").hide();

        $(document).ready(function() {
            App.init();

            @if(Session::has('success'))
            swal("{{ Session::get('success-title') }}", "{{ Session::get('success') }}", "success");
            @endif

            @if(Session::has('error'))
            swal("{{ Session::get('error-title') }}", "{{ Session::get('error') }}", "error");
            @endif
        });

        $("#resetForm").on('submit', function(e){
            var form = $(this);
            e.preventDefault();
            form.parsley().validate();

            $('#resetForm').parsley().validate();
            if ($('#resetForm').parsley().isValid()) {

                if($("#reset-confirmpass").val() != $("#reset-password").val()) {
                    $("#errorDiv").css('display','block');
                    $("#errorDiv").text("Password retype not match");
                    return false;
                }

                $.ajax({
                    url: "/reset-password",
                    type: 'POST',
                    data: $('#resetForm').serialize(),
                    beforeSend: function() {
                        $("#errorDiv").css('display','none');
                        $("#resetBtn").css('display','none');
                        $(".loader").show();
                    },
                    success: function (data) 
                    {
                        $("#resetBtn").css('display','block');
                        $(".loader").hide();
                        if (data.error != null)
                        {
                            $("#errorDiv").css('display','block');
                            $("#errorDiv").text(data.error);
                        }
                        else
                        {
                            location.href = '/';
                        }
                    }
                });
            }
        });
    </script>
</body>
</html>


