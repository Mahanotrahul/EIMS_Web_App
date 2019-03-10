<?php

include("connect.php");

$notif_query = mysqli_query($con, "SELECT * FROM notif WHERE MEM_ID = '".$_SESSION['mem_id']."' AND Viewed = '0'");
if(mysqli_num_rows($notif_query) >= 1)
{
  $notif_exists = 1;
}
else
{
  $notif_exists = 0;
}

echo '<div id="sidebar-collapse" class="col-sm-3 col-lg-2 sidebar">
  <div class="profile-sidebar">
    <div class="profile-userpic">
      <img src="'.profile_picture_location($_SESSION["mem_id"]).'" class="img-responsive" alt="">
    </div>
    <div class="profile-usertitle">
      <div class="profile-usertitle-name">'.$_SESSION["fname"].'</div>
      <div class="profile-usertitle-status"><span  class="indicator label-success" ></span> <span id ="aidno"></span></div>



    </div>
    <div class="clear"></div>
  </div>
  <div class="divider"></div>

  <ul class="nav menu">
    <li id="dashboard"><a href="index"><em class="fa fa-dashboard">&nbsp;</em> Dashboard</a></li>
    <li id="profile"><a href="profile"><em class="fa fa-user">&nbsp;</em> Profile</a></li>
    <li id="notifications"><a href="notifications"><em class="fa fa-globe">&nbsp;</em> Notifications&nbsp;&nbsp;';
      if($notif_exists == 1)
      {
        echo '<span  class="indicator label-success" id="notif-icon"></span><b><code style="color:#dbecd4; background:#12a131; border:2px;">'.mysqli_num_rows($notif_query).'</code></b>';
      }
    echo '</a></li>
    <li id="attendance"><a href="attendance"><em class="fa fa-users">&nbsp;</em> Attendance</a></li>';
    if(is_incharge($_SESSION['mem_id'], $con))
    {
      echo '<li id="approve_attendance"><a href="approve_attendance"><em class="fa fa-users">&nbsp;</em> Approve attendance</a></li>';
    }
    echo '<li id="assign_incharge"><a href="assign_incharge"><em class="fa fa-users">&nbsp;</em> Assign Incharge</a></li>
    <li id="make_admin"><a href="make_admin"><em class="fa fa-user-plus">&nbsp;</em> Make Admin</a></li>
    <li id="request_leave"><a href="request_leave"><em class="fa fa-tag">&nbsp;</em> Request for Leave</a></li>
    <li id="employee_details"><a href="employee_details"><em class="fa fa-users">&nbsp;</em> Employee Details</a></li>
    <li id="change_password"><a href="changepass"><em class="fa fa-key">&nbsp;</em> Change Password</a></li>';
    //<li id="contact"><a href="contact"><em class="fa fa-phone">&nbsp;</em> Contact Us</a></li>
    echo '<li id="logout"><a href="logout"><em class="fa fa-power-off">&nbsp;</em> Logout</a></li>
  </ul>
</div><!--/.sidebar-->';

?>
