<?php
	require_once("../Database.php");
	$SQL_SO = "SELECT item,scrap FROM master_item;";
	$RESULT_SO = MiQuery($SQL_SO,connection());
	$item_error=[];
	foreach($RESULT_SO as $key=>$value){	
		$item = $value['item'];
		$scrap = $value['scrap'];
		if(!empty($RESULT_SO[$key-1])){
			$item_pre = $RESULT_SO[$key-1]['item'];
			$scrap_pre = $RESULT_SO[$key-1]['scrap'];
			if($item_pre==$item&&$scrap_pre!=$scrap){
				$item_error[]=$item;
			}
		}
	}
	echo json_encode(array_unique($item_error));