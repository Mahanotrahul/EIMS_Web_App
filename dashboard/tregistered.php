<?php
@ob_start();
session_start();
if(!isset($_SESSION['validuser']) || $_SESSION['validuser']==false){
header("location: ../login.php");
}

?><!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Registered Members | Alma Fiesta'19</title>
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
 
                    function teamregno(){
                      $.ajax({
                        type:"post",
                        url:"teamsession.php",
                        data:"text",
                        success:function(data){
                             $("#teamregno").html(data);
							  $("#teamno_2").html(data);
                        }
                      });
                    }
 teamregno();
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
 aid(); });
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

					$query = mysqli_query($con, "SELECT * FROM online_reg where email= '".$_SESSION['email']."'") ;
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
						echo  "<script type='text/javascript'>alert('Sorry. Unable to process your request.');
					 			window.location = 'login.php';</script>";
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
					<!--<li class="dropdown"><a style="cursor:default;"  class="dropdown-toggle count-info" data-toggle="dropdown" href="#">
						<em class="fa fa-group"></em><span id= "teamregno" class="label label-danger"></span>
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
			<li > <a href="registration.php"><em class="fa fa-tags">&nbsp;</em> Events Registration</a></li>
			<li><a href="teamreg.php"><em class="fa fa-users">&nbsp;</em> Team Registration</a></li>
			<li class="active"><a href="tregistered.php"><em class="fa fa-file-text-o">&nbsp;</em>Registered Members</a></li>
			
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
		
	<div class="col-sm-12 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
		<div class="row">
			<ol class="breadcrumb">
				<li><a href="index.php">
					<em class="fa fa-home"></em>
				</a></li>
				<li class="active">Registered Members</li>
			</ol>
		</div><!--/.row-->
		
		<br>
	
		<div class="row">
				<div class="col-lg-12">
				
				<div class="panel panel-teal">
					
					<div class="panel-body">
                    <table  style="background:#1ebfae;" class=" table  primary  " cellspacing="0" width="100%" border="1px "  bordercolor="#E7EDEE" >
                    <caption>Leader Details</caption>
      <thead>
        <tr>
          <th style="text-align:center;  ">SI No</th>
		   <th style="text-align:center;  ">ALMA ID</th>
		    <th style="text-align:center;  ">EVENT</th>
          <th style="text-align:center;">Leader NAME</th>
          <th style="text-align:center;">Leader EMAIL</th>
		  
        </tr>
      </thead>
	 
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
				

				$query = mysqli_query($con, "SELECT * FROM team_leader_reg where email= '".$_SESSION['email']."'");
				
				 
				 
				 if(mysqli_num_rows($query)>0)
				 {
					  $x=1;
					  while($d=mysqli_fetch_assoc($query))
					  {
							echo '<tr style="text-align:center;">
							<td>'.$x.'</td>
							<td>'.$d['aid'].'</td>
							<td>'.$d['events'].'</td>
							
							<td>'.$d['name'].'</td>
							 <td>'.$d['email'].'</td>
							</tr>';
				   
				  			$x++;
				 
					   }
				 }
					  else{
						  echo ' You have not registered any members till now <br>';
					  }?>
	

    </table>
    <br>
					
						<table  style="background:#1ebfae;" class=" table  primary  " cellspacing="0" width="100%" border="1px "  bordercolor="#E7EDEE" >
 
      <thead>
        <tr>
          <th style="text-align:center;  ">SI No</th>
		   <th style="text-align:center;  ">ALMA ID</th>
		    <th style="text-align:center;  ">EVENT</th>
          <th style="text-align:center;">MEMBER NAME</th>
          <th style="text-align:center;">MEMBER EMAIL</th>
		  
        </tr>
      </thead>
	 
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
				

				$query = mysqli_query($con, "SELECT * FROM team_reg where email= '".$_SESSION['email']."'");
				
				 
				 
				 if(mysqli_num_rows($query)>0)
				 {
					  $x=1;
					  while($d=mysqli_fetch_assoc($query))
					  {
							echo '<tr style="text-align:center;">
							<td>'.$x.'</td>
							<td>'.$d['aid'].'</td>
							<td>'.$d['event'].'</td>
							
							<td>'.$d['member_name'].'</td>
							 <td>'.$d['member_email'].'</td>
							</tr>';
				   
				  			$x++;
				 
					   }
				 }
					  ?>
	

    </table>
					
								
								</div>
								
							
									
		
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