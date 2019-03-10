<?php 
ob_start();

session_start();
include("connect.php");
include("function.php");



	$fnErr = $mobnoErr  = $lnErr = $collegeErr = $emailErr  = $stateErr = $passErr= $passmatErr= $cityErr= $caErr= $fnErr = $lnErr = "";
	$fn= $ln = $mobno  = $college = $email = $state = $pass= $cpass=   $repass= $city= $ca= "";
	$x=""; 	
	
	if(isset($_POST['register']))
	{
			 if(empty($_POST["fname"]))
			 {
				 $fnErr=" First Name is Required";
				   $x=1;
			 }
			 
			 else
			 {
				 $fname=$_POST["fname"];
				  $_SESSION['fname'] = $_POST['fname'];
				 if(!preg_match("/^[a-zA-Z ]*$/",$fname))
				 {
						 $_SESSION['fname'] = '';
						 $fnErr=" Only letters and whitespaces allowed in Name";
						 $x=1;
			 	 }
			 }
			 
			 if(empty($_POST["lname"]))
			 {
				 $fnErr=" Last Name is Required";
				   $x=1;
			 }
			 else
			 {
				 $lname=$_POST["lname"];
				  $_SESSION['lname'] = $_POST['lname'];
				 if(!preg_match("/^[a-zA-Z ]*$/",$lname))
				 {
					 $lnErr=" Only letters and whitespaces allowed";
					  $_SESSION['lname'] = '';
					  $x=1;
			 	 }
			 }
			 
			 if(empty($_POST["mobno"]))
			 {
				 $mobnoErr=" Mobile Number is Required";
				 $x=1;
			 }
			 
			 else
			 {
				 $mobno=$_POST["mobno"];
				  $_SESSION['mobno'] = $_POST['mobno'];
				  if(!preg_match("/^\d{10}$/",$mobno))
				  {
					 $mobnoErr=" Mobile number is not valid";
					 $_SESSION['mobno'] = '';
					 $x=1;
				  }
			 }
			 
			 
			 
			 if(empty($_POST["college"]))
			 {
				 $collegeErr="College Name is Required";
				 $x=1;
			 }
			 
			 else
			 {
				 $college=$_POST["college"];
				  $_SESSION['college'] = $_POST['college'];
				 if(!preg_match("/^[a-zA-Z0-9-. \/,-]+$/",$college))
				 {
					 $collegeErr="Invalid College Name";
					  $_SESSION['college'] = '';
					 $x=1;
				 }
			 }
			 
			 
			 if(empty($_POST["email"]))
			 {
				 $emailErr="Email is Required";
				 $x=1;
			 }
				 
			 else
			 {
				 $email=$_POST["email"];
				 $_SESSION['email'] = $_POST['email'];
				 if(!filter_var($email,FILTER_VALIDATE_EMAIL))
				 {
					 $emailErr="Invalid email format" ;
					  $_SESSION['email'] = '';
					 $x=1;
			 	 }
			 }
			 
			 if(empty($_POST["state"]))
			 {
				 $stateErr="State is Required";
				 $x=1;
			 }
			 
			 else
			 {
				 $state=$_POST["state"];
				 $_SESSION['state'] = $_POST['state'];
				 if(!preg_match("/^[a-zA-Z ]*$/",$state))
				 {
					 $stateErr=" Only letters and whitespaces allowed";
					 $x=1;
					 $_SESSION['state'] = '';
				  }
			 }
	
			 if(empty($_POST["city"]))
			 {
				 $cityErr="City is Required";
				 $x=1;
			 }
			 
			 else
			 {
				 $city=$_POST["city"];
				  $_SESSION['city'] = $_POST['city'];
				 if(!preg_match("/^[a-zA-Z ]*$/",$city))
				 {
					 $cityErr=" Only letters and whitespaces allowed";
					 $x=1;
					  $_SESSION['city'] = '';
				 }
			 }
			 
	  		if(empty($_POST["ca"])){
			     $ca= '-';
				  $x=2;
				 }
		 
		 	else
		 	{
				 $ca=$_POST["ca"];
				 if(!preg_match("/^[0-9A-Za-z]+$/",$ca))
				 {
					 $caErr="Invalid CA ID";
					 $x=1;
				 } 
			 
			}
			
			if(empty($_POST["pass"])){
				 $passErr="Password is Required";
				 $x=1;
			}
				 
			else
			{
				 $pass=$_POST["pass"];
				 if(!preg_match("/^[0-9A-Za-z!@#$%?^&]+$/",$pass))
				 {
					 $passErr="Password is not valid";
					 $x=1;
				 } 
				 if(strlen($pass) < 6)
				 {
					  $passErr="Password should be at least 6 characters long";
					   $x=1;
				 }
			}
			 
			 if(empty($_POST["cpass"])){
				 $passmatErr="Re-enter the Password";
				 $x=1;
			 }
			 
			 else
			 {
				$cpass=$_POST["cpass"];
				 if(  $pass == $cpass) 
				 {
			
				 }
				
				else
				{
					$passmatErr="Passwords do not match";
					$x=1;
				}
			
			 }
			 
			 
	    	$sex = $_POST["sex"];
			
			if($x==1)	 
			{
				 echo  "<script type='text/javascript'>alert('Please check the details entered')
						window.location = 'index.php#one';</script>";
			}
			else
			{
	 
				$host="localhost";
				$username="main_reg19";
				$password="Alma@main_reg19";
				$dbname = "main_reg19";
				$con=mysqli_connect($host,$username,$password,$dbname);
				
			 
				
				if(mysqli_connect_errno())
				{
					$db_connection_status =  "Error occured when connecting with database";
					$error_code = 0;
				}
				else if(user_exists($email, $mobno, $con))
				{
					 echo  "<script type='text/javascript'>alert('User is already regsitered with the same Email id or Mobile No. Please Log In to regsiter for events or contact us.');
					 window.location = 'index.php#one';</script>";
				}
				else
				{
	
					$random_id_length = 10; 
					$rnd_id = uniqid(rand(),1); 
					$rnd_id = strip_tags(stripslashes($rnd_id)); 
					$rnd_id = str_replace(".","",$rnd_id); 
					$rnd_id = strrev(str_replace("/","",$rnd_id)); 
					$rnd_id = substr($rnd_id,0,$random_id_length); 
					
					$pass= md5($pass);
					$query= "INSERT INTO online_reg(refid,fname,lname,email,mobno,college,city,state,caid,pass,sex) VALUES ('$rnd_id','$fname','$lname','$email','$mobno','$college','$city','$state','$ca','$pass','$sex')";
					echo "\n" + $query + "\n";
	
				
					$result = mysqli_query($con, $query);
					if($result)
					{
						 echo  "<script type='text/javascript'>alert('Registered Succesfully. Kindly LOG IN to register for events');
						 window.location = 'login.php';
						 </script>";
					 
						 $reply = $_POST['email'];
						 $replysubject = "Registration Confirmation | ALMA FIESTA 2019 | IIT BHUBANESWAR";
						 $replyfrom = "From: publicrelations@almafiesta.com \r\n";
						 $replymessage .= "Congratulations !! \r\n\r\n";
						 $replymessage .= "You have successfully signed up for three of the most fun filled and enthralling days of your college life! You can check all the latest updates and happening by logging in your account at  http://register.almafiesta.com/login.php . You can also update your list of events that you want to participate in on the same page. \r\n\r\n";
	  
						$replymessage .= "Your Reference ID is : $rnd_id , make sure to enter the same on payment portal to complete your payment and participation to be confirmed.  \r\n\r\n";
						$replymessage .= "You can contact Rahul - 9583962928 for any account related queries. \r\n\r\n";
						$replymessage .= "For doubts regarding registration fee or the payment procedure, feel free to contact Vipul : +91 8895975692 / Manish : +91 9401140388  \r\n\r\n";
						$replymessage .= "We are eagerly waiting to be your hosts in our beautiful campus. See you on 11th!\r\n\r\n";
						
						$replymessage .= "Cheers! \r\n\r\n";
						$replymessage .= "Regards \r\n\r\n";
						$replymessage .= "Team Alma Fiesta \r\n\r\n";
						$replymessage .= "IIT Bhubaneswar \r\n\r\n";
						$replymessage .= "Follow our Facebook page for further updates : https://www.facebook.com/almafiesta/ \r\n\r\n";
							
					   
						mail($reply, $replysubject, $replymessage, $replyfrom);
	
					}
				
				 	else
				 	{
						$register_error = "Sorry. Unable to Process Request. Please contact the Support Service";
						echo "Unable to Register. Please Try again.";
					}	
				}
			 
			 
			 
	}
	
}
ob_end_flush();	
?>
	
    
<!DOCTYPE HTML>
<html>
	<head>
		<title>Employee Registration | Vasitars</title>
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
    
		<link rel="icon" href="<?php echo $vasitars_logo_location;?>" type="image/ico" />
		<!--[if lte IE 8]><script src="assets/js/ie/html5shiv.js"></script><![endif]-->
		<link rel="stylesheet" href="assets/css/main.css" />
		<!--[if lte IE 9]><link rel="stylesheet" href="assets/css/ie9.css" /><![endif]-->
		<!--[if lte IE 8]><link rel="stylesheet" href="assets/css/ie8.css" /><![endif]-->
	<link href="https://fonts.googleapis.com/css?family=Lobster" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Passion+One" rel="stylesheet">
	<link rel="icon" href="almalogo.png?" type="image/x-icon">
	</head>
	<body class="landing">
	
	
	
		<div id="page-wrapper">

			<!-- Header -->
				<header id="header">
					<h1 id="logo"><a href="http://www.almafiesta.com/" target="_blank" style="font-family: 'Lobster', cursive;
								font-size: 1.5em; "> Alma Fiesta'19</a></h1>
					<nav id="nav">
						<ul>
							<li><a href="http://2019.almafiesta.com">Home</a></li>
							<li>
								<a href="competitions.html">Events</a>
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
									<li>
								<a href="aworkshops.html">Workshops</a>
								<ul>
									
									
									<li><a href="salsa.html">Salsa Workshop</a></li>
									
								</ul>
							</li>
							<li><a href="#one" class="button warning  scrolly">Register</a></li>
							<li><a href="login.php" class="button special">Log In</a></li>
						</ul>
					</nav>
				</header>

			<!-- Banner -->
				

			<!-- One -->
		<!-- Main -->
		
		<section id="banner">
					<div class="content">
						<header>
							<h2 class="re" style="font-family: 'Passion One', cursive; font-size : 4em;"> ALMA FIESTA'19 REGISTRATION</h2>
						</header>
						
					</div>
					<a href="#one" class="goto-next scrolly">Next</a>
		</section>
				<div id="main" class="wrapper style1">
				
					<div class="container">
				<section id="one" >
								<p >*All fields are mandatory</p>
								<form action="<?php htmlspecialchars($_SERVER['PHP_SELF']);?>" method="post">
									<div class="row uniform 50%">
										<div class="6u 12u$(xsmall)">
											<input type="text" name="fname"  id="fname"  value="<?php if(isset($_SESSION['fname'])){ echo $_SESSION['fname'];}?>" required placeholder="First name" /><span class="error">  <?php  echo $fnErr;?> </span>
										</div>
										<div class="6u$ 12u$(xsmall)">
											<input type="text" name="lname" id="lname"  value="<?php if(isset($_SESSION['lname'])){ echo $_SESSION['lname'];}?>" required placeholder="Last name" /><span class="error">  <?php  echo $lnErr;?> </span>
										</div>
										<div class="6u 12u$(xsmall)">
											<input type="email" name="email" id="email"  value="<?php if(isset($_SESSION['email'])){ echo $_SESSION['email'];}?>"required placeholder="Email" /><span class="error">  <?php  echo $emailErr;?> </span>
										</div>
										<div class="6u$ 12u$(xsmall)">
											<input type="text" name="mobno" id="mobno"  value="<?php if(isset($_SESSION['mobno'])){ echo $_SESSION['mobno'];}?>" required  placeholder="Contact number" /><span class="error">  <?php  echo $mobnoErr;?> </span>
										</div>
										<div class="6u 12u$(xsmall)">
											<input type="text" name="college" id="college"  value="<?php if(isset($_SESSION['college'])){ echo $_SESSION['college'];}?>" required placeholder="College" /><span class="error">  <?php  echo $collegeErr;?> </span>
										</div>
										<div class="6u$ 12u$(xsmall)">
											<input type="text" name="city" id="city"  value="<?php if(isset($_SESSION['city'])){ echo $_SESSION['city'];}?>" required placeholder="City" /><span class="error">  <?php  echo $cityErr;?> </span>
										</div>
										<div class="6u 12u$(xsmall)">
											<input type="text" name="ca" id="ca"  value="<?php if(isset($_SESSION['ca'])){ echo $_SESSION['ca'];}?>"  placeholder="Referral CA ID (if any) " /><span class="error">  <?php  echo $caErr;?> </span>
										</div>
										<div class="6u$ 12u$(xsmall)">
											<input type="text" name="state" id="state"  value="<?php if(isset($_SESSION['state'])){ echo $_SESSION['state'];}?>" required placeholder="State" /><span class="error">  <?php  echo $stateErr;?> </span>
										</div>
										<div class="6u 12u$(xsmall)">
											<input type="password" name="pass" minlength="6" id="pass" value=""required placeholder="Choose password" /><span class="error">  <?php  echo $passErr;?> </span>
										</div>
										<div class="6u$ 12u$(xsmall)">
											<input type="password" name="cpass"  minlength="6" id="cpass" value="" required placeholder="Confirm password" /><span class="error">  <?php  echo $passmatErr;?> </span>
										</div>
										
										<div class="4u 12u$(medium)">
											<input type="radio" id="priority-low" required value="Male" name="sex" >
											<label for="priority-low">Male</label>
										</div>
										<div class="4u 12u$(medium)">
											<input type="radio" id="priority-normal" required value="Female" name="sex">
											<label for="priority-normal">Female</label>
										</div>
										<div class="4u$ 12u$(medium)">
											<input type="radio" id="priority-high" required value="Other" name="sex">
											<label for="priority-high">Other</label>
										</div>
									
										
									
										
										<div class="12u$">
											<ul class="actions">
												<li><input type="submit" value="Register" class="special" name="register" /></li>
												<li><input type="reset" value="Reset" /></li>
											</ul>
										</div>
										<header>
							<h3 >-- Facing any technical difficulty? Drop an Email at <a href="mailto:web19@almafiesta.com" target="_blank" ><u>web19@almafiesta.com</u></a></h3>
							
						</header>
									</div>
									
								</form>
							</section>
				</div>
				</div>

			<!-- Footer -->
				<footer id="footer">
					<ul class="icons">
						
						<li><a href="https://www.facebook.com/almafiesta/" target="_blank" class="icon alt fa-facebook"><span class="label">Facebook</span></a></li>
						<li><a href="https://www.youtube.com/channel/UCVAHFAfxXx0ZaOyS5VczKiA" target="_blank" class="icon alt fa-youtube"><span class="label">You Tube</span></a></li>
						<li><a href="https://www.instagram.com/almafiesta.iitbbs/" target="_blank" class="icon alt fa-instagram"><span class="label">Instagram</span></a></li>
						
					</ul>
					<ul class="copyright">
						<li>&copy;<a href="http://www.almafiesta.com/" target="_blank" style="text-decoration: none;">ALMA FIESTA'19 </a>  | WEB DEVELOPMENT</li>
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
<?php session_destroy()?>
	</body>
</html>