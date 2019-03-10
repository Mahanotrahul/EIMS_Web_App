<?php
@ob_start();
session_start();


?><!DOCTYPE HTML>

<html>
	<head>
		<title>Reset Password  | Alma Fiesta'19</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<!--[if lte IE 8]><script src="assets/js/ie/html5shiv.js"></script><![endif]-->
		<link rel="stylesheet" href="assets/css/main.css" />
		<!--[if lte IE 9]><link rel="stylesheet" href="assets/css/ie9.css" /><![endif]-->
		<!--[if lte IE 8]><link rel="stylesheet" href="assets/css/ie8.css" /><![endif]-->
		<link href="https://fonts.googleapis.com/css?family=Lobster" rel="stylesheet">
		<link href="https://fonts.googleapis.com/css?family=Passion+One" rel="stylesheet">
		<link rel="icon" href="almalogo.png?" type="image/x-icon"> 
	</head>
	<body class="landing">
	<?php 

$emailErr= $passwordErr="";
$email = $pass ="";
$x="";
   if($_SERVER["REQUEST_METHOD"]== "POST")
			
			{
			 if(empty($_POST["email"])){
			
			   $x=1;
			   }
			    else{
			 $email=$_POST["email"];
			  $_SESSION['email'] = $_POST['email'];
			 if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
			 $emailErr="Invalid Email address";
			  $_SESSION['email'] = '';
			 $x=1;} 
			 }
			 
			 /* Connection */
  
  if($x!=1){
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
				




		if(!empty($_POST['email']))
		{
			
			
					$query = mysqli_query($con, "SELECT * FROM online_reg where email='$_POST[email]' ") ;
					$count = mysqli_num_rows($query);

					if ($count == 1)
					{
							$row = mysqli_fetch_assoc($query) ;
							
							if(!empty($row['email']) )
							{ 
								$random_id_length = 10; 
								$rnd_id = uniqid(rand(),1); 
								$rnd_id = strip_tags(stripslashes($rnd_id)); 
								$rnd_id = str_replace(".","",$rnd_id); 
								$rnd_id = strrev(str_replace("/","",$rnd_id)); 
								$rnd_id = substr($rnd_id,0,$random_id_length); 
								$newpass = md5($rnd_id);

function getIp(){

        $ip = $_SERVER['REMOTE_ADDR'];   
        if($ip)
		{
            if (!empty($_SERVER['HTTP_CLIENT_IP'])) 
			{
                $ip = $_SERVER['HTTP_CLIENT_IP'];
            } 
			else if(!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
			{
                $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            }
            return $ip;
		}
       
        return false;
} 
$ip = getIp();
  
date_default_timezone_set('Asia/Kolkata');
$date = date('d/m/Y ');
$time = date('g:i:s a T ');

$query_new2 = mysqli_query($con, "UPDATE online_reg SET pass='$newpass' WHERE email= '".$_SESSION['email']."'");
				
		
	 
   if($query_new2){
	
date_default_timezone_set('Asia/Kolkata');


						$replysubject = "Password Reset - ALMA FIESTA 2019";
						$replyfrom = "From: publicrelations@almafiesta.com \r\n";
						$replymessage = "Greetings from Team Alma Fiesta 2019! \r\n Your password has been successfully updated, \r\n
New Password : $rnd_id   \r\n
Password change request through IP - $ip on $date at $time  \r\n
If you don't recognise this activity, Kindly mail to web19@almafiesta.com for further assistance. \r\n
Cheers! \r\n\r\n 
Regards \r\n
TEAM WEB DEVELOPMENT \r\n
Alma Fiesta 2019 \r\n
IIT Bhubaneswar \r\n";
													
						   
						   
						 mail($email, $replysubject, $replymessage, $replyfrom);	

 $_SESSION['resetpass']=true;
		echo "<script type='text/javascript'>alert('Your new password will be mailed to your REGISTERED E-MAIL address shortly')
window.location = 'login.php'; </script>";
		 }	
else 	{
echo "<script type='text/javascript'>alert('Please try again !')
window.location = 'resetpass.php'; </script>";		
}
}else {
	

	echo "<script type='text/javascript'>alert('E-mail address is not registered')
window.location = 'resetpass.php'; </script>";
 $_SESSION['email'] = '';
}
}

			}}}
?>
		<div id="page-wrapper">

			<!-- Header -->
				<header id="header">
					<h1 id="logo" ><a href="http://www.almafiesta.com/" target="_blank" style="font-family: 'Lobster', cursive;
								font-size: 1.5em; ">Alma Fiesta'19</a></h1>
							<nav id="nav">
						<ul>
							<li><a href="index.php">Home</a></li>
							<li>
								<a href="#">Events</a>
								<ul>
									<li><a href="music.html">Music</a></li>
									<li><a href="dance.html">Dance</a></li>
									<li><a href="drama.html">Dramatics</a></li>
									<li><a href="literary.html">Literary</a></li>
									<li><a href="finearts.html">Fine Arts</a></li>
									<li><a href="quiz.html">Quizzes</a></li>
                                    <li><a href="film.html">Films and Photography</a></li>
                                    <li><a href="http://2019.almafiesta.com/cyber-crusades">Cyber Crusades</a></li>
								</ul>
							</li>
							
							<li><a href="index.php" class="button warning">Register</a></li>
							<li><a href="login.php" class="button special">Log In</a></li>
						</ul>
					</nav>
				</header>

			<!-- Banner -->
				

			<!-- One -->
				

			<!-- Two -->
				<section id="fortyet" class="spotlight style2 right">
					<span class="image fit main"><img src="images/resetpass1.jpg" alt="" /></span>
					<div class="content">
						<header>
							<h2  style="
	font-family: 'Passion One', cursive;
	font-size : 3.5em; 
">Reset Password</h2>
							
						</header>
					
								
								<form method="post" action="<?php  echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
									<div class="row uniform 50%">
										
										<div class="12u$ 12u$(xsmall)">
											<input type="email" name="email" id="email" required  value="<?php if(isset($_SESSION['email'])){ echo $_SESSION['email'];}?>" placeholder="Registered Email" /> <?php 
		echo $emailErr;?> 
	   </span>
										</div>
										
										
										
										
										
									
										
										<div class="12u$">
											<ul class="actions">
												<li><input type="submit" value="Submit" class="special" /></li>
												
											</ul>
										</div>
							
									</div>
									
								</form>
							
						
						
						
					</div>
					
				</section>

			

		

					

				
			<!-- Footer -->
				<footer id="footer">
					<ul class="icons">
						
						<li><a href="https://www.facebook.com/almafiesta/" target="_blank" class="icon alt fa-facebook"><span class="label">Facebook</span></a></li>
						<li><a href="https://www.youtube.com/channel/UCVAHFAfxXx0ZaOyS5VczKiA" target="_blank" class="icon alt fa-youtube"><span class="label">You Tube</span></a></li>
						<li><a href="https://www.instagram.com/almafiesta.iitbbs/" target="_blank" class="icon alt fa-instagram"><span class="label">Instagram</span></a></li>
						
					</ul>
					<ul class="copyright">
						<li>&copy; <a href="http://www.almafiesta.com/" target="_blank" style="text-decoration: none;">ALMA FIESTA'19 </a>  | WEB DEVELOPMENT</li>
					</ul>
				</footer>
		</div>

		<!-- Scripts -->
			<script src="assets/js/jquery.min.js"></script>
			<script src="assets/js/jquery.scrolly.min.js"></script>
			<script src="assets/js/jquery.dropotron.min.js"></script>
			<script src="assets/js/jquery.scrollex.min.js"></script>
			<script src="assets/js/skel.min.js"></script>
			<script src="assets/js/util.js"></script>
			<!--[if lte IE 8]><script src="assets/js/ie/respond.min.js"></script><![endif]-->
			<script src="assets/js/main.js"></script>

	</body>
</html>