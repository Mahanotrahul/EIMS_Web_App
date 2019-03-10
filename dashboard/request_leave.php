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



		 if(($_SERVER["REQUEST_METHOD"]== "POST") && (isset($_POST['submit_leave_request'])))
		 {
			 $leave_type = mysqli_real_escape_string($con, $_POST['leave_type']);
			 $leave_request_from_date = mysqli_real_escape_string($con, $_POST['leave_request_from_date']);
			 $leave_request_to_date = mysqli_real_escape_string($con, $_POST['leave_request_to_date']);
			 $leave_request_reason = mysqli_real_escape_string($con, $_POST['leave_request_reason']);
			 $password = md5(mysqli_real_escape_string($con, $_POST['password']));

			 if($leave_type == "PL")
			 {
				 $leave_type_id = 1;
				 $leave_type = "Planned Leave";
			 }
			 else if($leave_type == "SL")
			 {
				 $leave_type_id = 2;
				 $leave_type = "Sick Leave";
			 }
			 else
			 {
				 $leave_type_id = 0;
			 }

			if(empty($leave_type))
			{
				$leave_type_err = "Leave Type is required";
				$error = 1;
			}
			else
			{
				$_SESSION['leave_type'] = $leave_type;
			}

			if(empty($leave_request_from_date))
			{
				$leave_request_from_date_errr = "From Date is required.";
				$error = 1;
			}
			else
			{
				$_SESSION['leave_request_from_date'] = $leave_request_from_date;
			}

			if(empty($leave_request_to_date))
			{
				$leave_request_to_date_err = "To Date is required.";
				$error = 1;
			}
			else
			{
				$_SESSION['leave_request_to_date'] = $leave_request_to_date;
			}

			if(empty($leave_request_reason))
			{
				$fname_err = "Leave request reason is required";
				$error = 1;
			}
			else if(strlen($leave_request_reason) > 100)
			{
				$leave_request_reason_err = "Leave request reason is Invalid. Max 100 characters allowed";
				$error = 1;
			}
			else
			{
				$_SESSION['leave_request_reason'] = $leave_request_reason;
			}

			$from_date = new DateTime($leave_request_from_date);
			$to_date = new DateTime($leave_request_to_date);
			$date_diff = date_diff($from_date, $to_date);
			$date_diff = $date_diff->format('%r%a');
			echo  "<script type='text/javascript'>alert('$date_diff');</script>";
			if($date_diff > $max_planned_leaves)
			{
				echo  "<script type='text/javascript'>alert('Leaves can be availed only for ".$max_planned_leaves." days.');</script>";
			}
			else if($date_diff < 0)
			{
				echo  "<script type='text/javascript'>alert('Date Range Invalid. Please Check.');</script>";
			}
			else if(strcmp($password, $orig_password) == 0)
			{
				$present_incharge_query = mysqli_query($con, "SELECT * FROM present_incharge WHERE LOC_ID = '".$_SESSION['mem_loc_id']."'");
				if(mysqli_num_rows($present_incharge_query) == 0)
				{
				 echo  "<script type='text/javascript'>alert('There are presently no InCharge for your location. Your leave request will not be approved. Kindly report this problem.');</script>";
				 $incharge_mem_id = -1; // -1 means No Incharge was not present for this location at the the time of giving attendance
				}
				else
				{
				 $row_present_incharge_query = mysqli_fetch_assoc($present_incharge_query);
				 $incharge_mem_id = $row_present_incharge_query['MEM_ID'];
				}

				$leave_query = mysqli_query($con, "SELECT * FROM leave_requests WHERE MEM_ID = '$mem_id' AND (Leave_Request_From_Date <= '$leave_request_from_date' AND Leave_Request_To_Date >= '$leave_request_to_date') OR (Leave_Request_From_Date <= '$leave_request_from_date' AND Leave_Request_To_Date <= '$leave_request_to_date' AND Leave_Request_To_Date >= '$leave_request_from_date') OR (Leave_Request_From_Date >= '$leave_request_from_date' AND Leave_Request_To_Date >= '$leave_request_to_date' AND Leave_Request_From_Date <= 'leave_request_to_date')");
				// 1st: find requests already applied for that includes the submitted date range.
				// 2nd: Submitted From_date fall within the available date, but To_date is after the available date.
				// 3rd: Submitted To_date falls within date available but From_date is before the available date
				if(mysqli_num_rows($leave_query) != 0)
				{
					echo  "<script type='text/javascript'>alert('You have already applied for request within or around the submitted date range. Unable to process request');</script>";
				}
				else
				{
					$query = mysqli_query($con, "INSERT INTO leave_requests(MEM_ID, Date, Time, Leave_Type_ID, Leave_Type, Leave_Request_From_Date, Leave_Request_To_Date, Leave_request_Reason, Request_Approval_Given_To_ID) VALUES('$mem_id', '$date', '$time', '$leave_type_id', '$leave_type', '$leave_request_from_date', '$leave_request_to_date', '$leave_request_reason', '$incharge_mem_id')");
					if($query)
					{
						echo  "<script type='text/javascript'>alert('Succesfully Applied for Leave. Leaves are granted unless approved by an Incharge.');</script>";
					}
					else
					{
						echo  "<script type='text/javascript'>alert('Unable to Process your request. Please try again.');</script>";
					}
				}



			}
			else
			{
				$password_err = "Incorrect password";
				echo "<script type='text/javascript'>alert('Incorrect password.')</script>";
			}
		 }
		 else if(($_SERVER["REQUEST_METHOD"]== "POST") && (isset($_POST['submit_leave_request'])))
		 {
			 $from_date = mysqli_real_escape_string($con, $_POST['from_date']);
			 $to_date = mysqli_real_escape_string($con, $_POST['to_date']);
			 $applied_date = mysqli_real_escape_string($con, $_POST['applied_date']);
			 $applied_time = mysqli_real_escape_string($con, $_POST['applied_time']);

			 $query = mysqli_query($con, "DELETE * FROM leave_requests WHERE Leave_Request_From_Date = '$from_date' AND Leave_Request_To_Date = '$to_date' AND DATE = '$applied_date' and Time = '$applied_time'");
			 if(!$query)
			 {
				 echo "<script type='text/javascript'>alert('Unable to remove leave request. Please Try again or report the problem.')</script>";
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
	<title>Request Leave | Vasitars</title>

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
				<li class="active">Request Leave</li>
			</ol>
		</div><!--/.row-->

		<br>

		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="panel" style="color:#bed4a4; background-color:#4f4a50">
					<div class="panel-body" style="font-family:Calibri">
						You can apply for Planned Leave or Sick Leave.<br>
						Leave Requests have to approved by the Incharge.</br>
						<?php
							$present_incharge_query = mysqli_query($con, "SELECT MEM_ID FROM present_incharge WHERE LOC_ID = '".$_SESSION['mem_loc_id']."'");
							if(mysqli_num_rows($present_incharge_query) == 0)
							{
								echo  "There is presently no InCharge for your location. Your leave request will not be approved. Kindly report this problem.";
								$incharge_mem_id = -1; // -1 means Incharge is not present for member's location
							}
						 ?>
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="panel panel-teal">

					<div class="panel-body">
						<form role="form" id="select_loc_form" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'])?>" method="post">
							<div class="row">

								<div class="col-lg-4 col-md-3">
									<label>Select Type of Leave</label>
									<select class="form-control" name="leave_type" id="leave_type"  style="height:45px;" value="<?php if(isset($_SESSION['leave_type'])) { echo $_SESSION['leave_type'];} ?>" required>
											<option value="" disabled selected> -- Select Type of Leave -- </option>';
											<option value="PL">Planned Leave</option>
											<option value="SL">Sick Leave</option>
									</select>
									<span class="error">  <?php  echo  $leave_type_err ?> </span>
								</div>

								<div class="col-lg-4 col-md-3">
									<label>From Date</label>
									<input type="date" style="width:100%; margin-right:10px; right:10px;" value="<?php if(isset($_SESSION['leave_request_from_date'])) { echo $_SESSION['leave_request_from_date'];} ?>" class="form-control" name="leave_request_from_date" class="btn btn-success" min="<?php echo date('Y-m-d')?>" id="leave_request_from_date"  placeholder="From Date" required><span class="error">  <?php  echo  $leave_request_from_date_errr ?> </span>
								</div>
								<div class="col-lg-4 col-md-3">
									<label>To Date</label>
									<input type="date" style="width:100%; margin-right:10px; right:10px;" value="<?php if(isset($_SESSION['leave_request_to_date'])) { echo $_SESSION['leave_request_to_date'];} ?>" class="form-control" name="leave_request_to_date" class="btn btn-success" min="<?php echo date('Y-m-d'); ?>" id="leave_request_to_date"  placeholder="To Date" required><span class="error">  <?php  echo  $leave_request_to_date_err?> </span>
								</div>
								<div class="col-lg-6 col-md-6">
									<label>Reason(in 100 characters)</label>
									<input type="text" style="width:100%; margin-right:10px; right:10px; resize:both; overflow:auto;" value="<?php if(isset($_SESSION['leave_request_reason'])) { echo $_SESSION['leave_request_reason'];} ?>" maxlength="100" class="form-control" name="leave_request_reason" class="btn btn-success" id="leave_request_reason" placeholder="Reason" required><span class="error">  <?php  echo  $leave_request_reason_err ?> </span>
								</div>
							</div><!-- row -->
							<div class="row">
								<div class="col-lg-4 col-md-3">
									<br>
									<input type="button" name="submit_leave_request_modal" class="btn btn-success" id="submit_leave_request_modal" value="Apply" placeholder="Apply">

								</div>
							</div><!-- row -->

							<div class="modal fade bs-example-modal-lg" id="leaveRequestModal" tabindex="-1" role="dialog" aria-hidden="true">
								<div class="modal-dialog modal-lg">
									<div class="modal-content">
										<div class="modal-header">
											<button type="button" class="close close_modal" data-dismiss="modal"><span aria-hidden="true">×</span>
											</button>
											<h4 class="modal-title" id="myModalLabel">Apply for Leave request</h4>
										</div>
										<div class="modal-body">
											<h4>Enter your password</h4>
											<br>
											<input type="password" class="form-control" name="password" placeholder="Type your Password" required>
										</div>
										<div class="modal-footer">
											<button class="btn btn-dark close_modal" data-dismiss="modal" style="margin-top:10px;">Close</button>
											<button type="submit" name="submit_leave_request" class="btn btn-success" id="submit_leave_request" style="margin-top:10px;">Submit</button>
										</div>
									</div>
								</div>
							</div>
						</form>
					</div><!-- panel body -->
				</div><!-- panel teal -->
			</div><!-- col -->
		</div><!-- row -->


		<?php


			$leave_requests_query = mysqli_query($con, "SELECT * FROM leave_requests WHERE MEM_ID = '".$_SESSION['mem_id']."'");
			if(mysqli_num_rows($leave_requests_query) == 0)
			{
				echo  '<div class="row">
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<div class="panel" style="color:#bed4a4; background-color:#4f4a50">
										<div class="panel-body" style="font-family:Calibri">
											You have previously not applied for any leave </br>
										</div>
									</div>
								</div>
							</div>';
			}
			else
			{
				$restrict_date = new DateTime( date('Y-m-d') );
				date_modify($restrict_date, '-1 month');
				$restrict_date = date_format($restrict_date, 'Y-m-d');
				$leave_requests_query = mysqli_query($con, "SELECT * FROM leave_requests WHERE MEM_ID = '".$_SESSION['mem_id']."' AND Leave_Request_To_Date >= '$restrict_date'");
				if(mysqli_num_rows($leave_requests_query) == 0)
				{
					echo  '<div class="row">
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
										<div class="panel" style="color:#bed4a4; background-color:#4f4a50">
											<div class="panel-body" style="font-family:Calibri">
												You have no leave requests in last 2 months. </br>
											</div>
										</div>
									</div>
								</div>';
				}
				else
				{
					echo '<div class="row">
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
											<div class="panel panel-teal">


												<div class="panel-body">
													<table  style="background:#1ebfae;" class="table  primary" cellspacing="0" border="1px "  bordercolor="#E7EDEE" >
													<div class="row">
															<div class="col-lg-6 col-md-6">
																<b>Applied Leave Requests</b>
															</div>

													</div>
													<br>

													<thead>
													<tr>
																<th style="text-align:center;">SI No</th>
																<th style="text-align:center;">From date</th>
																<th style="text-align:center;">To Date</th>
																<th style="text-align:center;">Total Days</th>
																<th style="text-align:center;">Approval Status</th>
																<th style="text-align:center;">Remove Request</th>
															</tr>
													</thead>';

													$x = 1;
													while($row_leave_requests_query = mysqli_fetch_assoc($leave_requests_query))
													{
														$from_date = new DateTime($row_leave_requests_query['Leave_Request_From_Date']);
														$to_date = new DateTime($row_leave_requests_query['Leave_Request_To_Date']);
														$date_diff = date_diff($from_date, $to_date);
														$date_diff = $date_diff->format('%r%a');
														$from_date = date_format($from_date, 'd/m/Y');
														$to_date = date_format($to_date, 'd/m/Y');

														$leave_approval_status = $row_leave_requests_query['Request_Approval_Status'];
														if($leave_approval_status == 1)
														{
															$leave_approval_status = "Approved";
														}
														else
														{
															$leave_approval_status = "Pending";
														}



														echo '<tr style="text-align:center;">
																	<td>'.$x.'</td>
																	<td>'.$from_date.'</td>
																	<td>'.$to_date.'</td>
																	<td>'.$date_diff.'</td>
																	<td>'.$leave_approval_status.'</td>';
														echo	'<td><form role="form" action="'.htmlspecialchars($_SERVER["PHP_SELF"]).'" method="post">
																			<input type="hidden" value="'.$row_leave_requests_query['Leave_Request_From_Date'].'" name="from_date">
																			<input type="hidden" value="'.$row_leave_requests_query['Leave_Request_To_Date'].'" name="to_date">
																			<input type="hidden" value="'.$row_leave_requests_query['Time'].'" name="time_applied">
																			<input type="hidden" value="'.$row_leave_requests_query['Date'].'" name="date_applied">
																			<button type="submit" class="btn btn-success" name="remove_leave_request_submit">Remove Request</button>
																			</form>
																		</td>';

														$x++;
													}
					echo '	</table>
								</div>
							</div>
						</div>
					</div>';


				}
			}
		?>



		<div class="row">

			<div class="col-sm-12">
					<p class="back-link">&copy; Vasitars <?php echo date("Y") ?></p>
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
	$(window).load(function(){
		$('#submit_leave_request_modal').on('click', function(e){
			$('#leaveRequestModal').modal();
		});
	});

$('#request_leave').addClass("active");
	</script>

</body>
</html>
