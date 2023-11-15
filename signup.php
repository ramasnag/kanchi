<html>
<head>
<style>
.container {
  display: flex;
  justify-content: center;
}
</style>
</head>
<body>
<form method=post>
<div class=container>
<div class=center>
<br><br>
<h1>Welcome to New Account Signup Page</h1>
<label for="name">Username:</label>
<input type="text" id="name" name="name"><br><br>
<label for="password">Password:</label>
<input type="password" id="password" name="password"><br><br>
<button name="submit" type="submit" value="submit"> Create Account</button>
</form>
</div>
</div>

<?php
ini_set('display_errors', '1');
$handle = fopen('/tmp/debug.log', 'ab');

function addLog($handle, $log)
{
  /* Datetime to add at the beginning of the log line. */
  $date = date('d/m/Y H:i:s');
  
  /* Complete log line. */
  $line = $date . ' ' . $log . "\n";
  
  /* Add the new line to the log file. */
  fwrite($handle, $line);
}

//echo "good" . $_POST['name'] . $_POST['password'] . $_POST['submit'];

$conn = mysqli_connect("localhost","root","user@123","academics");

if (isset($_POST['submit']) && !empty($_POST['name']) && !empty($_POST['password'])) 
{

	$uname = $_POST['name'];
	$pwd = $_POST['password'];
	addLog($handle, 'uname = ' . $uname);
	addLog($handle, 'pwd = ' . $pwd);

	//Check whether an account exists by that username
	$qry = "select count(*) as a from users where uname = '" . $uname . "'";
	addLog($handle, 'qry = ' . $qry);
	$result = mysqli_query($conn, $qry);
	addLog($handle, 'result = ' . print_r($result));
	$row = mysqli_fetch_array($result);
	addLog($handle, 'row = ' . print_r($row));
	$val = $row['a'];
	addLog($handle, 'val = ' . $val);
	if ($val == 1)
	{	
		addLog($handle, " in if val is " . $val);
		echo '<script>alert("Username already exists!")</script>';
		header('Refresh: 0; URL = http://10.21.24.60/kanchi/signup.php');
	} else {
		addLog($handle, " val is " . $val);
		$qry = 	"insert into users(uname,pwd) values ('" . $uname . "','" . $pwd . "')";
		addLog($handle, 'insert qry = ' . $qry);
		$val = mysqli_query($conn, $qry);
		addLog($handle, 'insert val = ' . $val);
		if ($val == 1)
		{
			addLog($handle, "user created");
			echo '<script>alert("User created successfully!")</script>';
			header('Refresh: 1; URL=http://10.21.24.60/kanchi/login.php');
			
		}
	}


}

?>

</body>
</html>

