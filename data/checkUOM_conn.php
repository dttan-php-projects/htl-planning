<?php
	ini_set('max_execution_time',300);  // set time 5 minutes
	header("Content-Type: application/json");

	$data = $_POST['data'];
	// $data = 'OH2011-0455';

	if(empty($data)){
		$response = [
			'status' => false,
			'message' =>  "KHÔNG LẤY ĐƯỢC UOM. SỬ DỤNG MẶC ĐỊNH EA ?"
		];
	} else {
		$OH = $data;
		if($OH){
			require_once("../Database.php");
			
			// get so line
			$sql_oh = "SELECT SO_LINE FROM oh_so WHERE OH='$OH' ";
			$res_oh = toQueryAll(connection(), $sql_oh);	
			if(empty($res_oh) ){
				$response = [
					'status' => false,
					'message' =>  'OH KHÔNG TỒN TẠI TRÊN HỆ THỐNG!'
				];
				
			}else{
				
				foreach ($res_oh as $OH_ITEM){					
					$SO_LINE = $OH_ITEM['SO_LINE'];
					$SO_LINES = explode("-",$SO_LINE);
					$ORDER_NUMBER = $SO_LINES[0];
					$LINE_NUMBER = $SO_LINES[1];
	
					$where = " ORDER_NUMBER = '$ORDER_NUMBER' AND LINE_NUMBER = '$LINE_NUMBER' ORDER BY ID DESC LIMIT 1; ";
					$sql = "SELECT ITEM FROM vnso WHERE $where ";

					$res = MiQuery($sql,connection("au_avery"));
					if(empty($res) ){
						$sql = "SELECT ID FROM vnso_total $where ";
						$res = MiQuery($sql,connection("au_avery"));
					}

					if (empty($res) ) {
						$response = [
							'status' => false,
							'message' =>  "SOLINE: $SO_LINE KHÔNG CÓ TRONG AUTOMAIL."
						];
						echo json_encode($response);die;
					} else {
						$Item = trim($res);
						$sql = "SELECT id FROM tbl_productline_item WHERE Item = '$Item' ORDER BY id DESC LIMIT 1 ; ";

						$res = MiQuery($sql,connection("cs_avery"));

						if(empty($res)){
							$response = [
								'status' => false,
								'message' =>  "KHÔNG LẤY ĐƯỢC UOM. SỬ DỤNG MẶC ĐỊNH EA ??"
							];
							echo json_encode($response);die;
						} else {
							$response = [
								'status' => true,
								'message' =>  "Success (UOM) "
							];
						}	

					}

									
				}

			}
		}
	}
		

	echo json_encode($response);die;