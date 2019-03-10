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
		 $_SESSION['mobno']= $row['PHONE_NUMBER']  ;
		 $_SESSION['lname']= $row['LNAME'];
		 $_SESSION['mem_id']= $row['ID'];
		 $name = $_SESSION['fname'].' '.$_SESSION['lname'];
		 if($name == "Rahul Mahanot")
		 {
			 $name = "Super Admin";
		 }
		 $orig_password = $row['PASSWORD'];
		 $mem_id = $row['ID'];
		 date_default_timezone_set("Asia/Kolkata");
		 $date = date('Y-m-d');
		 $time = date("H:i:sa");

		 $present_incharge_query = mysqli_query($con, "SELECT LOC_ID FROM present_incharge WHERE MEM_ID = '".$_SESSION['mem_id']."'");
		 if(mysqli_num_rows($present_incharge_query) == 0)
		 {
			 echo  "<script type='text/javascript'>alert('You are not an incharge. Access Denied.');
					 window.location = 'logout';</script>";
			 header("logout");
		 }

		 if(($_SERVER["REQUEST_METHOD"] == "POST") && (isset($_POST['approve_attendance_submit'])))
		 {
			 $mem_name_submit = mysqli_real_escape_string($con, $_POST['mem_name_approve']);
			 $mem_id_approve = mysqli_real_escape_string($con, $_POST['mem_id_approve']);
			 $checkin_time_approve = mysqli_real_escape_string($con, $_POST['checkin_time_approve']);

			 if(mem_exists($mem_id_approve, $con))
			 {
				 if(!admin_exists($mem_id_approve, $con))
				 {
					 $approve_attendance_query = mysqli_query($con, "UPDATE attendance SET CheckIn_Approval_Status = '1', CheckIn_Approved_By_ID = '$mem_id', CheckIn_Approved_Date = '$date', CheckIn_Approved_Time = '$time' WHERE MEM_ID = '$mem_id_approve' AND CheckInTime = '$checkin_time_approve'");
					 if($approve_attendance_query)
					 {
						 echo  "<script type='text/javascript'>alert('Check In Approved for ".$mem_name_submit.".')</script>";
					 }
					 else
					 {
						 echo  "<script type='text/javascript'>alert('Unable to Process Request. Please Try again.')</script>";
					 }
				 }
				 else
				 {
					 echo  "<script type='text/javascript'>alert('".$mem_name_submit." is an Admin. Approval not required for Admins.')</script>";
				 }
			 }
			 else
			 {
				 echo  "<script type='text/javascript'>alert('Member does not exist.')</script>";
			 }
		 }




	}
	else
	{
		echo  "<script type='text/javascript'>alert('Sorry. Unable to process your request.');
				window.location = 'logout';</script>";
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
	<title>Approve Attendance | Vasitars</title>

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
				<li class="active">Approve Attendance</li>
			</ol>
		</div><!--/.row-->

		<br>

					<div class="row">
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<div class="panel" style="color:#bed4a4; background-color:#4f4a50">
								<div class="panel-body" style="font-family:Calibri">
									Incharges are responsible to approve CheckIns and CheckOuts for the employees in a region.<br>
									Incharges will also approve leaves taken by the employees and they will act as the POC for the employees in that region.</br>
								</div>
							</div>
						</div>
					</div>
			<?php


			  $present_incharge_query = mysqli_query($con, "SELECT LOC_ID FROM present_incharge WHERE MEM_ID = '".$_SESSION['mem_id']."'");
				if(mysqli_num_rows($present_incharge_query) == 0)
				{
					echo  "<script type='text/javascript'>alert('You are not an incharge. Access Denied.');
							window.location = 'logout';</script>";
					header("logout");
				}
				else
				{
					while($row_present_incharge_query = mysqli_fetch_assoc($present_incharge_query))
					{
						$location_query = mysqli_query($con, "SELECT Loc FROM locations WHERE LOC_ID = '".$row_present_incharge_query['LOC_ID']."'");
						$row_location_query = mysqli_fetch_assoc($location_query);
						$location = $row_location_query['Loc'];

						$mem_details_query = mysqli_query($con, "SELECT ID, SALUTATION, FNAME, LNAME FROM member WHERE LOC_ID = '".$row_present_incharge_query['LOC_ID']."'");
						if(mysqli_num_rows($mem_details_query) == 0)
						{
							echo '<div class="row">
											<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
												<div class="panel" style="color:#bed4a4; background-color:#4f4a50">
													<div class="panel-body" style="font-family:Calibri">
														There are no employee for the location - '.$location.' </br>
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
																	<b>Approve Attendance - (Location - '.$location.')</b>
																</div>

														</div>
														<br>

													  <thead>
														<tr>
																  <th style="text-align:center;">SI No</th>
																	<th style="text-align:center;">Employee Name</th>
																	<th style="text-align:center;">Date</th>
																	<th style="text-align:center;">Check-In Time</th>
																	<th style="text-align:center;">Check-Out Time</th>
																	<th style="text-align:center;">Approve</th>
																</tr>
													  </thead>';

														$restrict_date = new DateTime( date('Y-m-d') );
														date_modify($restrict_date, '-1 month');
														$restrict_date = date_format($restrict_date, 'Y-m-d');

														$x = 1;
														while($mem_row = mysqli_fetch_assoc($mem_details_query))
														{
															if(admin_exists($mem_row['ID'], $con) || ($mem_row['ID'] == $mem_id))
															{
																continue;
															}


															$attendance_query = mysqli_query($con, "SELECT * FROM attendance WHERE CheckIn_Approval_Status = '0' AND MEM_ID = '".$mem_row['ID']."' AND DATE >= '$restrict_date'");
															while($row_attendance_query = mysqli_fetch_assoc($attendance_query))
															{
																//$array_mem_id[] = $mem_row['ID'];
																//$array_CheckInTime[] = $row_attendance_query['CheckInTime'];
																$row_attendance_date = new DateTime($row_attendance_query['DATE']);
																$row_attendance_date = date_format($row_attendance_date, 'd/m/Y');

																echo '<tr style="text-align:center;">
																			<td>'.$x.'</td>
																			<td>'.$mem_row['SALUTATION'].' '.$mem_row['FNAME'].' '.$mem_row['LNAME'].'</td>
																			<td>'.$row_attendance_date.'</td>
																			<td>'.$row_attendance_query['CheckInTime'].'</td>';
																if($row_attendance_query['CheckOutTime'] == "00:00:00")
																{
																		echo '<td> -- : -- : -- </td>';
																}
																else
																{
																	echo '<td>'.$row_attendance_query['CheckOutTime'].'</td>';
																}

																echo	'<td><form role="form" action="'.htmlspecialchars($_SERVER["PHP_SELF"]).'" method="post">
																					<input type="hidden" value="'.$row_attendance_query['CheckInTime'].'" name="checkin_time_approve">
																					<input type="hidden" value="'.$mem_row['ID'].'" name="mem_id_approve">
																					<input type="hidden" value="'.$mem_row['FNAME'].' '.$mem_row['LNAME'].'" name="mem_name_approve">
																					<button type="submit" class="btn btn-success" name="approve_attendance_submit">Approve</button>
																					</form>
																				</td>';
																		$x++;
															}
														}
														if($x == 1)
														{
															echo 'There are no pending approvals left.';
														}
									echo '	</table>
												</div>
											</div>
									</div>
								</div>';
							}
						}
				 }


			?>




		<div class="row">

			<div class="col-sm-12">
					<p class="back-link">&copy; Vasitars <?php echo date("Y"); ?></p>
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

$('#approve_attendance').addClass("active");
	</script>
</body>
</html>
