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
				$table = "oh_so";

				$delete = "TRUNCATE $table; ";
				$conn->query("TRUNCATE $table; ");

				foreach($data as $key => $value){
					$OH = !empty($value[0])?addslashes($value[0]):'';
					if($OH){
						$SO_LINE = !empty($value[1])?addslashes($value[1]):'';  
						// insert
						$check = $conn->query("INSERT INTO $table (`OH`,`SO_LINE`) VALUES ('$OH','$SO_LINE');");
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
				// closed
					if ($conn) mysqli_close($conn);
				// check 
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
