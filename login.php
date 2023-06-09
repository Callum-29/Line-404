<?php

/* If form is submitted then authenticate it*/

include("../include/url_users.php");

if(isset($_POST['submit'])) {

	$username=$_POST['username'];
	$password=$_POST['password'];
	
	/* Prepare statement */
	$stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE username=? AND password=?");

	/* Bind parameters */
	mysqli_stmt_bind_param($stmt, "ss", $username, $password);

	/* Execute query */
	mysqli_stmt_execute($stmt);

	/* Get result */
	$result = mysqli_stmt_get_result($stmt);

	/* query failed */
	if($result==FALSE) {
		printf("Query failed \n");
		header("location:login.php");
	}

	if(mysqli_num_rows($result) == 1) {
		$_SESSION['username']=$username;
		$_SESSION['password']=$password;
		/* user type */
		$detail=mysqli_fetch_assoc($result);
		$_SESSION['usertype']=$detail['usertype'];

		/* Redirect to current / previous page*/
		header('Location: ' . $_SERVER['HTTP_REFERER']);
		//header("location:../index.php");
	} else {
		echo "
		<div class=\"alert alert-danger container\" role=\"alert\">
	  <span class=\"glyphicon glyphicon-exclamation-sign\" aria-hidden=\"true\"></span>
	  <span class=\"sr-only\">Error:</span>
	   Invalid Username or Password
		</div>
		";
	}
} else {
			if(!isset($_SESSION['username'])) {
			echo "
			<div class=\"alert alert-danger\" role=\"alert\">
		  <span class=\"glyphicon glyphicon-exclamation-sign\" aria-hidden=\"true\"></span>
		  <span class=\"sr-only\">Error:</span>
		   Login Again
			</div>
			";
			} else {
				header("location:../index.php");
			}
}
