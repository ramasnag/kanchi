<?php
   ob_start();
   session_start();
?>

<?
ini_set('display_errors', '1');
?>

<html lang = "en">
   
   <head>
      <title>Login Page</title>
      <link href = "css/bootstrap.min.css" rel = "stylesheet">
      
      <style>
.container {
  display: flex;
  justify-content: center;
}       
      </style>
      
   </head>
	
   <body>
      
      
      <div class = "container form-signin">
         
         <?php
            $msg = '';
	    if (isset($_GET['status']))
	    {
		$msg = $_GET['status'];
		if ($msg == 'not_faculty')
		{
			$msg = "Students are not authorized to enter marks!";
		} else if($msg == 'login_error')
		{
			$msg = "Please login to view the marks page!";
		}
		echo $msg;
	    }
			
			$conn=mysqli_connect("localhost","root","user@123","academics");
			
            
            if (isset($_POST['login']) && !empty($_POST['username']) 
               && !empty($_POST['password'])) {
				   
				   $uname = $_POST['username'];
				   $pwd = $_POST['password'];
				   
				   //Check whether valid user details are entered
				   $qry = "select count(*) as a, role from users where uname='" . $uname . "' and pwd='" . $pwd . "'";

				   $result = mysqli_query($conn, $qry);
				   $row = mysqli_fetch_array($result);
				   $val = $row['a'];

				   if ($val == 1)
				   {
					   echo '<script>alert("login success")</script>';	
					   $_SESSION['valid']=true;
					   $_SESSION['timeout'] = time();
					   $_SESSION['uname'] = $uname;
					   $_SESSION['role'] = $row['role'];
					   header('Refresh: 1; URL = display.php');
				   }
				   else 
				   {
					   echo '<script>alert("invalid login details")</script>';	
				   }
				               
            }
         ?>
      </div> <!-- /container -->
      
      <div class = "container">
      <div class=center>
	<h2>Welcome to Login Page</h2> 
         <form class = "form-signin" role = "form" action = "<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method = "post">
<label for="name">Username:</label>
<input type="text" id="username" name="username" required autofocus><br><br>
<label for="password">Password:</label>
<input type="password" id="password" name="password" required><br><br>
<button type="submit" name="login" value="login"> Login</button>

            
         </form>
			
         Click here to <a href = "signup.php" tite = "Create Account"> Signup for a new Account
         
      </div> 
      
   </body>
</html>
