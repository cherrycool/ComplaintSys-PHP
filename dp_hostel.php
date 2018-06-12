<?php

	//Get the student entry number from the json
	$content = trim(file_get_contents("php://input"));
	//Get the jsondata sent

	$json_content = json_decode($content, true);

	$json_action = $json_content["action"];
	//$json_action = "sync";
	if($json_action == "hostel info"){

		sendHostelInfo($json_content);
	}


	function sendHostelInfo($json_content){

		$student_info = $json_content["student"];

		$student_entryno = $student_info[1];
		//$student_entryno = "2013CS5";
		$student_hostel_info = $student_info[2];
		//Connect to the database
		$user = "root";  
		$password = "";  
		$host ="localhost";  
		$db_name ="complaint_db";  
		$con = mysqli_connect($host,$user,$password,$db_name);  

		$sql = "select id from hostel_list where name = '".$student_hostel_info."' ;";
		
		$count = 0;

		$array_caretaker = array();

		if($hostel_ids = mysqli_query($con, $sql)){

			$row = mysqli_fetch_row($hostel_ids);
			$id = $row[0];
			
			//Extract the hostel caretaker information from the hostel
			$caretaker_sql = "select * from hostel_caretakers where hostel_id = ".(string)$id." ;";

			if($hostel_caretaker = mysqli_query($con, $caretaker_sql)){
				$row = mysqli_fetch_row($hostel_caretaker);
				$array_caretaker = array('status' => 'Success', 
					'caretaker' => $row[1],
					'designation' => $row[2],
					'phone_no' => $row[3],
					);
				echo json_encode($array_caretaker);

			}else{
				$array = array("status" => "Hostel Info not available");
		    echo json_encode($array);				
			}


		}else{

			$array = array("status" => "Error in MySQL Connection");
		    echo json_encode($array);
		}

	}

		

?>