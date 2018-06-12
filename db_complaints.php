<?php

	$content = trim(file_get_contents("php://input"));
	//Get the jsondata sent
	$json_content = json_decode($content, true);

	$student_info = $json_content["student"];

	$student_name = $student_info[0];
	$student_entryno = $student_info[1];
	$student_hostel = $student_info[2];
	$student_roomno = $student_info[3];

	$complaint_info = $json_content["complaint"];

	$complaint_LID = $complaint_info[0]; //ID in local database
	$complaint_class = $complaint_info[1];
	$complaint_issue = $complaint_info[2];
	$complaint_datetime = $complaint_info[3];

	$format = 'Y-m-d H:m:s';

	$complaint_status = $complaint_info[4];


	//Connect to the database
	$user = "root";  
	$password = "";  
	$host ="localhost";  
	$db_name ="complaint_db";  
	$con = mysqli_connect($host,$user,$password,$db_name);  

	//get the hostel id
	$hostel_sql = "select id from hostel_list where name = '".$student_hostel."' ;";

	if($hostel_ids = mysqli_query($con, $hostel_sql)){

		$row = mysqli_fetch_row($hostel_ids);
		$hostel_id = (int)$row[0];

		$sql = "insert into complaint_details (class, issue, date_time) values('".$complaint_class."', '".$complaint_issue."', '".$complaint_datetime."' );";

		if(mysqli_query($con, $sql)){
			
			$complaint_details_id = mysqli_insert_id($con);

			//Insert in the student_complaints table
			$student_sql = "insert into student_complaints (entry_no, complaint_id, status, hostel_id) values('".$student_entryno."', '".$complaint_details_id."', '".(string)$complaint_status."', ".$hostel_id." );";

			if(mysqli_query($con, $student_sql)){

				$array = array(
					"status" => "Success",
					"server_id" => $complaint_details_id,
					"datetime" => $complaint_datetime,
					);

				echo json_encode($array);

			} else{

				$array = array("status" => "Failed To Insert In Student_Complaints");
		    	echo json_encode($array);

			}

		}else {
			$array = array("status" => "Failed To Insert In Complaint_detaisl");
		    echo json_encode($array);		
		}



	}else{

		$array = array("status" => "Hostel ID err");
		    echo json_encode($array);		
	}

	
	mysqli_close($con);

?>