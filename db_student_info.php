<?php

	//Get the student entry number from the json
	$content = trim(file_get_contents("php://input"));
	//Get the jsondata sent

	$json_content = json_decode($content, true);

	$json_action = $json_content["action"];

	storeStudentInfo($json_content);
	

	function storeStudentInfo($json_content){

		$student_info = $json_content["student"];

		$student_name = $student_info[0];
		$student_entryno = $student_info[1];
		$student_branch = $student_info[2];
		$student_phone_no = $student_info[3];
		$student_year_join = $student_info[4];
		$student_hostel = $student_info[5];
		$student_room_no = $student_info[6];
		$student_floor = $student_info[7];
		$student_wing = $student_info[8];

		$user = "root";  
		$password = "";  
		$host ="localhost";  
		$db_name ="complaint_db";  
		$con = mysqli_connect($host,$user,$password,$db_name);  

		$sql = "select * from personal_info where entry_no = '".$student_entryno."' ;";

		if($students = mysqli_query($con, $sql)){

			//echo json_encode(mysqli_num_rows($students));
			$count = mysqli_num_rows($students);
			echo $count;
			if($count >= 1){

				//There is already a student info
				$student_sql = "update personal_info set name = '".$student_name."', entry_no = '".$student_entryno."', branch = '".$student_branch."', ph_no = ".(int)$student_phone_no.", year_of_joining = ".(int)$student_year_join." where entry_no = '".$student_entryno."';";

				$hostel_sql = "update hostel_details set entry_no = '".$student_entryno."', room_no = '".$student_room_no."', floor = '".$student_floor."', WING = '".$student_wing."', hostel_name = '".$student_hostel."' where entry_no = '".$student_entryno."';";

				if(mysqli_query($con, $student_sql) && mysqli_query($con, $hostel_sql)){

					$arr = array("Status" => "Success");
					echo json_encode($arr);

				}else{

					$arr = array("Status" => "Error in updation");
					echo json_encode($arr);
				}

			}else{

				$student_sql = "insert into personal_info (name, entry_no, branch, ph_no, year_of_joining) values ('".$student_name."', '".$student_entryno."', '".$student_branch."', ".(int)$student_phone_no.", ".(int)$student_year_join.");";

				echo $student_sql;
				echo "   ";

				$hostel_sql = "insert into hostel_details (entry_no, room_no, floor, WING, hostel_name) values ('".$student_entryno."', '".$student_room_no."', '".$student_floor."', '".$student_wing."', '".$student_hostel."');";

				echo $hostel_sql;

				if(mysqli_query($con, $student_sql) && mysqli_query($con, $hostel_sql)){

					$arr = array("Status" => "Success");
					echo json_encode($arr);

				}else{

					$arr = array("Status" => "Error in insert");
					echo json_encode($arr);
				}

			}

		}else{

			$array = array("Status" => "Error in beg");
		    echo json_encode($array);
		}

		

	}




?>