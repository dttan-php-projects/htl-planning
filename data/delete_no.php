<?php
header("Content-Type: application/json");
if(!empty($_POST['data'])){
	$NO = $_POST['data'];
	require_once("../Database.php");//connect to database
	$conn = connection();

	$save_item  				= "DELETE FROM save_item WHERE JOB_NO='$NO'";
	$save_oracle_item  			= "DELETE FROM save_oracle_item WHERE JOB_NO='$NO'";
	$save_printing  			= "DELETE FROM save_printing WHERE JOB_NO='$NO'";
	$save_so  					= "DELETE FROM save_so WHERE JOB_NO='$NO'";
	$check_1 = $conn->query($save_item);
	$check_2 = $conn->query($save_oracle_item);
	$check_3 = $conn->query($save_printing);
	$check_4 = $conn->query($save_so);
	if($check_1&&$check_2&&$check_3&&$check_4){
		$response = [
                'status' => true,
                'mess' =>''  
            ];
	}else{
		$response = [
                'status' => false,
                'mess' =>  $conn->error
            ];
	}

	if ($conn) mysqli_close($conn);
	
	echo json_encode($response);
}