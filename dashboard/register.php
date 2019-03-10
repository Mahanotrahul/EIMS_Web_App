<?php
ob_start();
session_start();
include("connect.php");
include("function.php");


if(isset($_POST['submit']))
{
	$fname = mysqli_real_escape_string($con, $_POST['fname']);
	$lname = mysqli_real_escape_string($con, $_POST['lname']);
	$dob = mysqli_real_escape_string($con, $_POST['dob']);
	$phone_number = mysqli_real_escape_string($con, $_POST['phone_number']);
	$email = mysqli_real_escape_string($con, $_POST['email']);
	$pan = mysqli_real_escape_string($con, $_POST['pan']);
	$gender = mysqli_real_escape_string($con, $_POST['gender']);

	$r_flat = mysqli_real_escape_string($con, $_POST['r_flat']);
	$r_street = mysqli_real_escape_string($con, $_POST['r_street']);
	$r_locality = mysqli_real_escape_string($con, $_POST['r_locality']);
	$r_dist = mysqli_real_escape_string($con, $_POST['r_dist']);
	$r_state = mysqli_real_escape_string($con, $_POST['r_state']);
	$r_city = mysqli_real_escape_string($con, $_POST['r_city']);
	$r_post_office = mysqli_real_escape_string($con, $_POST['r_post_office']);
	$r_pin = mysqli_real_escape_string($con, $_POST['r_pin']);

	$check = mysqli_real_escape_string($con, $_POST['same_as_r']);
	$p_flat = mysqli_real_escape_string($con, $_POST['p_flat']);
	$p_street = mysqli_real_escape_string($con, $_POST['p_street']);
	$p_locality = mysqli_real_escape_string($con, $_POST['p_locality']);
	$p_dist = mysqli_real_escape_string($con, $_POST['p_dist']);
	$p_state = mysqli_real_escape_string($con, $_POST['p_state']);
	$p_city = mysqli_real_escape_string($con, $_POST['p_city']);
	$p_post_office = mysqli_real_escape_string($con, $_POST['p_post_office']);
	$p_pin = mysqli_real_escape_string($con, $_POST['p_pin']);


	$password = md5(mysqli_real_escape_string($con, $_POST['password']));
	$c_password = md5(mysqli_real_escape_string($con, $_POST['c_password']));
	$error = 0;

	if(empty($fname))
	{
		$fname_err = "First name is required";
		$error = 1;
	}
	else
	{
		$_SESSION['fname'] = $fname;
		if(!preg_match("/^[a-zA-Z ]*$/",$fname))
		{
			$fname_err = "Only Whitespaces and letters are allowed in First Name";
			$error = 1;
		}
	}

	if(empty($lname))
	{
		$lname_err = "Last Name is Required";
		$error = 1;
	}
	else
	{
		$_SESSION['lname'] = $lname;
		if(!preg_match("/^[a-zA-Z ]*$/",$lname))
		{
			$lname_err = "Only letters and Whitespace allowed in Name";
			$error = 1;
		}
	}

	if(empty($dob))
	{
		$dob_err = "Date of Birth is Required";
		$error = 1;
	}
	else
	{
		$_SESSION['dob'] = $dob;
	}

	if(empty($phone_number))
	{
		$phone_number_error = "Phone Number is required";
		$error = 1;
	}
	else if(strlen($phone_number) != 10)
	{
		$_SESSION['phone_number'] = $phone_number;
		$phone_number_error = "Phone Number should be 10 digits number";
		$error = 1;
	}
	else
	{
		$_SESSION['phone_number'] = $phone_number;
	}

	if(empty($email))
	{
		$email_err = "Email Id is Required";
		$error = 1;
	}
	else if(!filter_var($email, FILTER_VALIDATE_EMAIL) || !(mb_substr($email, -12) == "vasitars.com"))
	{
		$_SESSION['email'] = $email;
		$email_err = "Invalid Email Id. Please use Official Vasitars Email Id";
		$error = 1;
	}
	else
	{
		$_SESSION['email'] = $email;
	}

	if(empty($pan))
	{
		$pan_err = "PAN Number is required";
		$error = 1;
	}
	else if(strlen($pan)!= 10)
	{
		$pan_err = "Invalid PAN Number";
		$error = 1;
	}
	else
	{
		$_SESSION['pan'] = $pan;
	}

	if(empty($gender))
	{
		$gender_err = "Gender is Required";
	}
	else
	{
		$_SESSION['gender'] = $gender;
	}

	if(empty($r_flat))
	{
		$r_flat_err = "Flat Number is Required";
		$error = 1;
	}
	else
	{
		$_SESSION['r_flat'] = $r_flat;
	}

	if(empty($r_street))
	{
		$r_street = " ";
	}
	else
	{
		$_SESSION['r_street'] = $r_street;
	}

	if(empty($r_locality))
	{
		$r_locality = " ";
	}
	else
	{
		$_SESSION['r_locality'] = $r_locality;
	}

	if(empty($r_pin))
	{
		$r_pin_err = "PIN Number in Required";
		$error = 1;
	}
	else if(!ctype_digit($r_pin) || (strlen($r_pin) != 6))
	{
		$_SESSION['r_pin'] = $r_pin;
		$r_pin_err = "PIN Number is Invalid";
		$error = 1;
	}
	else
	{
		$_SESSION['r_pin'] = $r_pin;
	}


	if(empty($p_flat))
	{
		$p_flat_err = "Flat Number is Required";
		$error = 1;
	}
	else
	{
		$_SESSION['p_flat'] = $p_flat;
	}

	if(empty($p_street))
	{
		$p_street = " ";
	}
	else
	{
		$_SESSION['p_street'] = $p_street;
	}

	if(empty($p_locality))
	{
		$p_locality = " ";
	}
	else
	{
		$_SESSION['p_locality'] = $p_locality;
	}

	if(empty($p_pin))
	{
		$p_pin_err = "PIN Number in Required";
		$error = 1;
	}
	else if(!ctype_digit($p_pin) || (strlen($p_pin) != 6))
	{
		$_SESSION['p_pin'] = $p_pin;
		$p_pin_err = "PIN Number is Invalid";
		$error = 1;
	}
	else
	{
		$_SESSION['p_pin'] = $p_pin;
	}

	if(strcmp($password, $c_password) != 0)
	{
		$password_err = "Passwords do not match";
		$error = 1;
	}
	if($error == 1)
	{
		echo "<script type='application/javascript'>alert('Please Correct the Errors')</script>";
	}
	else if(user_exists($email, $pan, $phone_number, $con))
	{
		$error = 1;
		echo "<script type='application/javascript'>alert('Employee is already registered. Please Login')</script>";
	}
	else
	{
		$query = "INSERT INTO member(FNAME, LNAME, DATE_OF_BIRTH, PHONE_NUMBER, EMAIL, PAN_NUMBER, PASSWORD, SEX, R_FLAT, R_STREET, R_LOCALITY, R_STATE, R_CITY, R_POST_OFFICE, R_PIN, P_FLAT, P_STREET, P_LOCALITY, P_STATE, P_CITY, P_POST_OFFICE, P_PIN) VALUES('$fname', '$lname', '$dob', '$phone_number', '$email', '$pan', '$password', '$gender', '$r_flat', '$r_street', '$r_locality', '$r_state', '$r_city', '$r_post_office', '$r_pin', '$p_flat', '$p_street', '$p_locality', '$p_state', '$p_city', '$p_post_office', '$p_pin')";

		if(mysqli_query($con, $query))
		{
			echo "<script type='application/javascript'>alert('Registered Succesfully');
				  window.location='login.php';</script>";
		}
		else
		{
			echo "<script type='text/javascript'>alert('Error = $query')</script>";
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
	<title>Employee registration | Vasitars</title>

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
						<a href="login.php" style="color: #FFFFFF;">
	          	<span class="fa fa-user"></span>
							&nbsp;&nbsp;
							Login
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
				<li><a href="index.php">
					<em class="fa fa-home"></em>
			  </a></li>
				<li class="active">Register</li>
			</ol>
		</div><!--/.row-->
    </div>


    <div class="col-sm-12 col-sm-offset-12 col-lg-9 col-lg-offset-1 main">

        <div class="row">
        	<div class="col-lg-12">
            	<div class="panel panel-teal">
                	<div class="panel-body">

                        <div class="row" style="">
                        	<div class="col-lg-12" style="background:#0F3">
                                <ol class="breadcrumb" style="background:#0F9">

                                    <li class="active">Personal Details
                                    </li>
                                </ol>
                            </div>
                        </div><!--/.row-->
                        <br><br>


                    	<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" >
                    	<div class="row">

                        	<div class="col-lg-6 col-md-6">
                            	<label class="label control-label">First Name</label>
                                <input type="text" class="form-control" name="fname" value="<?php if(isset($_SESSION['fname'])){ echo $_SESSION['fname'];}?>" placeholder="Your First Name" required><span class="error">  <?php  echo $fname_err?> </span>
                            </div>
                            <div class="col-lg-6 col-md-6">
                            	<label class="label control-label">Last Name</label>
                                <input type="text" class="form-control" name="lname" value="<?php if(isset($_SESSION['lname'])){ echo $_SESSION['lname'];}?>" placeholder="Your Last Name" required><span class="error">  <?php  echo $lname_err;?> </span>
                            </div>
                        </div>
                        <br><br>

                        <div class="row">
                        	<div class="col-lg-6 col-md-6">
                            	<label class="label control-label">Date of Birth</label>
                                <input type="date" class="form-control" name="dob" placeholder="Your Date of Birth" value="<?php if(isset($_SESSION['dob'])){ echo $_SESSION['dob'];}?>"  required><span class="error">  <?php  echo $dob_err?> </span>
                             </div>
                             <div class="col-lg-6 col-md-6">
                             	<label class="label control-label">Phone Number</label>
                                <input type="number" class="form-control" name="phone_number" value="<?php if(isset($_SESSION['phone_number'])){ echo $_SESSION['phone_number'];}?>" placeholder="Your Phone Number" required><span class="error">  <?php  echo $phone_number_error;?> </span>
                             </div>
                        </div><!-- row -->

                        <br><br>

                        <div class="row">
                        	<div class="col-lg-6 col-md-6">
                            	<label class="label control-label">Official Email Id</label>
                                <input type="text" class="form-control" name="email" value="<?php if(isset($_SESSION['email'])){ echo $_SESSION['email'];}?>" placeholder="Official Email Id" required><span class="error">  <?php  echo  $email_err?> </span>
                             </div>
                             <div class="col-lg-6 col-md-6">
                             	<label class="label control-label">PAN Number</label>
                                <input type="text" class="form-control" name="pan" placeholder="PAN Card Number"  required><span class="error">  <?php  echo $pan_err?> </span>
                             </div>
                        </div><!-- row -->


                        <br><br>

                        <div class="row">
                        	<div class="col-lg-6 col-md-6">
                            	<label class="label control-label">Gender</label>
                            	<select class="form-control" name="gender" value="<?php if(isset($_SESSION['gender'])){ echo $_SESSION['gender'];}?>" required>
                                	<option value="" disabled selected> -- Select Gender -- </option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                    <option value="Other">Other</option>
                                </select>
                                <span class="error">  <?php  echo $gender_err?> </span>
                             </div>
                             <div class="col-lg-6 col-md-6">

                             </div>
                        </div><!-- row -->
                        <br><br>

                        <div class="row">
                        	<div class="col-lg-12" style="background:#0F3">
                                <ol class="breadcrumb" style="background:#9F9">

                                    <li class="active">Residential Address
                                    </li>
                                </ol>
                            </div>
                        </div><!--/.row-->

                        <br><br>

                        <div class="row">
                        	<div class="col-lg-6 col-md-6">
                            	<label class="label control-label">Flat/Door/Block No:</label>
                                <input type="text" class="form-control" name="r_flat" placeholder="Flat/Door/Block No:"><span class="error">  <?php  echo $r_flat_err?> </span>
                             </div>
                             <div class="col-lg-6 col-md-6">
                             	<label class="label control-label">Street/Lane (optional)</label>
                                <input type="text" class="form-control" name="r_street" placeholder="Street/Lane">
                             </div>
                        </div><!-- row -->

                        <br><br>

                        <div class="row">
                        	<div class="col-lg-6 col-md-6">
                            	<label class="label control-label">Area/Locality (optional)</label>
                                <input type="text" class="form-control" name="r_locality" placeholder="Area / Locality">
                             </div>
                             <div class="col-lg-6 col-md-6">
                             	<label class="label control-label">PIN</label>
                                <input type="number" class="form-control" name="r_pin" maxlength="6" minlength="6"  value="<?php if(isset($_SESSION['r_pin'])){ echo $_SESSION['r_pin'];}?>" placeholder="PIN"><span class="error">  <?php  echo $r_pin_err?> </span>
                             </div>
                        </div><!-- row -->

                        <br><br>

                        <div class="row">
                        	<div class="col-lg-6 col-md-6">
                            	<label class="label control-label">State</label>
                                <select name="r_state" id="r_state" class="form-control" required>
                                    <option value="" disabled selected>----Select State----</option>
                                    <option value="Andaman and Nicobar Islands">Andaman and Nicobar Islands</option>
                                    <option value="Andhra Pradesh">Andhra Pradesh</option>
                                    <option value="Arunachal Pradesh">Arunachal Pradesh</option>
                                    <option value="Assam">Assam</option>
                                    <option value="Bihar">Bihar</option>
                                    <option value="Chandigarh">Chandigarh</option>
                                    <option value="Chhattisgarh">Chhattisgarh</option>
                                    <option value="Dadra and Nagar Haveli">Dadra and Nagar Haveli</option>
                                    <option value="Daman and Diu">Daman and Diu</option>
                                    <option value="Delhi">Delhi</option>
                                    <option value="Goa">Goa</option>
                                    <option value="Gujarat">Gujarat</option>
                                    <option value="Haryana">Haryana</option>
                                    <option value="Himachal Pradesh">Himachal Pradesh</option>
                                    <option value="Jammu and Kashmir">Jammu and Kashmir</option>
                                    <option value="Jharkhand">Jharkhand</option>
                                    <option value="Karnataka">Karnataka</option>
                                    <option value="Kerala">Kerala</option>
                                    <option value="Lakshadweep">Lakshadweep</option>
                                    <option value="Madhya Pradesh">Madhya Pradesh</option>
                                    <option value="Maharashtra">Maharashtra</option>
                                    <option value="Manipur">Manipur</option>
                                    <option value="Meghalaya">Meghalaya</option>
                                    <option value="Mizoram">Mizoram</option>
                                    <option value="Nagaland">Nagaland</option>
                                    <option value="Odisha">Odisha</option>
                                    <option value="Pondicherry">Pondicherry</option>
                                    <option value="Punjab">Punjab</option>
                                    <option value="Rajasthan">Rajasthan</option>
                                    <option value="Sikkim">Sikkim</option>
                                    <option value="Tamil Nadu">Tamil Nadu</option>
                                    <option value="Tripura">Tripura</option>
                                    <option value="Uttaranchal">Uttaranchal</option>
                                    <option value="Uttar Pradesh">Uttar Pradesh</option>
                                    <option value="West Bengal">West Bengal</option>
                                 </select>
                             </div>
                             <div class="col-lg-6 col-md-6">
                             	<label class="label control-label">City</label>
                                <input type="text" class="form-control" name="r_city" placeholder="City">
                             </div>
                        </div><!-- row -->

                        <br><br>

                        <div class="row">
                        	<div class="col-lg-6 col-md-6">
                            	<label class="label control-label">Post Office</label>
                                <input type="text" class="form-control" name="r_post_office" placeholder="Post Office">
                             </div>
                             <div class="col-lg-6 col-md-6">
                             	<label class="label control-label">District</label>
                                <input type="text" class="form-control" name="r_dist" placeholder="District">
                             </div>
                        </div><!-- row -->

                        <br><br>

                        <div class="row">
                        	<div class="col-lg-12" style="background:#0F3">
                                <ol class="breadcrumb" style="background:#9F9">

                                    <li class="active">Permanent Address
                                    </li>
                                </ol>
                            </div>
                        </div><!--/.row-->

                        <br><br>

                        <div class="row">
                        	<div class="col-lg-6 col-md-6">

                                <label class="control-label">Same as Residential Address<input type="checkbox" onClick="check_address()" id="check" class="form-control" name="same_as_r" style="width:15px; margin-top:10px;"></label>
                             </div>
                             <div class="col-lg-6 col-md-6">

                             </div>
                        </div><!-- row -->

                        <br><br>
                        <div id="perm_address">
                        <div class="row">
                        	<div class="col-lg-6 col-md-6">
                            	<label class="label control-label">Flat/Door/Block No:</label>
                                <input type="text" class="form-control" name="p_flat" placeholder="Flat/Door/Block No:"><span class="error">  <?php  echo $p_flat_err?> </span>
                             </div>
                             <div class="col-lg-6 col-md-6">
                             	<label class="label control-label">Street/Lane (optional)</label>
                                <input type="text" class="form-control" name="p_street" placeholder="Street/Lane">
                             </div>
                        </div><!-- row -->

                        <br><br>

                        <div class="row">
                        	<div class="col-lg-6 col-md-6">
                            	<label class="label control-label">Area/Locality (optional)</label>
                                <input type="text" class="form-control" name="p_locality" placeholder="Area / Locality">
                             </div>
                             <div class="col-lg-6 col-md-6">
                             	<label class="label control-label">PIN</label>
                                <input type="number" class="form-control" name="p_pin" maxlength="6" minlength="6" value="<?php if(isset($_SESSION['p_pin'])){ echo $_SESSION['p_pin'];}?>" placeholder="PIN"><span class="error">  <?php  echo $p_pin_err?> </span>
                             </div>
                        </div><!-- row -->

                        <br><br>

                        <div class="row">
                        	<div class="col-lg-6 col-md-6">
                            	<label class="label control-label">State</label>
                                <select name="p_state" id="p_state" class="form-control" required>
                                    <option value="" disabled selected>----Select State----</option>
                                    <option value="Andaman and Nicobar Islands">Andaman and Nicobar Islands</option>
                                    <option value="Andhra Pradesh">Andhra Pradesh</option>
                                    <option value="Arunachal Pradesh">Arunachal Pradesh</option>
                                    <option value="Assam">Assam</option>
                                    <option value="Bihar">Bihar</option>
                                    <option value="Chandigarh">Chandigarh</option>
                                    <option value="Chhattisgarh">Chhattisgarh</option>
                                    <option value="Dadra and Nagar Haveli">Dadra and Nagar Haveli</option>
                                    <option value="Daman and Diu">Daman and Diu</option>
                                    <option value="Delhi">Delhi</option>
                                    <option value="Goa">Goa</option>
                                    <option value="Gujarat">Gujarat</option>
                                    <option value="Haryana">Haryana</option>
                                    <option value="Himachal Pradesh">Himachal Pradesh</option>
                                    <option value="Jammu and Kashmir">Jammu and Kashmir</option>
                                    <option value="Jharkhand">Jharkhand</option>
                                    <option value="Karnataka">Karnataka</option>
                                    <option value="Kerala">Kerala</option>
                                    <option value="Lakshadweep">Lakshadweep</option>
                                    <option value="Madhya Pradesh">Madhya Pradesh</option>
                                    <option value="Maharashtra">Maharashtra</option>
                                    <option value="Manipur">Manipur</option>
                                    <option value="Meghalaya">Meghalaya</option>
                                    <option value="Mizoram">Mizoram</option>
                                    <option value="Nagaland">Nagaland</option>
                                    <option value="Odisha">Odisha</option>
                                    <option value="Pondicherry">Pondicherry</option>
                                    <option value="Punjab">Punjab</option>
                                    <option value="Rajasthan">Rajasthan</option>
                                    <option value="Sikkim">Sikkim</option>
                                    <option value="Tamil Nadu">Tamil Nadu</option>
                                    <option value="Tripura">Tripura</option>
                                    <option value="Uttaranchal">Uttaranchal</option>
                                    <option value="Uttar Pradesh">Uttar Pradesh</option>
                                    <option value="West Bengal">West Bengal</option>
                                 </select>
                             </div>
                             <div class="col-lg-6 col-md-6">
                             	<label class="label control-label">City</label>
                                <input type="text" class="form-control" name="p_city" placeholder="City">
                             </div>
                        </div><!-- row -->

                        <br><br>

                        <div class="row">
                        	<div class="col-lg-6 col-md-6">
                            	<label class="label control-label">Post Office</label>
                                <input type="text" class="form-control" name="p_post_office" placeholder="Post Office">
                             </div>
                             <div class="col-lg-6 col-md-6">
                             	<label class="label control-label">District</label>
                                <input type="text" class="form-control" name="p_dist" placeholder="District">
                             </div>
                        </div><!-- row -->

                        </div>



												<br><br>
												<div class="row">
													<div class="col-lg-12" style="background:#0F3">
																<ol class="breadcrumb" style="background:#9F9">

																		<li class="active">Profile Picture
																		</li>
																</ol>
														</div>
												</div><!--/.row-->

											<br><br>

											<div class="row">
												<div class="col-lg-1 col-md-2 col-sm-1"></div>


												<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
																	<div class="profile_img">
																		<div id="crop-avatar">
																			<!-- Current avatar -->

																			<br><br>
																			<label class="control-label">Update Profile Picture</label>
																			<img class="img-responsive img-thumbnail avatar-view" id="input_image" style="cursor:pointer; display:none" src="#">
																			<input id="SUBMITTED_PROFILE_PICTURE" type="file" name="SUBMITTED_PROFILE_PICTURE" onChange="$('#input_image').attr('src',window.URL.createObjectURL(this.files[0]));$('#input_image').attr('style','display:block');$('#reset_image').attr('style','display:block')" required>

																			<div id="file_error"></div>

																			<!--end of Current avatar-->
																	 </div>
																</div>

													</div>
											</div><!-- row -->

											<br><br>

											<div class="row">
												<div class="col-lg-12" style="background:#0F3">
															<ol class="breadcrumb" style="background:#9F9">

																	<li class="active">Password
																	</li>
															</ol>
													</div>
											</div><!--/.row-->


                        <br><br>

                        <div class="row">
                        	<div class="col-lg-6 col-md-6">
                                <input type="password" class="form-control" id="new_login_password" name="password" placeholder="Choose Password" required>
                                	<div id="new_login_password_details">
                                    </div>
                                    <div class="progress" id="new_password_progress" style="width:100%; min-height:20px; margin-bottom:0px; margin-top:20px;">
                                        <span class="progress-bar progress-bar-striped" role="progressbar" id="password_progress_bar" style="margin-bottom:10px;">
                                        </span>
                                    </div><!-- progress -->
                             </div>
                             <div class="col-lg-6 col-md-6">
                                <input type="text" class="form-control" id="confirm_login_password" name="c_password" placeholder="Confirm Password" required>

                                    <div id="confirm_login_password_progress" class="input_details"></div>
                             </div>
                        </div><!-- row -->

                        <br><br>

                        <div class="row">
                        	<div class="col-lg-6 col-md-6">
                            	<input type="submit" class="btn btn-success" id="submit" name="submit">

                             </div>
                             <div class="col-lg-6 col-md-6">
                                <input type="reset" class="btn btn-danger" id="reset" name="reset">
                             </div>
                        </div><!-- row -->


                        </form>
                     </div><!-- panel-body -->
                 </div><!-- panel -->
             </div><!-- col -->
        </div><!-- row -->







		<div class="col-sm-12">
				<p class="back-link">© Vasitars 2019</p>
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

	//binds to onchange event of your input field
	$('#SUBMITTED_PROFILE_PICTURE').bind('change', function() {
	document.getElementById("file_error").innerHTML = " ";
	var file_size = this.files[0].size;
	file_size = file_size/1024.00;
		//this.files[0].size gets the size of your file.
		if(file_size > 1024)
		{
			document.getElementById("submit").disabled = true;
			document.getElementById("file_error").innerHTML = "File Size too big";
		}

	});

	$('#reset_image').click(function() {
		document.getElementById("submit").disabled = false;
		document.getElementById('file_error').innerHTML = " ";
	});
	-->
  </script>

</body>
</html>
