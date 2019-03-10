<?php
ob_start();
session_start();
include("connect.php");
include("function.php");

function made_admin_email($EMAIL, $NAME, $made_by)
{
	//$replyto = $EMAIL;
		$replyto = "rahul_mahanot@vasitars.com";
  $replysubject = "Administrator | Employee Dashboard | Vasitars";

	// Set content-type header for sending HTML email
	$headers = "MIME-Version: 1.0" . "\r\n";
	$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
  $headers .= 'From:Vasitars <donotreply@vasitars.com>'."\r\n";


	 $replymessage .= '
    <html>
    <head>
        <title>New Admin Created</title>
	    <link href="https://fonts.googleapis.com/css?family=Raleway:300" rel="stylesheet">

    </head>
    <body style="font-family:"Raleway"">';
		$replymessage .= 'Hello there ';
		$replymessage .= $NAME;
		$replymessage .= ',<br><br>';
   $replymessage .= 'You have been given Administrator rights by ';
	 $replymessage.= $made_by;
   $replymessage .= '.<br><br>';
  	$replymessage .= 'Check out the new features in the Admin Dashboard now.';
		$replymessage.= '<br><br>';
		$replymessage .= "Regards <br>";
		$replymessage .= "Admin <br>";
		$replymessage .= "Vasitars <br>";
	$replymessage .= 'This e-mail is automated, so please DO NOT reply';
	$replymessage .= '
    </body>
    </html>';


 mail($replyto, $replysubject, $replymessage, $headers);
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

		 if(($_SERVER["REQUEST_METHOD"] == "POST") && (isset($_POST['make_admin'])))
		 {
			 $employee_id = mysqli_real_escape_string($con, $_POST['employee_id']);
			 if(mem_exists($employee_id, $con))
			 {
				 if(admin_exists($employee_id, $con))
				 {
					 echo  "<script type='text/javascript'>alert('Admin already exists.')</script>";
				 }
				 else
				 {
					 $new_admin_query = mysqli_query($con, "SELECT * FROM member WHERE ID = '$employee_id'");
					 $make_admin_query = mysqli_query($con, "INSERT INTO admin(MEM_ID, Date, Time, Made_By) VALUES('$employee_id', '$date', '$time', '$name')");
					 if($make_admin_query)
					 {
						 echo  "<script type='text/javascript'>alert('New Admin Created.')</script>";
						 $notification = "You have been given Administrator rights by ".$_SESSION['fname']." ".$_SESSION['lname']." . Check out the new Admin features.";
						 $notification_type = "New Admin";
						 send_notif($employee_id, $date, $time, $notification, $notification_type, $con);
						 $row = mysqli_fetch_assoc($new_admin_query);
						 $new_admin_name = $row['FNAME'].' '.$row['LNAME'];

						 made_admin_email($row['EMAIL'], $new_admin_name, $name);
					 }
					 else
					 {
						 echo  "<script type='text/javascript'>alert('Unable to create new admin now. Please Try again later.')</script>";
					 }
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
	<title>Assign Incharge | Vasitars</title>

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
				<li class="active">Assign Incharge</li>
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

				if(($_SERVER["REQUEST_METHOD"] == "POST") && (isset($_POST['select_loc'])))
			 	{
				 $location_id = mysqli_real_escape_string($con, $_POST['loc_id']);
				 $_SESSION['location_id'] = $location_id;
			 }
			 else if(($_SERVER["REQUEST_METHOD"] == "POST") && (isset($_POST['make_new_incharge'])))
			 {
			 }
			 else
			 {
				 	$_SESSION['location_id'] = " ";
			 }

			echo '<div class="row">
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<div class="panel panel-teal">

									<div class="panel-body">


												<form role="form" id="select_loc_form" action='.htmlspecialchars($_SERVER["PHP_SELF"]).' method="post">
												<div class="row">

													<div class="col-lg-3 col-md-3">
														<label>Select Location</label>
														<select class="form-control" name="loc_id" id="loc" required>
																<option value="" disabled selected> -- Select Location -- </option>';

																$loc_details_query = mysqli_query($con, "SELECT * FROM locations");
																while($row = mysqli_fetch_assoc($loc_details_query))
																{
																	echo '<option value="'.$row['LOC_ID'].'"';
																	echo '>'.$row['Loc'].'</option>';
																	echo '';
																}

															echo '</select>
													</div>

												</div><!-- row -->


												<div class="row">
													<div class="col-lg-3 col-md-3">
														<br>
														<input type="submit" name="select_loc" class="btn btn-success" id="select_loc" value="Select location" placeholder="Select Location">
													</div>
												</div><!-- row -->
												</form>
											</div><!-- panel body -->
										</div><!-- panel teal -->
									</div><!-- col -->
								</div><!-- row -->';



			 if(($_SERVER["REQUEST_METHOD"] == "POST") && (isset($_POST['select_loc'])))
	 		 {
	 			 $location_id = mysqli_real_escape_string($con, $_POST['loc_id']);
	 			 if(loc_exists($location_id, $con))
	 			 {
					 $location_query = mysqli_query($con, "SELECT * FROM locations WHERE LOC_ID = '$location_id'");
					 $row_location_query = mysqli_fetch_assoc($location_query);
					 $location = $row_location_query['Loc'];
					 $_SESSION['location'] = $location;

					 // Check if present incharge already exists for this location. If not, allow new incharge creation. Else if exists, do not allow.
					 if(present_incharge_exists($_SESSION['location_id'], $con))
					 {
						 echo  "<script type='text/javascript'>alert('Present Incharge already exists for ".$_SESSION['location'].".')</script>";
					 }
					 else
					 {
	 					 echo '<div class="row">
						 				<div class="col-lg-12 col-md-12 col-xs-12 c0l-sm-12">
											<div class="panel panel-teal">
												<div class="panel-body">


												<div class="row">
													<div class="col-lg-3 col-md-3">
															<span style="font-size:16px; color:#9F6">Make New Incharge For '.$location.'</span>
															<br>
													</div>
												</div><!-- row -->
												<br>
												<form role="form" id="assign_incharge_form" action='.htmlspecialchars($_SERVER["PHP_SELF"]).' method="post">
													<div class="row">
														<div class="col-lg-3 col-md-3">
															<label>Select Employee</label>
															<select class="form-control" name="employee_id" id="employee_id" required>
																	<option value="" disabled selected> -- Select Employee Name -- </option>';

																	$emp_details_query = mysqli_query($con, "SELECT SALUTATION, FNAME, LNAME, ID FROM member");
																	while($row = mysqli_fetch_assoc($emp_details_query))
																	{
																		echo '<option value="'.$row['ID'].'">'.$row['SALUTATION'].' '.$row['FNAME'].' '.$row['LNAME'].'</option>';
																		echo '';
																	}

																echo '</select>
														</div>';
														// <div class="col-lg-3 col-md-3">
														// 	<label>To Date</label>
														// 	<input type="date" class="form-control" name="to_date" placeholder="To Date" min="'.date("Y-m-d").'" style="height:35px" value="';
														// 		if(isset($_SESSION['to_date']))
														// 		{
														// 			echo $_SESSION['to_date'];
														// 		}
														// 	echo '"  required><span class="error"> '.$to_date.' </span>
														// </div>
													echo '</div><!-- row -->

														<div class="row">
															<div class="col-lg-3 col-md-3">
																<label></label>
																<input type="submit" name="make_new_incharge" class="btn btn-success" id="make_new_incharge" value="Make New Incharge" placeholder="Make New Incharge">
															</div>
														</div><!-- row -->
														</form>
													</div><!-- panel-body -->
												</div>
											</div>
									</div>';
							}
	 			 }
	 			 else
	 			 {
	 				 echo  "<script type='text/javascript'>alert('Location does not exist.')</script>";
	 			 }
			 }

			 if(($_SERVER["REQUEST_METHOD"] == "POST") && (isset($_POST['make_new_incharge'])))
	 		 {
				 if(present_incharge_exists($_SESSION['location_id'], $con))
				 {
					 echo  "<script type='text/javascript'>alert('Present Incharge already exists for ".$_SESSION['location'].".')</script>";
				 }
				 else
				 {
					 $location_id = $_SESSION['location_id'];
					 $employee_id = mysqli_real_escape_string($con, $_POST['employee_id']);
					 $new_incharge_query = mysqli_query($con, "INSERT INTO incharge(MEM_ID, LOC_ID, Date, Time, From_Date, Made_By, Made_By_ID) VALUES('$employee_id', '$location_id', '$date', '$time', '$date', '$name', '$mem_id')");
					 $new_p_incharge_query = mysqli_query($con, "INSERT INTO present_incharge(MEM_ID, LOC_ID, Date, Time, Made_By, Made_By_ID) VALUES('$employee_id', '$location_id', '$date', '$time', '$name', '$mem_id')");
					 $notification = "You have been given incharge responsibility for ".$_SESSION['location']." by ".$name.".";
					 $notification_type = "New Incharge";
					 send_notif($employee_id, $date, $time, $notification, $notification_type, $con);
					 header(htmlspecialchars($_SERVER["PHP_SELF"]));
					 echo  "<script type='text/javascript'>window.location = '".htmlspecialchars($_SERVER["PHP_SELF"])."';</script>";
				 }
			 }




			 	if(($_SERVER["REQUEST_METHOD"] == "POST") && (isset($_POST['remove_incharge_submit'])))
				{
					$mem_loc_id = mysqli_real_escape_string($con, $_POST['mem_loc_remove']);
					$location_query = mysqli_query($con, "SELECT * FROM locations WHERE LOC_ID = '$mem_loc_id'");
					$row_location_query = mysqli_fetch_assoc($location_query);
					$location = $row_location_query['Loc'];
					$mem_id = mysqli_real_escape_string($con, $_POST['mem_id_remove']);
					$mem_name = mysqli_real_escape_string($con, $_POST['mem_name_remove']);
					if($mem_name == "Rahul Mahanot")
					{
						$mem_name = "Super Admin";
					}
					$remove_incharge_query = mysqli_query($con, "DELETE FROM present_incharge WHERE LOC_ID = '$mem_loc_id'");
					$update_incharge_query = mysqli_query($con, "UPDATE incharge SET To_Date = '$date' AND To_Time = '$time'");
					$notification = "You were removed from incharge responsibility for ".$location." by ".$mem_name.".";
					$notification_type = "Remove Incharge";
					send_notif($mem_id, $date, $time, $notification, $notification_type, $con);
					header(htmlspecialchars($_SERVER["PHP_SELF"]));
					echo  "<script type='text/javascript'>window.location = '".htmlspecialchars($_SERVER["PHP_SELF"])."';</script>";
				}

			  $present_incharge_query = mysqli_query($con, "SELECT * FROM present_incharge");

				if(mysqli_num_rows($present_incharge_query) >= 1)
				{
					echo '<div class="row">
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
										<div class="panel panel-teal">


											<div class="panel-body">
												<table  style="background:#1ebfae;" class="table  primary" cellspacing="0" border="1px "  bordercolor="#E7EDEE" >
												<div class="row">
														<div class="col-lg-6 col-md-6">
															<b>Present Incharges </b>
														</div>

												</div>
												<br>

											  <thead>
												<tr>
														  <th style="text-align:center;">SI No</th>
															<th style="text-align:center;">Name</th>
															<th style="text-align:center;">Location</th>
															<th style="text-align:center;">Since Date</th>
															<th style="text-align:center;">Made By</th>
															<th style="text-align:center;">Remove Incharge</th>
														</tr>
											  </thead>';

												$x = 1;
												while($row = mysqli_fetch_assoc($present_incharge_query))
												{
													$mem_details_query = mysqli_query($con, "SELECT SALUTATION, FNAME, LNAME FROM member WHERE ID = '".$row['MEM_ID']."'");
													$mem_row = mysqli_fetch_assoc($mem_details_query);

													$location_query = mysqli_query($con, "SELECT * FROM locations WHERE LOC_ID = '".$row['LOC_ID']."'");
													$row_location_query = mysqli_fetch_assoc($location_query);
													$location = $row_location_query['Loc'];
													$row_date = new DateTime($row['Date']);
													$row_date = date_format($row_date, 'd/m/Y');

													echo '<tr style="text-align:center;">
													<td>'.$x.'</td>
													<td>'.$mem_row['SALUTATION'].' '.$mem_row['FNAME'].' '.$mem_row['LNAME'].'</td>
													<td>'.$location.'</td>
													<td>'.$row_date.'</td>
													<td>'.$row['Made_By'].'</td>
													<td><form role="form" action="'.htmlspecialchars($_SERVER["PHP_SELF"]).'" method="post">
																<input type="hidden" value="'.$row['LOC_ID'].'" name="mem_loc_remove">
																<input type="hidden" value="'.$row['MEM_ID'].'" name="mem_id_remove">
																<input type="hidden" value="'.$mem_row['FNAME'].' '.$mem_row['LNAME'].'" name="mem_name_remove">
																<button type="submit" class="btn btn-success" name="remove_incharge_submit">Remove Incharge</button>
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
				 else if(($_SERVER["REQUEST_METHOD"] == "POST") && (isset($_POST['select_loc'])))
				 {
					 echo  "<script type='text/javascript'>alert('There are presently no incharge.')</script>";
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
$('select option').filter(function() {
//may want to use $.trim in here
return $(this).text() == <?php echo '"'.$_SESSION['location'].'"'; ?>;
}).prop('selected', true);


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
$('#assign_incharge').addClass("active");
	</script>
</body>
</html>
