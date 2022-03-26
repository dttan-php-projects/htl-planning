<?php

	include_once ("_getFileExcel.php");

	if (@$_REQUEST["mode"] == "html5" || @$_REQUEST["mode"] == "flash") {
		header("Content-Type: text/json");
		$filename = date("d_m_Y__H_i_s");
		$excelType = ['d/vnd.ms-excel','application/vnd.openxmlformats-officedocument.spreadsheetml.sheet','application/vnd.ms-excel','application/vnd.ms-excel.sheet.macroEnabled.12'];
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
					'mess' => 'File dữ liệu phải là EXCEL, Vui lòng kiểm tra lại',
				]
			];
		}else{
			// move_uploaded_file($_FILES["file"]["tmp_name"],"uploaded/".$filename);
			$data = getFileExcel($_FILES['file']["tmp_name"]);	
			if(!empty($data)){
				require("../Database.php");
				$conn = connection();
				$table = "master_item";

				$UPDATED_BY  = '';
				$user = $_COOKIE["VNRISIntranet"];
				if(!empty($user)){
					$UPDATED_BY = $user;
				}
				foreach($data as $key => $value){
					$ITEM = !empty($value[0])?addslashes($value[0]):'';
					if($ITEM){
						$STT = !empty($value[1])?addslashes($value[1]):0;  					
						$LOP = !empty($value[2])?addslashes($value[2]):'';
						$TIEN_TRINH = !empty($value[3])?addslashes($value[3]):'';
						$PASS = !empty($value[4])?addslashes($value[4]):0;
						$KHUNG = !empty($value[5])?addslashes($value[5]):'';
						$VAT_TU = !empty($value[6])?addslashes($value[6]):'';
						$SETUP = !empty($value[7])?addslashes($value[7]):0;
						$TIME = !empty($value[8])?addslashes($value[8]):0;
						$CHECK_AGI	 = !empty($value[9])?addslashes($value[9]):'';
						$FOD = !empty($value[10])?addslashes($value[10]):'';
						$SCRAP = !empty($value[11])?addslashes($value[11]):0;
						$SCRAP = $SCRAP*100;
						$sql_check = "SELECT COUNT(1) as count_item FROM $table WHERE ITEM='$ITEM' and STT='$STT'";
						$rowsCheck = MiQuery($sql_check, connection());
						if(!empty($rowsCheck)){
							// update
							$sql = "UPDATE $table SET `LOP`='$LOP',`TIEN_TRINH`='$TIEN_TRINH',`PASS`='$PASS',`KHUNG`='$KHUNG',`VAT_TU`='$VAT_TU',`SETUP`='$SETUP',`TIME`='$TIME',`CHECK_AGI`='$CHECK_AGI',`FOD`='$FOD',`SCRAP`='$SCRAP',`UPDATED_BY`='$UPDATED_BY',`CREATED_TIME`=now() WHERE ITEM='$ITEM' and STT='$STT'";
						}else{
							// insert
							$sql = "INSERT INTO $table
							(`ITEM`,`STT`,`LOP`,`TIEN_TRINH`,`PASS`,`KHUNG`,`VAT_TU`,`SETUP`,`TIME`,`CHECK_AGI`,`FOD`,`SCRAP`,`UPDATED_BY`) VALUES ('$ITEM','$STT','$LOP','$TIEN_TRINH','$PASS','$KHUNG','$VAT_TU','$SETUP','$TIME','$CHECK_AGI','$FOD','$SCRAP','$UPDATED_BY')";
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
