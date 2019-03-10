<?php
ob_start();
session_start();
if(!isset($_SESSION['validuser']) || $_SESSION['validuser']==false){
header("location: ../login.php");
}
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Events Registration | Alma Fiesta'19</title>
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="css/font-awesome.min.css" rel="stylesheet">
	<link href="css/datepicker3.css" rel="stylesheet">
	<link href="css/styles.css" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Lobster" rel="stylesheet">
	<!--Custom Font-->
	 <link rel="icon" href="almalogo.png?" type="image/x-icon">
	<link href="https://fonts.googleapis.com/css?family=Montserrat:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script>
	
 $(document).ready(function(){
 
                    function eventsno(){
                      $.ajax({
                        type:"post",
                        url:"setsessions.php",
                        data:"text",
                        success:function(data){
                             $("#eventsno").html(data);
                        }
                      });
                    }
 eventsno(); 
 function aid(){
                      $.ajax({
                        type:"post",
                        url:"sessaid.php",
                        data:"text",
                        success:function(data){
                             $("#aidno").html(data);
							  
                        }
                      });
                    }
 aid();});
	</script>
</head>
<body>
<?php 


			$host="localhost";
			$username="main_reg19";
			$password="Alma@main_reg19";
			$dbname = "main_reg19";
			$con=mysqli_connect($host,$username,$password,$dbname);
			
		 
			
			if(mysqli_connect_errno())
			{
				$db_connection_status =  "Error occured when connecting with database";
				echo  "<script type='text/javascript'>alert('Error occured when connecting with database');
							window.location = 'login.php';</script>";
			}
			else
			{
				
				$query = mysqli_query($con, "SELECT * FROM online_reg where email= '".$_SESSION['email']."'");
				$row = mysqli_fetch_assoc($query) ;
				
				if(!empty($row['email']))
				{
				
					$_SESSION['fname']= $row['fname']  ;	
					$_SESSION['mobno']= $row['mobno']  ;
					$_SESSION['lname']= $row['lname']  ;
					$_SESSION['college']= $row['college']  ;
					
				}
				else
				{
					echo "Failed";
				}
			}
			$email = $_SESSION['email'];



function isChecked($eventsp) 
{
	global $con;
	global $email;
	$query3 = "SELECT * FROM events_reg WHERE email = '$email'";
	$result3 = mysqli_query($con,$query3);
	$row_2 = mysqli_fetch_assoc($result3);
	if(mysqli_num_rows($result3) != 0)
	{	
		$eventsr1 = explode(',', $row_2['events']);
		$eventsno = count($eventsr1);
		$_SESSION['eventsno'] = $eventsno ;
		$eventsl =  array($eventsp);
		$result_a = array_intersect($eventsr1, $eventsl);
		
		if($result_a != null) 
		{
			return "checked  disabled='disabled' ";
		} 
		else 
		{
			return "";
		}
	}
	else
	{
		return "";
	}
   
}

			if($_SERVER["REQUEST_METHOD"]== "POST")
			{
				
				$query2 = mysqli_query($con, "SELECT * FROM events_reg WHERE email= '".$_SESSION['email']."'");
				$row_1 = mysqli_fetch_assoc($query2);
				if(!empty($row_1['email']))
				{
					$alr_event = $row_1['events'];
					$eventtt = implode(',', $_POST['event']);

					if($alr_event == $eventtt)
					{	
						echo "<script type='text/javascript'>alert('You are already registered for $alr_event')</script>";
					}
					else if ($_POST['event'] != null)
					{
						$eventsr = implode(',', $_POST['event']);
						$acco = $_POST['acco'];
						$payment = $_POST['payment'];
						$eventall = $row_1['events'].','.$eventsr ;
						$eventpre = $row_1['events'] ;
						$email = $_SESSION['email'];
						$aid_old = $row_1['aid'];
						
					
						$query_new1= mysqli_query($con, "UPDATE events_reg SET events='$eventall', acco='$acco' , payment='$payment' WHERE email= '".$_SESSION['email']."'");
						
						if($query_new1)
						{
							echo "<script type='text/javascript'>alert(' Previously Registered : $eventpre  Newly Registered : $eventsr ')</script>";
							if($_POST['payment'] !='offline')
							{
								echo "<script type='text/javascript'>alert(' Kindly do your payment in the TheCollegeFever payment portal !! ');
									  window.location = 'https://thecollegefever.com/events/alma-fiesta-iit-bhubaneswar';
									  </script>";
							}
							
							$replysubject = "Events Registration Update | ALMA FIESTA 2019 | IIT BHUBANESWAR";
							$replyfrom = "From: publicrelations@almafiesta.com \r\n";
							$replymessage .= "Congratulations !! \r\n\r\n";
							$replymessage .= "You have successfully registered for events:  $eventall and your unique ALMA ID is : $aid_old \r\n\r\n";
							$replymessage .= "Kindly quote your ALMA ID during any future communications.   \r\n\r\n";
							$replymessage .= "You can contact Rahul - 9583962928 for any account related queries. \r\n\r\n";
							$replymessage .= "For doubts regarding registration fee or the payment procedure, feel free to contact Manas : +91 8439300990 / Vipul : +91 8895975692  \r\n\r\n";
							$replymessage .= "You can now complete your payment now at TheCollegeFever Website. Click on this link - https://thecollegefever.com/events/alma-fiesta-iit-bhubaneswar ";
							$replymessage .= "We are eagerly waiting to be your hosts in our beautiful campus. See you on 11th of January 2019!\r\n\r\n";
							
							$replymessage .= "Cheers! \r\n\r\n";
							$replymessage .= "Regards \r\n\r\n";
							$replymessage .= "Team Alma Fiesta \r\n\r\n";
							$replymessage .= "IIT Bhubaneswar \r\n\r\n";
							$replymessage .= "Follow our Facebook page for further updates : https://www.facebook.com/almafiesta/ \r\n\r\n";
		
	   
	   
							mail($email, $replysubject, $replymessage, $replyfrom);	
						
						
						}
					}
					
					else
					{
							echo "<script type='text/javascript'>alert('Please select some new event to UPDATE')
						</script>";
					}
				}
				
				
				else
				{
					$events = implode(',', $_POST['event']);
					if($events == NULL)
					{
						echo "<script type='text/javascript'>alert(' Please select at least one event.')
						</script>";
					}
					else
					{
						$name= $_SESSION['fname'].' '.$_SESSION['lname'];
					
						$id = "select MAX(id) as max_id from events_reg";
						$result_id = mysqli_query($con, $id);
						$id1 = mysqli_fetch_assoc($result_id);
						$id2 = $id1['max_id'];
						echo $id2;
						if($id2==NULL)
						{
						 $id2= $id2+1;
						}
						else
						{
						 $id2 = $id2+1;
						}
			
			
						if($id2 <= 9)
						{
							$aid = 'AF19EO'.'000'.$id2 ;
						}
						else if($id2 <= 99)
						{
							$aid = 'AF19EO'.'00'.$id2 ;
						}
						else
						{
							$aid = 'AF19EO'.'0'.$id2 ; 
						}
			
						$acco = $_POST['acco'];
						$payment = $_POST['payment'];
						$email = $_SESSION['email'];
					
						$query1= "INSERT INTO events_reg(aid,name,email,acco,payment,events) VALUES ('$aid','$name','$email','$acco','$payment','$events')";		
								$result = mysqli_query($con, $query1);
					
						if($result)
						{
							echo "<script type='text/javascript'>alert(' You have successfully registered for $events ')</script>";	
					
							$replysubject = "Events Registration | ALMA FIESTA 2019 | IIT BHUBANESWAR";
							$replyfrom = "From: publicrelations@almafiesta.com \r\n";
							$replymessage .= "Congratulations !! \r\n\r\n";
							$replymessage .= "You have successfully registered for events:  $events and your unique ALMA ID is : $aid  \r\n\r\n";
							$replymessage .= "Kindly quote your ALMA ID during any future communications.   \r\n\r\n";
							$replymessage .= "You can contact Rahul - 9583962928 for any account related queries. \r\n\r\n";
							$replymessage .= "For doubts regarding registration fee or the payment procedure, feel free to contact Manas : +91 8439300990 / Vipul : +91 8895975692  \r\n\r\n";
							$replymessage .= "You can now complete your payment now at TheCollegeFever Website. Click on this link - https://thecollegefever.com/events/alma-fiesta-iit-bhubaneswar ";
							$replymessage .= "We are eagerly waiting to be your hosts in our beautiful campus. See you on 11th of January 2019!\r\n\r\n";
							
							$replymessage .= "Cheers! \r\n\r\n";
							$replymessage .= "Regards \r\n\r\n";
							$replymessage .= "Team Alma Fiesta \r\n\r\n";
							$replymessage .= "IIT Bhubaneswar \r\n\r\n";
							$replymessage .= "Follow our Facebook page for further updates : https://www.facebook.com/almafiesta/ \r\n\r\n";
		
	   
	   
							mail($email, $replysubject, $replymessage, $replyfrom);				
					
					
							if($payment!='offline')
							{
								echo "<script type='text/javascript'>alert(' Kindly do your payment in the TheCollegeFever payment portal !! ');
									  window.location = 'https://thecollegefever.com/events/alma-fiesta-iit-bhubaneswar';
									  </script>";
							}
						}
						else
						{
							echo "<script type='text/javascript'>alert(' Please try again ')
										</script>";	
						}
					}
				}
		
					
					
				
 

				
			}	
			
?>
	<nav class="navbar navbar-custom navbar-fixed-top" role="navigation">
		<div class="container-fluid">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#sidebar-collapse"><span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span></button>
				<a class="navbar-brand" href="http://www.almafiesta.com/"  target="_blank"><span style="font-family :'Lobster', cursive;font-size : 0.85em; text-transform:  capitalize; color:#fff;">Alma Fiesta'19</span></a>
				<ul class="nav navbar-top-links navbar-right">
				<li class="dropdown"><a href="logout.php" class="dropdown-toggle count-info"  >
						<em class="fa fa-power-off"></em></a>
					
						
					</li>
					<!--<li class="dropdown"><a style="cursor:default;" class="dropdown-toggle count-info" data-toggle="dropdown" href="#">
						<em class="fa fa-tags"></em><span  id = "eventsno" class="label label-danger"></span>
					</a>
						
					</li>
					<li class="dropdown"><a class="dropdown-toggle count-info" data-toggle="dropdown" href="#">
						<em class="fa fa-bell"></em><span class="label label-info">2</span>
					</a>
						<ul class="dropdown-menu dropdown-alerts">
							<li><a href="#">
								<div><em class="fa fa-bullhorn"></em> Last date for complete registration has been EXTENDED !!
									<span class="pull-right text-muted small">11/01/18</span></div>
							</a></li>
							<li><a href="https://docs.google.com/forms/d/e/1FAIpQLSdILgFxK_74i0zvBgMQotkgkqt2-9nWGVECpfCm5NsEFm74qQ/viewform?usp=sf_link" target="_blank">
								<div><em class="fa fa-bullhorn"></em> Applications open for Core <br>Adjudicators for Parliamentary debate
									<span class="pull-right text-muted small">18/12/18</span></div>
							</a></li>
							
						</ul>
					</li>-->
				</ul>
			</div>
		</div><!-- /.container-fluid -->
	</nav>
	<div id="sidebar-collapse" class="col-sm-3 col-lg-2 sidebar">
		<div class="profile-sidebar">
			<div class="profile-userpic">
				<img src="user.png" class="img-responsive" alt="">
			</div>
			<div class="profile-usertitle">
				<div class="profile-usertitle-name"><?php echo $_SESSION["fname"];echo '&nbsp'; ?></div>
				<div class="profile-usertitle-status"><span class="indicator label-success"></span><span id ="aidno"></span></div>
			
				
			</div>
			<div class="clear"></div>
		</div>
		<div class="divider"></div>
		
		<ul class="nav menu">
			<li ><a href="index.php"><em class="fa fa-dashboard">&nbsp;</em> Dashboard</a></li>
			<li class="active"> <a href="registration.php"><em class="fa fa-tags">&nbsp;</em> Events Registration</a></li>
			<li><a href="teamreg.php"><em class="fa fa-users">&nbsp;</em> Team Registration</a></li>
		<li ><a href="tregistered.php"><em class="fa fa-file-text-o">&nbsp;</em>Registered Members</a></li>
			<li class="parent "><a data-toggle="collapse" href="#sub-item-1">
				<em class="fa fa-navicon">&nbsp;</em> Events<span data-toggle="collapse" href="#sub-item-1" class="icon pull-right"><em class="fa fa-plus"></em></span>
				</a>
				<ul class="children collapse" id="sub-item-1">
					<li><a class="" href="../music.html" target="_blank" style="font-size:0.95em;" >
						<span class="fa fa-arrow-right">&nbsp;</span> Music
					</a></li>
					<li><a class="" href="../dance.html" target="_blank" style="font-size:0.95em;" >
						<span class="fa fa-arrow-right">&nbsp;</span> Dance
					</a></li>
					<li><a class="" href="../drama.html" target="_blank" style="font-size:0.95em;" >
						<span class="fa fa-arrow-right">&nbsp;</span> Dramatics
					</a></li>
					<li><a class="" href="../literary.html" target="_blank" style="font-size:0.95em;" >
						<span class="fa fa-arrow-right">&nbsp;</span> Literary
					</a></li>
					<li><a class=""href="../finearts.html" target="_blank" style="font-size:0.95em;" >
						<span class="fa fa-arrow-right">&nbsp;</span> Fine Arts
					</a></li>
					<li><a class=""href="../quiz.html" target="_blank" style="font-size:0.95em;" >
						<span class="fa fa-arrow-right">&nbsp;</span> Quizzes
					</a></li>
					<li><a class="" href="../film.html" target="_blank" style="font-size:0.95em;" >
						<span class="fa fa-arrow-right">&nbsp;</span> Films and Photography
					</a></li>
				</ul>
			</li>
		
			<li><a href="eventco.php"><em class="fa fa-id-badge">&nbsp;</em> Event Co-ordinators</a></li>
				<li><a href="changepass.php"><em class="fa fa-key">&nbsp;</em> Change Password</a></li>
				<li><a href="contact.php"><em class="fa fa-phone">&nbsp;</em> Contact Us</a></li>
			<li><a href="logout.php"><em class="fa fa-power-off">&nbsp;</em> Logout</a></li>
		</ul>
	</div><!--/.sidebar-->
		
	<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
		<div class="row">
			<ol class="breadcrumb">
				<li><a href="index.php">
					<em class="fa fa-home"></em>
				</a></li>
				<li class="active">Events Registration</li>
			</ol>
		</div><!--/.row-->
		
		<br>
	
		<div class="row">
				<div class="col-lg-12">
				<form role="form" action="<?php  echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
				<div class="panel panel-teal">
					
					<div class="panel-body">
					<div class="col-md-6">
						
						<div class="form-group">
											<label>* Please select the individual events you want to participate in : </label>
											
										<div class="checkbox"  >
											<label>
												<input type="checkbox"  name="event[]" <?php echo isChecked('TRACK THE TRACK');?> value="TRACK THE TRACK">TRACK THE TRACK
											</label>
										</div>
										<div class="checkbox">
											<label>
												<input type="checkbox" name="event[]"<?php echo isChecked('UNPLUGGED');?> value="UNPLUGGED">UNPLUGGED
											</label>
										</div>
										<div class="checkbox">
											<label>
												<input type="checkbox" name="event[]"<?php echo isChecked('RIP OUT');?> value="RIP OUT">RIP OUT
											</label>
										</div>
										<div class="checkbox">
											<label>
												<input type="checkbox" name="event[]"<?php echo isChecked('FACE OFF');?> value="FACE OFF">FACE OFF
											</label>
										</div>
										
										<div class="checkbox">
											<label>
												<input type="checkbox" name="event[]"<?php echo isChecked('POETRY SLAM');?> value="POETRY SLAM">POETRY SLAM
											</label>
										</div>
										
										
									</div>
									<div class="form-group">
									 
										<select required name="acco" class="form-control">
										<option  value="" disabled="disabled" selected="selected" >--Require Accomodation?--</option>
											<option name="" value="yes">Yes</option>
											<option  name="" value="no">No</option>
											
										</select>
									</div>
								
								</div>
								
							<div class="col-md-6">
						
						<div class="form-group">
										
										
										<div class="checkbox">
											<label>
												<input type="checkbox" name="event[]"<?php echo isChecked('DRISHTIKON');?> value="DRISHTIKON">DRISHTIKON
											</label>
										</div>
										
										<div class="checkbox">
											<label>
												<input type="checkbox"  name="event[]" <?php echo isChecked('SEEDHA SAMVAD');?> value="SEEDHA SAMVAD">SEEDHA SAMVAD 
											</label>
										</div>
										<div class="checkbox">
											<label>
												<input type="checkbox"  name="event[]"<?php echo isChecked('SHAEDZ');?> value="SHAEDZ">SHAEDZ
											</label>
										</div>
										<div class="checkbox">
											<label>
												<input type="checkbox" name="event[]"<?php echo isChecked('PIC OF THE DAY');?> value="PIC OF THE DAY">PIC OF THE DAY
											</label>
										</div>
										<div class="checkbox">
											<label>
												<input type="checkbox" name="event[]"<?php echo isChecked('SALSA WORKSHOP');?> value="SALSA WORKSHOP">SALSA WORKSHOP
											</label>
										</div>
                                        <div class="checkbox">
											<label>
												<input type="checkbox" name="event[]"<?php echo isChecked('FACE PAINTING');?> value="FACE PAINTING">FACE PAINTING
											</label>
										</div>
										
									</div>
									
									<div class="form-group">
									<br>
										<select  required name="payment"class="form-control">
										<option  value="" disabled="disabled"   selected="selected" >--Payment--</option>
											<option value="TICKET-S - I"> TICKET-Single Solo [Rs.300/-]</option>
                                          	<option value="TICKET-S - II"> TICKET-Multiple Solo [Rs.500/-]</option>
                                          	<option value="TICKET-S-T - III"> TICKET-Solo + Team[Rs.200/-]</option>
											<option  value="offline">Offline</option>
											
										</select>
									</div>
</div>		
									
		<div style="text-align: right;">
	<button type="submit" class="btn btn-primary">Register</button>
									<button type="reset" class="btn btn-default">Reset </button>
</div>
					</div>
					
				</div>
				</div>
				<div class="col-lg-6">
				<div class="panel panel-red">
					
					<div class="panel-body"> 
						<p style="color:#fff;">
The Payment charges are <b>  Rs. 300 for participation in single solo event.</b> <br>
If registering for more than 1 event, Participation fee is <b> Rs. 500. </b><br>
If particpant wishes to participate in solo events as well as in team events, then he/she has to pay Rs. 200/- separately along with the team participation fees.<br><br>
For Offline Mode of Payment, <b>additional Rs. 100 </b>has to be paid by the participant. </p>
</p>
						
							
					</div>
					
				</div>
			</div>
            <div class="col-md-6">
				<div class="panel panel-default articles">
					<div class="panel-heading">
						Payments Link
					
						</div>
					<div class="panel-body articles-container">
						<div class="article ">
							<div class="col-xs-12">
								<div class="row">
									
									<div class="col-xs-10 col-md-10">
										You can now pay the participation fees from TheCollegeFever Website. To Pay <a href="https://www.thecollegefever.com/events/alma-fiesta-iit-bhubaneswar"> Click Here</a>.
									</div>
								</div>
							</div>
							<div class="clear"></div>
						</div><!--End .article-->
					
						
						
						
					</div>
				</div>
                </div>
                
               <div class="col-md-6">
				<div class="panel panel-default articles">
					<div class="panel-heading">
						Official Merchandise
					
						</div>
					<div class="panel-body articles-container">
						<div class="article ">
							<div class="col-xs-12">
								<div class="row">
									
									<div class="col-xs-10 col-md-10">
										You can now buy the Official Merchandise of Alma Fiesta in just Rs. 299/- only. To buy <a href="https://www.thecollegefever.com/events/alma-fiesta-iit-bhubaneswar"> Click Here</a>.
									</div>
								</div>
							</div>
							<div class="clear"></div>
						</div><!--End .article-->
					
						
						
						
					</div>
				</div>
                </div>
			
				</form>
			</div>
			
		<div class="col-sm-12">
				<p class="back-link">© ALMA FIESTA'19 | WEB DEVELOPMENT</p>
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
		
</body>
</html>