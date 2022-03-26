<?php

	include_once ("_getFileExcel.php");

	if (@$_REQUEST["mode"] == "html5" || @$_REQUEST["mode"] == "flash") {
		header("Content-Type: text/json");
		$filename = date("d_m_Y__H_i_s");
		$excelType = ['text/xls','text/xlsx','d/vnd.ms-excel','application/vnd.openxmlformats-officedocument.spreadsheetml.sheet','application/vnd.ms-excel','application/vnd.ms-excel.sheet.macroEnabled.12'];
		$fileSize = $_FILES['file']['size'];
		$fileType = $_FILES['file']['type'];
		if($fileSize>1000000){
			$response = [
				'state' 	=>	false,
				'name'   	=>	$filename,
				'extra' 	=>	[
					'mess' => 'File dữ liệu import quá lớn, Vui lòng kiểm tra lại' ,
				]
			];
		}elseif(!in_array($fileType,$excelType)){
			$response = [
				'state' 	=>	false,
				'name'   	=>	$filename,
				'extra'		=>	[
					'mess' => 'File dữ liệu phải là EXCEL, Vui lòng kiểm tra lại' ,
				]
			];
		}else{
			// move_uploaded_file($_FILES["file"]["tmp_name"],"uploaded/".$filename);
			$data = getFileExcel($_FILES['file']["tmp_name"]);	
			if(!empty($data)){
				require("../Database.php");
				$conn = connection();

				$UPDATED_BY  = '';
				$user = $_COOKIE["VNRISIntranet"];
				if(!empty($user)){
					$UPDATED_BY = $user;
				}
				foreach($data as $key => $value){
					$MIS_ITEM_CODE = !empty($value[2])?addslashes($value[2]):'';
					if($MIS_ITEM_CODE){
						$MIS_RBO 			= !empty($value[1])?addslashes($value[1]):'';  					
						$MIS_CUSTOMER_CODE 	= !empty($value[3])?addslashes($value[3]):'';
						$MIS_MATERIAL_CODE 	= !empty($value[4])?addslashes($value[4]):'';
						$MIS_MATERIAL_NAME 	= !empty($value[5])?addslashes($value[5]):'';
						$MIS_CREATED_BY 	= $UPDATED_BY;

						//MIS_ITEM_CODE,MIS_CUSTOMER_CODE,MIS_CREATED_BY,MIS_RBO, MIS_MATERIAL_CODE, MIS_MATERIAL_NAME,MIS_CREATED_DATE

						$sql_check = "SELECT COUNT(1) as count_item FROM `master_item_sum` WHERE MIS_ITEM_CODE='$MIS_ITEM_CODE' ";
						$rowsCheck = MiQuery($sql_check, $conn);
						if(!empty($rowsCheck)){
							// update
							$sql = "UPDATE `master_item_sum` SET `MIS_RBO`='$MIS_RBO',`MIS_CUSTOMER_CODE`='$MIS_CUSTOMER_CODE',`MIS_MATERIAL_CODE`='$MIS_MATERIAL_CODE',`MIS_MATERIAL_NAME`='$MIS_MATERIAL_NAME',`MIS_CREATED_BY`='$MIS_CREATED_BY',`MIS_CREATED_DATE`=now() WHERE MIS_ITEM_CODE='$MIS_ITEM_CODE' ";
						}else{
							// insert
							$sql = "INSERT INTO `master_item_sum` (`MIS_ITEM_CODE`,`MIS_RBO`,`MIS_CUSTOMER_CODE`,`MIS_CREATED_BY`,`MIS_MATERIAL_CODE`,`MIS_MATERIAL_NAME`) 
									VALUES ('$MIS_ITEM_CODE','$MIS_RBO','$MIS_CUSTOMER_CODE','$MIS_CREATED_BY','$MIS_MATERIAL_CODE','$MIS_MATERIAL_NAME')";
						}
						$check = $conn->query($sql);
						
						if(!$check){
							$response = [
									'state' 	=>	false,	
									'name'   	=>	$filename,			
									'extra'		=>	[
										'mess' => 'Có lỗi xảy ra trong quá trình import',
								]
							];
							echo json_encode($response);die;
						}
					}
				}		
				if($check){
					$response = [
						'state' 	=>	true,	
						'name'   	=>	$filename,			
						'extra'		=>	[
							'mess' 	=> 'Import dữ liệu thành công, Website sẽ reload!!!!',
						]
					];
				}
			}else{
				$response = [
					'state' 	=>	false,	
					'name'   	=>	$filename,			
					'extra'		=>	[
						'mess' => 'Kiểm tra lại dữ liệu file EXCEL',
					]
				];
			}				
		}

		if ($conn) mysqli_close($conn);
		echo json_encode($response);
	}

	/*

	HTML4 MODE

	response format:

	to cancel uploading
	{state: 'cancelled'}

	if upload was good, you need to specify state=true, name - will passed in form.send() as serverName param, size - filesize to update in list
	{state: 'true', name: 'filename', size: 1234}

	*/

	if (@$_REQUEST["mode"] == "html4") {
		header("Content-Type: text/html");
		if (@$_REQUEST["action"] == "cancel") {
			print_r("{state:'cancelled'}");
		} else {
			$filename = $_FILES["file"]["name"];
			move_uploaded_file($_FILES["file"]["tmp_name"], "uploaded/".$filename);
			print_r("{state: true, name:'".str_replace("'","\\'",$filename)."', size:".$_FILES["file"]["size"]/*filesize("uploaded/".$filename)*/.", extra: {info: 'just a way to send some extra data', param: 'some value here'}}");
		}
	}
?>
