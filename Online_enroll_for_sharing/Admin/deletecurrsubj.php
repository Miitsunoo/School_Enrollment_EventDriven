<?php
session_start();
include('logcheck.php');
require('includes/conn.php');

if(isset($_GET['accid']) && ctype_digit((string)$_GET['accid']))
{
	$num=(int)$_GET['accid'];
	$connection=$GLOBALS['online_enroll_admin_connection'];
	mysqli_query($connection, "DELETE FROM subjecttbl WHERE ID = $num");
	echo"<script>window.alert('Successfully Removed the selected subject')</script>";
	echo"<script>window.location='subjectreg.php'</script>";
}
else
{
	echo"<script>window.location='subjectreg.php'</script>";
}

?>