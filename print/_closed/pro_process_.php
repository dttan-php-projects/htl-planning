<?php
$sql_process = "SELECT * FROM save_printing WHERE JOB_NO='$id'";
$result_process = MiQuery($sql_process,connection());
$arr_process = [];
$COUNT_PROCESS = 0;
if(!empty($result_process)){
    foreach ($result_process as $key => $process){    
		$COUNT_PROCESS++;
        $arr_process[$key]['PRINTING_FOLLOW'] 		= $process['PRINTING_FOLLOW'];
        $arr_process[$key]['PROCESS_1'] 			= $process['PROCESS_1'];
        $arr_process[$key]['PROCESS_2'] 			= $process['PROCESS_2'];
		$arr_process[$key]['PROCESS_3'] 			= $process['PROCESS_3'];
		$arr_process[$key]['PROCESS_4'] 			= $process['PROCESS_4'];
		$arr_process[$key]['PROCESS_5'] 			= $process['PROCESS_5'];
		$arr_process[$key]['PASSES'] 				= $process['PASSES'];
		$arr_process[$key]['SCREEN'] 				= $process['SCREEN'];
		$arr_process[$key]['TIME'] 					= $process['TIME'];
		$arr_process[$key]['SETUP'] 				= $process['SETUP'];		
    }
}
// check delete $arr_process

$COUNT_DUP_PROCESS_2 = 0;
$COUNT_DUP_PROCESS_3 = 0;
$COUNT_DUP_PROCESS_4 = 0;
$REMOVE_ITEM_PROCESS_2 = 0;
$REMOVE_ITEM_PROCESS_3 = 0;
$REMOVE_ITEM_PROCESS_4 = 0;
foreach ($arr_process as $key => $process){
	if($process['PROCESS_2']==$process['PROCESS_1']){
		$COUNT_DUP_PROCESS_2++;
	}
	if($process['PROCESS_3']==$process['PROCESS_1']||$process['PROCESS_3']==$process['PROCESS_2']){
		$COUNT_DUP_PROCESS_3++;
	}
	if($process['PROCESS_4']==$process['PROCESS_3']||$process['PROCESS_4']==$process['PROCESS_2']||$process['PROCESS_4']==$process['PROCESS_1']){
		$COUNT_DUP_PROCESS_4++;
	}
}
if($COUNT_DUP_PROCESS_2==$COUNT_PROCESS){
	$REMOVE_ITEM_PROCESS_2 = 1;
}
if($COUNT_DUP_PROCESS_3==$COUNT_PROCESS){
	$REMOVE_ITEM_PROCESS_3 = 1;
}
if($COUNT_DUP_PROCESS_4==$COUNT_PROCESS){
	$REMOVE_ITEM_PROCESS_4 = 1;
}
foreach ($arr_process as $key => $process){
	if($REMOVE_ITEM_PROCESS_2){
		$arr_process[$key]['PROCESS_2']='';
	}
	if($REMOVE_ITEM_PROCESS_3){
		$arr_process[$key]['PROCESS_3']='';
	}
	if($REMOVE_ITEM_PROCESS_4){
		$arr_process[$key]['PROCESS_4']='';
	}
}
?>