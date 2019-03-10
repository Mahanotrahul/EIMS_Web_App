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
	$query = mysqli_query($con, "SELECT * FROM member where EMAIL = '".$_SESSION['LOGIN_EMAIL']."'");
	$row = mysqli_fetch_assoc($query);

	if(!empty($row['EMAIL']))
	{

		 $_SESSION['fname']= $row['FNAME'];
		 $_SESSION['mobno']= $row['PHONE_NUMBER'];
		 $_SESSION['lname']= $row['LNAME'];
		 $_SESSION['mem_id'] = $row['ID'];
		 //$_SESSION['college']= $row['college']  ;
		 $newadmin_query = mysqli_query($con, "SELECT * FROM login WHERE MEM_ID = '".$row['ID']."'");
		 if(mysqli_num_rows($newadmin_query) == 1)
		 {
			 $_SESSION['NewAdmin_notif'] = 1;
		 }
		 else
		 {
			 $_SESSION['NewAdmin_notif'] = 0;
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
	<title>Employee Dashboard | Vasitars</title>

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
				<li><a href="index">
					<em class="fa fa-home"></em>
			  </a></li>
				<li class="active">Dashboard</li>
			</ol>
		</div><!--/.row-->

		<br>

		<div class="panel panel-container">
			<div class="row">
				<div class="col-xs-6 col-md-3 col-lg-3 no-padding">
					<div class="panel panel-teal panel-widget border-right">
						<div class="row no-padding"><em class="fa fa-2x fa-xl fa-user color-blue"></em>
						<div class="large"></div><br>
							<div class="text-muted"><?php echo $_SESSION["fname"] ;echo '&nbsp'; echo $_SESSION["lname"]; ?> </div>

						</div>
					</div>
				</div>
				<div class="col-xs-6 col-md-3 col-lg-3 no-padding">
					<div class="panel panel-blue panel-widget border-right">
						<div class="row no-padding"><em class="fa fa-2x fa-xl fa-mobile color-orange"></em>
							<div class="large"></div><br>
							<div class="text-muted" style="letter-spacing: 2px;"><?php echo $_SESSION["mobno"] ?> </div>
						</div>
					</div>
				</div>




			</div><!--/.row-->
		</div><!-- panel-container -->


		<div class="modal fade bs-example-modal-lg-2" id="new_admin_notif" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="modal-dialog modal-lg-2">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close close_modal" data-dismiss="modal"><span aria-hidden="true">×</span>
						</button>
						<h4 class="modal-title" id="myModalLabel-2" style="color:#f00;">Notification</h4>
					</div>
					<div class="modal-body">
						<h4>You now have Administrator rights.<br> Check out the features for Administrator now.</h4>
						<br>
					</div>
					<div class="modal-footer">
						<button class="btn btn-dark close_modal" data-dismiss="modal" style="margin-top:10px;">Close</button>
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-12">
					<p class="back-link">&copy Vasitars <?php echo date("Y"); ?></p>
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

$('#dashboard').addClass("active");
if(<?php if($_SESSION['NewAdmin_notif'] == 1){echo 1;} else{ echo 0;} ?>)
{
	$('#new_admin_notif').modal();
}
	</script>

</body>
</html>
