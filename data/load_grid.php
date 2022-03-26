<?php
	ini_set('max_execution_time',300);  // set time 5 minutes
	header("Content-Type: application/json");

	function getUOM($ITEM) 
	{
		$sql = "SELECT `UOMCost` FROM `tbl_productline_item` WHERE `Item` = '$ITEM' ORDER BY id DESC LIMIT 1 ; ";
		$res = MiQuery($sql,connection("cs_avery"));

		return $res;
	}

	$data = $_POST['data'];
	// $data = '{"OH":"OH2010-1960","checkUOM":true}';
	$data = json_decode($data,true);

	if(!empty($data) ){
		$OH = $data["OH"];
		$checkUOM = (bool)$data["checkUOM"];
		
		if($OH){
			require_once("../Database.php");
			require_once("./class/helper.php");
			// get so line
			$sql_oh = "SELECT SO_LINE FROM oh_so WHERE OH='$OH'";
			$res_oh = MiQuery($sql_oh,connection());
			if(empty($res_oh) ){
				$response = [
					'status' => false,
					'mess' =>  'OH KHÔNG TỒN TẠI TRÊN HỆ THỐNG!'
				];
				echo json_encode($response);die;
			}else{
				if(is_array($res_oh)){
					$dataResult = [];
					$table_vnso = "vnso";
					$table_vnso_total = "vnso_total";

					foreach ($res_oh as $OH_ITEM){
						$SO_LINE = $OH_ITEM['SO_LINE'];
						$SO_LINES = explode("-",$SO_LINE);
						$ORDER_NUMBER = $SO_LINES[0];
						$LINE_NUMBER = $SO_LINES[1];

						if ($checkUOM == false ) {
							$fields = "ID, PROMISE_DATE, REQUEST_DATE, ORDERED_DATE, QTY, ITEM, SOLD_TO_CUSTOMER, ORDERED_ITEM";
							$where = " ORDER_NUMBER = '$ORDER_NUMBER' AND LINE_NUMBER = '$LINE_NUMBER' ORDER BY id DESC LIMIT 1; ";
							$sql = "SELECT $fields FROM $table_vnso WHERE $where ";
							$res = MiQuery($sql,connection("au_avery"));
							if(empty($res) ) {
								$sql = "SELECT $fields FROM $table_vnso_total WHERE $where ";
								$res = MiQuery($sql,connection("au_avery"));
							}
						} else {

							$fields = "ID, PROMISE_DATE, REQUEST_DATE, ORDERED_DATE, QTY, ITEM, SOLD_TO_CUSTOMER, ORDERED_ITEM";
							$where = " ORDER_NUMBER = '$ORDER_NUMBER' AND LINE_NUMBER = '$LINE_NUMBER' ORDER BY id DESC LIMIT 1; ";
							$sql = "SELECT $fields FROM $table_vnso WHERE $where ";
							$res = MiQuery($sql,connection("au_avery"));
							if(empty($res) ) {
								$sql = "SELECT $fields FROM $table_vnso_total WHERE $where ";
								$res = MiQuery($sql,connection("au_avery"));
							}
							
						}


						if(empty($res)){
							$response = [
								'status' => false,
								'mess' =>  "SO LINE $SO_LINE KHÔNG TỒN TẠI !!"
							];
							echo json_encode($response);die;
						}
						$STT = 0;
						foreach ($res as $row){
							$ID = $row['ID'];

							if(!empty($row['REQUEST_DATE'])){
								$REQUEST_DATE = formatDate2($row['REQUEST_DATE']);
								// $REQUEST_DATE = date("d-M-y", strtotime("$REQUEST_DATE - 1 day"));
							}else{
								$REQUEST_DATE = '';
							}
							if(!empty($row['PROMISE_DATE'])){
								//@tandoan: Hàm formatDate đã trừ 2 ngày (hoặc 1 ngày). Them ham formatDate2 tru 3 ngay neu ngay thu 2, 2 ngay neu ngay
								$PROMISE_DATE = formatDate2($row['PROMISE_DATE']);
							} else {
								$PROMISE_DATE = '';
							}

							if(!empty($row['ORDERED_DATE'])){
								$ORDERED_DATE = formatDate2($row['ORDERED_DATE'],'',0);
							}else{
								$ORDERED_DATE = '';
							}
							$SO = $SO_LINE;
							$STT++;

							$ITEM = trim($row['ITEM']);

							// @tandoan - 20200928: Lấy thông tin UOM, nếu là SET thì nhân 2 số lượng.
							// Trường hợp check UOM = false mặc định là EA
							if ($checkUOM == false ) {
								$UOMCost = "EA";
							} else {
								// $UOMCost = trim($row['UOMCost']);
								$UOMCost = getUOM($ITEM);
							}

							$QTY = (int)trim($row['QTY']);
							$QTY = (strtoupper($UOMCost) == 'SET' ) ? $QTY * 2 : $QTY;

							

							$sql_ITEM = "SELECT * FROM master_item WHERE ITEM='$ITEM' LIMIT 0,1";
							$res_ITEM = MiQuery($sql_ITEM,connection());
							if(empty($res_ITEM)){
								$response = [
									'status' => false,
									'mess' =>  "MASTER ITEM $ITEM KHÔNG TỒN TẠI!"
								];
								echo json_encode($response);die;
							}
							$MASTER_ITEM 		= trim($res_ITEM[0]['ITEM']);
							$RBO 				= trim($row['SOLD_TO_CUSTOMER']);
							$CUSTOMER_ITEM 		= trim($row['ORDERED_ITEM']);
							$FOD 				= trim($res_ITEM[0]['FOD']);
							$sql_count_so_item 	= "SELECT count(*) FROM save_so WHERE ITEM='$ITEM'";
							$count_so_item = MiQuery($sql_count_so_item,connection());
							if(!empty($count_so_item)){
								$FOD = '';
							}
							$CHECK_AGI 			= trim($res_ITEM[0]['CHECK_AGI']);
							
							// tandoan: Lấy code vật tư và tên vật tư trong master item (20201107)
								$MATERIAL_CODE = trim($res_ITEM[0]['VAT_TU']);
								$MATERIAL_NAME 		= trim($res_ITEM[0]['TEN_VAT_TU']);
								$MATERIAL_CODE_ARR = explode("-",$MATERIAL_CODE);
								$MATERIAL_SIZE = !empty($MATERIAL_CODE_ARR[2])?$MATERIAL_CODE_ARR[2]:'';
								if (strpos($MATERIAL_CODE, 'PHIM') !==false) {
									$MATERIAL_SIZE = !empty($MATERIAL_CODE_ARR[1])?$MATERIAL_CODE_ARR[1]:'';
								} else {
									$MATERIAL_SIZE = !empty($MATERIAL_CODE_ARR[2])?$MATERIAL_CODE_ARR[2]:'';
								}

							
							if(empty($MASTER_ITEM)){
								$response = [
									'status' 	=> false,
									'mess' 		=>  "MASTER ITEM $ITEM KHÔNG TỒN TẠI, VUI LÒNG CẬP NHẬT!"
								];
								echo json_encode($response);die;
							}
							if(empty($MATERIAL_CODE)){
								$response = [
									'status' 	=> false,
									'mess' 		=>  "SO LINE $SO KHÔNG CÓ MÃ VẬT TƯ, VUI LÒNG CẬP NHẬT!"
								];
								echo json_encode($response);die;
							}
							$SCRAP_ERROR 		= trim($res_ITEM[0]['SCRAP']);

							$dataResult[] = [
								'id' => $ID,
								'data' => [1,'',$SO,$QTY,$ITEM,$RBO,$PROMISE_DATE,$REQUEST_DATE,$ORDERED_DATE,$CUSTOMER_ITEM,$MATERIAL_CODE,$MATERIAL_NAME,$MATERIAL_SIZE,$CHECK_AGI,$FOD,$SCRAP_ERROR,$UOMCost]
							];
						}
					}
				}else{
					$dataResult = [];
					$SO_LINE = $res_oh;
					$SO_LINES = explode("-",$SO_LINE);
					$ORDER_NUMBER = $SO_LINES[0];
					$LINE_NUMBER = $SO_LINES[1];

					$fields = " ID,PROMISE_DATE,REQUEST_DATE,ORDERED_DATE,QTY,ITEM,SOLD_TO_CUSTOMER,ORDERED_ITEM ";
					$where = " ORDER_NUMBER='$ORDER_NUMBER' and LINE_NUMBER='$LINE_NUMBER' order by id DESC LIMIT 0,1;";

					$sql = "SELECT $fields FROM vnso where $where ";
					$res = MiQuery($sql,connection("au_avery"));
					if(empty($res)||count($res)<1){
						$sql = "SELECT $fields FROM vnso_total where $where ";
						$res = MiQuery($sql,connection("au_avery"));
					}
					if(empty($res)){
						$response = [
							'status' => false,
							'mess' =>  "SO LINE $SO_LINE KHÔNG TỒN TẠI TRÊN HỆ THỐNG ORACLE!"
						];
						echo json_encode($response);die;
					}
					$STT = 0;
					foreach ($res as $row){
						$ID = $row['ID'];
						if(!empty($row['PROMISE_DATE'])){
							$PROMISE_DATE = formatDate2($row['PROMISE_DATE']);
							//@tandoan: tru 2 ngay hay 1 ngay
							//$PROMISE_DATE = date("d-M-y", strtotime("$PROMISE_DATE - 2 day"));
						}else{
							$PROMISE_DATE = '';
						}
						if(!empty($row['REQUEST_DATE'])){
							$REQUEST_DATE = formatDate2($row['REQUEST_DATE']);
						}else{
							$REQUEST_DATE = '';
						}
						if(!empty($row['ORDERED_DATE'])){
							$ORDERED_DATE = formatDate2($row['ORDERED_DATE'],'',0);
						}else{
							$ORDERED_DATE = '';
						}
						$SO = $SO_LINE;
						$STT++;
						$QTY = trim($row['QTY']);
						$ITEM = trim($row['ITEM']);
						$sql_ITEM = "SELECT * FROM master_item WHERE ITEM='$ITEM' LIMIT 0,1";
						$res_ITEM = MiQuery($sql_ITEM,connection());
						if(empty($res_ITEM)){
							$response = [
								'status' => false,
								'mess' =>  "MASTER ITEM $ITEM KHÔNG TỒN TẠI!"
							];
							echo json_encode($response);die;
						}
						$MASTER_ITEM = trim($res_ITEM[0]['ITEM']);
						$RBO = trim($row['SOLD_TO_CUSTOMER']);
						$CUSTOMER_ITEM = trim($row['ORDERED_ITEM']);
						$FOD = trim($res_ITEM[0]['FOD']);
						$sql_count_so_item = "SELECT count(*) FROM save_so WHERE ITEM='$ITEM'";
						$count_so_item = MiQuery($sql_count_so_item,connection());
						if(!empty($count_so_item)){
							$FOD = '';
						}
						$CHECK_AGI = trim($res_ITEM[0]['CHECK_AGI']);

						// tandoan: Lấy code vật tư và tên vật tư trong master item (20201107)
							$MATERIAL_CODE = trim($res_ITEM[0]['VAT_TU']);
							$MATERIAL_NAME 		= trim($res_ITEM[0]['TEN_VAT_TU']);
							$MATERIAL_CODE_ARR = explode("-",$MATERIAL_CODE);
							$MATERIAL_SIZE = !empty($MATERIAL_CODE_ARR[2])?$MATERIAL_CODE_ARR[2]:'';
							if (strpos($MATERIAL_CODE, 'PHIM') !==false) {
								$MATERIAL_SIZE = !empty($MATERIAL_CODE_ARR[1])?$MATERIAL_CODE_ARR[1]:'';
							} else {
								$MATERIAL_SIZE = !empty($MATERIAL_CODE_ARR[2])?$MATERIAL_CODE_ARR[2]:'';
							}


						if(empty($MASTER_ITEM)){
							$response = [
								'status' => false,
								'mess' =>  "MASTER ITEM $ITEM KHÔNG TỒN TẠI, VUI LÒNG CẬP NHẬT!"
							];
							echo json_encode($response);die;
						}
						if(empty($MATERIAL_CODE)){
							$response = [
								'status' => false,
								'mess' =>  "SO LINE $SO KHÔNG CÓ MÃ VẬT TƯ, VUI LÒNG CẬP NHẬT!"
							];
							echo json_encode($response);die;
						}
						$SCRAP_ERROR = trim($res_ITEM[0]['SCRAP']);


						$dataResult[] = [
							'id' => $ID,
							'data' => [1,'',$SO,$QTY,$ITEM,$RBO,$PROMISE_DATE,$REQUEST_DATE,$ORDERED_DATE,$CUSTOMER_ITEM,$MATERIAL_CODE,$MATERIAL_NAME,$MATERIAL_SIZE,$CHECK_AGI,$FOD,$SCRAP_ERROR]
						];
					}
				}

				// @tandoan: check Neu khac Vat tu thi khong cho lam don
				if (!empty($dataResult) ) {
					$dataResult_tmp = $dataResult;
					$material_code_tmp = '';
					foreach ($dataResult_tmp as $key => $item ) {
						
						$data = $item['data'];
						$material_code = $data[10];
						if ($key == 0 ) $material_code_tmp = $material_code;
						if ($material_code != $material_code_tmp ) {
							$response = [
								'status' 	=> false,
								'mess' 		=>  "VUI LÒNG KIỂM TRA LẠI VẬT TƯ (*) "
							];
							echo json_encode($response);die;
						}

						$material_code_tmp = $material_code;
						
					}
				}

				
				
				$response = [
					'status' => TRUE,
					'data' => $dataResult
				];
				echo json_encode($response);die;
			}
		}
	}