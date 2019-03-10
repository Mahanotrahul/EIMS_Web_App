<?php

function user_exists($email_id, $mobile_no, $con)
{
	$result = mysqli_query($con,"SELECT * FROM online_reg WHERE email='$email_id' OR mobno = '$mobile_no'");
	if(!$result || mysqli_num_rows($result) == 0)
	{
		return false;	
	}
	else
	{
		return true;
	}

}

?>