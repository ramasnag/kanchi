<?php
ob_start();
   session_start();

ini_set('display_errors', '1');

if (!$_SESSION['valid'])
{
	echo '<script>alert("Please login to view this page")</script>';
	header('Refresh: 0; location: http://10.21.24.60/kanchi/login.php?status=login_error');
}
?>
<html>
<head>
<link href="menu.css" rel="stylesheet">
<style>
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
	echo "<br/><h2> Welcome " . $uname . "</h2>";
	$conn=mysqli_connect("localhost","root","user@123","academics");

	if (mysqli_connect_errno())
	{
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}

	// Display the logged in student's marks
	if ($_SESSION['role'] == 'student')
	{
	
		$qry = "select * from marks where name = '" . $uname . "' order by subject";
		$result=mysqli_query($conn, $qry);
		echo "<table border=1>";
		echo "<tr><th>Subject</th><th>Marks</th></tr>";
		while($row=mysqli_fetch_array($result))
		{
			$score = $row['marks'];

			if ($score > 80)
				$bgcolor="green";
			else if ($score > 50 && $score <= 80)
				$bgcolor="yellow";
			else
				$bgcolor="red";
			echo "<tr bgcolor='" . $bgcolor . "'>";
			echo "<td>" . $row['subject']. "</td>";
			echo "<td>" . $row['marks']. "</td>";
			echo "</tr>";
		}
		echo "</table><br/><br/>";
		echo "<h3>Statistics of marks obtained by " . $uname . "</h3>";
		echo "<table>";

		$qry = "select min(marks) as min, max(marks) as max, round(avg(marks)) as avg, sum(marks) as sum from marks where name = '" . $uname . "'";
		$result=mysqli_query($conn, $qry);
		$row=mysqli_fetch_array($result);
		echo "<tr><td>Min</td><td>" . $row['min'] . "</td></tr>";
		echo "<tr><td>Max</td><td>" . $row['max'] . "</td></tr>";
		echo "<tr><td>Avg</td><td>" . $row['avg'] . "</td></tr>";
		echo "<tr><td>Total</td><td>" . $row['sum'] . "</td></tr>";
		echo "</table>";
	}

	// In case of faculty, display various options
	if ($_SESSION['role'] == 'faculty') {
	?>
	<form method=post >
	<label>Student</label>
	<input type=text name=student />
	<input type="submit" name="onestudent" value="Show marks for this student"/><br/><br/>
	<label>Subject</label>
	<input type=text name=subject />
	<input type="submit" name="onesubject" value="Show marks for this subject"/><br/><br/>
	<input type="submit" name="groupstudent"
                value="Show all marks student wise"/> 
          
        <input type="submit" name="groupsubject"
                value="Show all marks subject wise"/>

	</form>
	<?php 
	$qry = "select * from marks";
	
	if (isset($_POST['groupstudent']))
	{
		$qry = $qry . " order by name";
		echo "<h3>Displaying all marks student wise</h3>";
	} else if(isset($_POST['groupsubject']))
	{
		echo "<h3>Displaying all marks subject wise</h3>";
		$qry = $qry . " order by subject";
	} else if(isset($_POST['onestudent']))
	{
		$student=$_POST['student'];
		echo "<h3>Displaying marks for student " . $student . "</h3>";
		if(!empty($student))
		{
			$qry = $qry . " where name = '" . $student . "'";
		}
	} else if(isset($_POST['onesubject']))
	{
		$subject=$_POST['subject'];
		echo "<h3>Displaying marks for subject " . $subject . "</h3>";
		if(!empty($subject))
		{
			$qry = $qry . " where subject = '" .$subject . "'";
		}
	}
	$result=mysqli_query($conn, $qry);
	echo "<table border=1>";
	echo "<tr><th>User</th><th>Subject</th><th>Marks</th></tr>";
	while($row=mysqli_fetch_array($result))
	{
		$score = $row['marks'];

		if ($score > 80)
			$bgcolor="green";
		else if ($score > 50 && $score <= 80)
			$bgcolor="yellow";
		else
			$bgcolor="red";
		echo "<tr bgcolor='" . $bgcolor . "'>";
		echo "<td>" . $row['name'] . "</td>";
		echo "<td>" . $row['subject']. "</td>";
		echo "<td>" . $row['marks']. "</td>";
		echo "</tr>";
	}
	echo "</table>";
	} //closing faculty role
	$conn->close();


}
else
{
	echo "Please login to view this page";
	header('Refresh: 0; URL = http://10.21.24.60/kanchi/login.php?status=login_error');
}
?>
</body>
</html>
