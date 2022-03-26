<?php
header("Content-Type: application/json");
if(!empty($_POST['data'])){
	$formatData = json_decode($_POST['data'],true);
	if(!empty($formatData)){
		$listID = implode(',',$formatData);
		if(!empty($listID)){
			require_once("../Database.php");//connect to database
			$conn = connection();

			$delete_ms = "DELETE FROM user WHERE id IN ($listID);";
			$check_1 = $conn->query($delete_ms);
			if($check_1){
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
	}
}