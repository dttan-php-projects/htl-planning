<?php
header("Content-Type: application/json");
if(!empty($_POST['data'])){
	$formatData = json_decode($_POST['data'],true);
	if(!empty($formatData)){
		$UPDATED_BY  = '';
		$user = $_COOKIE["VNRISIntranet"];
		if(!empty($user)){
			$UPDATED_BY = $user;
		}

		//connect to database
			require_once("../Database.php");
			$conn = connection();
			$table = "user";
		// get data
			$EMAIL = !empty($formatData['EMAIL'])?addslashes($formatData['EMAIL']):'';
			$NOTE = $formatData['NOTE'];
			$idUA = $formatData['ITEM_ID'];
		if(strpos($idUA,'new_id_')!==false){  // insert
			if($EMAIL){
				$sql = "INSERT INTO $table (`EMAIL`,`IS_ADMIN`,`UPDATED_BY`) VALUES ('$EMAIL','$NOTE','$UPDATED_BY')";
				$check_1 = $conn->query($sql);
				$insert_id = $conn->insert_id;
			}			
		}else{
			// update
			$sql = "UPDATE $table SET `EMAIL`='$EMAIL',`IS_ADMIN`='$NOTE',`UPDATED_BY`='$UPDATED_BY' WHERE ID='$idUA'";
			$check_1 = $conn->query($sql);
		}

		if ($conn) mysqli_close($conn);
		if($check_1){
			$response = [
				'status' => true,
				'mess' =>'',
				'id' => !empty($insert_id)?$insert_id:''
			];
		}else{
			$response = [
				'status' => false,
				'mess' =>  'Update User Error'
			];
		}
		echo json_encode($response);
	}
}