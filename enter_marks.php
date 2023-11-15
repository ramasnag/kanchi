<?php
ob_start();
   session_start();
ini_set('display_errors', '1');
if (!$_SESSION['valid'])
{
	//echo '<script>alert("Please login to view this page")</script>';
	header('Refresh: 0; URL= http://10.21.24.60/kanchi/login.php?status=login_error');
}
else if ($_SESSION['role'] != 'faculty')
{
	echo '<script>alert("Students are not authorized! Please login as faculty to enter marks")</script>';
	header('Refresh: 0; URL= http://10.21.24.60/kanchi/display.php?status=not_faculty');
}
?>

<html>
<head>

<link href="menu.css" rel="stylesheet">
<style>
.container
{
	display: flex;
	justify-content: center;
}
</style>
</head>
<body>
<nav>
		<ul id="mainMenu">
			<li><a href="login.php">Login</a></li>
			<li><a href="display.php">Display</a></li>
			<li><a href="enter_marks.php">Marks Entry</a></li>
			<li><a href="signup.php">Signup</a></li>
			<li><a href="logout.php">Logout</a></li>
		</ul>
	</nav>
<br/><br/>


<?php
$uname = $_SESSION['uname'];
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

if ($_SESSION['valid'])
{
	echo "<br/><h1> Welcome " . $uname . "</h1>";
$conn=mysqli_connect("localhost","root","user@123","academics");

//Enter marks
if(isset($_POST['submit']) && !empty($_POST['name']) && !empty($_POST['subject']) && !empty($_POST['marks']))
{
	if (mysqli_connect_errno())
	{
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
	$name = $_REQUEST['name'];
	if (!empty($name))
	{
		$subject = $_REQUEST['subject'];
		$marks = $_REQUEST['marks'];
		$qry = "insert into marks values ('$name','$subject',$marks)";
		addLog($handle, "insert query = " . $qry);
		$ins = mysqli_query($conn, $qry);
		echo '<script>alert("record inserted")</script>';
	}

}

$conn->close();
} else
{
	echo "Please login to view this page";
	header('Refresh: 0; URL = http://10.21.24.60/kanchi/login.php?status=login_error');
}
?>

<div class=container>
<div class=center>
<form name="form" method="post">
<label for="name">Name:</label>
<input type="text" id="name" name="name"><br><br>
<label for="subject">Subject:</label>
<input type="text" id="subject" name="subject"><br><br>
<label for="marks">Marks:</label>
<input type="text" id="marks" name="marks"><br><br>
<button name="submit" value="submit"> Enter Marks</button>
</form>
</div>
</div>
</body>
</html>
