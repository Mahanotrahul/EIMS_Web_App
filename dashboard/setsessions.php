<?php 
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

 					$query3 = mysql_query("SELECT * FROM events_reg WHERE email= '".$_SESSION['email']."'");
					$row_2 = mysql_fetch_array($query3);
					if(!empty($row_2['email']))
					{
						$eventsr1 = explode(',', $row_2['events']);
						$eventsno = count($eventsr1);
						
						$_SESSION['eventsno'] = $eventsno ;
						echo $_SESSION['eventsno'];
				
					}
				}
				
 


?>