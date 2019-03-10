<?php
@ob_start();
session_start();
if(!isset($_SESSION['validuser']) || $_SESSION['validuser']==false){
header("location: ../login.php");
}
?>
<!DOCTYPE html>
<html>
<head>
 <link rel="icon" href="almalogo.png?" type="image/x-icon">
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Team Registration | Alma Fiesta'19</title>
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="css/font-awesome.min.css" rel="stylesheet">
	<link href="css/datepicker3.css" rel="stylesheet">
	<link href="css/styles.css" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Lobster" rel="stylesheet">
	<!--Custom Font-->
	<link href="https://fonts.googleapis.com/css?family=Montserrat:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>

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
					 			window.location = '../login.php';</script>";
				}
				else
				{
				}


				$str = "SELECT * FROM online_reg where email= '".$_SESSION['email']."'";

				$query = mysqli_query($con, $str);
				$query_team = mysqli_query($con, "SELECT *  FROM team_reg where email= '".$_SESSION['email']."'");
				$row_team = mysqli_num_rows($query_team);
				$team_no = $row_team;
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


				if (isset($_POST['fsubmit']))
				{

					$rows_1 =  $_SESSION['members'];
					$array_em = array();
					$f=false;

					$email = $_SESSION['email'];
					$event = $_SESSION['eventsel'];
					$payment = $_POST['payment'];
					$acco = $_POST['acco'];
					$leader_exists_query = mysqli_query($con, "SELECT * FROM team_leader_reg WHERE email= '".$_SESSION['email']."'");
					$result_leader = mysqli_fetch_assoc($leader_exists_query);
					if(mysqli_num_rows($leader_exists_query) != 0)
					{
						$exploded_array = explode(",", $result_leader['events']);

						if(in_array($event, $exploded_array))
						{
							echo  "<script type='text/javascript'>alert('Team is already registered for the event : $event. If you want any changes, please contact the Technical Co-ordinators.')</script>";
						}
						else
						{
							for($i=0;$i<$rows_1;$i=$i+1)
							{

								$mname[$i]=$memail[$i]=$mno[$i]= "";
								$str = "mname".$i;
								$str1 = "memail".$i;
								$strm = "mno".$i;
								$mname[$i] = $_POST[$str];
								$memail[$i] = $_POST[$str1];
								$mno[$i] = $_POST[$strm];
								$a='';

								$id = "select MAX(id) as max_id_team from team_reg";
								$result_id = mysqli_query($con, $id);
								$id1 = mysqli_fetch_assoc($result_id);
								$id2 = $id1['max_id_team'];
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
									$aid = 'AF19TM'.'000'.$id2;
								}

								else if($id2 <= 99)
								{
									$aid = 'AF19TM'.'00'.$id2 ;
								}

								else
								{
									$aid = 'AF19TM'.'0'.$id2 ;
								}


								$leader = $_SESSION['fname'].' '.$_SESSION['lname'];
								$email = $_SESSION['email'];
								$event = $_SESSION['eventsel'];
								$payment = $_POST['payment'];
								$acco = $_POST['acco'];

								mysqli_query($con, "INSERT INTO team_reg(aid,email,event,leader_name,member_name,member_mobno,member_email,payment,accommodation) VALUES('$aid','$email','$event','$leader','$mname[$i]','$mno[$i]','$memail[$i]','$payment','$acco')");
								$f=true;


							}
							$eventsr = $event;
							$eventall = $result_leader['events'].','.$eventsr ;
							$aid =	$result_leader['aid'];

							mysqli_query($con, "UPDATE team_leader_reg SET events='$eventall', acco='$acco' , payment='$payment' WHERE email= '".$_SESSION['email']."'");

							echo  "<script type='text/javascript'>alert('All the members have been registered successfully ! ')</script>";

							$replysubject = "Team Registration | ALMA FIESTA 2019 | IIT BHUBANESWAR";
							$replyfrom = "From: publicrelations@almafiesta.com \r\n";
							$replymessage .= "Congratulations !! \r\n\r\n";
							$replymessage .= "You have successfully registered your team for events : $eventsr and your unique ALMA ID is : $aid  \r\n\r\n";
							$replymessage .= "Kindly quote your ALMA ID during any future communications.   \r\n\r\n";
							$replymessage .= "You can check your members detail at http://register.almafiesta.com/dashboard/tregistered.php ,for any corrections kindly mail to web19@almafiesta.com   \r\n\r\n";
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
					}
					else
					{
							for($i=0;$i<$rows_1;$i=$i+1)
							{

								$mname[$i]=$memail[$i]=$mno[$i]= "";
								$str = "mname".$i;
								$str1 = "memail".$i;
								$strm = "mno".$i;
								$mname[$i] = $_POST[$str];
								$memail[$i] = $_POST[$str1];
								$mno[$i] = $_POST[$strm];
								$a='';

								$id = "select MAX(id) as max_id_team from team_reg";
								$id_l = "select MAX(id) as max_id_event from team_leader_reg";
								$result_id_l = mysqli_query($con, $id_l);
								$result_id = mysqli_query($con, $id);
								$id1 = mysqli_fetch_assoc($result_id);
								$id_l1 = mysqli_fetch_assoc($result_id_l);
								$id_l_2 = $id_l1['max_id_event'] ;
								$id2 = $id1['max_id_team'];
								if($id2==NULL)
								{
									$id2= $id2+1;
								}
								else
								{
									$id2 = $id2+1;
								}

								if($id_l_2==NULL)
								{
									$id_l_2 = $id_l_2 +1;
								}
								else
								{
									$id_l_2 = $id_l_2 +1;
								}

								if($id2 <= 9)
								{
									$aid = 'AF19TM'.'000'.$id2;
								}

								else if($id2 <= 99)
								{
									$aid = 'AF19TM'.'00'.$id2 ;
								}

								else
								{
									$aid = 'AF19TM'.'0'.$id2 ;
								}



								if($id_l_2 <= 9)
								{
									$aid_l = 'AF19TL'.'000'.$id_l_2;
								}
								if($id_l_2 <= 99)
								{
									$aid_l = 'AF19TL'.'00'.$id_l_2 ;
								}
								else
								{
									$aid_l = 'AF19TL'.'0'.$id_l_2 ;
								}



								$leader = $_SESSION['fname'].' '.$_SESSION['lname'];
								$email = $_SESSION['email'];
								$event = $_SESSION['eventsel'];
								$payment = $_POST['payment'];
								$acco = $_POST['acco'];

								mysqli_query($con, "INSERT INTO team_reg(aid,email,event,leader_name,member_name,member_mobno,member_email,payment,accommodation) VALUES('$aid','$email','$event','$leader','$mname[$i]','$mno[$i]','$memail[$i]','$payment','$acco')");
								$f=true;


							}
							mysqli_query($con, "INSERT INTO team_leader_reg(aid,name,email,acco,payment,events) VALUES ('$aid_l','$leader','$email','$acco','$payment','$event')");
							echo  "<script type='text/javascript'>alert('All the members have been registered successfully ! ')</script>";

							$replysubject = "Team Registration | ALMA FIESTA 2019 | IIT BHUBANESWAR";
							$replyfrom = "From: publicrelations@almafiesta.com \r\n";
							$replymessage .= "Congratulations !! \r\n\r\n";
							$replymessage .= "You have successfully registered your team for events : $event and your unique ALMA ID is : $aid_l  \r\n\r\n";
							$replymessage .= "Kindly quote your ALMA ID during any future communications.   \r\n\r\n";
							$replymessage .= "You can check your members detail at http://register.almafiesta.com/dashboard/tregistered.php ,for any corrections kindly mail to web19@almafiesta.com   \r\n\r\n";
							$replymessage .= "You can contact Rahul - 9583962928 for any account/technical related queries. \r\n\r\n";
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




					if($f==true)
					{

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
				<a class="navbar-brand" href="http://www.almafiesta.com/"  target="_blank"><span style="font-family :'Lobster', cursive; text-transform:  capitalize;font-size : 0.85em; color:#fff;">Alma Fiesta'19</span></a>
				<ul class="nav navbar-top-links navbar-right">

                <li class="dropdown"><a class="dropdown-toggle count-info" data-toggle="dropdown" href="#">
						<em class="fa fa-bell"></em><span class="label label-info">2</span>
					</a>
						<ul class="dropdown-menu dropdown-alerts">
							<li><a href="#">
								<div><em class="fa fa-bullhorn"></em>Buy the Official Merchandise of Alma Fiesta in just Rs. 299/- only. To buy <a href="https://www.thecollegefever.com/events/alma-fiesta-iit-bhubaneswar"> Click Here</a>  !!
									<span class="pull-right text-muted small">11/01/18</span></div>
							</a></li>
							<li><a href="https://thecollegefever.com/events/alma-fiesta-iit-bhubaneswar" target="_blank">
								<div><em class="fa fa-bullhorn"></em> Payment Portal Live. Click here to pay.

							</a></li>

						</ul>
					</li>

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
			<li class="active"><a href="teamreg.php"><em class="fa fa-users">&nbsp;</em> Team Registration</a></li>
			<li ><a href="tregistered.php"><em class="fa fa-file-text-o">&nbsp;</em>Registered Members</a></li>
			<li class="parent "><a data-toggle="collapse" href="#sub-item-1">
				<em class="fa fa-navicon">&nbsp;</em> Events<span data-toggle="collapse" href="#sub-item-1" class="icon pull-right"><em class="fa fa-plus"></em></span>
				</a>
				<ul class="children collapse"  id="sub-item-1">
					<li><a class="" href="../music.html" style="font-size:0.95em;"
					target="_blank">
						<span class="fa fa-arrow-right">&nbsp;</span> Music
					</a></li>
					<li><a class="" href="../dance.html" style="font-size:0.95em;"  target="_blank">
						<span class="fa fa-arrow-right">&nbsp;</span> Dance
					</a></li>
					<li><a class="" href="../drama.html" style="font-size:0.95em;"  target="_blank">
						<span class="fa fa-arrow-right">&nbsp;</span> Dramatics
					</a></li>
					<li><a class="" href="../literary.html" style="font-size:0.95em;"  target="_blank">
						<span class="fa fa-arrow-right">&nbsp;</span> Literary
					</a></li>
					<li><a class=""href="../finearts.html" style="font-size:0.95em;"  target="_blank">
						<span class="fa fa-arrow-right">&nbsp;</span> Fine Arts
					</a></li>
					<li><a class=""href="../quiz.html" style="font-size:0.95em;"  target="_blank">
						<span class="fa fa-arrow-right">&nbsp;</span> Quizzes
					</a></li>
					<li><a class="" href="../film.html" style="font-size:0.95em;"  target="_blank">
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
				<li class="active">Team Registration</li>
			</ol>
		</div><!--/.row-->

		<br>

		<div class="row">
		<div class="col-lg-12">
				<div class="panel panel-blue">

					<div class="panel-body">
						<p style="color:#fff;">Only the Team leader should register the team (EXCLUDING him/her) </p>


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
					  echo '<p  style="color:#fff;">Total members registered : <span id = "teamno_2">'.(mysqli_num_rows($query) + 1).' (including Team Leader)';
				 }
					  else{
						  echo '<p  style="color:#fff;"> You have not registered any members till now <br>';
					  }?>

                </p>

					</div>

				</div>
			</div>
				<div class="col-lg-12">
				<form role="form"  method="post"  action="<?php  echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
				<div class="panel panel-teal">

					<div class="panel-body">
					<p id="teaml"> Team leader : <b ><?php echo $_SESSION["fname"];echo '&nbsp'; echo $_SESSION["lname"]; ?> </b>  </p>
					<div class="row"><div id ="headh" class="col-md-6">

						<div class="form-group">


									<div class="form-group">

										<select class="form-control"  name="eventsel"  required>
										<option value=""  disabled="disabled" selected="selected" >--Select Event--</option>
											<option value="EUPHONY">EUPHONY</option>
											<option value="UPBEAT">UPBEAT</option>
											<option value="DUETTO">DUETTO</option>
											<option value="TOPSY TURVY" >TOPSY TURVY</option>
											<option value="RAB NE BANA DI JODI" >RAB NE BANA DI JODI</option>
											<option value="N CIRCLED" >N CIRCLED</option>
											<option value="SPOT-LIGHT" >SPOT-LIGHT</option>
                      <option value="RETRO QUIZ" >RETRO QUIZ</option>
                      <option value="SHORT FILM MAKING COMPETITION" >SHORT FILM MAKING COMPETITION</option>
                      <option value="DOCUMENTARY MAKING" >DOCUMENTARY MAKING</option>


										</select>

									</div>

								</div>








				</div>
				<div class="col-md-6" id="teamno">
							<div class="form-group">

									<input class="form-control"required  name="numberp" type="number" id="numberp" placeholder="Enter the number of team members (excluding Team Leader)">
								</div>
									<br>
								<div style="text-align: right;">
	<button type="submit" id="isubmit" class="btn btn-primary">Enter</button>
									<button  id="rsubmit" type="reset" class="btn btn-default">Reset </button>
</div>
							</div>

							</div>
								</form>
								<form role="form"  method="post"  action="<?php  echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">

				<table style="color:#000;"  id="mytable" class=" table table-striped primary  table-hover" cellspacing="0" width="100%">

      <thead id="thead">
        <tr>
          <th style="color:#000;">SI No</th>
          <th style="color:#000;">Member Name</th>
		   <th style="color:#000;">Contact number</th>
          <th style="color:#000;">E-mail address</th>
        </tr>
      </thead>
<?php

	  if(isset($_POST['numberp']))
	  {
		   $rows=$_POST['numberp'];
		   $_SESSION['members']= $_POST['numberp'];
		   $_SESSION['eventsel']= $_POST['eventsel'];

		  echo '<script>
				$(document).ready(function(){

					$("#headh").hide();
					$("#teamno").hide();

				});
			  </script>';


			for($i = 0;$i < $rows;$i=$i+1)
			{
					$str = "mname".$i;
					$str1 = "memail".$i;
					$strm = "mno".$i;
					echo '<input type="hidden" name="count" value='.$rows.'>';
					echo
					'<tr>
					 <td  > '.($i+1).'</td>
					<td><input required type="text" class="form-control" name="'.$str.'" placeholder="Enter name"></td>
					<td><input required type="text" class="form-control" type="number" name="'.$strm.'" placeholder="Enter mobile number"></td>
					<td><input required type="email" class="form-control" name="'.$str1.'" placeholder="Enter email address"></td>



			  		</tr>';

			}
			echo '<script>
					$(document).ready(function(){

						$("#fsubmit").show();
						$("#teaml").show();
						$("#payment").show();
						$("#macco").show();
						$("#thead").show();
					});
				  </script>';

			  }

	  else
	  {
		  echo '<script>
			$(document).ready(function(){
				$("#thead").hide();
				$("#fsubmit").hide();
				$("#payment").hide();
				$("#teaml").hide();
				$("#macco").hide();
			});
		  </script>';
	  }

?>
	   </table>
	   <div class="col-md-6" >


										<div class="form-group" id="macco">

										<select required name="acco"class="form-control">
										<option value=""  disabled="disabled" selected="selected" >--Require Accomodation?--</option>
											<option >Yes</option>
											<option>No</option>

										</select>
									</div>

									</div>
	   <div class="col-md-6" >
	   <div class="form-group" id="payment">

										<select required  name="payment" class="form-control">
										<option value=""  disabled="disabled" selected="selected" >--Payment--</option>
											<option value= "TICKET-T-I" >TICKET-I [Single Team Event.]</option>
											<option value= "TICKET-T-II" >TICKET-II [Multiple Team Event.]</option>
											<option value= "offline" >Offline</option>

										</select>
									</div>
									</div>
</div>
	   <div style="text-align: center; padding-bottom: 5px;">
	<input  type="submit" name="fsubmit" id="fsubmit" class="btn btn-primary" value="Submit"/>

</div>
				</div>
				</div>
					<div class="col-lg-6">
				<div class="panel panel-red">

					<div class="panel-body">
						<p style="color:#fff;">
For participation in single team event, the Participation fees are <b>Rs. 300 per head.</b><br>
For participation in multiple team events, the Participation fees are <b>Rs. 500 per head.</b><br>

If total members including Team Leader is greater than 16, Participation fees is <b> for 17 members only </b><br>
For Offline Mode of Payment, additional Rs. 500 will be charged per team.<br>

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





				</form>

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
