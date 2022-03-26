<?php
	date_default_timezone_set('Asia/Ho_Chi_Minh');
	header("Content-Type: application/json");
	$data = $_POST['data'];
	if(!empty($data)){
		$formatData = json_decode($data,true);  
		
		// save date process after that
		if($formatData){
			// get data
			$save_item 			= $formatData["save_item"];
			$save_so 			= $formatData["save_so"];
			$save_item_oracle 	= $formatData["save_item_oracle"];	
			$save_process 		= $formatData["save_process"];
			require_once("../Database.php");//connect to database
			$conn = connection();

			// save item        
			$check_item = true;
			$check_so = true;
			$check_item_oracle = true;
			$check_process = true;
			$check_all = true;
			$AGI = !empty($save_item['AGI'])?addslashes($save_item['AGI']):'';
			$FOD = !empty($save_item['FOD'])?addslashes($save_item['FOD']):'';
			$CREATED_DATE = !empty($save_item['CREATED_DATE'])?addslashes($save_item['CREATED_DATE']):'';
			if(!empty($CREATED_DATE)){
				$CREATED_DATE = date("Y-m-d",strtotime($CREATED_DATE));
			}
			$MACHINE_TYPE = !empty($save_item['MACHINE_TYPE'])?addslashes($save_item['MACHINE_TYPE']):'';
			$JOB_NO = !empty($save_item['JOB_NO'])?addslashes($save_item['JOB_NO']):'';
			// delete all
			if(!empty($save_item['create_OH_exist'])){
				$sql_save_item  				= "DELETE FROM save_item WHERE JOB_NO='$JOB_NO'";
				$sql_save_oracle_item  			= "DELETE FROM save_oracle_item WHERE JOB_NO='$JOB_NO'";
				$sql_save_printing  			= "DELETE FROM save_printing WHERE JOB_NO='$JOB_NO'";
				$sql_save_so  					= "DELETE FROM save_so WHERE JOB_NO='$JOB_NO'";
				$check_1 = $conn->query($sql_save_item);
				$check_2 = $conn->query($sql_save_oracle_item);
				$check_3 = $conn->query($sql_save_printing);
				$check_4 = $conn->query($sql_save_so);
			}
			$PD = !empty($save_item['PD'])?addslashes($save_item['PD']):'';
			if(!empty($PD)){
				$PD = date("Y-m-d",strtotime($PD));
			}
			$PRINTING_TYPE = !empty($save_item['PRINTING_TYPE'])?addslashes($save_item['PRINTING_TYPE']):'';	
			$QTY = !empty($save_item['QTY'])?addslashes($save_item['QTY']):0;
			$RBO = !empty($save_item['RBO'])?addslashes($save_item['RBO']):'';
			$ITEM = !empty($save_item['ITEM'])?addslashes($save_item['ITEM']):'';
			$CUSTOMER_ITEM = !empty($save_item['CUSTOMER_ITEM'])?addslashes($save_item['CUSTOMER_ITEM']):'';
			$NUMBER_FILM = !empty($save_item['NUMBER_FILM'])?addslashes($save_item['NUMBER_FILM']):0;
			$TOTAL_PASSES_1 = !empty($save_item['TOTAL_PASSES_1'])?addslashes($save_item['TOTAL_PASSES_1']):0;
			$TOTAL_COLOUR = !empty($save_item['TOTAL_COLOUR'])?addslashes($save_item['TOTAL_COLOUR']):0;
			$LABEL_SIZE = !empty($save_item['LABEL_SIZE'])?addslashes($save_item['LABEL_SIZE']):'';
			$UPS = !empty($save_item['UPS'])?addslashes($save_item['UPS']):'';
			$UPS_CAL = !empty($save_item['UPS_CAL'])?addslashes($save_item['UPS_CAL']):0;
			$TOTAL_TIME = !empty($save_item['TOTAL_TIME'])?addslashes($save_item['TOTAL_TIME']):0;
			$SHEET_BATCHING = !empty($save_item['SHEET_BATCHING'])?addslashes($save_item['SHEET_BATCHING']):0;
			$ORGINAL_NEED = !empty($save_item['ORGINAL_NEED'])?addslashes($save_item['ORGINAL_NEED']):0;
			$TOTAL_SETUP = !empty($save_item['TOTAL_SETUP'])?addslashes($save_item['TOTAL_SETUP']):0;
			$PACKING = !empty($save_item['PACKING'])?addslashes($save_item['PACKING']):0;
			$PRINTING = !empty($save_item['PRINTING'])?addslashes($save_item['PRINTING']):0;
			$SCRAP_DESIGN = !empty($save_item['SCRAP_DESIGN'])?addslashes($save_item['SCRAP_DESIGN']):0;
			$SCRAP_SETUP = !empty($save_item['SCRAP_SETUP'])?addslashes($save_item['SCRAP_SETUP']):0;
			$SCRAP_ERROR = !empty($save_item['SCRAP_ERROR'])?addslashes($save_item['SCRAP_ERROR']):0;
			$SCRAP_PRINTING = !empty($save_item['SCRAP_PRINTING'])?addslashes($save_item['SCRAP_PRINTING']):0;
			$TOTAL_SCRAP = !empty($save_item['TOTAL_SCRAP'])?addslashes($save_item['TOTAL_SCRAP']):0;
			$PAPER_COMPENSATE = !empty($save_item['PAPER_COMPENSATE'])?addslashes($save_item['PAPER_COMPENSATE']):0;
			$TOTAL_SHEET = !empty($save_item['TOTAL_SHEET'])?addslashes($save_item['TOTAL_SHEET']):0;
			$TOTAL_PASSES_2 = !empty($save_item['TOTAL_PASSES_2'])?addslashes($save_item['TOTAL_PASSES_2']):0;
			$TIME_RUNNING = !empty($save_item['TIME_RUNNING'])?addslashes($save_item['TIME_RUNNING']):0;
			$PLANNING_NAME = !empty($save_item['PLANNING_NAME'])?addslashes($save_item['PLANNING_NAME']):'';
			$NUMBER_SO = !empty($save_item['NUMBER_SO'])?addslashes($save_item['NUMBER_SO']):0;
			$NUMBER_ITEM = !empty($save_item['NUMBER_ITEM'])?addslashes($save_item['NUMBER_ITEM']):0;
			$NUMBER_SCREEN = !empty($save_item['NUMBER_SCREEN'])?addslashes($save_item['NUMBER_SCREEN']):0;
			$MATERIAL_CODE = !empty($save_item['MATERIAL_CODE'])?addslashes($save_item['MATERIAL_CODE']):'';
			$MATERIAL_NAME = !empty($save_item['MATERIAL_NAME'])?addslashes($save_item['MATERIAL_NAME']):'';
			$MATERIAL_SIZE = !empty($save_item['MATERIAL_SIZE'])?addslashes($save_item['MATERIAL_SIZE']):'';
			$UPDATED_BY = '';
			if(!empty($_COOKIE["VNRISIntranet"])){
				$UPDATED_BY = $_COOKIE["VNRISIntranet"];
			}
			$sql_save_item="INSERT INTO `save_item` 
			(`AGI`,`FOD`,`CREATED_DATE`,`MACHINE_TYPE`,`JOB_NO`,`PD`,`PRINTING_TYPE`,`QTY`,`RBO`,`ITEM`,`CUSTOMER_ITEM`,`NUMBER_FILM`,`TOTAL_PASSES_1`,`TOTAL_COLOUR`,`LABEL_SIZE`,`UPS`,`UPS_CAL`,`TOTAL_TIME`,`SHEET_BATCHING`,`ORGINAL_NEED`,`TOTAL_SETUP`,`PACKING`,`PRINTING`,`SCRAP_DESIGN`,`SCRAP_SETUP`,`SCRAP_ERROR`,`SCRAP_PRINTING`,`TOTAL_SCRAP`,`PAPER_COMPENSATE`,`TOTAL_SHEET`,`TOTAL_PASSES_2`,`TIME_RUNNING`,`PLANNING_NAME`,`NUMBER_SO`,`NUMBER_ITEM`,`NUMBER_SCREEN`,`MATERIAL_CODE`,`MATERIAL_NAME`,`MATERIAL_SIZE`,`UPDATED_BY`) 
			VALUES ('$AGI','$FOD','$CREATED_DATE','$MACHINE_TYPE','$JOB_NO','$PD','$PRINTING_TYPE','$QTY','$RBO','$ITEM','$CUSTOMER_ITEM','$NUMBER_FILM','$TOTAL_PASSES_1','$TOTAL_COLOUR','$LABEL_SIZE','$UPS','$UPS_CAL','$TOTAL_TIME','$SHEET_BATCHING','$ORGINAL_NEED','$TOTAL_SETUP','$PACKING','$PRINTING','$SCRAP_DESIGN','$SCRAP_SETUP','$SCRAP_ERROR','$SCRAP_PRINTING','$TOTAL_SCRAP','$PAPER_COMPENSATE','$TOTAL_SHEET','$TOTAL_PASSES_2','$TIME_RUNNING','$PLANNING_NAME','$NUMBER_SO','$NUMBER_ITEM','$NUMBER_SCREEN','$MATERIAL_CODE','$MATERIAL_NAME','$MATERIAL_SIZE','$UPDATED_BY')";
			//echo $sql_save_item;die;
			$check_item = $conn->query($sql_save_item);    
			if($check_item){
				//$insert_id = $conn->insert_id;
				// update material					
				if(!empty($save_so)){
					foreach($save_so as $key=>$so_value){
						$SO_LINE = !empty($so_value['SO_LINE'])?addslashes($so_value['SO_LINE']):'';
						$SO_LINE_QTY = !empty($so_value['SO_LINE_QTY'])?($so_value['SO_LINE_QTY']):0;
						$ITEM = !empty($so_value['ITEM'])?addslashes($so_value['ITEM']):'';
						$CUSTOMER_ITEM = !empty($so_value['CUSTOMER_ITEM'])?addslashes($so_value['CUSTOMER_ITEM']):'';
						$FOD = !empty($so_value['FOD'])?($so_value['FOD']):'';
						$AGI = !empty($so_value['AGI'])?($so_value['AGI']):'';
						$RBO = !empty($so_value['RBO_SO'])?addslashes($so_value['RBO_SO']):'';
						$UOM = !empty($so_value['UOM'])?addslashes($so_value['UOM']):'';
						$sql_save_so = "INSERT INTO `save_so` 
						(`JOB_NO`,`SO_LINE`,`QTY`,`ITEM`,`CUSTOMER_ITEM`,`FOD`,`AGI`,`RBO`, `UOM`)
						VALUES('$JOB_NO','$SO_LINE','$SO_LINE_QTY','$ITEM','$CUSTOMER_ITEM','$FOD','$AGI','$RBO', '$UOM')";
						$check_so = $conn->query($sql_save_so);
						if(!$check_so){
							$response = [
								'status' => false,
								'mess' =>  'Save Data Error (save_so)'
							];
							echo json_encode($response);die;
							$check_all = false;
							break;
						}
					}
				}
				if(!empty($save_item_oracle)){
					foreach($save_item_oracle as $key=>$item_oracle){
						$ORACLE_ITEM = !empty($item_oracle['ITEM_CODE'])?addslashes($item_oracle['ITEM_CODE']):'';
						$FOD = !empty($item_oracle['FOD'])?($item_oracle['FOD']):'';
						$AGI = !empty($item_oracle['AGI'])?($item_oracle['AGI']):'';
						$RBO = !empty($item_oracle['RBO'])?addslashes($item_oracle['RBO']):'';
						$MATERIAL_CODE = !empty($item_oracle['MATERIAL_CODE'])?($item_oracle['MATERIAL_CODE']):'';
						$sql_save_item_oracle = "INSERT INTO `save_oracle_item` 
						(`JOB_NO`,`ORACLE_ITEM`,`FOD`,`AGI`,`RBO`,`MATERIAL_CODE`)
						VALUES('$JOB_NO','$ORACLE_ITEM','$FOD','$AGI','$RBO','$MATERIAL_CODE')";
						$check_item_oracle = $conn->query($sql_save_item_oracle);
						if(!$check_item_oracle){
							$response = [
								'status' => false,
								'mess' =>  'Save Data Error (save_oracle_item)'
							];
							echo json_encode($response);die;
							$check_all = false;
							break;
						}
					}
				}
				if(!empty($save_process)){
					foreach($save_process as $key=>$item_process){
						$PRINTING_FOLLOW = !empty($item_process['PRINTING_FOLLOW'])?addslashes($item_process['PRINTING_FOLLOW']):'';
						$PROCESS_1 = !empty($item_process['PROCESS_1'])?addslashes($item_process['PROCESS_1']):'';
						$PROCESS_2 = !empty($item_process['PROCESS_2'])?addslashes($item_process['PROCESS_2']):'';
						$PROCESS_3 = !empty($item_process['PROCESS_3'])?addslashes($item_process['PROCESS_3']):'';
						$PROCESS_4 = !empty($item_process['PROCESS_4'])?addslashes($item_process['PROCESS_4']):'';
						$PROCESS_5 = !empty($item_process['PROCESS_5'])?addslashes($item_process['PROCESS_5']):'';
						$PASSES = !empty($item_process['PASSES'])?addslashes($item_process['PASSES']):0;
						$SCREEN = !empty($item_process['SCREEN'])?addslashes($item_process['SCREEN']):0;
						$TIME = !empty($item_process['TIME'])?addslashes($item_process['TIME']):0;
						$SETUP = !empty($item_process['SHEET'])?addslashes($item_process['SHEET']):0;
						$sql_save_process = "INSERT INTO `save_printing` 
						(`JOB_NO`,`PRINTING_FOLLOW`,`PROCESS_1`,`PROCESS_2`,`PROCESS_3`,`PROCESS_4`,`PROCESS_5`,`PASSES`,`SCREEN`,`TIME`,`SETUP`)
						VALUES('$JOB_NO','$PRINTING_FOLLOW','$PROCESS_1','$PROCESS_2','$PROCESS_3','$PROCESS_4','$PROCESS_5','$PASSES','$SCREEN','$TIME','$SETUP')";
						$check_process = $conn->query($sql_save_process);
						if(!$check_process){
							$response = [
								'status' => false,
								'mess' =>  'Save Data Error (save_printing)'
							];
							echo json_encode($response);die;
							$check_all = false;
							break;
						}
					}
				}
			}else{
				$check_all = false;			
			}               
		}
		if($check_all){
			$response = [
				'status' => true,
				'mess' =>  '',// use to debug code
				'JOB_NO' => $JOB_NO
			];
		}else{
			$response = [
					'status' => false,
					'mess' =>  'Save Data Error'
			];
		}

		if ($conn ) mysqli_close($conn);
		echo json_encode($response);
	}
?>