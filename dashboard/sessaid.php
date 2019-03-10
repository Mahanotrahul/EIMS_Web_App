<?php 
ob_start();
session_start();
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
					$query1 = mysqli_query($con, "SELECT * FROM team_leader_reg WHERE email= '".$_SESSION['email']."'");
					$row_1 = mysqli_fetch_assoc($query1);
					$query2 = mysqli_query($con, "SELECT * FROM events_reg WHERE email= '".$_SESSION['email']."'");
					$row_2 = mysqli_fetch_assoc($query2);
					if(!empty($row_1['email']) && !empty($row_2['email']))
					{
						$aid_1 = $row_1['aid'];
						$aid_2 = $row_2['aid'];
						$_SESSION['aid'] = $aid_1.' + '.$aid_2;
						echo $_SESSION['aid'];
					}
					else
					{
						$aid_1 = $row_1['aid'];
						$_SESSION['aid'] = $aid_1;
						echo $_SESSION['aid'];
						$aid_2 = $row_2['aid'];
						$_SESSION['aid'] = $aid_2;
						echo $_SESSION['aid'];
					}
						
					
					
					
					
					 
				}
 

ob_end_flush();
?>