<?php
ob_start();
session_start();
include("connect.php");
include("function.php");

if(!logged_in())
{
	header("../login");
	echo  "<script type='text/javascript'>window.location = '../login';</script>";
}
else
{
	$query = mysqli_query($con, "SELECT * FROM member where EMAIL = '".$_SESSION['LOGIN_EMAIL']."'") ;
	$row = mysqli_fetch_assoc($query) ;

	if(!empty($row['EMAIL']))
	{

		 $_SESSION['fname']= $row['FNAME'];
		 $_SESSION['mobno']= $row['PHONE_NUMBER'];
		 $_SESSION['lname']= $row['LNAME'];
		 $_SESSION['mem_id'] = $row['ID'];
		 $orig_password = $row['PASSWORD'];
		 //$_SESSION['college']= $row['college'];

		 if(($_SERVER["REQUEST_METHOD"]== "POST") && (isset($_POST['submit'])))
		 {
			 $cur_password = md5(mysqli_real_escape_string($con, $_POST['cur_login_password']));
			 $new_password = md5(mysqli_real_escape_string($con, $_POST['new_login_password']));
			 $c_password = md5(mysqli_real_escape_string($con, $_POST['c_password']));

			 if(strcmp($orig_password, $cur_password) != 0)
			 {
				 echo  "<script type='text/javascript'>alert('Incorrect Current Login Password');</script>";
			 }
			 else if(strcmp($new_password, $c_password) != 0)
			 {
				 echo  "<script type='text/javascript'>alert('Passwords do not match.');</script>";
			 }
			 else if(strlen($new_password) < 6)
			 {
				 echo  "<script type='text/javascript'>alert('Password is too weak. Try using stronger password');</script>";
			 }
			 else
			{
				$query = mysqli_query($con, "UPDATE member SET PASSWORD = '".$new_password."' WHERE ID = '".$row['ID']."' AND EMAIL = '".$_SESSION['LOGIN_EMAIL']."'");
				if($query)
				{
					echo  "<script type='text/javascript'>alert('Password updated');</script>";
				}
				else
				{
					echo  "<script type='text/javascript'>alert('Unable to Update Password. Try again.');</script>";
				}
			}

		 }

	}
	else
	{
		echo  "<script type='text/javascript'>alert('Sorry. Unable to process your request.');
				window.location = '../logout';</script>";
	}

}
ob_end_flush();
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<link rel="icon" href="<?php echo $vasitars_logo_location; ?>" type="image/x-icon">
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
	<title>Change Password | Vasitars</title>

	<link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="css/font-awesome.min.css" rel="stylesheet">
	<link href="css/datepicker3.css" rel="stylesheet">
	<link href="css/styles.css" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Lobster" rel="stylesheet">
    <!-- Switchery -->
    <link href="css/switchery.min.css" rel="stylesheet">

	<!--Custom Font-->
	<link href="https://fonts.googleapis.com/css?family=Montserrat:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>


</head>
<body>

	<?php
		include("top_nav_template.php");
		include("side_nav_template.php");
	?>

	<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
		<div class="row">
			<ol class="breadcrumb">
				<li><a href="index">
					<em class="fa fa-home"></em>
				</a></li>
				<li class="active">Change Password</li>
			</ol>
		</div><!--/.row-->

		<br>

		<div class="row">

				<div class="col-lg-12">
				<form role="form"  method="post"  action="<?php  echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
				<div class="panel panel-teal">

					<div class="panel-body">
						<div class="row">
							<div class="col-lg-12" style="background:#0F3">
										<ol class="breadcrumb" style="background:#9F9">

												<li class="active">Current Password
												</li>
										</ol>
							</div>
					</div><!--/.row-->

					<br><br>

					<div class="row">
						<div class="col-md-6">
							<label> Current Password</label>
							<input type="password" class="form-control" id="cur_login_password" name="cur_login_password" placeholder="Enter your Current Password" required>
						</div>
				</div><!-- row -->

				<br><br>
				<div class="row" style="">
					<div class="col-lg-12" style="background:#0F3">
								<ol class="breadcrumb" style="background:#0F9">
									<li class="active">New Password</li>
								</ol>
						</div>
				</div><!--/.row-->
				<br><br>

				<div class="row">
					<div class="col-md-6">
						<label> New Password</label>
						<input type="password" class="form-control" id="new_login_password" name="new_login_password" placeholder="Choose Password" required>
							<div id="new_login_password_details"></div>
							<div class="progress" id="new_password_progress" style="width:100%; min-height:20px; margin-bottom:0px; margin-top:20px;">
									<span class="progress-bar progress-bar-striped" role="progressbar" id="password_progress_bar" style="margin-bottom:10px;"></span>
							</div><!-- progress -->
					</div>
					<div class="col-lg-6 col-md-6">
						<label>Confirm Password</label>
						 <input type="text" class="form-control" id="confirm_login_password" name="c_password" placeholder="Confirm Password" required>

								 <div id="confirm_login_password_progress" class="input_details"></div>
					</div>
			</div><!-- row -->

			<br><br>



			<br><br>

			<div class="row">
				<div class="col-lg-6 col-md-6">
						<input type="submit" class="btn btn-success" id="submit" name="submit">

					 </div>
					 <div class="col-lg-6 col-md-6">
							<input type="reset" class="btn btn-danger" id="reset" name="reset">
					 </div>
			</div><!-- row -->

							</div>
								</form>

			</div>

		<div class="col-sm-12"><br>
				<p class="back-link" style="color: #000;">© Vasitars 2019</p>
			</div>
		</div>

	</div>	<!--/.main-->
	</div></div>
	<script src="js/jquery-1.11.1.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/chart.min.js"></script>
	<script src="js/chart-data.js"></script>
	<script src="js/easypiechart.js"></script>
	<script src="js/easypiechart-data.js"></script>
	<script src="js/bootstrap-datepicker.js"></script>
	<script src="js/custom.js"></script>
	<!-- Dropzone.js -->
	<script src="js/dropzone.min.js"></script>
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
	$('#change_password').addClass('active');
	</script>

</body>
</html>
