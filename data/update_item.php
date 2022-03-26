<?php
header("Content-Type: application/json");
if(!empty($_POST['data'])){
	$formatData = json_decode($_POST['data'],true);
	$check_1 = true;
	if(!empty($formatData)){		
		$UPDATED_BY  = '';
		$user = $_COOKIE["VNRISIntranet"];
		if(!empty($user)){
			$UPDATED_BY = $user;
		}
		require_once("../Database.php");//connect to database
		$conn = connection();
		$table = "master_item";

		$ITEM = !empty($formatData['ITEM'])?addslashes($formatData['ITEM']):'';
		$STT = !empty($formatData['STT'])?addslashes($formatData['STT']):0;
		$LOP = !empty($formatData['LOP'])?addslashes($formatData['LOP']):'';
		$TIEN_TRINH = !empty($formatData['TIEN_TRINH'])?addslashes($formatData['TIEN_TRINH']):'';
		$PASS = !empty($formatData['PASS'])?addslashes($formatData['PASS']):0;
		$KHUNG = !empty($formatData['KHUNG'])?addslashes($formatData['KHUNG']):'';
		$VAT_TU = !empty($formatData['VAT_TU'])?addslashes($formatData['VAT_TU']):'';
		$SETUP = !empty($formatData['SETUP'])?addslashes($formatData['SETUP']):0;
		$TIME = !empty($formatData['TIME'])?addslashes($formatData['TIME']):0;
		$CHECK_AGI = !empty($formatData['CHECK_AGI'])?addslashes($formatData['CHECK_AGI']):'';
		$FOD = !empty($formatData['FOD'])?addslashes($formatData['FOD']):'';
		$SCRAP = !empty($formatData['SCRAP'])?addslashes($formatData['SCRAP']):0;
		$idUA = $formatData['ITEM_ID'];
		if(strpos($idUA,'new_id_')!==false){  // insert
			if($ITEM){
				$sql = "INSERT INTO $table 
				(`ITEM`,`STT`,`LOP`,`TIEN_TRINH`,`PASS`,`KHUNG`,`VAT_TU`,`SETUP`,`TIME`,`CHECK_AGI`,`FOD`,`SCRAP`,`UPDATED_BY`) VALUES ('$ITEM','$STT','$LOP','$TIEN_TRINH','$PASS','$KHUNG','$VAT_TU','$SETUP','$TIME','$CHECK_AGI','$FOD','$SCRAP','$UPDATED_BY')";
				$check_1 = $conn->query($sql);
				$insert_id = $conn->insert_id;
			}			
		}else{
			// update
			$sql = "UPDATE $table SET `ITEM`='$ITEM',`STT`='$STT',`LOP`='$LOP',`TIEN_TRINH`='$TIEN_TRINH',`PASS`='$PASS',`KHUNG`='$KHUNG',`VAT_TU`='$VAT_TU',`SETUP`='$SETUP',`TIME`='$TIME',`CHECK_AGI`='$CHECK_AGI',`FOD`='$FOD',`SCRAP`='$SCRAP',`UPDATED_BY`='$UPDATED_BY' where ID='$idUA'";
			$check_1 = $conn->query($sql);
		}
		if($check_1){
			$response = [
				'status' => true,
				'mess' =>'',
				'id' => !empty($insert_id)?$insert_id:''
			];
		}else{
			$response = [
					'status' => false,
					'mess' =>  'Update Item Error'
				];
		}

		if ($conn) mysqli_close($conn);

		echo json_encode($response);
	}
}