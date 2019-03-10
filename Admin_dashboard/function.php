<?php

$vasitars_logo_location = "images/vasitars_logo.png";
$vasitars_big_logo_location = "images/vasi.png";
$max_planned_leaves = 15;

function profile_picture_location($mem_id)
{
		$dir = "../dashboard/profile_picture/";

	// Open a directory, and read its contents
	if (is_dir($dir))
	{
	  if ($dh = opendir($dir))
	  {
			while (($file = readdir($dh)) !== false)
			{
				$file_name = $file;
			  $filetype = pathinfo($file,PATHINFO_EXTENSION);
				$filename = pathinfo($file, PATHINFO_FILENAME);
				if($filename != $mem_id)
				{
					continue;
				}
			  if(($filetype == "png")||($filetype == "jpg")||($filetype == "jpeg")||($filetype == "JPG"))
			  {
				  $image_file_type = $filetype;
				  $profile_picture_location = $dir.$file_name;
				  return $profile_picture_location;

			  }

			}
			closedir($dh);
			copy("assets/images/user.png","user.png");
			$profile_picture_location = "user.png";
			return $profile_picture_location;

	  }
	}

}

function admin_exists($MEM_ID, $con)
{
	$query = mysqli_query($con, "SELECT * FROM admin WHERE MEM_ID = '$MEM_ID'");
	if(!query || (mysqli_num_rows($query) == 0))
	{
		return false;
	}
	else
	{
		return true;
	}
}

function mem_exists($MEM_ID, $con)
{
	$query = mysqli_query($con, "SELECT * FROM member WHERE ID = '$MEM_ID'");
	if(!query || (mysqli_num_rows($query) == 0))
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

function logged_in()
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


function send_notif($MEM_ID, $date, $time, $notification, $notification_type, $con)
{
	$notif_query = mysqli_query($con, "INSERT INTO notif(MEM_ID, Date, Time, Notification, Notification_Type, Viewed) VALUES('$MEM_ID', '$date', '$time', '$notification', '$notification_type', '0')");
}

function present_incharge_exists($loc_id, $con)
{
	$incharge_exists_query = mysqli_query($con, "SELECT * FROM present_incharge WHERE LOC_ID = '$loc_id'");
	if(!$incharge_exists_query || (mysqli_num_rows($incharge_exists_query) == 0))
	{
		return false;
	}
	else
	{
		return true;
	}
}

function is_incharge($mem_id, $con)
{
	$is_incharge_query = mysqli_query($con, "SELECT LOC_ID FROM present_incharge WHERE MEM_ID = '$mem_id'");
	if(!$is_incharge_query || (mysqli_num_rows($is_incharge_query) == 0))
	{
		return false;
	}
	else
	{
		return true;
	}
}

function loc_exists($loc_id, $con)
{
	$loc_exists_query = mysqli_query($con, "SELECT * FROM locations WHERE LOC_ID = '$loc_id'");
	if(!$loc_exists_query || (mysqli_num_rows($loc_exists_query) == 0))
	{
		return false;
	}
	else
	{
		return true;
	}
}

?>
