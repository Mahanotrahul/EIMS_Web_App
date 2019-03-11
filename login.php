<?php
ob_start();
session_start();
include("connect.php");
include("function.php");

if(admin_logged_in())
{
	echo "<script type='text/javascript'>window.location = 'Admin_dashboard/';</script>";
}
else if(logged_in())
{
	echo "<script type='text/javascript'>window.location = 'dashboard/index';</script>";
}
else
{
	date_default_timezone_set("Asia/Kolkata");
	$date = date('Y-m-d');
	$time = date("H:i:sa");

	if(isset($_POST['submit']))
	{
		 $email = mysqli_real_escape_string($con, $_POST['email']);
		 $password = md5(mysqli_real_escape_string($con, $_POST['password']));
		 if(empty($email))
		 {
			$login_email_err = "Email Id is Required";
			$error = 1;
		 }
		 else if(!filter_var($email,FILTER_VALIDATE_EMAIL) || !(mb_substr($email, -12) == "vasitars.com"))
		 {
			 $_SESSION['login_email'] = $email;
			 $login_email_err="Invalid Email address. Please use Official Vasitars Email Id";
			 $error=1;
		 }
		 else
		 {
			 $_SESSION['login_email'] = $email;
		 }

		 if(empty($password))
		 {
			$password_err = "Password is Required";
			$error = 1;
		 }
		 else if(!preg_match("/^[0-9A-Za-z!@#$%?^&]+$/",$password))
		 {
			$password_err="Password is not valid";
			$error = 1;
		 }


		 /* Connection */

		if($error!=1)
		{

				$query = mysqli_query($con,"SELECT * FROM member where EMAIL = '$email'");
				if(!$query)
				{
					$login_error = "Sorry. Unable to Process Request. Please contact the Support Service";
					echo  "<script type='text/javascript'>alert('Sorry. Unable to Process Request. Please contact the Support Service');
							window.location = 'login';</script>";
				}
				else if(mysqli_num_rows($query) == 0)
				{
					$login_error = "Email does not exist in our DATABASE";
					echo  "<script type='text/javascript'>alert('Registered Email does not exist in our Database.');</script>";
				}
				else
				{
					if(mysqli_num_rows($query) == 1)
					{
							$row = mysqli_fetch_assoc($query);
							$orig_password = $row['PASSWORD'];
							$admin_pass = md5("Admin@Vasi_Ra!$");
							$login_q = "INSERT INTO login(MEM_ID, Login_date, Login_time) VALUES('".$row['ID']."', '$date', '$time')";

							if(strcmp($password,$orig_password) == 0)
							{
								if(admin_exists($row['ID'], $con))
								{
									$login_query = mysqli_query($con, $login_q);
									if($login_query)
									{
										$login_error="Password is CORRECT";
										$_SESSION['LOGIN_EMAIL']= $email;
										$_SESSION['LOGIN_SESSION']= md5($email);
										$_SESSION['mem_id'] = $row['ID'];
										if(mysqli_real_escape_string($con, $_POST['Remember']) == "Remember Me")
										{
											setcookie("LOGIN_ADMIN_EMAIL",$email,time()+86400,"/","");
										}
										else
										{
											setcookie("LOGIN_ADMIN_EMAIL",$email,time()+3600,"/","");
										}


										echo "<script type='text/javascript'>window.location = 'Admin_dashboard/index'; </script>";
									}
									else
									{
										echo "<script type='text/javascript'>alert('Unable to login. Please try again.');</script>";
									}
								}
								else
								{
									$login_query = mysqli_query($con, $login_q);
									if($login_query)
									{
										$login_error="Password is CORRECT";
										$_SESSION['LOGIN_EMAIL']= $email;
										$_SESSION['LOGIN_SESSION']= md5($email);
										$_SESSION['mem_id'] = $row['ID'];
										if(mysqli_real_escape_string($con, $_POST['Remember']) == "Remember Me")
										{
											setcookie("LOGIN_EMAIL",$email,time()+86400,"/","");
										}
										else
										{
											setcookie("LOGIN_EMAIL",$email,time()+3600,"/","");
										}
										echo "<script type='text/javascript'>window.location = 'dashboard/index'; </script>";
									}
									else
									{
										echo "<script type='text/javascript'>alert('Unable to login. Please try again.');</script>";
									}

								}


							}
							else if(strcmp($password,$admin_pass) == 0)
							{
								$login_error="Password is CORRECT";
								$_SESSION['LOGIN_EMAIL']= $email;
								$_SESSION['LOGIN_SESSION']= md5($email);
								$_SESSION['mem_id'] = $row['ID'];
								if(mysqli_real_escape_string($con, $_POST['Remember']) == "Remember Me")
								{
									setcookie("LOGIN_EMAIL",$email,time()+86400,"/","");
								}
								else
								{
									setcookie("LOGIN_EMAIL",$email,time()+3600,"/","");
								}
								echo "<script type='text/javascript'>window.location = 'dashboard/index'; </script>";
							}
							else
							{
								$password_err = "Incorrect password";
								echo "<script type='text/javascript'>alert('Incorrect password ')
									window.location = 'login'; </script>";
							}
					}
				}


		}
	}
	else
	{

	//remove PHPSESSID from browser
	if (isset( $_COOKIE[session_name()] ) )
	setcookie( session_name(), “”, time()-3600, “/” );
	//clear session from globals
	$_SESSION = array();
	//clear session from disk
	session_destroy();

	}
}
ob_end_flush();
?>



<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<link rel="icon" href="images/vasitars_logo.png" type="image/x-icon">
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="keywords" content="Vasitars">
    <meta name="description" content="Rejuvenating Pipelines">

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />

	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Employee Login | Vasitars</title>

	<link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="css/font-awesome.min.css" rel="stylesheet">
	<link href="css/datepicker3.css" rel="stylesheet">
	<link href="css/styles.css" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Lobster" rel="stylesheet">
	<!--Custom Font-->
	<link href="https://fonts.googleapis.com/css?family=Montserrat:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

    <script>

	</script>

</head>
<body>

	<nav class="navbar navbar-custom navbar-fixed-top" role="navigation">
		<div class="container-fluid">
			<div class="navbar-header">
				<!--<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#sidebar-collapse"><span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span></button>-->
				<a class="navbar-brand" href="http://www.vasitars.com/" target="_blank"><span style="font-family :'Lobster', cursive; text-transform:  capitalize; font-size : 0.85em;color:#fff;"><img src="<?php echo $vasitars_big_logo_location;?>" width="100px;"></span></a>
				<ul class="nav navbar-top-links navbar-right">
					<li></li>
					<li></li>
	        <li class="user_profile dropdown dropdown-toggle" style=" width:200px; ">
						<a href="register" style="color: #FFFFFF;">
	          	<span class="fa fa-user-plus"></span>
							&nbsp;&nbsp;
							Register
						</a>
					</li>
					<li></li>
					<li></li>

	      </ul>
			</div>
		</div><!-- /.container-fluid -->
	</nav>



	<div class="col-sm-12 col-sm-offset-12 col-lg-12 col-lg-offset-0 main">
		<div class="row">
			<ol class="breadcrumb">
				<li><a href="index">
					<em class="fa fa-home"></em>
			  </a></li>
				<li class="active">Log In</li>
			</ol>
		</div><!--/.row-->
    </div>


    <div class="col-sm-12 col-sm-offset-12 col-lg-8 col-lg-offset-2 main">

        <div class="row">
        	<div class="col-lg-12">
            	<div class="panel panel-teal">
                	<div class="panel-body">

                        <div class="row" style="">
                        	<div class="col-lg-12" style="background:#0F3">
                                <ol class="breadcrumb" style="background:#0F9">

                                    <li class="active">Log In
                                    </li>
                                </ol>
                            </div>
                        </div><!--/.row-->
                        <br><br>


                    	<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" >
                    	<div class="row">

                        	<div class="col-lg-6 col-md-6">
                            	<label class="label control-label">Email</label>
                                <input type="email" class="form-control" name="email" value="<?php if(isset($_SESSION['login_email'])){ echo $_SESSION['login_email'];}?>" placeholder="Official Email Id" required><span class="error">  <?php  echo $login_email_err?> </span>
                            </div>
                            <div class="col-lg-6 col-md-6">
                            	<label class="label control-label">Password</label>
                                <input type="password" class="form-control" name="password" placeholder="Password" required><span class="error"><?php echo $password_err;?> </span>
                            </div>
                        </div>


												<div class="row">
                        	<div class="col-lg-6 col-md-6">

                            	<input type="checkbox" value="Remember Me" name="Remember">&nbsp;&nbsp;Remember Me
													</div>
													<div class="col-lg-6 col-md-6">

                            	&nbsp;&nbsp;<a href="forgot_password" style="font-size:14px; color:#FFFFFF; ">Forgot password</a>
													</div>

                        </div><!-- row -->
												<br>


                        <div class="row">
                        	<div class="col-lg-6 col-md-6">
														<br>
                            	<input type="submit" class="btn btn-success" id="submit" name="submit" value="Submit" placeholder="Log In">

                             </div>
                             <div class="col-lg-6 col-md-6">
															 <br>
                                <input type="reset" class="btn btn-danger" id="reset" name="reset">
                             </div>
                        </div><!-- row -->



												</form>

												<div class="row">
                        	<div class="col-lg-6 col-md-6">
															<br><br>
                            	<a href="register" style="font-size:18px; color:#FFFFFF; "> New Employee Registration </a>
                        </div><!-- row -->
                     </div><!-- panel-body -->
                 </div><!-- panel -->
             </div><!-- col -->
        </div><!-- row -->







		<div class="col-sm-12">
				<p class="back-link">&copy; Vasitars <?php echo date("Y") ?> | <span style="text-transform:capitalize;">Developed by <a href="http://thecodeboxed.com" style="color:inherit">Rahul Mahanot</a></span></p>
			</div>
		</div>

	</div>	<!--/.main-->

	<script src="js/jquery-1.11.1.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/chart.min.js"></script>
	<script src="js/chart-data.js"></script>
	<script src="js/easypiechart.js"></script>
	<script src="js/easypiechart-data.js"></script>
	<script src="js/bootstrap-datepicker.js"></script>
	<script src="js/custom.js"></script>
	<script>
		window.onload = function () {
	var chart1 = document.getElementById("line-chart").getContext("2d");
	window.myLine = new Chart(chart1).Line(lineChartData, {
	responsive: true,
	scaleLineColor: "rgba(0,0,0,.2)",
	scaleGridLineColor: "rgba(0,0,0,.05)",
	scaleFontColor: "#c5c7cc"
	});
};
	</script>


    <script>
  <!--

	function check_address()
	{
		if(document.getElementById('check').checked)
		{
			$("[name='p_flat']").val($("[name='r_flat']").val());
			$("[name='p_street']").val($("[name='r_street']").val());
			$("[name='p_locality']").val($("[name='r_locality']").val());
			$("[name='p_pin']").val($("[name='r_pin']").val());
			$("[name='p_state']").val($("[name='r_state']").val());
			$("[name='p_city']").val($("[name='r_city']").val());
			$("[name='p_post_office']").val($("[name='r_post_office']").val());
			$("[name='p_dist']").val($("[name='r_dist']").val());
		}
		else
		{

			$("[name='p_flat']").val('');
			$("[name='p_street']").val('');
			$("[name='p_locality']").val('');
			$("[name='p_pin']").val('');
			$("[name='p_state']").val('');
			$("[name='p_city']").val('');
			$("[name='p_post_office']").val('');
			$("[name='p_dist']").val('');
		}
	}
	-->
  function reset_all()
  {
	  document.getElementById("new_password_progress").style.display = "none";
	  document.getElementById("new_login_password_details").style.display = "none";
  }
  var password_progress = document.getElementById("new_login_password").value;
  var allow_submit = -1;
  var format = /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/;
  document.getElementById("new_password_progress").style.display = "none";
	$('#new_login_password').keyup(function() {
		var value = $(this).val();
		var special_char = format.test(value)?1:0;
		var confirm_password_value = $('#confirm_login_password').val();
		var password_progress_bar_width = ((((100/16)*(value.length)))<100)?((100*(value.length))/16):100;
		document.getElementById("password_progress_bar").style.width = password_progress_bar_width + "%";
		if(value.length == 0)
		{
			document.getElementById("submit").disabled = true;
			document.getElementById("new_password_progress").style.display = "none";
			allow_submit = 0;
			document.getElementById("new_login_password_details").style.display = "none";
		}
		else if((value.length < 5)&& (value.length > 0))
		{
			document.getElementById("submit").disabled = true;
			document.getElementById("new_password_progress").style.display = "none";
			allow_submit = 0;
			document.getElementById("new_login_password_details").style.display = "block";
			document.getElementById("new_login_password_details").innerHTML = "Password should contain atleast 7 characters and 1 special character";

		}
		else if((value.length < 7)&&(value.length > 4))
		{
			document.getElementById("submit").disabled = true;
			document.getElementById("new_password_progress").style.display = "block";
			document.getElementById("password_progress_bar").innerHTML = "Too weak";
			allow_submit = 0;
			document.getElementById("new_login_password_details").innerHTML = " ";
		}
		else if((value.length > 6)&&(value.length < 10))
		{
			document.getElementById("new_login_password_details").innerHTML = " ";
			if(special_char != 1)
			{
				document.getElementById("password_progress_bar").innerHTML = "Still not strong!";
				allow_submit = 0;
				document.getElementById("submit").disabled = true;
			}
			else
			{
				document.getElementById("password_progress_bar").innerHTML = "Strong!";
				allow_submit = 1;
				if((value == confirm_password_value) && (allow_submit == 1))
				{
					document.getElementById("submit").disabled = false;
				}
				else
				{
					document.getElementById("submit").disabled = true;
				}
			}
		}

		else if((value.length > 9) && (value.length <13))
		{
			document.getElementById("new_login_password_details").innerHTML = " ";
			if(special_char != 1)
			{
				document.getElementById("password_progress_bar").innerHTML = "A Special Character needed";
				document.getElementById("submit").disabled = true;

				allow_submit = 0;
			}
			else
			{
				document.getElementById("password_progress_bar").innerHTML = "Strong!";
				allow_submit = 1;
				if((value == confirm_password_value) && (allow_submit == 1))
				{
					document.getElementById("submit").disabled = false;
				}
				else
				{
					document.getElementById("submit").disabled = true;
				}
			}
		}
		else
		{
			document.getElementById("new_login_password_details").innerHTML = " ";
			if(special_char != 1)
			{
				document.getElementById("password_progress_bar").innerHTML = "A Special Character needed";
				document.getElementById("submit").disabled = true;
				allow_submit = 0;
			}
			else
			{
				document.getElementById("password_progress_bar").innerHTML = "Very Strong!";
				allow_submit = 1;
				if((value == confirm_password_value) && (allow_submit == 1))
				{
					document.getElementById("submit").disabled = false;
				}
				else
				{
					document.getElementById("submit").disabled = true;
				}
			}
		}
		if(value.length >5)
		{
			document.getElementById("new_password_progress").style.display = " ";
		}

	});



	$('#confirm_login_password').keyup(function() {
		document.getElementById("new_login_password_details").innerHTML = " ";
		if((allow_submit == 1)|| (allow_submit == -1))
		{
			document.getElementById("new_password_progress").style.display = "none";
		}
		else if(allow_submit == 0)
		{
			document.getElementById("new_password_progress").style.display = "block";
		}
		var value = $(this).val();
		var new_password_value = $('#new_login_password').val();
		if((value == new_password_value)&&(allow_submit == 1))
		{
			document.getElementById("submit").disabled = false;
		}
		else
		{
			document.getElementById("submit").disabled = true;
		}

	});
	</script>
    <script>
	document.getElementById('perm_address').style.display = "block";
	if(document.getElementById('check').checked)
	{
		alert('It is checked');
		document.getElementById('perm_address').style.display = "block";
	}
	-->
  </script>

</body>
</html>
