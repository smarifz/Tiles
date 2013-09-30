<!DOCTYPE html>
<html>
	<head>
		<title></title>
		<style>
		</style>
	</head>

	<body>
		<?php
		
		//--------------------------------------------------------------------------
		// 1) Connect to mysql database
		//--------------------------------------------------------------------------
		include 'DB.php';
		$con = mysql_connect($host,$user,$pass);
		$dbs = mysql_select_db($databaseName, $con);

		//--------------------------------------------------------------------------
		// 2) Query database for data
		//--------------------------------------------------------------------------
		$queryString = "SELECT * FROM movies WHERE genre='Horror'";
		$result = mysql_query($queryString);          //query
		$array = mysql_fetch_row($result);                          //fetch result

		//--------------------------------------------------------------------------
		// 3) echo result as json
		//--------------------------------------------------------------------------
		echo json_encode($array);
		

		?>

	</body>
</html>
