<?php
ob_start();
session_start();
include("connect.php");
include("function.php");

if(!logged_in())
{
	header("../login.php");
	echo  "<script type='text/javascript'>window.location = '../login.php';</script>";
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
		 $mem_id = $row['ID'];
		 $profile_picture_location = profile_picture_location($mem_id);
		 //$_SESSION['college']= $row['college']  ;

	}
	else
	{
		echo  "<script type='text/javascript'>alert('Sorry. Unable to process your request.');
				window.location = '../logout.php';</script>";
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
	<title>Employee Profile | Vasitars</title>

	<link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="css/font-awesome.min.css" rel="stylesheet">
	<link href="css/datepicker3.css" rel="stylesheet">
	<link href="css/styles.css" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Lobster" rel="stylesheet">
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
				<li><a href="index.php">
					<em class="fa fa-home"></em>
			  </a></li>
				<li class="active">Profile</li>
			</ol>
		</div><!--/.row-->

		<br>

		<?php
		$mem_details_query = mysqli_query($con, "SELECT * FROM member WHERE ID = '".$_SESSION['mem_id']."'");
		$row = mysqli_fetch_assoc($mem_details_query);
		if(mysqli_num_rows($mem_details_query) == 1)
		{
			$location_query = mysqli_query($con, "SELECT Loc FROM locations WHERE LOC_ID = '".$row['LOC_ID']."'");
			$row_location_query = mysqli_fetch_assoc($location_query);
			$location = $row_location_query['Loc'];

			$row_dob = new DateTime($row['DATE_OF_BIRTH']);
			$row_dob = date_format($row_dob, 'd/m/Y');

			echo '<div class="row">
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<div class="panel panel-teal">


									<div class="panel-body">
										<div class="row">
											<div class="col-md-4">
												<img src="'.profile_picture_location($_SESSION['mem_id']).'" class="img-responsive thumbnail" alt="" style="height:200px; width:200px; margin-left:20px;">
											</div>
										</div><!-- row -->


										<div class="row">
											<div class="col-lg-4 col-md-6">
												<label>Name: </label>
												'.$row['SALUTATION'].' '.$row['FNAME'].' '.$row['LNAME'].'
											</div>
											<div class="col-lg-4 col-md-6">
												<label>Email Id: </label>
												<a href="mailto:'.$row['EMAIL'].'" style="color:inherit">
												'.$row['EMAIL'].'
												</a>
											</div>
											<div class="col-lg-4 col-md-6">
												<label>Contact Number: </label>
												'.$row['PHONE_NUMBER'].'
											</div>
											<div class="col-lg-4 col-md-6">
												<label>Gender: </label>
												'.$row['SEX'].'
											</div>
											<div class="col-lg-4 col-md-6">
												<label>Date of Birth: </label>
												'.$row_dob.'
											</div>
											<div class="col-lg-4 col-md-6">
												<label>Office Location: </label>
												'.$location.'
											</div>
										</div><!-- row -->



									</div><!-- panel-body -->
								</div><!-- panel-teal -->
						</div><!-- col-lg-12 -->
					</div><!-- row -->';

					echo '<div class="row">
									<div class="col-md-12">
										<div class="panel panel-default articles">
											<div class="panel-heading">
												Residential Address
												<span class="pull-right clickable panel-toggle panel-button-tab-left"><em class="fa fa-toggle-up"></em></span>
											</div>
											<div class="panel panel-teal">
												<div class="panel-body">
													<div class="row">
														<div class="col-lg-12 col-md-6">
															<label>Address: </label>
															'.$row['R_FLAT'].' - ';
															if(!($row['R_STREET'] == ' '))
															{
																echo $row['R_STREET'].', ';
															}
															if(!($row['R_LOCALITY'] == ' '))
															{
																echo $row['P_LOCALITY'].', ';
															}
															echo $row['R_CITY'].', '.$row['R_STATE'].'
														</div>
														<div class="col-lg-4 col-md-6">
															<label>Post Office: </label>
															'.$row['R_POST_OFFICE'].'
														</div>
														<div class="col-lg-4 col-md-6">
															<label>District: </label>
															'.$row['R_DISTRICT'].'
														</div>
														<div class="col-lg-4 col-md-6">
															<label>PIN: </label>
															'.$row['R_PIN'].'
														</div>
													</div>
													<div class="clear"></div>
												</div><!-- panel-body -->
											</div><!-- panel panel-teal -->
										</div><!-- panel -->
									</div>
								</div>';

					echo '<div class="row">
									<div class="col-md-12">
										<div class="panel panel-default articles">
											<div class="panel-heading">
												Permanent Address
												<span class="pull-right clickable panel-toggle panel-button-tab-left"><em class="fa fa-toggle-up"></em></span>
											</div>
											<div class="panel panel-teal">
												<div class="panel-body">
													<div class="row">
														<div class="col-lg-12 col-md-6">
															<label>Address: </label>
															'.$row['P_FLAT'].' - ';
															if(!($row['P_STREET'] == ' '))
															{
																echo $row['P_STREET'].', ';
															}
															if(!($row['P_LOCALITY'] == ' '))
															{
																echo $row['P_LOCALITY'].', ';
															}
															echo $row['P_CITY'].', '.$row['P_STATE'].'
														</div>
														<div class="col-lg-4 col-md-6">
															<label>Post Office: </label>
															'.$row['P_POST_OFFICE'].'
														</div>
														<div class="col-lg-4 col-md-6">
															<label>District: </label>
															'.$row['P_DISTRICT'].'
														</div>
														<div class="col-lg-4 col-md-6">
															<label>PIN: </label>
															'.$row['P_PIN'].'
														</div>
													</div>
													<div class="clear"></div>
												</div><!-- panel-body -->
											</div><!-- panel panel-teal -->
										</div><!-- panel -->
									</div>
								</div>';


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
	<script>


$('#profile').addClass("active");
	</script>

</body>
</html>
