<?php

$vasitars_logo_location = "images/vasitars_logo.png";
$vasitars_big_logo_location = "images/vasi.png";

function admin_exists($MEM_ID, $con)
{
	$query_admin_exists = mysqli_query($con, "SELECT * FROM admin WHERE MEM_ID = '$MEM_ID'");
	if(!$query_admin_exists || (mysqli_num_rows($query_admin_exists) == 0))
	{
		return false;
	}
	else
	{
		return true;
	}
}

function user_exists($email, $pan, $phone_number, $con)
{
	$result = mysqli_query($con,"SELECT * FROM member WHERE EMAIL='$email' OR PHONE_NUMBER = '$phone_number' OR PAN_NUMBER = '$pan'");
	if(!$result || (mysqli_num_rows($result) == 0))
	{
		return false;
	}
	else
	{
		return true;
	}

}

function admin_logged_in()
{
	if(isset($_SESSION) &&(isset($_SESSION['LOGIN_SESSION'])) && (isset($_COOKIE['LOGIN_ADMIN_EMAIL'])))
	{
		if($_SESSION['LOGIN_SESSION'] == md5($_COOKIE['LOGIN_ADMIN_EMAIL']))
		{

			return true;
		}
		else
		{
			return false;
		}
	}
	else
	{

		return false;
	}
}

function logged_in()
{
	if(isset($_SESSION) &&(isset($_SESSION['LOGIN_SESSION'])) && (isset($_COOKIE['LOGIN_EMAIL'])))
	{
		if($_SESSION['LOGIN_SESSION'] == md5($_COOKIE['LOGIN_EMAIL']))
		{

			return true;
		}
		else
		{
			return false;
		}
	}
	else
	{

		return false;
	}
}

?>
