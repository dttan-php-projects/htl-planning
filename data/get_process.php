<?php
function to_slug($str) {
	$str = trim(mb_strtolower($str));
	$str = preg_replace('/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/', 'a', $str);
	$str = preg_replace('/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/', 'e', $str);
	$str = preg_replace('/(ì|í|ị|ỉ|ĩ)/', 'i', $str);
	$str = preg_replace('/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/', 'o', $str);
	$str = preg_replace('/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/', 'u', $str);
	$str = preg_replace('/(ỳ|ý|ỵ|ỷ|ỹ)/', 'y', $str);
	$str = preg_replace('/(đ)/', 'd', $str);
	$str = preg_replace('/([0-9][.])/', '', $str);
	//$str = preg_replace('/([\s]+)/', '', $str);
	return $str;
}
function delete_duplicate($value,&$array){
	foreach ($array as $key => $value){
	}
}
require_once("../Database.php");

$ITEM_ARR = $_POST['data'];
$COUNT_ITEM = count($ITEM_ARR);
$ITEM_LIST = implode("','",$ITEM_ARR);
$ITEM_LIST = "'".$ITEM_LIST."'";
$ITEM_DATA_MASTER = [];
$ITEM_DATA_PROCESS = [];
$SORT_ID = "ITEM,".$ITEM_LIST;
$SQL = "SELECT ITEM,LOP,TIEN_TRINH,PASS,KHUNG,TIME,SETUP,STT FROM master_item WHERE ITEM IN($ITEM_LIST) ORDER BY KHUNG DESC,STT,FIELD($SORT_ID)";
$RESULT_MASTER = MiQuery($SQL,connection());
$ITEM_DATA_MASTER_DELETE = [];
if(!empty($RESULT_MASTER)){
	foreach ($RESULT_MASTER as $MASTER){
		$ITEM 		= 	$MASTER['ITEM'];
		$STT 		= 	$MASTER['STT'];
		$LOP 		= 	$MASTER['LOP'];
		$LOP_FORMAT = 	to_slug($MASTER['LOP']);
		$PASS 		= 	!empty($MASTER['PASS'])?$MASTER['PASS']:0;
		$TIEN_TRINH = 	$MASTER['TIEN_TRINH'];
		$TIEN_TRINH = 	$TIEN_TRINH."*".$PASS;
		$KHUNG 		= 	$MASTER['KHUNG'];
		$TIME 		= 	!empty($MASTER['TIME'])?$MASTER['TIME']:0;
		$SETUP 		= 	!empty($MASTER['SETUP'])?$MASTER['SETUP']:0;
		$ITEM_DATA_MASTER[] = [
			'ITEM'			=> $ITEM,
			'STT'			=> $STT,
			'LOP' 			=> $LOP,
			'LOP_FORMAT' 	=> $LOP_FORMAT,
			'PROCESS_1' 	=> '',
			'PROCESS_2' 	=> '',
			'PROCESS_3' 	=> '',
			'PROCESS_4' 	=> '',
			'PROCESS_5' 	=> '',
			'PROCESS_6' 	=> '',
			'PROCESS_7' 	=> '',
			'PASS' 			=> $PASS,
			'KHUNG' 		=> $KHUNG,
			'TIME' 			=> $TIME,
			'SETUP' 		=> $SETUP,
			'TIEN_TRINH' 		=> $TIEN_TRINH,
			];
	}
}
// DELETE
$ITEM_DATA_MASTER_DELETE = $ITEM_DATA_MASTER;
if(!empty($ITEM_DATA_MASTER_DELETE)){
	foreach ($ITEM_DATA_MASTER_DELETE as $KEY_1 => $MASTER_1){
		$LOP_FORMAT_1 = $MASTER_1['LOP_FORMAT'];
		$KHUNG_1 = $MASTER_1['KHUNG'];
		foreach ($ITEM_DATA_MASTER_DELETE as $KEY_2 => $MASTER_2){
			if($KEY_1!=$KEY_2){
				$LOP_FORMAT_2 = $MASTER_2['LOP_FORMAT'];
				$KHUNG_2 = $MASTER_2['KHUNG'];
				if(strpos($LOP_FORMAT_2,$LOP_FORMAT_1)!==false&&$KHUNG_1==$KHUNG_2){
					unset($ITEM_DATA_MASTER_DELETE[$KEY_1]);
				}
			}
		}
	}
}
/*
echo "<pre>";
print_r($ITEM_DATA_MASTER_DELETE);die;
*/
// sorting
$ITEM  = array_column($ITEM_DATA_MASTER, 'ITEM');
$KHUNG = array_column($ITEM_DATA_MASTER, 'KHUNG');
$STT  = array_column($ITEM_DATA_MASTER, 'STT');
array_multisort($ITEM, SORT_ASC, $KHUNG, SORT_DESC,$STT,SORT_ASC,$ITEM_DATA_MASTER);
// GET PROCESS
foreach ($ITEM_ARR as $INDEX => $ITEM){
	$ITEM_PROCESS_TMP = [];
	if(!empty($ITEM_DATA_MASTER)){		
		foreach ($ITEM_DATA_MASTER as $INDEX_MASTER => $ITEM_MASTER){
			if(trim($ITEM)==trim($ITEM_MASTER["ITEM"])){
				$ITEM_PROCESS_TMP[]=[
					"TIEN_TRINH" => $ITEM_MASTER["TIEN_TRINH"],
					"LOP_FORMAT" => $ITEM_MASTER["LOP_FORMAT"],
					"KHUNG" => $ITEM_MASTER["KHUNG"],
				];
			}			
		}		
	}	
	$ITEM_DATA_PROCESS[$ITEM] = $ITEM_PROCESS_TMP;
}
// COMBINE
if(!empty($ITEM_DATA_MASTER_DELETE)){
	foreach ($ITEM_DATA_MASTER_DELETE as $INDEX_DELETE => $MASTER_DELETE){
		$STT = 0;
		foreach ($ITEM_DATA_PROCESS as $ITEM_PROCESS => $PROCESS){	
			$STT++;
			$PROCESS_TEXT = 'PROCESS_'.$STT;
			if(!empty($PROCESS)){
				foreach ($PROCESS as $KEY => $VALUE_PROCESS){
					if(strpos($MASTER_DELETE['LOP_FORMAT'],$VALUE_PROCESS['LOP_FORMAT'])!==false&&trim($MASTER_DELETE['KHUNG'])==trim($VALUE_PROCESS['KHUNG'])){
						$ITEM_DATA_MASTER_DELETE[$INDEX_DELETE][$PROCESS_TEXT] = $VALUE_PROCESS['TIEN_TRINH'];
						unset($ITEM_DATA_PROCESS[$ITEM_PROCESS][$KEY]); // delete after insert
					}
				}
				
			}			
		}		
	}
}
if(empty($ITEM_DATA_MASTER_DELETE)||count($ITEM_DATA_MASTER_DELETE)<1){
	$response = [
		'status' => false,
		'mess' =>  'KHÔNG LẤY ĐƯỢC THỨ TỰ IN VUI LÒNG KIỂM TRA',
	];
}else{
	$ITEM_DATA_DELETE = [];
	if(!empty($ITEM_DATA_MASTER_DELETE)){
		foreach ($ITEM_DATA_MASTER_DELETE as $KEY => $ITEM_MASTER_DELETE){
			$ITEM_DATA_DELETE[] = $ITEM_MASTER_DELETE;
		}
	}
	/*
	echo "<pre>";
	print_r($ITEM_DATA_DELETE);die;
	*/
	if(!empty($ITEM_DATA_DELETE)){
		foreach($ITEM_DATA_DELETE as $key => $valueArray){
			$checkTrung = 1;
			for($i=1;$i<=7;$i++){
				if(!empty($valueArray['PROCESS_'.$i])){
					$string_pass = explode("*",$valueArray['PROCESS_'.$i]);
					$value_pass = $string_pass[1];
					if($value_pass){
						break;
					}
				}
			}
			if(!empty($value_pass)){
				for($j=$i+1;$j<=7;$j++){
					if(!empty($valueArray['PROCESS_'.$j])){
						$string_pass = explode("*",$valueArray['PROCESS_'.$j]);
						$value_pass_2 = $string_pass[1];
						if($value_pass!=$value_pass_2){
							$checkTrung = 0;
							break;
						}
					}
				}
			}
			if(!$checkTrung){
				for($i=1;$i<=7;$i++){
					if(!empty($valueArray['PROCESS_'.$i])){						
						$ITEM_DATA_DELETE[$key]['PROCESS_'.$i] = str_replace("*1","-1PASS",$ITEM_DATA_DELETE[$key]['PROCESS_'.$i]);
						$ITEM_DATA_DELETE[$key]['PROCESS_'.$i] = str_replace("*2","-2PASS",$ITEM_DATA_DELETE[$key]['PROCESS_'.$i]);
						$ITEM_DATA_DELETE[$key]['PROCESS_'.$i] = str_replace("*3","-3PASS",$ITEM_DATA_DELETE[$key]['PROCESS_'.$i]);
					}
				}
			}
		}
	}
	$response = [
		'status' => true,
		'data' =>  $ITEM_DATA_DELETE,
	];
}
echo json_encode($response);