<?php 
session_start();
$host="166.62.8.49";
$user="mainreg18";
$password="Alma@2k18reg";

define('DB_NAME',"mainreg18");

$con= mysql_connect($host,$user,$password);
$db= mysql_select_db(DB_NAME,$con);

if($con  ){
	
}
else{
echo "Not Connected";
}

$query_team = mysql_query("SELECT *  FROM team_reg where email= '".$_SESSION['email']."'");

$row_team = mysql_num_rows($query_team);

$team_no = $row_team;
 echo $team_no;
 


?>