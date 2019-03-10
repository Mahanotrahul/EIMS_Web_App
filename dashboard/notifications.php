<?php
ob_start();
session_start();
include("connect.php");
include("function.php");

function isChecked($mem_id)
{
	global $con;
	date_default_timezone_set("Asia/Kolkata");
	$date = date('d/m/Y');
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
		 $orig_password = $row['PASSWORD'];
		 $mem_id = $row['ID'];
		 date_default_timezone_set("Asia/Kolkata");
		 $date = date('d/m/Y');
		 $time = date("H:i:sa");

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
	<title>Notifications | Vasitars</title>

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
				<li class="active">Notifications</li>
			</ol>
		</div><!--/.row-->

		<br>


			<div class="row">
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<div class="panel panel-teal">

									<div class="panel-body">

												<div class="row">
													<div class="col-lg-3 col-md-3">
															<span style="font-size:16px; color:#9F6">Notifications</span>

													</div>
												</div><!-- row -->

												<div class="row">
                          <center>
                            <div class="col-lg-3"></div>
													<div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                            <?php

                              include("connect.php");
                              $notif_query = mysqli_query($con, "SELECT * FROM notif WHERE MEM_ID = '".$_SESSION['mem_id']."'");

                              if(mysqli_num_rows($notif_query) == 0)
                              {
                                echo 'You do not have any notifications now. Check back later.';
                              }
                              else
                              {

                                while($row = mysqli_fetch_assoc($notif_query))
                                {
																	$items[] = $row;
																}
																$items = array_reverse($items, true);

																foreach($items as $row)
																{
                                  echo '<div class="row">
                                          <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-left">';

                                          if($row['Viewed'] == '0')
                                          {
                                            echo '<div class="panel" style="color:#53195b; background-color:#8ac972">
                                                    <div class="panel-body">';
                                          }

                                            echo $row['Notification'];
																						echo '<p>
																										 '.$row['Date'].'  -  '.$row['Time'].'
																									  </p>';

                                            if($row['Viewed'] == '0')
                                            {
                                              echo '</div>
                                                  </div>';
                                            }


                                          echo '</div><!-- col -->
                                        </div><!-- row -->
                                        <br>';
                                  if($row['Viewed'] == '0')
                                  {
                                    $set_viewed_query = mysqli_query($con, "UPDATE notif SET Viewed = '1' WHERE MEM_ID = '".$_SESSION['mem_id']."' AND SL_No = '".$row['SL_No']."'");
                                  }
                                }


                              }

                            ?>
													</div>
                        </center>




												</div><!-- row -->



											</div><!-- panel body -->
										</div><!-- panel teal -->
									</div><!-- col -->
								</div><!-- row -->





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
return $(this).text() == <?php echo '"'.$_SESSION['employee_name'].'"'; ?>;
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
$('#notifications').addClass("active");
	</script>
</body>
</html>
