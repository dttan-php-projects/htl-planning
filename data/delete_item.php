<?php
	header("Content-Type: application/json");
	if(!empty($_POST['data'])){
		$formatData = json_decode($_POST['data'],true);
		if(!empty($formatData)){
			$listID = implode(',',$formatData);
			if(!empty($listID)){
				require_once("../Database.php");//connect to database
				$delete_ms = "DELETE FROM master_item WHERE id IN ($listID);";
				$check_1 = connection()->query($delete_ms);
				if($check_1){
					$response = [
						'status' => true,
						'mess' =>''  
					];
				}else{
					$response = [
							'status' => false,
							'mess' =>  connection()->error
						];
				}
				echo json_encode($response);
			}
		}
	}