<?php
ob_start();
session_start();
include("connect.php");
include("function.php");

function isChecked($mem_id)
{
	global $con;
	date_default_timezone_set("Asia/Kolkata");
	$date = date('Y-m-d');
	$time = date("H:i:sa");
	$query = mysqli_query($con, "SELECT * FROM attendance WHERE MEM_ID = '$mem_id' AND DATE = '$date'");
	$_SESSION['Checked'] = 0;
	if(mysqli_num_rows($query) > 0)
	{
		while($row = mysqli_fetch_assoc($query))
		{

			if($row['Status'] == 1)
			{
				$_SESSION['Checked'] = 1;
				$_SESSION['CheckInTime'] = $row['CheckInTime'];
				return " checked ";
			}
		}
		return " disabled";

	}
}

function checkedOut($mem_id)
{
	global $con;
	date_default_timezone_set("Asia/Kolkata");
	$date = date('Y-m-d');
	$time = date("H:i:sa");
	$query = mysqli_query($con, "SELECT * FROM attendance WHERE MEM_ID = '$mem_id' AND DATE = '$date' AND Status = '0'");
	$_SESSION['CheckedOut'] = 0;
	if(mysqli_num_rows($query) > 0)
	{
		$_SESSION['CheckedOut'] = 1;
		return 1;
	}
}

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
		 $_SESSION['mobno']= $row['PHONE_NUMBER']  ;
		 $_SESSION['lname']= $row['LNAME'];
		 $_SESSION['mem_id']= $row['ID'];
		 $mem_loc_id = $row['LOC_ID'];
		 $_SESSION['mem_loc_id'] = $row['LOC_ID'];
		 $_SESSION['salutation'] = $row['SALUTATION'];
		 $orig_password = $row['PASSWORD'];
		 $mem_id = $row['ID'];
		 date_default_timezone_set("Asia/Kolkata");
		 $date = date('Y-m-d');
		 $time = date("H:i:sa");



		 if(($_SERVER["REQUEST_METHOD"]== "POST") && (isset($_POST['CheckIn'])))
		 {
			 $present_incharge_query = mysqli_query($con, "SELECT * FROM present_incharge WHERE LOC_ID = '".$_SESSION['mem_loc_id']."'");
			 if(mysqli_num_rows($present_incharge_query) == 0)
			 {
				 echo  "<script type='text/javascript'>alert('There are presently no InCharge for your location. Your attendance will not be approved. Kindly report this problem.');</script>";
				 $incharge_mem_id = -1; // -1 means No Incharge was not present for this location at the the time of giving attendance
			 }
			 else
			 {
				 $row_present_incharge_query = mysqli_fetch_assoc($present_incharge_query);
				 $incharge_mem_id = $row_present_incharge_query['MEM_ID'];
			 }

			 $query = mysqli_query($con, "SELECT * FROM attendance WHERE MEM_ID = '$mem_id' AND DATE = '$date'");
			 $row = mysqli_fetch_assoc($query);
			 $password = md5(mysqli_real_escape_string($con, $_POST['password']));

			 if(strcmp($password,$orig_password) == 0)
			 {
				 if(mysqli_num_rows($query) == 0)
				 {
					 $query = mysqli_query($con, "INSERT INTO attendance(MEM_ID, DATE, CheckInTime, Status, CheckIn_Approval_Given_To_ID) VALUES('$mem_id', '$date' , '$time' , 1, '$incharge_mem_id')");
					 if($query)
					 {
						 echo  "<script type='text/javascript'>alert('Succesfully Checked In');</script>";
						 $_SESSION['AlreadyCheckedIn'] = 1;
					 }
					 else
					 {
							echo  "<script type='text/javascript'>alert('Unable to Check In. Try again.');</script>";
					 }
				 }
				 else
				 {
				 	 echo  "<script type='text/javascript'>alert('You have already Checked in for today. You can request for change in attendance timings.');</script>";
				 }

			 }
			 else
			 {
				 $password_err = "Incorrect password";
				 echo "<script type='text/javascript'>alert('Incorrect password.')</script>";
			 }


		 }
		 // else if(($_SERVER["REQUEST_METHOD"]== "POST") && (isset($_POST['NewSessionCheckIn'])))
		 // {
			//  $password = md5(mysqli_real_escape_string($con, $_POST['password']));
		 //
			//  if(strcmp($password,$orig_password) == 0)
			//  {
			// 	 $query = mysqli_query($con, "INSERT INTO attendance(MEM_ID, DATE, CheckInTime, Status) VALUES('$mem_id', '$date' , '$time' , 1)");
			// 	 if($query)
			// 	 {
			// 		 $_SESSION['NewCheckInSession'] = 0;
			// 		 echo  "<script type='text/javascript'>alert('Succesfully Checked In. New Session Created');</script>";
			// 	 }
			// 	 else
			// 	 {
			// 			echo  "<script type='text/javascript'>alert('Unable to Check In. Try again.');</script>";
			// 	 }
			//  }
			//  else
			//  {
			// 	 $password_err = "Incorrect password";
			// 	 echo "<script type='text/javascript'>alert('Incorrect password.')</script>";
			//  }
		 //
		 //
		 //
		 // }


		 else if(($_SERVER["REQUEST_METHOD"]== "POST") && (isset($_POST['CheckOut'])))
		 {
			 $query = mysqli_query($con, "SELECT * FROM attendance WHERE MEM_ID = '$mem_id' AND DATE = '$date'");
			 if(mysqli_num_rows($query) == 0)
			 {
				 echo  "<script type='text/javascript'>alert('Not Checked In');</script>";
			 }
			 else
			 {
				 $query = mysqli_query($con, "UPDATE attendance SET CheckOutTime = '$time', Status = 0 WHERE MEM_ID = '$mem_id' AND DATE = '$date' AND CheckInTime = '".$_SESSION['CheckInTime']."'");
				 if($query)
				 {
					 echo  "<script type='text/javascript'>alert('Succesfully Checked Out');</script>";
				 }
				 else
				 {
						echo  "<script type='text/javascript'>alert('Unable to Check Out. Try again.');</script>";
				 }

			 }

		 }

		 else
		 {
			 $query = mysqli_query($con, "SELECT * FROM attendance WHERE MEM_ID = '$mem_id' AND DATE = '$date'");
			 if(mysqli_num_rows($query) >= 1)
			 {
				 $_SESSION['AlreadyCheckedIn'] = 1;
			 }
			 else
			 {
				 $_SESSION['AlreadyCheckedIn'] = 0;
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
	<title>Attendance | Vasitars</title>

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
				<li><a href="index" title="Dashboard">
					<em class="fa fa-home"></em>
			  </a></li>
				<li class="active">Attendance</li>
			</ol>
		</div><!--/.row-->

		<br>



											<?php
												if(checkedOut($_SESSION['mem_id']))
												{
													echo '<div class="row">
														<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
															<div class="panel" style="color:#bed4a4; background-color:#4f4a50">
																<div class="panel-body" style="font-family:Calibri">
																 You have already Checked In for the day. You can request for change in attendance timings below.
																 </div>
								 							</div>
								 						</div>
								 					</div>';
												}
												else
												{
													echo '<div class="row">
														<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
															<div class="panel" style="color:#bed4a4; background-color:#4f4a50">
																<div class="panel-body" style="font-family:Calibri">
																 Admins do not require approval for their attendance.
																 </div>
								 							</div>
								 						</div>
								 					</div>';

													echo '<form role="form" action="'.htmlspecialchars($_SERVER["PHP_SELF"]).'" method="post">
																<div class="row">
																	<div class="col-lg-12">
																			<div class="panel panel-teal">

																				<div class="panel-body">

																					<div class="row">
																						<div id ="headh" class="col-md-6">
																							<div class="form-group">
																							<label> Attendance </label>
																			&nbsp;&nbsp;
																			<input type="checkbox" class="js-switch" id="check_box"'.isChecked($_SESSION['mem_id']).'data-toggle="modal" data-target=".bs-example-modal-lg">&nbsp;&nbsp;Check';
																		if((isset($_SESSION['Checked'])) && ($_SESSION['Checked'] == 1))
							 											 {
							 												 echo 'Out';
							 											 }
							 											 else
							 											 {
							 												 echo 'In';
							 											 }
																		 echo '</div>
												 									</div>
												 								</div><!-- row -->
												 							</div><!-- panel-body -->
												 						</div><!-- panel-teal -->

												 					</div><!-- col-lg-12 -->
												 			</div><!--/.row-->';
												}


											?>








			<div class="modal fade bs-example-modal-lg" id="myCheckInModal" tabindex="-1" role="dialog" aria-hidden="true">
				<div class="modal-dialog modal-lg">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close close_modal" data-dismiss="modal"><span aria-hidden="true">×</span>
							</button>
							<h4 class="modal-title" id="myModalLabel">Check In</h4>
						</div>
						<div class="modal-body">
							<h4>Are you sure, You want to Check In?</h4>
							<br>
							<input type="password" class="form-control" name="password" placeholder="Type your Password" required>
						</div>
						<div class="modal-footer">
							<button class="btn btn-dark close_modal" data-dismiss="modal" style="margin-top:10px;">Close</button>
							<button type="submit" formaction="<?php  echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" name="CheckIn" class="btn btn-success" id="submit-CheckIn" style="margin-top:10px;">Check In</button>
						</div>
					</div>
				</div>
			</div>


			<div class="modal fade bs-example-modal-lg" id="myCheckOutModal" tabindex="-1" role="dialog" aria-hidden="true">
				<div class="modal-dialog modal-lg">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close close_modal" data-dismiss="modal"><span aria-hidden="true">×</span>
							</button>
							<h4 class="modal-title" id="myModalLabel-1">Check Out</h4>
						</div>
						<div class="modal-body">
							<h4>Are you sure, You want to Check Out?</h4>
							<br>

						</div>
						<div class="modal-footer">
							<button class="btn btn-dark close_modal" data-dismiss="modal" style="margin-top:10px;">Close</button>
							<input type="submit" name="CheckOut" formaction="<?php  echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" formnovalidate class="btn btn-success" id="submit-CheckOut" value="Check Out" style="margin-top:10px;"></button>
						</div>
					</div>
				</div>
			</div>
		</form>
				<!-- <form role="form" action="<?php  echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
					<div class="modal fade bs-example-modal-lg-2" id="NewSessionModal" tabindex="-1" role="dialog" aria-hidden="true">
						<div class="modal-dialog modal-lg-2">
							<div class="modal-content">
								<div class="modal-header">
									<button type="button" class="close close_modal" data-dismiss="modal"><span aria-hidden="true">×</span>
									</button>
									<h4 class="modal-title" id="myModalLabel-2">Check In</h4>
								</div>
								<div class="modal-body">
									<h4>You have already Checked In.<br> Are you sure, You want to Check In with a new Session?</h4>
									<br>
									<input type="password" class="form-control" name="password" placeholder="Type your Password" required>
								</div>
								<div class="modal-footer">
									<button class="btn btn-dark close_modal" data-dismiss="modal" style="margin-top:10px;">Close</button>
									<button type="submit" formaction="<?php  echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" name="NewSessionCheckIn" class="btn btn-success" id="submit-NewSessionCheckIn" style="margin-top:10px;">Check In</button>
								</div>
							</div>
						</div>
					</div>
				</form> -->

			<?php


				include("connect.php");
				date_default_timezone_set("Asia/Kolkata");
				$date = date('Y-m-d');
				$time = date("H:i:sa");
				if(($_SERVER["REQUEST_METHOD"]== "POST") && (isset($_POST['Work_Check_date'])))
				{
					$date = date("Y-m-d", strtotime($_POST['Work_Check_date']));
					$value_date = date('Y-m-d', strtotime($_POST['Work_Check_date']));

					$d1 = new DateTime($value_date);
					$today = date("Y-m-d");

					$d2 = new DateTime($today);
					$diff = date_diff($d1, $d2);
					$date_diff = $diff->format("%a");

					$query = mysqli_query($con, "SELECT * FROM attendance WHERE MEM_ID = ".$_SESSION['mem_id']." AND DATE = '$date'");
				}
				else
				{
					$date_diff = "No_diff";
					$value_date = date('Y-m-d');
					$query = mysqli_query($con, "SELECT * FROM attendance WHERE MEM_ID = ".$_SESSION['mem_id']." AND DATE = '$date'");
				}

				if(mysqli_num_rows($query) > 0)
				{

						echo '<div class="row">
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
											<div class="panel panel-teal">

												<div class="panel-body">
													<table  style="background:#1ebfae;" class="table  primary" cellspacing="0" border="1px "  bordercolor="#E7EDEE" >
															<div class="row">
																<div class="col-lg-3 col-md-3">
																		<span style="font-size:16px; color:#9F6">Work Schedule</span>

																</div>
																<div class="col-lg-5 col-md-5 text-right">
																	<label>Check Work Schedule for different date</label>
																</div>
																<div class="col-lg-4 col-md-4 text-right" style="align:right;">
																	<form role="form" id="WorkCheckDateForm" action='.htmlspecialchars($_SERVER["PHP_SELF"]).' method="post">

																		<input style="width:100%; margin-right:10px; right:10px;" class="form-control" id="WorkCheckDate" type="date" value="'.$value_date.'" max="'.date("Y-m-d").'"   name="Work_Check_date" formaction='.htmlspecialchars($_SERVER["PHP_SELF"]).'">
																	</form>
																</div>
															</div>

												  <thead>
													<tr>
													  <th style="text-align:center;  ">SI No</th>
																<th style="text-align:center;">Date</th>
																<th style="text-align:center;">Check In Time</th>
																<th style="text-align:center;">Check Out Time</th>
																<th style="text-align:center;">Session Time</th>
																<th style="text-align:center;">Approval Status</th>

															</tr>
												  </thead>';

														$x = 1;
														while($row = mysqli_fetch_assoc($query))
														{
															$row_date = new DateTime( $row['DATE']);
															$row_date = date_format($row_date, 'd/m/Y');


															echo '<tr style="text-align:center;">
															<td>'.$x.'</td>
															<td>'.$row_date.'</td>';
															echo '<td>'.$row['CheckInTime'].'</td>';

															if($row['CheckOutTime'] == "00:00:00")
															{
																if(($date_diff != 0) && ($date_diff != "No_diff"))
																{
																	$t = date("H:i:s", strtotime("23:59:59"));;
																	$date2 = new DateTime($t);
																	echo '<td>'.$t.'</td>';

																}
																else
																{
																	date_default_timezone_set("Asia/Kolkata");
																	$t = date('H:i:s');
																	$date2 = new DateTime($t);
																	echo '<td> -- : -- : -- </td>';
																}

															}
															else
															{
																$date2 = new DateTime($row['CheckOutTime']);
																echo '<td>'.$row['CheckOutTime'].'</td>';
															}
															$date1 = new DateTime($row['CheckInTime']);
															$diff=date_diff($date1,$date2);
															$time_diff = $diff->format("%H hour %i minutes");
															if(mb_substr($time_diff, 0, 2) == "00")
															{
																$time_diff = $diff->format("%i minutes");
																echo '<td>'.$time_diff.'</td>';
															}
															else
															{
																	echo '<td>'.$time_diff.'</td>';
															}

															if($row['CheckIn_Approval_Status'] == 1)
															{
																echo '<td>Approved</td>';
															}
															else
															{
																echo '<td>Pending</td>';
															}

															echo '</tr>';

															$x++;
														}


					}
					else
					 {
						  echo 'No Data Found for this date.';
							echo '<div class="row">
											<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
												<div class="panel panel-teal">

													<div class="panel-body">
														<table  style="background:#1ebfae;" class="table  primary" cellspacing="0" border="1px "  bordercolor="#E7EDEE" >
																<div class="row">
																	<div class="col-lg-3 col-md-3">
																			<span style="font-size:16px; color:#9F6">Work Schedule</span>

																	</div>
																	<div class="col-lg-5 col-md-5 text-right">
																		<label>Check Work Schedule for different date</label>
																	</div>
																	<div class="col-lg-4 col-md-4 text-right" style="align:right;">
																		<form role="form" id="WorkCheckDateForm" action='.htmlspecialchars($_SERVER["PHP_SELF"]).' method="post">

																			<input style="width:100%; margin-right:10px; right:10px;" class="form-control" id="WorkCheckDate" type="date" name="Work_Check_date" value="'.$value_date.'" max="'.date("Y-m-d").'" formaction='.htmlspecialchars($_SERVER["PHP_SELF"]).'">
																		</form>
																	</div>
																</div>

													  <thead>
														<tr>
														  <th style="text-align:center;  ">SI No</th>
																	<th style="text-align:center;">Date</th>
																	<th style="text-align:center;">Check In Time</th>
																	<th style="text-align:center;">Check Out Time</th>
																	<th style="text-align:center;">Session Time</th>
																	<th style="text-align:center;">Approval Status</th>

																</tr>
													  </thead>';
					 }



			?>

									</table>
								</div>
							</div>
					</div>
				</div>


		<div class="row">

			<div class="col-sm-12">
					<p class="back-link">&copy; Vasitars 2019</p>
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
    <!-- Switchery -->
    <script src="js/switchery.min.js"></script>
    <!-- Custom Theme Scripts -->
    <script src="js/custom.min.js"></script>




	<script>

//el = document.querySelectorAll(".close_modal");
//for(var i=0; i < el.length; i++)
//{
//	el[i].onclick = function(){
//		if(document.getElementById("checkbox").checked)
//		{
//			alert(document.getElementById("checkbox").checked);
//			document.getElementById("checkbox").click();
//		}
//	}
//}

//$('.close_modal').on('click', function(e){
//	//alert(document.getElementById("checkbox").checked);
//	if(document.getElementById("check_box").checked)
//	{
//		$('#check_box').removeAttr("checked");
//		document.getElementById('check_box').checked = 0;
//		document.getElementById("check_box").click();
//	}
//});
//

$('#WorkCheckDate').on('change', function(){
	$('#WorkCheckDateForm').submit();
});
$('input[type="checkbox"]').on('change', function(e){
	var NewSession = <?php
						if(isset($_SESSION['NewCheckInSession']) && ($_SESSION['NewCheckInSession'] == 1))
						{
							echo 1;
						}
						else
						{
							echo 0;
						}
					 ?>;
  if(e.target.checked)
	{
				//alert(document.getElementById("check_box").checked);
				//$('#check_box').removeAttr("checked");
				document.getElementById('check_box').checked = false;
				//document.getElementById("check_box").click();
				if(NewSession == 1)
				{
					$('#NewSessionModal').modal();
				}
				else
				{
					$('#myCheckInModal').modal();
				}

	}
	else
	{
		document.getElementById('check_box').checked = true;
		$('#myCheckOutModal').modal();
	}
});
$('#attendance').addClass("active");
	</script>

</body>
</html>
