<?php
	session_start();
	include('logcheck.php');
	include('includes/conn.php');
	$connection=$GLOBALS['online_enroll_admin_connection'];
	$editId=isset($_GET['accid']) && ctype_digit((string)$_GET['accid']) ? (int)$_GET['accid'] : 0;
	$userRow=null;
	$studentRow=null;
	if($editId)
	{
		$result=mysqli_query($connection, "SELECT * FROM studenttbl WHERE ID=$editId");
		$studentRow=mysqli_fetch_assoc($result);
		if($studentRow)
		{
			$accountId=(int)$studentRow['accountID'];
			$result=mysqli_query($connection, "SELECT * FROM usertbl WHERE ID=$accountId");
			$userRow=mysqli_fetch_assoc($result);
		}
	}
	if(isset($_POST['register']))
	{
		$editId=isset($_POST['accid']) && ctype_digit((string)$_POST['accid']) ? (int)$_POST['accid'] : 0;
		$username=trim($_POST['username']);
		$password=$_POST['password'];
		$course=$_POST['course'];
		$yrlvlsec=$_POST['yrlvlsec'];
		$snum=$_POST['snum'];
		$fname=$_POST['fname'];
		$minitial=$_POST['minitial'];
		$lname=$_POST['lname'];
		$fullname=trim($fname.' '.$minitial.' '.$lname);
		$gender=$_POST['gender'];
		$bdate=$_POST['bdate'];
		$address=$_POST['address'];
		$cnum=$_POST['cnumber'];
		$email=$_POST['email'];
		$fathername=$_POST['fathername'];
		$mothername=$_POST['mothername'];
		$guardianname=$_POST['guardianname'];
		$civilstatus=$_POST['cstatus'];
		$bplace=$_POST['bplace'];
		$elemschool=$_POST['elemschool'];
		$yrelem=$_POST['yrgradelem'];
		$hsschool=$_POST['hschool'];
		$yrgradhs=$_POST['yrgradhs'];
		$lastschool=$_POST['lastschool'];
		$yrlast=$_POST['yrlast'];
		$gradaddresslast=$_POST['address2'];

		mysqli_begin_transaction($connection);
		$ok=true;
		if($editId)
		{
			$result=mysqli_query($connection, "SELECT accountID FROM studenttbl WHERE ID=$editId");
			$row=mysqli_fetch_assoc($result);
			$accountId=$row ? (int)$row['accountID'] : 0;
			$stmt=mysqli_prepare($connection, "UPDATE usertbl SET username=?, password=?, fullname=? WHERE ID=?");
			mysqli_stmt_bind_param($stmt,'sssi',$username,$password,$fullname,$accountId);
			$ok=$ok && mysqli_stmt_execute($stmt);
			mysqli_stmt_close($stmt);
			$stmt=mysqli_prepare($connection, "UPDATE studenttbl SET studnum=?, fname=?, minitial=?, lname=?, address=?, contact=?, emailaddress=?, birthdate=?, course=?, yearlvl=?, gender=?, fathername=?, mothername=?, guardianname=?, birthplace=?, civilstatus=?, elemschool=?, dategradelem=?, hsschool=?, datehsgrad=?, lastgrad=?, datelastgrad=?, addresslastgrad=? WHERE ID=?");
			mysqli_stmt_bind_param($stmt,'sssssssssssssssssssssssi',$snum,$fname,$minitial,$lname,$address,$cnum,$email,$bdate,$course,$yrlvlsec,$gender,$fathername,$mothername,$guardianname,$bplace,$civilstatus,$elemschool,$yrelem,$hsschool,$yrgradhs,$lastschool,$yrlast,$gradaddresslast,$editId);
			$ok=$ok && mysqli_stmt_execute($stmt);
			mysqli_stmt_close($stmt);
		}
		else
		{
			$stmt=mysqli_prepare($connection, "INSERT INTO usertbl (username,password,fullname,user_type) VALUES (?,?,?,'student')");
			mysqli_stmt_bind_param($stmt,'sss',$username,$password,$fullname);
			$ok=$ok && mysqli_stmt_execute($stmt);
			$accountId=mysqli_insert_id($connection);
			mysqli_stmt_close($stmt);
			if($ok)
			{
				$stmt=mysqli_prepare($connection, "INSERT INTO studenttbl (accountID,studnum,fname,minitial,lname,address,contact,emailaddress,birthdate,course,yearlvl,gender,fathername,mothername,guardianname,birthplace,civilstatus,elemschool,dategradelem,hsschool,datehsgrad,lastgrad,datelastgrad,addresslastgrad) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
				mysqli_stmt_bind_param($stmt,'isssssssssssssssssssssss',$accountId,$snum,$fname,$minitial,$lname,$address,$cnum,$email,$bdate,$course,$yrlvlsec,$gender,$fathername,$mothername,$guardianname,$bplace,$civilstatus,$elemschool,$yrelem,$hsschool,$yrgradhs,$lastschool,$yrlast,$gradaddresslast);
				$ok=mysqli_stmt_execute($stmt);
				mysqli_stmt_close($stmt);
			}
		}
		if($ok)
		{
			mysqli_commit($connection);
			echo"<script>window.alert('Successfully ".($editId?'updated':'created')." user account')</script>";
		}
		else
		{
			mysqli_rollback($connection);
			echo"<script>window.alert('Unable to save user account')</script>";
		}
		echo"<script>window.location='userreg.php'</script>";
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
			<legend>Account Details</legend>
			  <ol>
			  <li>
				  <label for="name">UserName<em>*</em></label>
				  <input type='text' required='required' maxlength='50' id="name" name='username' value="<?php echo htmlspecialchars($userRow['username'] ?? '');?>"/>
				  <label for="name">Password<em>*</em></label>
				  <input type='password' required='required' maxlength='50' id="name" name='password' value="<?php echo htmlspecialchars($userRow['password'] ?? '');?>" />
				</li>
				
  </ol>
			
	</fieldset>
	
	<fieldset>
			<legend>College Details</legend>
			  <ol>
			  <li>
				  <label for="name">Student Number:<em>*</em></label>
				  <input type='text' maxlength='50' id="name" required='required' name='snum' value="<?php echo htmlspecialchars($studentRow['studnum'] ?? '');?>"/>
				  <label for="name">Course<em>*</em></label>
				  <select name='course'>
				  <option value='bsit' <?php echo (($studentRow['course'] ?? 'bsit')==='bsit')?'selected':'';?>>BSIT</option>
				  <option value='bscs' <?php echo (($studentRow['course'] ?? '')==='bscs')?'selected':'';?>>BSCS</option></select>
				  <label for="name">Section/Year Level:<em>*</em></label>
				  <input type='text' required='required' maxlength='50' id="name" name='yrlvlsec' value="<?php echo htmlspecialchars($studentRow['yearlvl'] ?? '');?>"/>
				</li>
				
			</ol>
			
	</fieldset>
	
	<fieldset>	
			  <legend>Personal Details</legend>
			  <ol>
			  <li>
					<table>
						<tr>
							<td>First Name:</td><td><input type='text' maxlength='50' id="name" name='fname' value="<?php echo htmlspecialchars($studentRow['fname'] ?? '');?>"/></td><td>Civil Status:</td><td><select name='cstatus'>
																																				<option value='single' <?php echo (($studentRow['civilstatus'] ?? 'single')==='single')?'selected':'';?>>Single</option>
																																				<option value='married' <?php echo (($studentRow['civilstatus'] ?? '')==='married')?'selected':'';?>>Married</option>
																																				<option value='widow' <?php echo (($studentRow['civilstatus'] ?? '')==='widow')?'selected':'';?>>Widow</option>
																																				</select></td>
						</tr>
						<tr>
							<td>Middle Initial:</td><td align='left'><input type='text' style='width:20px;' maxlength='5' id="name" name='minitial' value="<?php echo htmlspecialchars($studentRow['minitial'] ?? '');?>"/></td>
							<td>Guardian's Name:</td><td align='left'><input type='text'  maxlength='50' id="name" name='guardianname' value="<?php echo htmlspecialchars($studentRow['guardianname'] ?? '');?>"/></td>
						</tr>
						<tr>
							<td>Last Name:</td><td><input type='text' maxlength='50' id="name" name='lname' value="<?php echo htmlspecialchars($studentRow['lname'] ?? '');?>"/></td>
							<td>Father's Name:</td><td align='left'><input type='text'  maxlength='50' id="name" name='fathername' value="<?php echo htmlspecialchars($studentRow['fathername'] ?? '');?>"/></td>
						</tr>
						<tr>
							<td><label>Gender:</label></td><td align='left'><input type='radio' id='name' name='gender' value='male' <?php echo (($studentRow['gender'] ?? '')==='male')?'checked':'';?>>Male &nbsp;&nbsp;<input type='radio' id='name' name='gender' value='female' <?php echo (($studentRow['gender'] ?? '')==='female')?'checked':'';?>>Female</td>
							<td>Mother's Name:</td><td align='left'><input type='text'  maxlength='50' id="name" name='mothername' value="<?php echo htmlspecialchars($studentRow['mothername'] ?? '');?>"/></td>
						</tr>
						<tr>
							<td>BirthDate:</td><td><input type="date" name="bdate" value="<?php echo htmlspecialchars($studentRow['birthdate'] ?? '');?>"/></td><td>Birth Place:</td><td align='left'><input type='text'  maxlength='50' id="name" name='bplace' value="<?php echo htmlspecialchars($studentRow['birthplace'] ?? '');?>"/></td>
						</tr>
					</table>
					
				</li>
						
				
			  <li>
				  <label for="address1">Address<em>*</em></label>
				  <textarea name='address' height='500' width='200'><?php echo htmlspecialchars($studentRow['address'] ?? '');?></textarea>
			</li>
			  <li>
				  <label for="address2">Contact Number</label>
				  <input type='text' name='cnumber' maxlength='11' value="<?php echo htmlspecialchars($studentRow['contact'] ?? '');?>"/>
				</li>
			  <li>
				  <label for="town-city">Email Address</label>
				  <input type='text' name='email' id="town-city" value="<?php echo htmlspecialchars($studentRow['emailaddress'] ?? '');?>"/>
				</li>
				  </ol>
				  </fieldset>
				<fieldset>
		<legend>Educational Background</legend>
			  <ol>
			  <li>
				  <table>
			
						<tr>
							<td>Elementary School:</td><td align='left'><input type='text'  id="name" name='elemschool' value="<?php echo htmlspecialchars($studentRow['elemschool'] ?? '');?>"/></td>
							<td>Year Graduated:</td><td align='left'><select name='yrgradelem'>
																	<?php
																	 $num=1990;
																	 while($num<=2010)
																	 {
																		$selected=(isset($studentRow['dategradelem']) && (string)$studentRow['dategradelem']===(string)$num)?'selected':'';
																		echo"<option value='$num' $selected>$num</option>";
																		$num=$num + 1;
																	 }
																	?>
																	</select></td>
						</tr>
						<tr>
							<td>High School:</td><td><input type='text' id="name" name='hschool' value="<?php echo htmlspecialchars($studentRow['hsschool'] ?? '');?>"/></td>
							<td>Year Graduated:</td><td align='left'><select name='yrgradhs'>
																	<?php
																	 $num=1990;
																	 while($num<=2010)
																	 {
																		$selected=(isset($studentRow['datehsgrad']) && (string)$studentRow['datehsgrad']===(string)$num)?'selected':'';
																		echo"<option value='$num' $selected>$num</option>";
																		$num=$num + 1;
																	 }
																	?>
																	</select></td>
						</tr>
						<tr>
							<td>School Last Attended:</td><td><input type='text' id="name" name='lastschool' value="<?php echo htmlspecialchars($studentRow['lastgrad'] ?? '');?>"/></td>
							<td>Year Graduated:</td><td align='left'><select name='yrlast'>
																	<?php
																	 $num=1990;
																	 while($num<=2010)
																	 {
																		$selected=(isset($studentRow['datelastgrad']) && (string)$studentRow['datelastgrad']===(string)$num)?'selected':'';
																		echo"<option value='$num' $selected>$num</option>";
																		$num=$num + 1;
																	 }
																	?>
																	</select></td>
						</tr>
						<tr>
							<td colspan='4' align='center'><label for="address1">Address<em>*</em></label>
				  <textarea name='address2' height='500' width='200'><?php echo htmlspecialchars($studentRow['addresslastgrad'] ?? '');?></textarea></td>
						</tr>
					</table>
			</li>
				
			</ol>
			
	</fieldset>
			  
			  


<div align='center'><input type='submit' name='register' value='Add new user'>&nbsp;&nbsp;
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
