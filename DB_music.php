
		<?php

		//--------------------------------------------------------------------------
		// Example php script for fetching data from mysql database
		//--------------------------------------------------------------------------
		$host = "localhost";
		$user = "smarifz_syed";
		$pass = "test1";
		
		
		$databaseName = "smarifz_prototype";
		$tableName = "movies";
		
		$con=mysqli_connect($host,$user,$pass,$databaseName);
		
		// Check connection
		if (mysqli_connect_errno($con))
		{
			echo "Failed to connect to MySQL: " . mysqli_connect_error();
		}
		
		$sql="SELECT * from music";
		$result=mysqli_query($con,$sql);
		$jsonArray = array();
		
		while($row=mysqli_fetch_array($result,MYSQLI_NUM))
		{
			$jsonArray[] = $row;
		}
		
		echo json_encode($jsonArray);
		
		?>
