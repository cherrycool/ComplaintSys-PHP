<?php 

	$content = trim(file_get_contents("php://input"));

	$json_content = json_decode($content, true);

	$user_name = $json_content["name"];
	$user_pass = $json_content["email"];

	//$user_name = isset($_POST["name"]);  
	//$user_pass = isset($_POST["email"]);  
	$user = "root";  
	$password = "";  
	$host ="localhost";  
	$db_name ="data_user";  
	$con = mysqli_connect($host,$user,$password,$db_name);  
	$sql = "insert into user_info values('".$user_name."','".$user_pass."');";  
	if(mysqli_query($con,$sql))  
	{  
		$array = array(
   			"name" => $user_name,
   			"email" => $user_pass,
		);
	    echo json_encode($array);
	}  
	else   
	{  
		$array = array("error" => "Something wrong");
	    echo json_encode($array);
	}  
	mysqli_close($con);  
?>   