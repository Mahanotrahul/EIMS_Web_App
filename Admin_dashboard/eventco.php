<?php
@ob_start();
session_start();
if(!isset($_SESSION['validuser']) || $_SESSION['validuser']==false){
header("location: ../login.php");

}
if($_SESSION['resetpass'] == true){
	$_SESSION['resetpass'] = false;
	$_SESSION['changepass'] = true ;
	header("location: changepass.php");
	
	
}

?><!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	 <link rel="icon" href="almalogo.png?" type="image/x-icon">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Event Co-ordinators | Alma Fiesta'19</title>
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="css/font-awesome.min.css" rel="stylesheet">
	<link href="css/datepicker3.css" rel="stylesheet">
	<link href="css/styles.css" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Lobster" rel="stylesheet">
	<!--Custom Font-->
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
							  $("#eventsno_1").html(data);
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
 aid();

 });
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
?>
	<nav class="navbar navbar-custom navbar-fixed-top" role="navigation">
		<div class="container-fluid">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#sidebar-collapse"><span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span></button>
				<a class="navbar-brand" href="http://www.almafiesta.com/" target="_blank"><span style="font-family :'Lobster', cursive; text-transform:  capitalize; font-size : 0.85em;color:#fff;">Alma Fiesta'19</span></a>
				<ul class="nav navbar-top-links navbar-right">
					<li class="dropdown"><a href="logout.php" class="dropdown-toggle count-info"  >
						<em class="fa fa-power-off"></em></a>
					
						
					<!--</li>
					<li class="dropdown"><a style="cursor:default;" class="dropdown-toggle count-info" data-toggle="dropdown" href="#">
						<em class="fa fa-tags"></em><span id = "eventsno" class="label label-danger"></span>
					</a>
						
					</li>-->
					<!--<li class="dropdown"><a class="dropdown-toggle count-info" data-toggle="dropdown" href="#">
						<em class="fa fa-bell"></em><span class="label label-info">2</span>
					</a>
						<ul class="dropdown-menu dropdown-alerts">
							<li><a href="#">
								<div><em class="fa fa-bullhorn"></em> Last date for complete registration has been EXTENDED !!
									<span class="pull-right text-muted small">12/01/18</span></div>
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
				<div class="profile-usertitle-status"><span  class="indicator label-success" ></span> <span id ="aidno"></span></div>
			
				
				
			</div>
			<div class="clear"></div>
		</div>
		<div class="divider"></div>
		
		<ul class="nav menu">
			<li><a href="index.php"><em class="fa fa-dashboard">&nbsp;</em> Dashboard</a></li>
			<li><a href="registration.php"><em class="fa fa-tags">&nbsp;</em> Events Registration</a></li>
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
			
			<li  class="active"><a href="eventco.php"><em class="fa fa-id-badge">&nbsp;</em> Event Co-ordinators</a></li>
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
				<li class="active">Event Co-ordinators</li>
			</ol>
		</div><!--/.row-->
		
		<br>
	
		<div class="row">
				<div class="col-md-6">
				<div class="panel panel-default articles">
					<div class="panel-heading">
						Music Events
					
						<span class="pull-right clickable panel-toggle panel-button-tab-left"><em class="fa fa-toggle-up"></em></span></div>
					<div class="panel-body articles-container">
						<div class="article border-bottom">
							<div class="col-xs-12">
								<div class="row">
									
									<div class="col-xs-10 col-md-10">
										<h4><a style="cursor: text;   text-decoration: none;">EUPHONY</a></h4>
										<p>Aditya Gautam : +91-8309295413    <br>   
        Manish : +91-9401140388</p>
									</div>
								</div>
							</div>
							<div class="clear"></div>
						</div><!--End .article-->
						
						<div class="article border-bottom">
							<div class="col-xs-12">
								<div class="row">
									<div class="col-xs-10 col-md-10">
										<h4><a style="cursor: text;   text-decoration: none;">UPBEAT</a></h4>
										<p>Manthan : +91-8280549114    <br>   
       Manish : +91-9401140388 </p>
									</div>
								</div>
							</div>
							<div class="clear"></div>
						</div><!--End .article-->
						
						<div class="article border-bottom">
							<div class="col-xs-12">
								<div class="row">
									<div class="col-xs-10 col-md-10">
										<h4><a style="cursor: text;   text-decoration: none;">TRACK THE TRACK</a></h4>
										<p>Manthan : +91-8280549114    <br>   
      Manish : +91-9401140388 </p>
									</div>
								</div>
							</div>
							<div class="clear"></div>
						</div><!--End .article-->
						<div class="article border-bottom">
							<div class="col-xs-12">
								<div class="row">
									<div class="col-xs-10 col-md-10">
										<h4><a style="cursor: text;   text-decoration: none;">DUETTO</a></h4>
										<p>Manthan : +91-8280549114     <br>   
    Manish : +91-9401140388 </p>
									</div>
								</div>
							</div>
							<div class="clear"></div>
						</div><!--End .article-->
						<div class="article">
							<div class="col-xs-12">
								<div class="row">
									<div class="col-xs-10 col-md-10">
										<h4><a style="cursor: text;   text-decoration: none;">UNPLUGGED</a></h4>
										<p>Manthan : +91-8280549114     <br>   
     Manish : +91-9401140388</p>
										
									</div>
								</div>
							</div>
							<div class="clear"></div>
						</div><!--End .article-->
					</div>
				</div>
				<!--End .articles-->
				
					<div class="panel panel-default articles">
					<div class="panel-heading">
						Literary Events
					
						<span class="pull-right clickable panel-toggle panel-button-tab-left"><em class="fa fa-toggle-up"></em></span></div>
					<div class="panel-body articles-container">
						<div class="article border-bottom">
							<div class="col-xs-12">
								<div class="row">
									<div class="col-xs-10 col-md-10">
										<h4><a style="cursor: text;   text-decoration: none;">SEEDHA SAMVAD </a></h4>
										<p>Abhishek Mishra: +91-9415708406  <br>   
    Ajay Jatoliya : +91-7597271871 </p>
									</div>
								</div>
							</div>
							<div class="clear"></div>
						</div><!--End .article-->
						
                        <div class="article border-bottom">
							<div class="col-xs-12">
								<div class="row">
									<div class="col-xs-10 col-md-10">
										<h4><a style="cursor: text;   text-decoration: none;">Poetry Slam</a></h4>
										<p>Abhishek Mishra: +91-9415708406  <br>   
     Ajay Jatoliya : +91-7597271871 </p>
									</div>
								</div>
							</div>
							<div class="clear"></div>
						</div><!--End .article-->
                        
						<div class="article border-bottom">
							<div class="col-xs-12">
								<div class="row">
									<div class="col-xs-10 col-md-10">
										<h4><a style="cursor: text;   text-decoration: none;">DRISHTIKON</a></h4>
										<p>Abhishek Mishra: +91-9415708406  <br>   
     Ajay Jatoliya : +91-7597271871 </p>
									</div>
								</div>
							</div>
							<div class="clear"></div>
						</div><!--End .article-->
						<div class="article">
							<div class="col-xs-12">
								<div class="row">
									<div class="col-xs-10 col-md-10">
										<h4><a style="cursor: text;   text-decoration: none;">IIT BBSR MUN</a></h4>
										<p>Chirag : +91-832962756   <br>   
     Chinmay : +91-9969539650</p>
									</div>
								</div>
							</div>
							<div class="clear"></div>
						</div><!--End .article-->
					</div>
				</div>
				
			<div class="panel panel-default articles">
					<div class="panel-heading">
						Fine Art Events
					
						<span class="pull-right clickable panel-toggle panel-button-tab-left"><em class="fa fa-toggle-up"></em></span></div>
					<div class="panel-body articles-container">
						<div class="article border-bottom">
							<div class="col-xs-12">
								<div class="row">
									
									<div class="col-xs-10 col-md-10">
										<h4><a style="cursor: text;   text-decoration: none;">SHAEDZ</a></h4>
										<p>Avani : +91-9039407035  <br>
Soumyajit : +91-8584927909 </p>
									</div>
								</div>
							</div>
							<div class="clear"></div>
						</div><!--End .article-->
						
						
						
						<div class="article ">
							<div class="col-xs-12">
								<div class="row">
									<div class="col-xs-10 col-md-10">
										<h4><a style="cursor: text;   text-decoration: none;">Face Painting</a></h4>
										<p>Avani : +91-9039407035  <br>
Soumyajit:+91-8584927909 </p>
									</div>
								</div>
							</div>
							<div class="clear"></div>
						</div><!--End .article-->
						
						
						
						
						
					
						
					
					
					</div>
				</div>
			</div>
			<!--/.col-->
			<div class="col-md-6">
				<div class="panel panel-default articles">
					<div class="panel-heading">
						Dance Events
					
						<span class="pull-right clickable panel-toggle panel-button-tab-left"><em class="fa fa-toggle-up"></em></span></div>
					<div class="panel-body articles-container">
						<div class="article border-bottom">
							<div class="col-xs-12">
								<div class="row">
									
									<div class="col-xs-10 col-md-10">
										<h4><a style="cursor: text;   text-decoration: none;">RIP-OUT</a></h4>
										<p>Ayush : +91-7376355778  <br>   
        Kaushal : +91-7064419297 </p>
									</div>
								</div>
							</div>
							<div class="clear"></div>
						</div><!--End .article-->
						
						<div class="article border-bottom">
							<div class="col-xs-12">
								<div class="row">
									<div class="col-xs-10 col-md-10">
										<h4><a style="cursor: text;   text-decoration: none;">TOPSY TURVY</a></h4>
										<p>Ayush : +91-7376355778  <br>   
        Kaushal : +91-7064419297 </p>
									</div>
								</div>
							</div>
							<div class="clear"></div>
						</div><!--End .article-->
						
						<div class="article">
							<div class="col-xs-12">
								<div class="row">
									<div class="col-xs-10 col-md-10">
										<h4><a style="cursor: text;   text-decoration: none;">RAB NE BANA DI JODI</a></h4>
										<p>Kaushal : +91-7064419297  <br>   
       Ayush : +91-7376355778 </p>
									</div>
								</div>
							</div>
							<div class="clear"></div>
						</div><!--End .article-->
					</div>
					
				</div>
				<!--End .articles-->
				<div class="panel panel-default articles">
					<div class="panel-heading">
						Dramatics Events
					
						<span class="pull-right clickable panel-toggle panel-button-tab-left"><em class="fa fa-toggle-up"></em></span></div>
					<div class="panel-body articles-container">
						<div class="article border-bottom">
							<div class="col-xs-12">
								<div class="row">
									
									<div class="col-xs-10 col-md-10">
										<h4><a style="cursor: text;   text-decoration: none;">FACE OFF</a></h4>
										<p>Shipra : +91-7300599701   <br>   
       Ashish : +91-7064419140</p>
									</div>
								</div>
							</div>
							<div class="clear"></div>
						</div><!--End .article-->
						
						<div class="article border-bottom">
							<div class="col-xs-12">
								<div class="row">
									<div class="col-xs-10 col-md-10">
										<h4><a style="cursor: text;   text-decoration: none;">N CIRCLED</a></h4>
										<p>Shipra : +91-7300599701  <br>   
       Ashish : +91-7064419140 </p>
									</div>
								</div>
							</div>
							<div class="clear"></div>
						</div><!--End .article-->
						
						<div class="article">
							<div class="col-xs-12">
								<div class="row">
									<div class="col-xs-10 col-md-10">
										<h4><a style="cursor: text;   text-decoration: none;">SPOT-LIGHT</a></h4>
										<p>Shipra : +91-7300599701  <br>   
       Ashish : +91-7064419140 </p>
									</div>
								</div>
							</div>
							<div class="clear"></div>
						</div><!--End .article-->
						
						
					</div>
					
				</div>
				
					<div class="panel panel-default articles">
					<div class="panel-heading">
						Quiz Events
					
						<span class="pull-right clickable panel-toggle panel-button-tab-left"><em class="fa fa-toggle-up"></em></span></div>
					<div class="panel-body articles-container">
						<div class="article border-bottom">
							<div class="col-xs-12">
								<div class="row">
									
									<div class="col-xs-10 col-md-10">
										<h4><a style="cursor: text;   text-decoration: none;">Retro QUIZ</a></h4>
										<p>Abhishek Mishra: +91-9415708406  <br> 
                                        Harsh Mantri : 7021447626  </p>
									</div>
								</div>
							</div>
							<div class="clear"></div>
						</div><!--End .article-->
						
						
						
						
						
						
					</div>
					
				</div>
			<div class="panel panel-default articles">
					<div class="panel-heading">
						Films and Photography Events
					
						<span class="pull-right clickable panel-toggle panel-button-tab-left"><em class="fa fa-toggle-up"></em></span></div>
					<div class="panel-body articles-container">
						<div class="article border-bottom">
							<div class="col-xs-12">
								<div class="row">
									
									<div class="col-xs-10 col-md-10">
										<h4><a style="cursor: text;   text-decoration: none;">SHORT FILM MAKING COMPETITION</a></h4>
										<p>Aakash Garg : +91-7417417224   <br>   
    Sai Gowtam: +91-8712883921 </p>
									</div>
								</div>
							</div>
							<div class="clear"></div>
						</div><!--End .article-->
						
						<div class="article ">
							<div class="col-xs-12">
								<div class="row">
									<div class="col-xs-10 col-md-10">
										<h4><a style="cursor: text;   text-decoration: none;">PIC OF THE DAY</a></h4>
										<p>Aakash Garg : +91-7417417224   <br>   
    Sai Gowtam: +91-8712883921 </p>
									</div>
								</div>
							</div>
							<div class="clear"></div>
						</div><!--End .article-->
						<div class="article ">
							<div class="col-xs-12">
								<div class="row">
									
									<div class="col-xs-10 col-md-10">
										<h4><a style="cursor: text;   text-decoration: none;">DOCUMENTARY MAKING</a></h4>
										<p>Sai Gowtam: +91-8712883921 <br>   
   Aakash Garg : +91-7417417224  </p>
									</div>
								</div>
							</div>
							<div class="clear"></div>
						</div><!--End .article-->
					
						
						
						
					</div>
					
				</div>
			
			</div><!--/.col-->
			
			</div>
			
			
		
			
			
			
			
			
			
		
		<div class="col-sm-12">
				<p class="back-link">© ALMA FIESTA'19 | WEB DEVELOPMENT</p>
			</div>
		</div>
	
	
	<!--/.main-->
	
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