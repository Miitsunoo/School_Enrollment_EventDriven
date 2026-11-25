<?php
	session_start();
	include('logcheck.php');
	include('includes/conn.php');
	$connection=$GLOBALS['online_enroll_admin_connection'];
	$editId=isset($_GET['accid']) && ctype_digit((string)$_GET['accid']) ? (int)$_GET['accid'] : 0;
	$subjectRow=null;
	if($editId)
	{
		$result=mysqli_query($connection, "SELECT * FROM subjecttbl WHERE ID=$editId");
		$subjectRow=mysqli_fetch_assoc($result);
	}
	if(isset($_POST['register']))
	{
		$course=$_POST['course'];
		$stitle=$_POST['stitle'];
		$scode=$_POST['scode'];
		$sdescription=$_POST['cdescription'];
		$numunits=$_POST['numunits'];
		
		$sroom=$_POST['sroom'];
		$stime=$_POST['stime'];
		$sday=$_POST['sday'];
		$sinstructor=$_POST['sinstructor'];
		
		if(isset($_POST['islab']))
		{
			$islab=1;
		}
		else
		{
			$islab=0;
		}
	
		if(isset($_POST['istutorial']))
		{
			$istutorial=1;
		}
		else
		{
			$istutorial=0;
		}
		
		$editId=isset($_POST['accid']) && ctype_digit((string)$_POST['accid']) ? (int)$_POST['accid'] : 0;
		if($editId)
			$stmt=mysqli_prepare($connection, "UPDATE subjecttbl SET subjectcode=?, subjecttitle=?, numofunits=?, room=?, time=?, instructor=?, day=?, islab=?, istutorial=?, description=?, course_con=? WHERE ID=?");
		else
			$stmt=mysqli_prepare($connection, "INSERT INTO subjecttbl (subjectcode,subjecttitle,numofunits,room,time,instructor,day,islab,istutorial,description,course_con) VALUES (?,?,?,?,?,?,?,?,?,?,?)");
		if($editId)
		{
			mysqli_stmt_bind_param($stmt,'sssssssiissi',$scode,$stitle,$numunits,$sroom,$stime,$sinstructor,$sday,$islab,$istutorial,$sdescription,$course,$editId);
		}
		else
		{
			mysqli_stmt_bind_param($stmt,'sssssssiiss',$scode,$stitle,$numunits,$sroom,$stime,$sinstructor,$sday,$islab,$istutorial,$sdescription,$course);
		}
		$ok=mysqli_stmt_execute($stmt);
		mysqli_stmt_close($stmt);
		echo"<script>window.alert('".($ok?'Successfully '.($editId?'updated':'created').' subject':'Unable to save subject')."')</script>";
		echo"<script>window.location='subjectreg.php'</script>";
	}
	
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>John Paul College</title>


<link href = "style.css" rel = "stylesheet" type = "text/css"/>

</head>

<body>

<div class='header'>
</div>
	
<nav class='container'>
	<?php
include('includes/menu1.php');
?>
</nav>





<div id="main">
	<div class="shell">
	<center>
	<form class='cmxform' method='POST'>
	<input type='hidden' name='accid' value='<?php echo $editId;?>'>
	<fieldset>
			<legend>Course Details</legend>
			  <ol>
			  <li>
				  <label for="name">Select Course:<em>*</em></label>
				  <select name='course'>
					<option value='bsit' <?php echo (!$subjectRow || $subjectRow['course_con']=='bsit')?'selected':'';?>>BSIT</option>
				  </select>
				
  </ol>
			
	</fieldset>
	
	<fieldset>	
			  <legend>Subject Details</legend>
			  <ol>
			  <li>
					<table>
						<tr>
							<td>Subject Code:</td><td align='left'><input type='text' required='required' maxlength='50' id="name" name='scode' value="<?php echo htmlspecialchars($subjectRow['subjectcode'] ?? '');?>" /></td>
						</tr>
						<tr>
							<td>Subject Title:</td><td align='left'><input type='text' required='required' id="name" name='stitle' value="<?php echo htmlspecialchars($subjectRow['subjecttitle'] ?? '');?>" /></td>
						</tr>
						<tr>
							<td>Number of Units:</td><td align='left'><select name='numunits'>
														<?php for($units=1;$units<=6;$units++){ $selected=isset($subjectRow['numofunits']) && (string)$subjectRow['numofunits']===(string)$units?'selected':''; echo "<option value='$units' $selected>$units</option>"; } ?></select></td>
						</tr>
						<tr>
							<td><label>Subject Description:</label></td><td align='left'><textarea name='cdescription'><?php echo htmlspecialchars($subjectRow['description'] ?? '');?></textarea></td>
						</tr>
						<tr>
							<td><label>Room:</label></td><td align='left'><input type='text' required='required' id="name" name='sroom' value="<?php echo htmlspecialchars($subjectRow['room'] ?? '');?>" /></td>
						</tr>
						<tr>
							<td><label>Day:</label></td><td align='left'><input type='text' required='required' id="name" name='sday' value="<?php echo htmlspecialchars($subjectRow['day'] ?? '');?>" /></td>
						</tr>
						<tr>
							<td><label>Time:</label></td><td align='left'><input type='text' required='required' id="name" name='stime' value="<?php echo htmlspecialchars($subjectRow['time'] ?? '');?>" /></td>
						</tr>
						<tr>
							<td><label>Instructor:</label></td><td align='left'><input type='text' required='required' id="name" name='sinstructor' value="<?php echo htmlspecialchars($subjectRow['instructor'] ?? '');?>" /></td>
						</tr>
						<tr>
							<td><label>With Lab:</label></td><td align='left'><input type='checkbox' name='islab' <?php echo !empty($subjectRow['islab'])?'checked':'';?>></td>
						</tr>
						<tr>
							<td><label>With Tutorial:</label></td><td align='left'><input type='checkbox' name='istutorial' <?php echo !empty($subjectRow['istutorial'])?'checked':'';?>></td>
						</tr>
					</table>
					
				</li>
						
				
			 
			  
			  
  </ol>
</fieldset>
<div align='center'><input type='submit' name='register' value='Add new subject'>&nbsp;&nbsp;
<input type='reset' name='clear' value='Clear'></div>
</form>
</center>
	</div>
	<div><br></div>
</div>
<!-- /Main -->

<!-- Footer -->
<div id="footer">
	<div class="shell">
		<!-- Mini Nav --> 
		<div class="footer-navigation"> 
			<?php
include('includes/menu2.php');
?>
		</div> 
		<!-- /Mini Nav --> 
		
		<!-- Copyrights --> 
		<p class="right"> 
			Copyright 2014 | Online Enrollment
		</p> 
		<!-- /Copyrights --> 
		
		<div class="cl">&nbsp;</div> 
	</div>
</div>




</body>
</html>
