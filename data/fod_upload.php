<?php
	date_default_timezone_set('Asia/Ho_Chi_Minh');
	ini_set('max_execution_time',300);  // set time 5 minutes
	include_once ("_getFileExcel.php");

if (@$_REQUEST["mode"] == "html5" || @$_REQUEST["mode"] == "flash") {
	header("Content-Type: text/json");
	
	$filename = date("d_m_Y__H_i_s");
	$excelType = ['application/octet-stream', 'd/vnd.ms-excel','application/vnd.openxmlformats-officedocument.spreadsheetml.sheet','application/vnd.ms-excel','application/vnd.ms-excel.sheet.macroEnabled.12'];
	
	$fileSize = $_FILES['file']['size'];
	$fileType = $_FILES['file']['type'];
	if($fileSize>1000000){
		$response = [
			'state' 	=>	false,
			'name'   	=>	$filename,
			'extra' 	=>	[
				'mess' => 'File dữ liệu import quá lớn, Vui lòng kiểm tra lại',
			]
		];
	}elseif(!in_array($fileType,$excelType)){
		$response = [
			'state' 	=>	false,
			'name'   	=>	$filename,
			'extra'		=>	[
				'mess' => 'File dữ liệu phải là EXCEL, Vui lòng kiểm tra lại ',
			]
		];
	}else{
		// move_uploaded_file($_FILES["file"]["tmp_name"],"uploaded/".$filename);

		//$file_data = "test.xlsx";
		$file_data = $_FILES['file']["tmp_name"];
		$data = getFileExcel($file_data);

		foreach($data as $key => $value){
			$ITEM = !empty($value[0])?addslashes($value[0]):'';
			$data[$key][0] = preg_replace('/[^a-zA-Z0-9-]/', '',$ITEM);
		}		

		if(!empty($data)){
			require("../Database.php");
			$conn = connection();

			$CREATED_TIME = date('Y-m-d H:i:s');
			$UPDATED_BY  = '';
			$user = $_COOKIE["VNRISIntranet"];
			if(!empty($user)){
				$UPDATED_BY = $user;
			}

			$table = "master_item";
			foreach($data as $key => $value){
				$ITEM = !empty($value[0])?addslashes($value[0]):'';
				$ITEM = trim($ITEM);
				//$ITEM = preg_replace('/[^a-zA-Z0-9]/', '',$ITEM);
				if($ITEM){
					$FOD = !empty($value[1])?addslashes($value[1]):'';
					$SCRAP = trim($value[2]);
					$SCRAP_LEN = strlen($SCRAP);

					$VAT_TU = trim(addslashes($value[3]) );
					$TEN_VAT_TU = trim(addslashes($value[4]) );


					if (!empty($VAT_TU) ) {
						if($SCRAP_LEN>0){
							if($SCRAP<1){
								$SCRAP=$SCRAP*100;
							}
							$sql = "UPDATE $table SET `FOD`='$FOD',`SCRAP`='$SCRAP', `VAT_TU`='$VAT_TU', `TEN_VAT_TU`='$TEN_VAT_TU', `UPDATED_BY`='$UPDATED_BY', `CREATED_TIME`=CURRENT_TIMESTAMP() WHERE ITEM='$ITEM'";
						}else{
							$sql = "UPDATE $table SET `FOD`='$FOD', `VAT_TU`='$VAT_TU', `TEN_VAT_TU`='$TEN_VAT_TU', `UPDATED_BY`='$UPDATED_BY', `CREATED_TIME`=CURRENT_TIMESTAMP() WHERE ITEM='$ITEM'";
						}
					} else {
						if($SCRAP_LEN>0){
							if($SCRAP<1){
								$SCRAP=$SCRAP*100;
							}
							$sql = "UPDATE $table SET `FOD`='$FOD',`SCRAP`='$SCRAP', `UPDATED_BY`='$UPDATED_BY', `CREATED_TIME`=CURRENT_TIMESTAMP() WHERE ITEM='$ITEM'";
						}else{
							$sql = "UPDATE $table SET `FOD`='$FOD',`UPDATED_BY`='$UPDATED_BY', `CREATED_TIME`=CURRENT_TIMESTAMP() WHERE ITEM='$ITEM'";
						}	
					}
									
					//echo $SCRAP;die;
					// update
					$check = $conn->query($sql);

					if(!$check){
						$response = [
								'state' 	=>	false,	
								'name'   	=>	$filename,			
								'extra'		=>	[
									'mess' => 'Có lỗi xảy ra trong quá trình import ',
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
						'mess' => 'Import dữ liệu thành công, Website sẽ reload!!!!',
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
	echo json_encode($response); exit();
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
