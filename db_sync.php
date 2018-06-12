<?php

	//Get the student entry number from the json
	$content = trim(file_get_contents("php://input"));
	//Get the jsondata sent

	$json_content = json_decode($content, true);

	$json_action = $json_content["action"];
	//$json_action = "sync";
	if($json_action == "updated"){

		sendUpdatedData($json_content);
	}else if ($json_action == "updated done"){

		uncheckUpdates($json_content);
	}else{

		sendRecordsToSync($json_content);
	}


	function sendRecordsToSync($json_content){

		$student_info = $json_content["student"];

		$student_entryno = $student_info[1];
		//$student_entryno = "2013CS5";
		//Connect to the database
		$user = "root";  
		$password = "";  
		$host ="localhost";  
		$db_name ="complaint_db";  
		$con = mysqli_connect($host,$user,$password,$db_name);  

		$sql = "select complaint_id from student_complaints where entry_no = '".$student_entryno."' ;";
		
		$count = 0;

		$array_link = array();

		if($updated_ids = mysqli_query($con, $sql)){


			while($row = mysqli_fetch_row($updated_ids)){
				$count = $count + 1;
				array_push($array_link, $row[0]);
			}

		}else{

			$array = array("status" => "Error in MySQL Connection");
		    echo json_encode($array);
		}

		if($count == 1){

			$sql = "select * from complaint_details where id = ".(string)$array_link[0]." ;";

			if($complaints = mysqli_query($con, $sql)){

				$json_array = form_json($complaints);
				echo json_encode($json_array);

			}else{

				$array = array("status" => "Error in MySQL Connection");
		    	echo json_encode($array);
			}

		}else if($count > 1){

			$in_stmt = "( ";

			for($i=0; $i<$count-1; $i++){
				$in_stmt = $in_stmt.(string)$array_link[$i].", ";
			}

			$in_stmt = $in_stmt.(string)$array_link[$count-1].")";

			$sql = "select * from complaint_details where id in ".$in_stmt." ;";

			//echo $sql;

			if($complaints = mysqli_query($con, $sql)){

				$json_array = form_json($complaints);

				echo json_encode($json_array);


			}else{
				$array = array("status" => "Error in MySQL Connection");
		    	echo json_encode($array);
			}

		}else{ //$count == 0. No updated records

			$array = array("status" => "No Updates");
		    	echo json_encode($array);

		}



	}


	function sendUpdatedData($json_content){

		$student_info = $json_content["student"];

		$student_entryno = $student_info[1];
		//$student_entryno = "2013CS5";
		//Connect to the database
		$user = "root";  
		$password = "";  
		$host ="localhost";  
		$db_name ="complaint_db";  
		$con = mysqli_connect($host,$user,$password,$db_name);  

		$sql = "select complaint_id from student_complaints where updated = 1 and entry_no = '".$student_entryno."' ;";
		
		$count = 0;

		$array_link = array();

		if($updated_ids = mysqli_query($con, $sql)){


			while($row = mysqli_fetch_row($updated_ids)){
				$count = $count + 1;
				array_push($array_link, $row[0]);
			}

		}else{

			$array = array("status" => "Error in MySQL Connection");
		    echo json_encode($array);
		}


		if($count == 1){

			$sql = "select * from complaint_details where id = ".(string)$array_link[0]." ;";

			if($complaints = mysqli_query($con, $sql)){

				$json_array = form_json($complaints);
				echo json_encode($json_array);

			}else{

				$array = array("status" => "Error in MySQL Connection");
		    	echo json_encode($array);
			}

		}else if($count > 1){

			$in_stmt = "( ";

			for($i=0; $i<$count-1; $i++){
				$in_stmt = $in_stmt.(string)$array_link[$i].", ";
			}

			$in_stmt = $in_stmt.(string)$array_link[$count-1].")";

			$sql = "select * from complaint_details where id in ".$in_stmt." ;";

			//echo $sql;

			if($complaints = mysqli_query($con, $sql)){

				$json_array = form_json($complaints);

				echo json_encode($json_array);


			}else{
				$array = array("status" => "Error in MySQL Connection");
		    	echo json_encode($array);
			}

		}else{ //$count == 0. No updated records

			$array = array("status" => "No Updates");
		    	echo json_encode($array);

		}

	}

	function form_json($complaints){


		$complaints_updated = array();

		while($row = mysqli_fetch_row($complaints)){

			array_push($complaints_updated, $row);
		}

		$json_array = array(
			"status" => "Success",
			"complaints" => $complaints_updated
			);

		return $json_array;

	}

	function uncheckUpdates($json_content){

		$student_info = $json_content["student"];

		$student_entryno = $student_info[1];
		//$student_entryno = "2013CS5";
		//Connect to the database
		$user = "root";  
		$password = "";  
		$host ="localhost";  
		$db_name ="complaint_db";  
		$con = mysqli_connect($host,$user,$password,$db_name);  

		$sql = "UPDATE student_complaints SET updated = 0 where updated = 1 and entry_no = '".$student_entryno."' ;";

		if(mysqli_query($con, $sql)){

			$array = array(
				"status" => "Success",
				);

			echo json_encode($array);
		}else{

			$array = array("status" => "Error in sending confirmation");
		    	echo json_encode($array);
		}


	}


?>